<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class TutorApiController extends BaseUvsController
{
    /**
     * GET /api/tutorprogram/person?person_id=1-0026419
     * person_id-Format: "{institut_id}-{person_nr}"
     * Liefert:
     *  - tutor   (Stammdaten + Person)
     *  - themes  (Themengebiete)
     *  - modules (Dozenten-Bausteine aus doz_baust)
     *  - courses (NEU: konkrete Klassen/Kurse über ma_u_kla)
     */
    public function getTutorProgramByPersonId(Request $request)
    {
        $data = $request->validate([
            'person_id' => 'required|string|max:255',
        ]);

        $this->connectToUvsDatabase();

        $personId = trim($data['person_id']);
        if (!str_contains($personId, '-')) {
            return response()->json([
                'ok'    => false,
                'error' => 'Ungültiges person_id-Format. Erwartet: {institut_id}-{person_nr}',
            ], 422);
        }

        [$institutId, $personNr] = array_map('trim', explode('-', $personId, 2));

        try {
            // 1) Tutor (mitarbei) + Person
            $tutor = DB::connection('uvs')
                ->table('mitarbei as m')
                ->leftJoin('person as p', 'p.person_id', '=', 'm.person_id')
                ->where('m.institut_id', $institutId)
                ->where(function ($q) use ($personNr) {
                    $q->where('m.mitarbeiter_id', $personNr)
                      ->orWhere('m.person_nr',    $personNr);
                })
                ->select([
                    'm.mitarbeiter_id',
                    'm.person_id',
                    'm.person_nr',
                    'm.institut_id',
                    'm.status',
                    'p.nachname',
                    'p.vorname',
                    'p.email_priv',
                ])
                ->first();

            if (!$tutor) {
                return response()->json([
                    'ok'    => false,
                    'error' => 'Tutor nicht gefunden.',
                ], 404);
            }

            // 2) Themengebiete
            $themes = DB::connection('uvs')
                ->table('doz_themengebiete as dt')
                ->join('themengebiete as t', 't.uid', '=', 'dt.themengebiet_id')
                ->where('dt.institut_id', $tutor->institut_id)
                ->where('dt.mitarbeiter_id', $tutor->mitarbeiter_id)
                ->orderBy('t.name')
                ->get([
                    DB::raw('t.uid as themengebiet_id'),
                    't.name',
                    'dt.bemerkung',
                ]);



            // 4) NEU: Konkrete Kurse/Klassen über ma_u_kla
            $courses = DB::connection('uvs')
                ->table('ma_u_kla as mk') // Dozent-Zuordnung zu Klassen
                ->join('u_klasse as k', 'k.klassen_id', '=', 'mk.klassen_id')
                ->leftJoin('baustein as b', 'b.kurzbez', '=', 'k.kurzbez_ba')        // b.langbez
                ->leftJoin('termin   as t', 't.termin_id', '=', 'k.termin_id')       // t.beginn_baustein / t.ende_baustein (varchar YYYY-MM-DD)
                ->where('mk.mitarbeiter_id', $tutor->mitarbeiter_id)
                ->orderByRaw("STR_TO_DATE(t.beginn_baustein, '%Y-%m-%d') DESC")
                ->orderBy('k.klassen_id', 'DESC')
                ->get([
                    'k.klassen_id',
                    DB::raw('k.kurzbez_ba      as kurzbez'),
                    DB::raw('b.langbez         as bezeichnung'),
                    DB::raw('t.beginn_baustein as beginn'),
                    DB::raw('t.ende_baustein   as ende'),
                    // praktische Zusatzinfos aus u_klasse (falls vorhanden)
                    'k.status',
                    'k.unterr_raum',
                    'k.unterr_beginn',
                    'k.unterr_ende',
                    // Teilnehmer-/Dozenten-Zähler als Subselects, falls gewünscht:
                    DB::raw("(
                        SELECT COUNT(*)
                        FROM tn_u_kla tuk
                        WHERE tuk.klassen_id = k.klassen_id
                          AND (tuk.deleted IS NULL OR tuk.deleted = 0)
                    ) as participants_count"),
                    DB::raw("(
                        SELECT COUNT(DISTINCT mk2.mitarbeiter_id)
                        FROM ma_u_kla mk2
                        WHERE mk2.klassen_id = k.klassen_id
                    ) as teachers_count"),
                ]);

            return response()->json([
                'ok'   => true,
                'data' => [
                    'tutor'   => $tutor,
                    'themes'  => $themes,
                    'courses' => $courses, // <-- neu über ma_u_kla
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('TutorProgramByPersonId failed', [
                'person_id'  => $personId,
                'institutId' => $institutId ?? null,
                'personNr'   => $personNr ?? null,
                'msg'        => $e->getMessage(),
            ]);

            return response()->json([
                'ok'    => false,
                'error' => 'Interner Fehler bei der Abfrage (Details im Log).',
            ], 500);
        }
    }
}
