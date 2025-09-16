<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Setting;

class PersonApiController extends Controller
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
     * GET /api/person/status?person_id=1-0026419
     * person_id-Format: "{institut_id}-{person_nr}"
     */
    public function getStatus(Request $request)
    {
        $data = $request->validate([
            'person_id' => 'required|string|max:255',
        ]);

        $this->connectToUvsDatabase();

        // person_id aufsplitten: institut_id - person_nr
        $personId = $data['person_id'];
        if (!str_contains($personId, '-')) {
            return response()->json([
                'ok'    => false,
                'error' => 'Ungültiges person_id-Format. Erwartet: {institut_id}-{person_nr}',
            ], 422);
        }

        [$institutId, $personNr] = explode('-', $personId, 2);

        // --- Teilnehmer-Abfrage (tvertrag) ---
        // Nimmt den neuesten Vertrag (ORDER BY vertrag_ende DESC LIMIT 1)
        $vertrag = DB::connection('uvs')->table('tvertrag')
            ->where('person_nr', $personNr)
            ->where('institut_id', $institutId)
            ->orderByDesc('vertrag_ende')
            ->first();

        $teilnehmerNr   = null;
        $lastTnDatumStr = null; // letzter_tag (String wie in UVS)
        if ($vertrag) {
            $teilnehmerNr   = $vertrag->teilnehmer_nr ?? null;
            $lastTnDatumStr = $vertrag->letzter_tag ?? null;
        }

        // --- Absolventen-Abfrage (absolven) ---
        // Neueste Absolventen-Zeile nach absolvent_id DESC
        $absolvent = DB::connection('uvs')->table('absolven')
            ->where('person_id', $personId)
            ->orderByDesc('absolvent_id')
            ->first();

        $absolventNr   = null; // rechte Seite aus "absolvent_id" (pers_inst_id-ab_nr)
        $lastAbsStr    = null; // ab_ende
        if ($absolvent) {
            $absId = $absolvent->absolvent_id ?? null; // z.B. "1-0026419001"
            if ($absId && str_contains($absId, '-')) {
                [, $absolventNr] = explode('-', $absId, 2);
            }
            $lastAbsStr = $absolvent->ab_ende ?? null;
        }

        // --- Mitarbeiter-Abfrage (mitarbei) ---
        $mitarbeiter = DB::connection('uvs')->table('mitarbei')
            ->where('person_id', $personId)
            ->first();

        $mitarbeiterNr = null;
        if ($mitarbeiter) {
            // alte Logik: person_nr . mitarbeiter_fnr
            $mitarbeiterNr = $personNr . ($mitarbeiter->mitarbeiter_fnr ?? '');
        }

        // Hilfsparser (YYYY-MM-DD oder YYYY/MM/DD)
        $parseDate = function ($value) {
            if (!$value) return null;
            try {
                return Carbon::parse(str_replace('/', '-', $value))->startOfDay();
            } catch (\Throwable $e) {
                return null;
            }
        };

        $lastTnDate  = $parseDate($lastTnDatumStr);
        $lastAbsDate = $parseDate($lastAbsStr);

        // Für Vergleich: null -> 0
        $tnTs  = $lastTnDate?->timestamp ?? 0;
        $absTs = $lastAbsDate?->timestamp ?? 0;

        // Status bestimmen (wie im alten Skript)
        // - Wenn beides 0 -> Interessent (+ interessent_nr = person_nr.'00')
        // - Sonst: größerer Timestamp gewinnt; bei Gleichheit -> Absolvent
        $personStatus = null;       // "Teilnehmer" | "Absolvent" | "Interessent"
        $personStatusShort = null;  // "TN" | "AB" | "IN"
        $interessentNr = null;

        if ($tnTs === 0 && $absTs === 0) {
            $personStatus      = 'Interessent';
            $personStatusShort = 'IN';
            $interessentNr     = $personNr . '00';
        } elseif ($tnTs > $absTs) {
            $personStatus      = 'Teilnehmer';
            $personStatusShort = 'TN';
        } elseif ($absTs > $tnTs) {
            $personStatus      = 'Absolvent';
            $personStatusShort = 'AB';
        } else { // Gleichheit
            $personStatus      = 'Absolvent';
            $personStatusShort = 'AB';
        }

        // Optionale Person-Stammdaten (falls du sie brauchst)
        // (Im alten Skript wurde nur mit Session gearbeitet; hier als saubere Ergänzung:)
        $person = DB::connection('uvs')->table('person')
            ->where('person_id', $personId)
            ->first();

        return response()->json([
            'ok'   => true,
            'data' => [
                'person_id'        => $personId,
                'institut_id'      => $institutId,
                'person_nr'        => $personNr,

                'status'           => $personStatus,        // "Teilnehmer" | "Absolvent" | "Interessent"
                'status_short'     => $personStatusShort,   // "TN" | "AB" | "IN"

                'teilnehmer_nr'    => $teilnehmerNr,
                'absolvent_nr'     => $absolventNr,
                'interessent_nr'   => $interessentNr,
                'mitarbeiter_nr'   => $mitarbeiterNr,

                // Für Transparenz/Debug
                'last_teilnehmer_tag' => $lastTnDatumStr,   // original String aus UVS (letzter_tag)
                'last_absolvent_ende' => $lastAbsStr,       // original String aus UVS (ab_ende)

                // optional: ein paar Stammdaten zurückgeben, wenn vorhanden
                'person' => $person ? [
                    'name'        => $person->name ?? null,
                    'geschlecht'  => $person->geschlecht ?? null,
                    'email_priv'  => $person->email_priv ?? null,
                    'geburt_datum'=> $person->geburt_datum ?? null,
                ] : null,
            ],
        ], 200);
    }
}
