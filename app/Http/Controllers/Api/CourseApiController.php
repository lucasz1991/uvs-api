<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;




class CourseApiController extends BaseUvsController
{
    /**
     * GET /api/course-classes?search=ABC&limit=25&from=2025-01-01&to=2025-12-31
     */
    public function getCourseClasses(Request $request)
    {
        $data = $request->validate([
            'search' => 'sometimes|nullable|string|max:255',
            'limit'  => 'sometimes|integer|min:1|max:100',
            'from'   => 'sometimes|date_format:Y-m-d',
            'to'     => 'sometimes|date_format:Y-m-d|after_or_equal:from',
            'sort'   => 'sometimes|in:beginn,ende,bezeichnung,participants_count,teachers_count',
            'order'  => 'sometimes|in:asc,desc',
        ]);

        $this->connectToUvsDatabase();

        $limit  = $data['limit'] ?? 25;
        $search = trim((string)($data['search'] ?? ''));

        // Kernabfrage: u_klasse + baustein (Langbezeichnung) + termin (Datumsfelder)
        $q = DB::connection('uvs')
            ->table('u_klasse as k')
            ->leftJoin('baustein as b', 'b.kurzbez', '=', 'k.kurzbez_ba')       // b.langbez
            ->leftJoin('termin   as t', 't.termin_id', '=', 'k.termin_id')      // t.beginn_baustein, t.ende_baustein
            ->leftJoin('ma_u_kla as mk', 'mk.klassen_id', '=', 'k.klassen_id'); // nur für evtl. spätere Erweiterung

        // optionale Suche: auf kurzbez_ba und baustein.langbez
        if ($search !== '') {
            $q->where(function ($w) use ($search) {
                $like = "%{$search}%";
                $w->where('k.kurzbez_ba', 'like', $like)
                  ->orWhere('b.langbez',   'like', $like);
            });
        }

        // optionale Datumsfilter (auf termin.* – sind varchar(10); hier in DATE casten, falls Format YYYY-MM-DD)
        if (!empty($data['from'])) {
            $q->whereRaw("STR_TO_DATE(t.beginn_baustein, '%Y-%m-%d') >= STR_TO_DATE(?, '%Y-%m-%d')", [$data['from']]);
        }
        if (!empty($data['to'])) {
            $q->whereRaw("STR_TO_DATE(t.ende_baustein,   '%Y-%m-%d') <= STR_TO_DATE(?, '%Y-%m-%d')", [$data['to']]);
        }

        $classes = $q->select([
                'k.klassen_id',
                DB::raw('k.kurzbez_ba      as kurzbez'),
                DB::raw('b.langbez         as bezeichnung'),
                DB::raw('t.beginn_baustein as beginn'),
                DB::raw('t.ende_baustein   as ende'),

                DB::raw("(
                    SELECT COUNT(*)
                    FROM tn_u_kla tuk
                    WHERE tuk.klassen_id = k.klassen_id
                ) as participants_count"),

                DB::raw("(
                    SELECT COUNT(DISTINCT mk2.mitarbeiter_id)
                    FROM ma_u_kla mk2
                    WHERE mk2.klassen_id = k.klassen_id
                ) as teachers_count"),
            ]);

        // dynamische Sortierung
        $sort  = $data['sort']  ?? 'beginn';
        $order = $data['order'] ?? 'desc';

        switch ($sort) {
            case 'ende':
                $q->orderByRaw("STR_TO_DATE(t.ende_baustein, '%Y-%m-%d') {$order}");
                break;
            case 'bezeichnung':
                $q->orderBy('b.langbez', $order);
                break;
            case 'participants_count':
                $q->orderBy('participants_count', $order);
                break;
            case 'teachers_count':
                $q->orderBy('teachers_count', $order);
                break;
            case 'beginn':
            default:
                $q->orderByRaw("STR_TO_DATE(t.beginn_baustein, '%Y-%m-%d') {$order}");
                break;
        }

        // Fallback, falls mehrere den gleichen Wert haben
        $q->orderBy('k.klassen_id', 'desc');

        $classes = $q->limit($limit)->get();

        return response()->json(['ok' => true, 'data' => $classes]);
    }

