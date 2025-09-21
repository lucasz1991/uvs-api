<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class TutorApiController extends Controller
{
    protected function connectToUvsDatabase(): void
    {
        config(['database.connections.uvs' => [
            'driver'    => 'mysql',
            'host'      => Setting::getValue('database', 'hostname'),
            'database'  => Setting::getValue('database', 'database'),
            'username'  => Setting::getValue('database', 'username'),
            'password'  => Setting::getValue('database', 'password'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]]);
    }

    /**
     * GET /api/tutorprogram/person?person_id=1-0026419
     * person_id-Format: "{institut_id}-{person_nr}"
     * Liefert:
     *  - tutor (Basis/Profil)
     *  - themes (Themengebiete)
     *  - modules (Dozenten-Bausteine)
     */
    public function getTutorProgramByPersonId(Request $request)
    {
        $data = $request->validate([
            'person_id' => 'required|string|max:255',
        ]);

        $this->connectToUvsDatabase();

        // person_id in institut_id + person_nr splitten
        [$institutId, $personNr] = $this->splitPersonId($data['person_id']);

        // 1) DOZENT (Stammdaten/Profil) – angelehnt an 1_6_2_mitarbeit_quali_profil.php
        // -----------------------------------------------------------
        // Typische Tabellen/Spalten (bitte an eure UVS-DB angleichen):
        // - mitarbeiter (oder: mitarbeiter_stamm)
        //   Felder: mitarbeiter_id, person_nr, institut_id, name, vorname, email, telefon, status, ...
        // - mitarbeiter_quali (Qualifikationen / Profil-Ergänzungen)
        //   Felder: mitarbeiter_id, institut_id, quali_text|bemerkung|titel|fachrichtung|...
        $tutor = DB::connection('uvs')
            ->table('mitarbeiter as m')                // <— ggf. anpassen
            ->leftJoin('mitarbeiter_quali as q', function ($join) { // <— ggf. anpassen
                $join->on('q.mitarbeiter_id', '=', 'm.mitarbeiter_id')
                     ->on('q.institut_id', '=', 'm.institut_id');
            })
            ->where('m.institut_id', $institutId)
            ->where(function ($q) use ($personNr) {
                // Falls eure DB statt mitarbeiter_id die person_nr nutzt – beide Varianten unterstützen
                $q->where('m.mitarbeiter_id', $personNr)
                  ->orWhere('m.person_nr', $personNr);
            })
            ->selectRaw('
                m.mitarbeiter_id,
                m.person_nr,
                m.institut_id,
                m.name as nachname,
                m.vorname,
                m.email as email,
                m.telefon as telefon,
                m.status as status,
                q.titel as quali_titel,
                q.fachrichtung as quali_fachrichtung,
                q.bemerkung as quali_bemerkung
            ')
            ->first();

        if (!$tutor) {
            return response()->json([
                'ok' => false,
                'error' => 'Tutor nicht gefunden (bitte Tabellen-/Spaltenmapping prüfen).',
            ], 404);
        }

        $mitarbeiterId = $tutor->mitarbeiter_id ?? $personNr; // Fallback: nutze personNr, wenn mitarbeiter_id fehlt

        // 2) THEMENGEBIETE – angelehnt an 1_6_3_dozent_themen.php
        // -----------------------------------------------------------
        // Tabellen/Spalten (bitte angleichen):
        // - doz_themengebiete (dt): mitarbeiter_id, institut_id, themengebiet_id, bemerkung, deleted
        // - themengebiete (t): uid, name
        $themes = DB::connection('uvs')
            ->table('doz_themengebiete as dt') // <— ggf. anpassen
            ->join('themengebiete as t', 't.uid', '=', 'dt.themengebiet_id') // <— ggf. anpassen
            ->where('dt.institut_id', $institutId)
            ->where('dt.mitarbeiter_id', $mitarbeiterId)
            ->where(function ($q) {
                // deleted-Flag falls vorhanden (defensiv)
                $q->whereNull('dt.deleted')->orWhere('dt.deleted', 0);
            })
            ->orderBy('t.name')
            ->get([
                DB::raw('t.uid as themengebiet_id'),
                DB::raw('t.name'),
                DB::raw('dt.bemerkung'),
            ]);

        // 3) DOZENTEN-BAUSTEINE – angelehnt an 1_6_4_dozent_bausteine.php
        // -----------------------------------------------------------
        // Tabelle: doz_baust (Feldernamen lt. altem Skript)
        // Felder: uid, mitarbeiter_id, kurzbez, bemerkung, deleted
        $modules = DB::connection('uvs')
            ->table('doz_baust') // <— ggf. anpassen
            ->where('mitarbeiter_id', $mitarbeiterId)
            ->where(function ($q) {
                $q->whereNull('deleted')->orWhere('deleted', 0);
            })
            ->groupBy('kurzbez')           // aus dem alten Skript
            ->orderByDesc('uid')           // aus dem alten Skript
            ->get([
                DB::raw('uid as doz_baust_id'),
                'kurzbez',
                'bemerkung',
            ]);

        return response()->json([
            'ok' => true,
            'data' => [
                'tutor'   => $tutor,
                'themes'  => $themes,
                'modules' => $modules,
            ]
        ]);
    }

    private function splitPersonId(string $personId): array
    {
        // Erwartet "{institut_id}-{person_nr}"
        $parts = explode('-', $personId, 2);
        if (count($parts) !== 2) {
            abort(response()->json([
                'ok' => false,
                'error' => 'person_id muss im Format "{institut_id}-{person_nr}" übergeben werden.',
            ], 422));
        }
        return [$parts[0], $parts[1]];
    }
}
