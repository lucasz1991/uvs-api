<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class CourseApiController extends Controller
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
     * GET /api/course-classes?search=ABC&limit=25&from=2025-01-01&to=2025-12-31
     *
     * Quelle (alt): 3_1_2_suche_klassen.php / 3_10_2_suche_klassen.php
     * Kern-Tabellen: u_klasse (Klassen/Kurse), tn_u_kla (Teilnehmer↔Klasse), ma_u_kla (Dozent↔Klasse)
     */
    public function getCourseClasses(Request $request)
    {
        $data = $request->validate([
            'search' => 'sometimes|nullable|string|max:255',
            'limit'  => 'sometimes|integer|min:1|max:100',
            'from'   => 'sometimes|date_format:Y-m-d',
            'to'     => 'sometimes|date_format:Y-m-d|after_or_equal:from',
        ]);

        $this->connectToUvsDatabase();

        $limit  = $data['limit'] ?? 25;
        $search = trim((string)($data['search'] ?? ''));

        $has = fn(string $c) => \Illuminate\Support\Facades\Schema::connection('uvs')->hasColumn('u_klasse', $c);

        $hasKurzbez     = $has('kurzbez');        // bei dir: false
        $hasBezeichnung = $has('bezeichnung');    // meist true
        $hasBeginn      = $has('unterr_beginn');
        $hasEnde        = $has('unterr_ende');
        $hasDeleted     = $has('deleted');

        $q = DB::connection('uvs')
            ->table('u_klasse as k')
            ->leftJoin('ma_u_kla as mk', 'mk.klassen_id', '=', 'k.klassen_id');

        if ($search !== '') {
            $q->where(function ($w) use ($search, $hasKurzbez, $hasBezeichnung) {
                $like = "%{$search}%"; $first = true;
                if ($hasKurzbez)     { $first ? $w->where('k.kurzbez', 'like', $like)     : $w->orWhere('k.kurzbez', 'like', $like); $first=false; }
                if ($hasBezeichnung) { $first ? $w->where('k.bezeichnung', 'like', $like) : $w->orWhere('k.bezeichnung', 'like', $like); $first=false; }
                if ($first)          { $w->where('k.klassen_id', 'like', $like); }
            });
        }

        if ($hasDeleted) {
            $q->where(fn($w) => $w->whereNull('k.deleted')->orWhere('k.deleted', 0));
        }

        if (!empty($data['from']) && $hasBeginn) $q->whereDate('k.unterr_beginn', '>=', $data['from']);
        if (!empty($data['to'])   && $hasEnde)   $q->whereDate('k.unterr_ende',   '<=', $data['to']);

        $select = ['k.klassen_id'];
        if ($hasKurzbez)     $select[] = 'k.kurzbez';
        if ($hasBezeichnung) $select[] = 'k.bezeichnung';
        if ($hasBeginn)      $select[] = 'k.unterr_beginn';
        if ($hasEnde)        $select[] = 'k.unterr_ende';

        $select[] = DB::raw("(
            SELECT COUNT(*) FROM tn_u_kla tuk
            WHERE tuk.klassen_id = k.klassen_id
            AND (tuk.deleted IS NULL OR tuk.deleted = 0)
        ) as participants_count");

        $select[] = DB::raw("(
            SELECT COUNT(DISTINCT mk2.mitarbeiter_id) FROM ma_u_kla mk2
            WHERE mk2.klassen_id = k.klassen_id
            AND (mk2.deleted IS NULL OR mk2.deleted = 0)
        ) as teachers_count");

        $orderCol = $hasBeginn ? 'k.unterr_beginn' : 'k.klassen_id';

        $classes = $q->select($select)->orderBy($orderCol, 'desc')->limit($limit)->get();

        return response()->json(['ok' => true, 'data' => $classes]);
    }




    /**
     * GET /api/course-classes/participants?course_class_id=123
     *
     * Quelle (alt): 2_10_1_tn_liste.inc.php
     * Kern-Tabellen: tn_u_kla (Teilnehmer↔Klasse), person (Stammdaten), evtl. tvertrag
     */
    public function getCourseClassesParticipants(Request $request)
    {
        $data = $request->validate([
            'course_class_id' => 'required|integer|min:1',
        ]);

        $this->connectToUvsDatabase();

        $classId = (int) $data['course_class_id'];

        // Teilnehmerliste einer Klasse:
        // tn_u_kla: klassen_id, person_id (oder tn_id), deleted
        // person: person_id, nachname, vorname, email_priv, telefon1, ...
        // In manchen alten Flows war tvertrag als Brücke im Spiel; wir holen aber direkt über tn_u_kla -> person.
        $participantsQuery = DB::connection('uvs')->table('tn_u_kla as tuk');

        if (Schema::connection('uvs')->hasColumn('tn_u_kla', 'person_id')) {
            $participantsQuery
                ->join('person as p', 'p.person_id', '=', 'tuk.person_id');
        } else {
            // Fallback: über Vertrag verbinden (tn_id -> tvertrag -> person_id)
            $participantsQuery
                ->join('tvertrag as tv', 'tv.tn_id', '=', 'tuk.tn_id')
                ->join('person as p', 'p.person_id', '=', 'tv.person_id');
        }

        $participants = $participantsQuery
            ->where('tuk.klassen_id', $classId)
            ->where(function ($w) {
                $w->whereNull('tuk.deleted')->orWhere('tuk.deleted', 0);
            })
            ->orderBy('p.nachname')
            ->orderBy('p.vorname')
            ->get([
                'p.person_id',
                'p.nachname',
                'p.vorname',
                Schema::connection('uvs')->hasColumn('person','email_priv') ? 'p.email_priv' : DB::raw('NULL as email_priv'),
                Schema::connection('uvs')->hasColumn('person','telefon1')   ? 'p.telefon1'   : DB::raw('NULL as telefon1'),
                DB::raw('tuk.klassen_id'),
            ]);

        return response()->json([
            'ok'   => true,
            'data' => [
                'class_id'     => $classId,
                'participants' => $participants,
            ],
        ]);
    }
}
