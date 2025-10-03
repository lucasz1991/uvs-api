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
     *  - tutor  (Stammdaten + Person)
     *  - themes (Themengebiete)
     *  - modules(Dozenten-Bausteine)
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
            // 1) Tutor (mitarbei) + Namen/Email aus person
            $tutor = DB::connection('uvs')
                ->table('mitarbei as m')                           // NOTE: Schema hat 'mitarbeiter_id' (nicht 'mitarbei_id')
                ->leftJoin('person as p', 'p.person_id', '=', 'm.person_id') // Namen liegen in 'person'
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
                    // aus person:
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

            // 2) Themen (doz_themengebiete → themengebiete.uid)
            $themes = DB::connection('uvs')
                ->table('doz_themengebiete as dt')
                ->join('themengebiete as t', 't.uid', '=', 'dt.themengebiet_id')
                ->where('dt.institut_id', $tutor->institut_id)
                ->where('dt.mitarbeiter_id', $tutor->mitarbeiter_id)
                ->where(function ($q) {                 // <- deleted-Flag berücksichtigen
                    $q->whereNull('dt.deleted')->orWhere('dt.deleted', 0);
                })
                ->orderBy('t.name')
                ->get([
                    DB::raw('t.uid as themengebiet_id'),
                    't.name',
                    'dt.bemerkung',
                ]);

            // 3) Bausteine (doz_baust)
            $modules = DB::connection('uvs')
                ->table('doz_baust')
                ->where('mitarbeiter_id', $tutor->mitarbeiter_id)
                ->where(function ($q) {
                    $q->whereNull('deleted')->orWhere('deleted', 0);
                })
                ->orderByDesc('uid')
                ->get([
                    DB::raw('uid as doz_baust_id'),
                    'kurzbez',
                    'bemerkung',
                ]);

            return response()->json([
                'ok'   => true,
                'data' => [
                    'tutor'   => $tutor,
                    'themes'  => $themes,
                    'modules' => $modules,
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