    /**
     * GET /api/course-classes/participants?course_class_id=123
     */
    public function getCourseClassesParticipants(Request $request)
    {
        $data = $request->validate([
            'course_class_id' => 'required|string|max:25',     // u_klasse.klassen_id
        ]);

        $this->connectToUvsDatabase();

        $classId    = $data['course_class_id'];

        $participants = DB::connection('uvs')
            ->table('tn_u_kla as tuk')
            ->join('person as p', 'p.person_id', '=', 'tuk.person_id')
            ->where('tuk.klassen_id', $classId)
            ->orderBy('p.nachname')
            ->orderBy('p.vorname')
            ->get([
                'p.*', 
                DB::raw('tuk.klassen_id as klassen_id'),
            ]);

        return response()->json([
            'ok'   => true,
            'data' => [
                'class_id'     => $classId,
                'participants' => $participants,
            ],
        ]);
    }





     /**
     * GET /api/Course/CourseByKlassenId?klassen_id=XYZ
     *
     * Response:
     * {
     *   "ok": true,
     *   "data": {
     *     "course": {...},
     *     "participants": [...],
     *     "teachers": [...]
     *   }
     * }
     */
    public function getCourseByKlassenId(Request $request)
    {
        $data = $request->validate([
            'klassen_id' => 'required|string|max:25',
        ]);

        $this->connectToUvsDatabase();

        $klassenId = $data['klassen_id'];

        // Kurs-/Klassen-Stammdaten
        $course = DB::connection('uvs')
            ->table('u_klasse as k') // u_klasse.klassen_id, kurzbez_ba, termin_id
            ->leftJoin('baustein as b', 'b.kurzbez', '=', 'k.kurzbez_ba') // baustein.langbez
            ->leftJoin('termin   as t', 't.termin_id', '=', 'k.termin_id') // termin.beginn_baustein/ende_baustein
            ->where('k.klassen_id', $klassenId)
            ->select([
                'k.klassen_id',
                'k.termin_id',
                DB::raw('k.kurzbez_ba      as kurzbez'),
                DB::raw('b.langbez         as bezeichnung'),
                DB::raw('t.beginn_baustein as beginn'),  // varchar(10)
                DB::raw('t.ende_baustein   as ende'),    // varchar(10)
                'k.institut_id_ks',
                'k.vtz_kennz_ks',
                'k.klassen_co_ks',
                'k.status',
                'k.unterr_raum',
                'k.unterr_beginn',
                'k.unterr_ende',
                'k.unterr_ende_fr',
                'k.unterr_ende_fr2',
            ])
            ->first();

        if (!$course) {
            return response()->json(['ok' => false, 'error' => 'Klasse nicht gefunden.'], 404);
        }

        // Teilnehmerliste (nur nicht-gelöschte)
        $participants = DB::connection('uvs')
            ->table('tn_u_kla as tuk')
            ->join('person as p', 'p.person_id', '=', 'tuk.person_id')
            ->where('tuk.klassen_id', $klassenId)
            ->orderBy('p.nachname')
            ->orderBy('p.vorname')
            ->get([
                'p.*', 
                DB::raw('tuk.klassen_id as klassen_id'),
            ]);

        // Dozenten/Lehrkräfte
        $teachers = DB::connection('uvs')
            ->table('ma_u_kla as mk')
            ->join('person as p', 'p.person_id', '=', 'mk.person_id')
            ->where('mk.klassen_id', $klassenId)
            ->orderBy('p.nachname')
            ->orderBy('p.vorname')
            ->get([
                'p.*', 
                'mk.mitarbeiter_id',
                DB::raw('mk.klassen_id as klassen_id'),
            ]);

        // Kurstage (Tagesliste) zum Termin der Klasse
        $courseDays = DB::connection('uvs')
            ->table('termtag as tt')
            ->where('tt.termin_id', $course->termin_id)
            ->when(!empty($course->institut_id_ks), fn($q) =>
                $q->where('tt.institut_id', $course->institut_id_ks)
            )
            ->whereNotNull('tt.std')
            ->where('tt.std', '!=', '0.00')
            ->orderByRaw("STR_TO_DATE(tt.datum, '%Y-%m-%d') ASC")
            ->get([
                'tt.termtag_id',
                'tt.termin_id',
                'tt.institut_id',
                'tt.datum',            // varchar(10) YYYY-MM-DD
                'tt.wochentag',        // z.B. 'Mo', 'Di', ...
                'tt.std',              // Unterrichtsstunden (decimal)
                'tt.unterr_beginn',    // 'HH:MM' (Default lt. Schema 08:00)
                'tt.unterr_ende',      // 'HH:MM'
                'tt.art',              // 1-char Typ (Unterricht/Prüfung/…)
            ]);

        
            // Bildungsmittel zum Baustein der Klasse (kurzbez)
            $now     = time();
            $kurzbez = $course->kurzbez; // alias aus SELECT oben

            $materials = DB::connection('uvs')
                ->table('bmittel as bm')
                ->where(function ($w) use ($kurzbez) {
                    $w->where('bm.baustein_1', $kurzbez)
                    ->orWhere('bm.baustein_2', $kurzbez)
                    ->orWhere('bm.baustein_3', $kurzbez)
                    ->orWhere('bm.baustein_4', $kurzbez)
                    ->orWhere('bm.baustein_5', $kurzbez)
                    ->orWhere('bm.baustein_6', $kurzbez);
                })
                ->where('bm.freigabe_von', '<=', $now) // bigint(20) Unixzeit
                ->where('bm.freigabe_bis', '>=', $now) // bigint(20) Unixzeit
                // optional: nur aktive Datensätze, falls in deiner DB so genutzt
                // ->where('bm.status', '=', '1')
                ->orderBy('bm.titel')
                ->get([
                    'bm.titel',
                    'bm.titel2',
                    'bm.verlag',
                    'bm.isbn',
                    'bm.isbn_pdf',
                    'bm.bestell_nr',
                    'bm.preis',
                    'bm.preis_pdf',
                ]);


        return response()->json([
            'ok'   => true,
            'data' => [
                'course'       => $course,
                'participants' => $participants,
                'teachers'     => $teachers,
                'days'         => $courseDays,
                'materials'    => $materials,
            ],
        ]);
    }



public function syncCourseDayAttendanceData(Request $request)
{
    Log::info('syncCourseDayAttendanceData called', ['request' => $request->all()]);

    /*
    |--------------------------------------------------------------------------
    | 1) VALIDIERUNG
    |--------------------------------------------------------------------------
    */
$data = $request->validate([
    'teilnehmer_ids'           => 'required|array',
    'teilnehmer_ids.*'         => 'required|string|max:25',

    'termin_id'                => 'required|string|max:25',
    'date'                     => 'required|date_format:Y-m-d',

    'changes'                  => 'sometimes|array',

    // Basis für alle Changes
    'changes.*.teilnehmer_id'  => 'required_with:changes|string|max:25',
    'changes.*.termin_id'      => 'nullable|string|max:25',
    'changes.*.date'           => 'nullable|date_format:Y-m-d',
    'changes.*.institut_id'    => 'nullable|integer',

    // Aktion: create/update/delete
    'changes.*.action'         => 'nullable|string|in:create,update,delete',

    // Detailfelder – dürfen leer sein (werden gleich im Code geprüft)
    'changes.*.fehl_grund'     => 'nullable|string|max:4',
    'changes.*.fehl_bem'       => 'nullable|string|max:75',
    'changes.*.gekommen'       => 'nullable|string|max:5',
    'changes.*.gegangen'       => 'nullable|string|max:5',
    'changes.*.fehl_std'       => 'nullable|numeric',
    'changes.*.status'         => 'nullable|integer',
    'changes.*.upd_user'       => 'nullable|string|max:50',
    'changes.*.tn_fehltage_id' => 'nullable|string|max:27',
]);


    $teilnehmerIds = $data['teilnehmer_ids'] ?? null;
    $terminId      = $data['termin_id']      ?? null;
    $dateFilter    = $data['date']           ?? null;
    $changes       = $data['changes']        ?? [];

    if (empty($terminId) && empty($teilnehmerIds) && empty($dateFilter)) {
        throw ValidationException::withMessages([
            'termin_id' => 'Mindestens eines der Felder termin_id, teilnehmer_ids oder date muss gesetzt sein.',
        ]);
    }

    $this->connectToUvsDatabase();

    /*
    |--------------------------------------------------------------------------
    | 2) REMOTE → LOCAL: tn_fehl lesen (PULL)
    |--------------------------------------------------------------------------
    */
    $q = DB::connection('uvs')->table('tn_fehl');

    if (!empty($terminId)) {
        $q->where('termin_id', $terminId);
    }

    if (!empty($teilnehmerIds)) {
        $q->whereIn('teilnehmer_id', $teilnehmerIds);
    }

    if (!empty($dateFilter)) {
        $fehlDatum = str_replace('-', '/', $dateFilter); // 2025-12-08 -> 2025/12/08
        $q->where('fehl_datum', $fehlDatum);
    }

    $rows = $q
        ->orderBy('fehl_datum')
        ->orderBy('teilnehmer_id')
        ->orderBy('uid', 'desc')
        ->get([
            'uid',
            'tn_fehltage_id',
            'ec_tag_id',
            'teilnehmer_id',
            'institut_id',
            'termin_id',
            'status',
            'upd_date',
            'upd_user',
            'fehl_datum',
            'fehl_grund',
            'fehl_bem',
            'gekommen',
            'gegangen',
            'fehl_std',
        ]);

    $items = $rows->map(function ($row) {
        $isoDate = null;
        if (!empty($row->fehl_datum) && preg_match('#^\d{4}/\d{2}/\d{2}$#', $row->fehl_datum)) {
            $isoDate = str_replace('/', '-', $row->fehl_datum); // YYYY-MM-DD
        }

        return [
            'uid'              => (int) $row->uid,
            'tn_fehltage_id'   => (string) $row->tn_fehltage_id,
            'ec_tag_id'        => (int) $row->ec_tag_id,
            'teilnehmer_id'    => (string) $row->teilnehmer_id,
            'institut_id'      => (int) $row->institut_id,
            'termin_id'        => (string) $row->termin_id,
            'status'           => (int) $row->status,
            'upd_date_raw'     => (string) $row->upd_date,
            'upd_user'         => (string) $row->upd_user,
            'fehl_datum_raw'   => (string) $row->fehl_datum,
            'fehl_datum_iso'   => $isoDate,
            'fehl_grund'       => (string) $row->fehl_grund,
            'fehl_bem'         => (string) $row->fehl_bem,
            'gekommen'         => (string) $row->gekommen,
            'gegangen'         => (string) $row->gegangen,
            'fehl_std'         => (float) $row->fehl_std,
        ];
    });

    $byParticipant = $items->groupBy('teilnehmer_id');

    $pulled = [
        'items'          => $items,
        'by_participant' => $byParticipant,
    ];

    /*
    |--------------------------------------------------------------------------
    | 3) LOCAL → REMOTE: changes in tn_fehl schreiben (PUSH)
    |--------------------------------------------------------------------------
    */

    $pushed = null;

if (!empty($changes)) {
    $results = [];

    foreach ($changes as $change) {
        $teilnehmerId = $change['teilnehmer_id'] ?? null;
        $cTermin      = $change['termin_id']     ?? $terminId;
        $cDate        = $change['date']          ?? $dateFilter;
        $action       = $change['action']        ?? null; // create|update|delete|null

        if (!$teilnehmerId || !$cTermin || !$cDate) {
            $results[] = [
                'teilnehmer_id' => $teilnehmerId,
                'date'          => $cDate,
                'action'        => 'skipped',
                'reason'        => 'teilnehmer_id, termin_id oder date fehlen',
            ];
            continue;
        }

        $fehlDatum  = str_replace('-', '/', $cDate); // "YYYY/MM/DD"
        $institutId = $change['institut_id'] ?? 0;

        $tnFehltageId = $change['tn_fehltage_id']
            ?? ($teilnehmerId . '-' . $cTermin);

        // Bestehenden Datensatz suchen
        $existing = DB::connection('uvs')
            ->table('tn_fehl')
            ->where('teilnehmer_id', $teilnehmerId)
            ->where('termin_id', $cTermin)
            ->where('fehl_datum', $fehlDatum)
            ->orderBy('uid', 'desc')
            ->first();

        // ----------------------------------------------------
        // DELETE-Pfad
        // ----------------------------------------------------
        if ($action === 'delete') {
            if ($existing) {
                DB::connection('uvs')
                    ->table('tn_fehl')
                    ->where('uid', $existing->uid)
                    ->delete();

                $results[] = [
                    'uid'           => (int) $existing->uid,
                    'tn_fehltage_id'=> (string) $existing->tn_fehltage_id,
                    'teilnehmer_id' => (string) $teilnehmerId,
                    'date'          => $cDate,
                    'action'        => 'deleted',
                ];
            } else {
                // nichts zu löschen – zur Info zurückgeben
                $results[] = [
                    'tn_fehltage_id'=> (string) $tnFehltageId,
                    'teilnehmer_id' => (string) $teilnehmerId,
                    'date'          => $cDate,
                    'action'        => 'noop_delete',
                    'reason'        => 'Kein bestehender tn_fehl-Datensatz für diese Kombination gefunden.',
                ];
            }

            continue; // nächste Änderung
        }

        // ----------------------------------------------------
        // CREATE/UPDATE-Pfad (wie bisher, leicht abgesichert)
        // ----------------------------------------------------
        $payload = [
            'tn_fehltage_id' => $tnFehltageId,
            'ec_tag_id'      => 0,
            'teilnehmer_id'  => $teilnehmerId,
            'institut_id'    => $institutId,
            'termin_id'      => $cTermin,
            'status'         => (int)($change['status'] ?? 1),
            'upd_date'       => now()->format('Y/m/d'),
            'upd_user'       => $change['upd_user'] ?? 'Baustein-Dozent (Schulnetz)',
            'fehl_datum'     => $fehlDatum,
            'fehl_grund'     => $change['fehl_grund'] ?? '',
            'fehl_bem'       => $change['fehl_bem'] ?? '',
            'gekommen'       => $change['gekommen'] ?? '00:00',
            'gegangen'       => $change['gegangen'] ?? '00:00',
            'fehl_std'       => $change['fehl_std'] ?? 0,
        ];

        if ($existing) {
            DB::connection('uvs')
                ->table('tn_fehl')
                ->where('uid', $existing->uid)
                ->update($payload);

            $results[] = [
                'uid'           => (int) $existing->uid,
                'tn_fehltage_id'=> $tnFehltageId,
                'action'        => 'updated',
                'teilnehmer_id' => $teilnehmerId,
                'date'          => $cDate,
            ];
        } else {
            $newUid = DB::connection('uvs')
                ->table('tn_fehl')
                ->insertGetId($payload);

            $results[] = [
                'uid'           => (int) $newUid,
                'tn_fehltage_id'=> $tnFehltageId,
                'action'        => 'created',
                'teilnehmer_id' => $teilnehmerId,
                'date'          => $cDate,
            ];
        }
    }

    $pushed = [
        'changes_count' => count($changes),
        'results'       => $results,
    ];
}

    /*
    |--------------------------------------------------------------------------
    | 4) RESPONSE
    |--------------------------------------------------------------------------
    */
    return response()->json([
        'ok'   => true,
        'data' => [
            'termin_id'      => $terminId,
            'teilnehmer_ids' => $teilnehmerIds,
            'pulled'         => $pulled,
            'pushed'         => $pushed,
        ],
    ]);
}



}
