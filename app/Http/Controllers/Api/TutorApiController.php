<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

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

        $personId = trim($data['person_id']);

        if (!str_contains($personId, '-')) {
            return response()->json([
                'ok'    => false,
                'error' => 'Ungültiges person_id-Format. Erwartet: {institut_id}-{person_nr}',
            ], 422);
        }

        [$institutId, $personNr] = array_map('trim', explode('-', $personId, 2));

        try {
            // -----------------------------
            // 1) Tutor-Stammdaten (mitarbei)
            // -----------------------------
            $tutorQuery = DB::connection('uvs')->table('mitarbei as m');

            $hasMitarbeiterQuali = Schema::connection('uvs')->hasTable('mitarbeiter_quali');
            $hasMitarbQuali      = Schema::connection('uvs')->hasTable('mitarb_quali');

            if ($hasMitarbeiterQuali) {
                $tutorQuery->leftJoin('mitarbeiter_quali as q', function ($join) {
                    $join->on('q.mitarbeiter_id', '=', 'm.mitarbei_id')
                        ->on('q.institut_id',   '=', 'm.institut_id');
                });
            } elseif ($hasMitarbQuali) {
                $tutorQuery->leftJoin('mitarb_quali as q', function ($join) {
                    $join->on('q.mitarbei_id', '=', 'm.mitarbei_id')
                        ->on('q.institut_id',  '=', 'm.institut_id');
                });
            }

            $select = [
                'm.mitarbei_id as mitarbeiter_id',
                'm.institut_id',
            ];
            if (Schema::connection('uvs')->hasColumn('mitarbei','person_nr')) $select[] = 'm.person_nr';
            if (Schema::connection('uvs')->hasColumn('mitarbei','name'))      $select[] = DB::raw('m.name as nachname');
            if (Schema::connection('uvs')->hasColumn('mitarbei','vorname'))   $select[] = 'm.vorname';
            if (Schema::connection('uvs')->hasColumn('mitarbei','email'))     $select[] = 'm.email';
            if (Schema::connection('uvs')->hasColumn('mitarbei','telefon'))   $select[] = 'm.telefon';
            if (Schema::connection('uvs')->hasColumn('mitarbei','status'))    $select[] = 'm.status';

            if ($hasMitarbeiterQuali || $hasMitarbQuali) {
                // Quali-Spalten aliasieren – wenn es die Spalten in q nicht gibt, kommen sie als NULL
                $select[] = DB::raw('q.titel as quali_titel');
                $select[] = DB::raw('q.fachrichtung as quali_fachrichtung');
                $select[] = DB::raw('q.bemerkung as quali_bemerkung');
            }

            $tutor = $tutorQuery
                ->where('m.institut_id', $institutId)
                ->where(function ($q) use ($personNr) {
                    $q->where('m.mitarbei_id', $personNr);
                    if (Schema::connection('uvs')->hasColumn('mitarbei','person_nr')) {
                        $q->orWhere('m.person_nr', $personNr);
                    }
                })
                ->select($select)
                ->first();

            if (!$tutor) {
                return response()->json([
                    'ok'    => false,
                    'error' => 'Tutor nicht gefunden (prüfe Tabellen/Spalten: "mitarbei", optional "mitarbeiter_quali"/"mitarb_quali").',
                ], 404);
            }

            $mitarbeiterId = $tutor->mitarbeiter_id ?? $personNr;

            // -----------------------------
            // 2) Themen (doz_themengebiete + themengebiete)
            // -----------------------------
            $themes = collect();
            if (Schema::connection('uvs')->hasTable('doz_themengebiete') && Schema::connection('uvs')->hasTable('themengebiete')) {

                $tgIdCol  = Schema::connection('uvs')->hasColumn('themengebiete','uid')            ? 't.uid'
                        : (Schema::connection('uvs')->hasColumn('themengebiete','themengeb_id') ? 't.themengeb_id' : null);

                $dtTgFk   = Schema::connection('uvs')->hasColumn('doz_themengebiete','themengebiet_id') ? 'dt.themengebiet_id'
                        : (Schema::connection('uvs')->hasColumn('doz_themengebiete','themengeb_id')    ? 'dt.themengeb_id'     : null);

                $dtMaFk   = Schema::connection('uvs')->hasColumn('doz_themengebiete','mitarbeiter_id')   ? 'dt.mitarbeiter_id'
                        : (Schema::connection('uvs')->hasColumn('doz_themengebiete','mitarbei_id')     ? 'dt.mitarbei_id'      : null);

                if ($tgIdCol && $dtTgFk && $dtMaFk) {
                    $themes = DB::connection('uvs')
                        ->table('doz_themengebiete as dt')
                        ->join('themengebiete as t', DB::raw($tgIdCol), '=', DB::raw($dtTgFk))
                        ->where('dt.institut_id', $institutId)
                        ->where(DB::raw($dtMaFk),  $mitarbeiterId)
                        ->where(function ($q) {
                            $q->whereNull('dt.deleted')->orWhere('dt.deleted', 0);
                        })
                        ->orderBy('t.name')
                        ->select([
                            DB::raw($tgIdCol . ' as themengebiet_id'),
                            't.name',
                            'dt.bemerkung',
                        ])
                        ->get();
                }
            }

            // -----------------------------
            // 3) Bausteine (doz_baust)
            // -----------------------------
            $modules = collect();
            if (Schema::connection('uvs')->hasTable('doz_baust')) {
                $modules = DB::connection('uvs')
                    ->table('doz_baust')
                    ->where('mitarbeiter_id', $mitarbeiterId)
                    ->where(function ($q) {
                        $q->whereNull('deleted')->orWhere('deleted', 0);
                    })
                    ->orderByDesc('uid') // wie im Alt-Script
                    ->select([
                        DB::raw('uid as doz_baust_id'),
                        'kurzbez',
                        'bemerkung',
                    ])
                    ->get();
            }

            return response()->json([
                'ok' => true,
                'data' => [
                    'tutor'   => $tutor,
                    'themes'  => $themes,
                    'modules' => $modules,
                ]
            ]);
        } catch (\Throwable $e) {
            Log::error('TutorProgramByPersonId failed', [
                'person_id'  => $personId,
                'institutId' => $institutId,
                'personNr'   => $personNr,
                'msg'        => $e->getMessage(),
            ]);

            return response()->json([
                'ok'    => false,
                'error' => 'Interner Fehler bei der Abfrage (Details im Log).',
            ], 500);
        }
    }

}
