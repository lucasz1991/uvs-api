<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TutorApiController extends BaseUvsController
{
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

            // 3) Kurse/Klassen über ma_u_kla (robust, mit Subselect-Zählern)
            $db = DB::connection('uvs');

            $courses = $db->table('ma_u_kla as mk')                   // Dozent-Zuordnung zu Klassen
                ->leftJoin('u_klasse as k', 'k.klassen_id', '=', 'mk.klassen_id')
                ->leftJoin('termin   as t', 't.termin_id', '=', 'k.termin_id')        // beginn/ende (yyyy-mm-dd als VARCHAR)
                ->leftJoin('baustein as b', 'b.kurzbez', '=', 'k.kurzbez_ba')         // für b.langbez

                // Zugehörigkeit/Filter analog zu deinem Beispiel:
                ->where('mk.mitarbeiter_id', $tutor->mitarbeiter_id)
                ->where('t.institut_id',     $tutor->institut_id)     // wie: where('termin.institut_id', $terminInstId)

                // Deduplizieren auf Klassenebene (wie dein GROUP BY auf tn_baustein_id)
                ->groupBy('k.klassen_id')

                // Sortierung wie bei dir – Datum aus VARCHAR
                ->orderByRaw("STR_TO_DATE(COALESCE(t.beginn_baustein,'0001-01-01'), '%Y-%m-%d') DESC")
                ->orderBy('k.klassen_id', 'DESC')

                ->select([
                    'k.klassen_id',
                    DB::raw('k.kurzbez_ba      as kurzbez'),
                    DB::raw('b.langbez         as bezeichnung'),
                    DB::raw('t.beginn_baustein as beginn'),
                    DB::raw('t.ende_baustein   as ende'),
                    'k.status',
                    'k.unterr_raum',
                    'k.unterr_beginn',
                    'k.unterr_ende',
                ])

                // Teilnehmer-Zähler (nur nicht-gelöschte)
                ->selectSub(function ($q) {
                    $q->from('tn_u_kla as tuk')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('tuk.klassen_id', 'k.klassen_id');
                }, 'participants_count')

                // Dozenten-Zähler (distinct)
                ->selectSub(function ($q) {
                    $q->from('ma_u_kla as mk2')
                    ->selectRaw('COUNT(DISTINCT mk2.mitarbeiter_id)')
                    ->whereColumn('mk2.klassen_id', 'k.klassen_id');
                }, 'teachers_count')

                ->get();

            return response()->json([
                'ok'   => true,
                'data' => [
                    'tutor'   => $tutor,
                    'themes'  => $themes,
                    'courses' => $courses,
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
