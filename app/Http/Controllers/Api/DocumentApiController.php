<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
<<<<<<< HEAD
use Illuminate\Support\Facades\URL;
=======
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use RuntimeException;
use Throwable;
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130

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
<<<<<<< HEAD
 *  - sign()  ist per API-Key geschuetzt (nur das UVS darf URLs erzeugen).
 *  - show()  ist NICHT per API-Key geschuetzt, sondern ausschliesslich durch
 *            die Laravel-Signatur inkl. Ablaufzeit ('signed'-Middleware).
=======
 *  - sign()  ist bewusst ohne API-Key erreichbar und gibt nur URLs fuer
 *            vorhandene PDFs in den explizit freigegebenen Ordnern aus.
 *  - show()  ist ebenfalls nicht per API-Key geschuetzt, sondern durch die
 *            Laravel-Signatur inkl. Ablaufzeit ('signed'-Middleware).
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
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
<<<<<<< HEAD
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
=======
            'typ' => 'required|string|in:angebot,vertrag',
            'path' => 'required|string|max:512',
            'item_id' => 'nullable|string|max:64',
            'context' => 'sometimes|array',
            'context.flow' => 'nullable|string|max:64',
            'context.beratung_id' => 'nullable|string|max:64',
            'context.angebot_id' => 'nullable|string|max:64',
            'context.ivertrag_uid' => 'nullable|string|max:64',
            'context.tvertrag_uid' => 'nullable|string|max:64',
            'context.dokument_uid' => 'nullable|string|max:64',
            'context.person_uid' => 'nullable|string|max:64',
        ]);

        $reason = null;
        $resolved = $this->resolveDocument($data['typ'], $data['path'], $reason);
        $syncContext = $this->syncContext($data['context'] ?? []);

        if ($resolved === null) {
            $this->log('document.sign_rejected', [
                'typ' => $data['typ'],
                'item_id' => $data['item_id'] ?? null,
                'requested_path' => $this->pathForLog($data['path']),
                'reason' => $reason ?? 'document_resolution_failed',
                'request_ip' => $request->ip(),
                'sync_context' => $syncContext,
            ], 'Signed document URL rejected');

            return response()->json([
                'message' => 'Dokument nicht gefunden, nicht lesbar oder Pfad nicht freigegeben.',
                'reason' => $reason ?? 'document_resolution_failed',
            ], 404);
        }

        $ttl = max(1, (int) config('uvs.document_url_ttl', 30));
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
        $expires = Carbon::now()->addMinutes($ttl);

        $url = URL::temporarySignedRoute('documents.pdf', $expires, [
            'typ' => $data['typ'],
<<<<<<< HEAD
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
=======
            'p' => $this->encodePath($resolved['rel']),
        ]);

        $sourceUrlHash = hash('sha256', $url);

        $this->log('document.signed', [
            'typ' => $data['typ'],
            'item_id' => $data['item_id'] ?? null,
            'document' => $resolved['filename'],
            'filename' => $resolved['filename'],
            'relative_path' => $resolved['rel'],
            'file_size' => $resolved['size'],
            'file_modified_at' => $resolved['modified_at'],
            'file_exists' => true,
            'file_readable' => true,
            'pdf_header_valid' => true,
            'source_url' => $this->urlForLog($url),
            'source_url_hash' => $sourceUrlHash,
            'expires_at' => $expires->toIso8601String(),
            'ttl_min' => $ttl,
            'request_ip' => $request->ip(),
            'sync_context' => $syncContext,
        ], 'Signed document URL created');

        return response()->json([
            'url' => $url,
            'expires_at' => $expires->toIso8601String(),
            'filename' => $resolved['filename'],
            'file_size' => $resolved['size'],
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
        ]);
    }

    /**
     * Liefert das Dokument aus. Zugriff ausschliesslich ueber eine gueltige,
     * nicht abgelaufene Signatur ('signed'-Middleware auf der Route).
     */
    public function show(Request $request, string $typ)
    {
<<<<<<< HEAD
        $resolved = $this->resolveDocument($typ, $this->decodePath((string) $request->query('p', '')));

        if ($resolved === null) {
            $this->log('document.delivery_failed', [
                'typ'    => $typ,
                'reason' => 'Pfad ungueltig oder Datei nicht vorhanden',
=======
        $decodedPath = $this->decodePath((string) $request->query('p', ''));
        $reason = null;
        $resolved = $this->resolveDocument($typ, $decodedPath, $reason);
        $requestUrl = $request->fullUrl();
        $sourceUrlHash = hash('sha256', $requestUrl);

        if ($resolved === null) {
            $this->log('document.delivery_failed', [
                'typ' => $typ,
                'requested_path' => $this->pathForLog($decodedPath),
                'source_url' => $this->urlForLog($requestUrl),
                'source_url_hash' => $sourceUrlHash,
                'reason' => $reason ?? 'document_resolution_failed',
                'download_success' => false,
                'request_ip' => $request->ip(),
                'user_agent' => $this->userAgentForLog($request),
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
            ], 'Signed document delivery failed');

            return response()->json(['message' => 'Dokument nicht gefunden.'], 404);
        }

<<<<<<< HEAD
        $this->log('document.delivered', [
            'typ'      => $typ,
            'document' => basename($resolved['rel']),
        ], 'Signed document delivered');

        return response()->file($resolved['abs'], [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($resolved['rel']) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'       => 'private, no-store',
=======
        $deliveryProperties = [
            'typ' => $typ,
            'document' => $resolved['filename'],
            'filename' => $resolved['filename'],
            'relative_path' => $resolved['rel'],
            'file_size' => $resolved['size'],
            'file_modified_at' => $resolved['modified_at'],
            'source_url' => $this->urlForLog($requestUrl),
            'source_url_hash' => $sourceUrlHash,
            'download_success' => null,
            'request_ip' => $request->ip(),
            'user_agent' => $this->userAgentForLog($request),
        ];

        $this->log(
            'document.delivery_started',
            $deliveryProperties,
            'Signed document delivery started'
        );

        return response()->stream(function () use ($resolved, $deliveryProperties): void {
            $handle = null;
            $bytesSent = 0;
            $previousIgnoreUserAbort = ignore_user_abort(true);

            try {
                $handle = @fopen($resolved['abs'], 'rb');
                if ($handle === false) {
                    throw new RuntimeException('PDF-Datei konnte fuer die Auslieferung nicht geoeffnet werden.');
                }

                while (! feof($handle)) {
                    $chunk = fread($handle, 1024 * 1024);
                    if ($chunk === false) {
                        throw new RuntimeException('PDF-Datei konnte nicht vollstaendig gelesen werden.');
                    }
                    if ($chunk === '') {
                        if (feof($handle)) {
                            break;
                        }
                        throw new RuntimeException('PDF-Dateistrom lieferte unerwartet keine Daten.');
                    }

                    echo $chunk;
                    $bytesSent += strlen($chunk);

                    if (connection_aborted() !== 0) {
                        throw new RuntimeException('Download-Verbindung wurde vorzeitig beendet.');
                    }
                }

                if ($bytesSent !== $resolved['size']) {
                    throw new RuntimeException(sprintf(
                        'PDF-Auslieferung war unvollstaendig (%d von %d Bytes).',
                        $bytesSent,
                        $resolved['size']
                    ));
                }

                $this->log('document.delivered', array_merge($deliveryProperties, [
                    'bytes_sent' => $bytesSent,
                    'complete' => true,
                    'download_success' => true,
                ]), 'Signed document delivered');
            } catch (Throwable $error) {
                $this->log('document.delivery_failed', array_merge($deliveryProperties, [
                    'bytes_sent' => $bytesSent,
                    'complete' => false,
                    'download_success' => false,
                    'reason' => 'stream_failed',
                    'error_class' => get_class($error),
                    'error_message' => mb_substr($error->getMessage(), 0, 500),
                ]), 'Signed document delivery failed');

                throw $error;
            } finally {
                if (is_resource($handle)) {
                    fclose($handle);
                }
                ignore_user_abort((bool) $previousIgnoreUserAbort);
            }
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Length' => (string) $resolved['size'],
            'Content-Disposition' => 'inline; filename="'.$this->filenameForHeader($resolved['filename']).'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
        ]);
    }

    /* ---------------------------------------------------------------- */

    /**
     * Normalisiert und haertet den uebergebenen Pfad.
     *
<<<<<<< HEAD
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
=======
     * Liefert Metadaten zur geprueften PDF oder null. Der optionale Grund wird
     * ausschliesslich fuer Diagnose und Activity Log gesetzt.
     */
    private function resolveDocument(string $typ, string $rawPath, ?string &$reason = null): ?array
    {
        $reason = 'document_resolution_failed';
        $dirs = (array) config('uvs.document_dirs', []);
        if (! isset($dirs[$typ])) {
            $reason = 'unsupported_document_type';

            return null;
        }

        $root = rtrim((string) config('uvs.root', ''), '/\\');
        if ($root === '' || ! is_dir($root)) {
            $reason = 'configured_uvs_root_missing';

>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
            return null;
        }

        $rel = str_replace('\\', '/', $rawPath);
        $rel = (string) preg_replace('#/+#', '/', $rel);
        $rel = ltrim($rel, '/');

        // Der UVS-Pfad enthaelt einen umgebungsabhaengigen BASE_PATH-Praefix
        // (z. B. /uvs_dev/data/pdf/...). Ab "data/pdf/" normalisieren.
        $pos = strpos($rel, 'data/pdf/');
        if ($pos === false) {
<<<<<<< HEAD
=======
            $reason = 'data_pdf_prefix_missing';

>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
            return null;
        }
        $rel = substr($rel, $pos);

        if ($rel === '' || strpos($rel, '..') !== false || strpos($rel, "\0") !== false) {
<<<<<<< HEAD
            return null;
        }
        if (strtolower((string) pathinfo($rel, PATHINFO_EXTENSION)) !== 'pdf') {
=======
            $reason = 'unsafe_document_path';

            return null;
        }
        if (strtolower((string) pathinfo($rel, PATHINFO_EXTENSION)) !== 'pdf') {
            $reason = 'document_extension_not_pdf';

>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
            return null;
        }

        // Muss im freigegebenen Verzeichnis des jeweiligen Typs liegen.
        $allowedDir = trim((string) $dirs[$typ], '/');
<<<<<<< HEAD
        if ($allowedDir === '' || strpos($rel, $allowedDir . '/') !== 0) {
            return null;
        }

        $baseReal = realpath($root . '/' . $allowedDir);
        $fileReal = realpath($root . '/' . $rel);
        if ($baseReal === false || $fileReal === false) {
=======
        if ($allowedDir === '' || strpos($rel, $allowedDir.'/') !== 0) {
            $reason = 'document_path_not_allowed';

            return null;
        }

        $baseReal = realpath($root.'/'.$allowedDir);
        $fileReal = realpath($root.'/'.$rel);
        if ($baseReal === false || $fileReal === false) {
            $reason = 'document_or_allowed_directory_missing';

>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
            return null;
        }

        // Containment-Pruefung gegen Path-Traversal ueber Symlinks o. ae.
<<<<<<< HEAD
        $baseNorm = rtrim(str_replace('\\', '/', $baseReal), '/') . '/';
=======
        $baseNorm = rtrim(str_replace('\\', '/', $baseReal), '/').'/';
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
        $fileNorm = str_replace('\\', '/', $fileReal);

        $matches = DIRECTORY_SEPARATOR === '\\'
            ? strncasecmp($fileNorm, $baseNorm, strlen($baseNorm)) === 0
            : strncmp($fileNorm, $baseNorm, strlen($baseNorm)) === 0;

<<<<<<< HEAD
        if (!$matches || !is_file($fileReal) || !is_readable($fileReal)) {
            return null;
        }

        return ['rel' => $rel, 'abs' => $fileReal];
=======
        if (! $matches) {
            $reason = 'resolved_path_not_allowed';

            return null;
        }
        if (! is_file($fileReal)) {
            $reason = 'document_not_a_file';

            return null;
        }
        if (! is_readable($fileReal)) {
            $reason = 'document_not_readable';

            return null;
        }

        $size = filesize($fileReal);
        if ($size === false || $size < 5) {
            $reason = 'document_size_invalid';

            return null;
        }

        $handle = @fopen($fileReal, 'rb');
        if ($handle === false) {
            $reason = 'document_open_failed';

            return null;
        }

        try {
            $header = fread($handle, 5);
        } finally {
            fclose($handle);
        }

        if ($header === false) {
            $reason = 'document_header_read_failed';

            return null;
        }
        if ($header !== '%PDF-') {
            $reason = 'document_header_not_pdf';

            return null;
        }

        $modifiedAt = filemtime($fileReal);
        $reason = null;

        return [
            'rel' => $rel,
            'abs' => $fileReal,
            'filename' => basename($rel),
            'size' => (int) $size,
            'modified_at' => $modifiedAt === false ? null : $modifiedAt,
        ];
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
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

<<<<<<< HEAD
    private function log(string $event, array $properties, string $message): void
    {
        activity('uvs')
            ->withProperties(array_merge(['event' => $event], $properties))
            ->log($message);
=======
    private function syncContext(array $context): array
    {
        $allowed = [
            'flow',
            'beratung_id',
            'angebot_id',
            'ivertrag_uid',
            'tvertrag_uid',
            'dokument_uid',
            'person_uid',
        ];

        $result = [];
        foreach ($allowed as $key) {
            if (! array_key_exists($key, $context) || $context[$key] === null) {
                continue;
            }

            $value = trim((string) $context[$key]);
            if ($value !== '') {
                $result[$key] = mb_substr($value, 0, 64);
            }
        }

        return $result;
    }

    private function pathForLog(string $path): string
    {
        $normalized = str_replace('\\', '/', trim($path));

        return mb_substr($normalized, 0, 512);
    }

    /**
     * Die URL wird fuer die Diagnose gespeichert, die gueltige Signatur aber
     * bewusst maskiert. Der separate Hash korreliert Signierung und Abruf.
     */
    private function urlForLog(string $url): string
    {
        $parts = parse_url($url);
        if ($parts === false) {
            return '[invalid-url]';
        }

        $query = [];
        parse_str((string) ($parts['query'] ?? ''), $query);
        if (array_key_exists('signature', $query)) {
            $query['signature'] = '[redacted]';
        }

        $scheme = isset($parts['scheme']) ? $parts['scheme'].'://' : '';
        $host = (string) ($parts['host'] ?? '');
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $path = (string) ($parts['path'] ?? '');
        $queryString = http_build_query($query, '', '&', PHP_QUERY_RFC3986);

        return $scheme.$host.$port.$path.($queryString !== '' ? '?'.$queryString : '');
    }

    private function filenameForHeader(string $filename): string
    {
        return str_replace(["\r", "\n", '"', '\\'], '_', $filename);
    }

    private function userAgentForLog(Request $request): ?string
    {
        $userAgent = trim((string) $request->userAgent());

        return $userAgent === '' ? null : mb_substr($userAgent, 0, 512);
    }

    private function log(string $event, array $properties, string $message): void
    {
        try {
            activity('uvs')
                ->event($event)
                ->withProperties(array_merge(['event' => $event], $properties))
                ->log($message);
        } catch (Throwable $error) {
            // Ein Ausfall des Activity-Log-Speichers darf eine ansonsten
            // gueltige Dokumentauslieferung nicht selbst zum Scheitern bringen.
            try {
                Log::warning('UVS document activity log could not be written.', [
                    'event' => $event,
                    'message' => $message,
                    'properties' => $properties,
                    'logging_error' => mb_substr($error->getMessage(), 0, 500),
                ]);
            } catch (Throwable) {
                // Auch ein nicht beschreibbares Fallback-Log darf den
                // Dokument-Download nicht beeinflussen.
            }
        }
>>>>>>> 85421f6ac5913a25715451b7ff669d6b471e8130
    }
}
