<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


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
}
