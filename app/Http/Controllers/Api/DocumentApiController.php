<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

/**
 * Stellt Angebots- und Vertrags-PDFs des UVS unter einer signierten,
 * zeitlich begrenzten URL bereit.
 *
 * Hintergrund: Das UVS erzeugt die Dokumente lokal. Make (Szenario UVS-02)
 * benoetigt die Datei, um sie in HubSpot abzulegen. Statt die Datei selbst
 * hochzuladen, laesst das UVS hier eine signierte URL erzeugen und uebergibt
 * sie als pdf_url im Webhook-Payload.
 *
 * Die API liegt produktiv direkt neben dem UVS-Verzeichnis und liest die
 * Dateien unmittelbar vom Dateisystem (config/uvs.php -> 'root').
 *
 * Sicherheitsprinzip:
 *  - sign()  ist per API-Key geschuetzt (nur das UVS darf URLs erzeugen).
 *  - show()  ist NICHT per API-Key geschuetzt, sondern ausschliesslich durch
 *            die Laravel-Signatur inkl. Ablaufzeit ('signed'-Middleware).
 *  - Beide Wege pruefen den Pfad erneut gegen die freigegebenen Verzeichnisse,
 *    damit ueber diesen Endpunkt keine anderen Dateien lesbar werden.
 */
class DocumentApiController extends Controller
{
    /**
     * Erzeugt eine signierte, zeitlich begrenzte URL zu einem Dokument.
     *
     * Erwartet: { "typ": "angebot"|"vertrag", "path": "<Pfad aus dem UVS>" }
     * Der Pfad darf den umgebungsabhaengigen BASE_PATH-Praefix enthalten -
     * es wird ab "data/pdf/" normalisiert.
     */
    public function sign(Request $request)
    {
        $data = $request->validate([
            'typ'     => 'required|string|in:angebot,vertrag',
            'path'    => 'required|string|max:512',
            'item_id' => 'nullable|string|max:64',
        ]);

        $resolved = $this->resolveDocument($data['typ'], $data['path']);

        if ($resolved === null) {
            $this->log('document.sign_rejected', [
                'typ'     => $data['typ'],
                'item_id' => $data['item_id'] ?? null,
                'reason'  => 'Pfad ungueltig oder Datei nicht vorhanden',
            ], 'Signed document URL rejected');

            return response()->json([
                'message' => 'Dokument nicht gefunden oder Pfad nicht freigegeben.',
            ], 404);
        }

        $ttl     = max(1, (int) config('uvs.document_url_ttl', 30));
        $expires = Carbon::now()->addMinutes($ttl);

        $url = URL::temporarySignedRoute('documents.pdf', $expires, [
            'typ' => $data['typ'],
            'p'   => $this->encodePath($resolved['rel']),
        ]);

        $this->log('document.signed', [
            'typ'      => $data['typ'],
            'item_id'  => $data['item_id'] ?? null,
            'document' => basename($resolved['rel']),
            'ttl_min'  => $ttl,
        ], 'Signed document URL created');

        return response()->json([
            'url'        => $url,
            'expires_at' => $expires->toIso8601String(),
        ]);
    }

    /**
     * Liefert das Dokument aus. Zugriff ausschliesslich ueber eine gueltige,
     * nicht abgelaufene Signatur ('signed'-Middleware auf der Route).
     */
    public function show(Request $request, string $typ)
    {
        $resolved = $this->resolveDocument($typ, $this->decodePath((string) $request->query('p', '')));

        if ($resolved === null) {
            $this->log('document.delivery_failed', [
                'typ'    => $typ,
                'reason' => 'Pfad ungueltig oder Datei nicht vorhanden',
            ], 'Signed document delivery failed');

            return response()->json(['message' => 'Dokument nicht gefunden.'], 404);
        }

        $this->log('document.delivered', [
            'typ'      => $typ,
            'document' => basename($resolved['rel']),
        ], 'Signed document delivered');

        return response()->file($resolved['abs'], [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($resolved['rel']) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'       => 'private, no-store',
        ]);
    }

    /* ---------------------------------------------------------------- */

    /**
     * Normalisiert und haertet den uebergebenen Pfad.
     *
     * Liefert ['rel' => <Pfad relativ zum UVS-Root>, 'abs' => <absoluter Pfad>]
     * oder null, wenn der Pfad nicht zulaessig ist.
     */
    private function resolveDocument(string $typ, string $rawPath): ?array
    {
        $dirs = (array) config('uvs.document_dirs', []);
        if (!isset($dirs[$typ])) {
            return null;
        }

        $root = rtrim((string) config('uvs.root', ''), "/\\");
        if ($root === '' || !is_dir($root)) {
            return null;
        }

        $rel = str_replace('\\', '/', $rawPath);
        $rel = (string) preg_replace('#/+#', '/', $rel);
        $rel = ltrim($rel, '/');

        // Der UVS-Pfad enthaelt einen umgebungsabhaengigen BASE_PATH-Praefix
        // (z. B. /uvs_dev/data/pdf/...). Ab "data/pdf/" normalisieren.
        $pos = strpos($rel, 'data/pdf/');
        if ($pos === false) {
            return null;
        }
        $rel = substr($rel, $pos);

        if ($rel === '' || strpos($rel, '..') !== false || strpos($rel, "\0") !== false) {
            return null;
        }
        if (strtolower((string) pathinfo($rel, PATHINFO_EXTENSION)) !== 'pdf') {
            return null;
        }

        // Muss im freigegebenen Verzeichnis des jeweiligen Typs liegen.
        $allowedDir = trim((string) $dirs[$typ], '/');
        if ($allowedDir === '' || strpos($rel, $allowedDir . '/') !== 0) {
            return null;
        }

        $baseReal = realpath($root . '/' . $allowedDir);
        $fileReal = realpath($root . '/' . $rel);
        if ($baseReal === false || $fileReal === false) {
            return null;
        }

        // Containment-Pruefung gegen Path-Traversal ueber Symlinks o. ae.
        $baseNorm = rtrim(str_replace('\\', '/', $baseReal), '/') . '/';
        $fileNorm = str_replace('\\', '/', $fileReal);

        $matches = DIRECTORY_SEPARATOR === '\\'
            ? strncasecmp($fileNorm, $baseNorm, strlen($baseNorm)) === 0
            : strncmp($fileNorm, $baseNorm, strlen($baseNorm)) === 0;

        if (!$matches || !is_file($fileReal) || !is_readable($fileReal)) {
            return null;
        }

        return ['rel' => $rel, 'abs' => $fileReal];
    }

    private function encodePath(string $relativePath): string
    {
        return rtrim(strtr(base64_encode($relativePath), '+/', '-_'), '=');
    }

    private function decodePath(string $encoded): string
    {
        if ($encoded === '') {
            return '';
        }

        $base64 = strtr($encoded, '-_', '+/');
        $padding = strlen($base64) % 4;
        if ($padding > 0) {
            $base64 .= str_repeat('=', 4 - $padding);
        }

        $decoded = base64_decode($base64, true);

        return $decoded === false ? '' : $decoded;
    }

    private function log(string $event, array $properties, string $message): void
    {
        activity('uvs')
            ->withProperties(array_merge(['event' => $event], $properties))
            ->log($message);
    }
}
