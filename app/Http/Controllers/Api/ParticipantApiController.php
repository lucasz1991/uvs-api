<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Setting;

class ParticipantApiController extends Controller
{
    protected function connectToUvsDatabase(): void
    {
        config(['database.connections.uvs' => [
            'driver' => 'mysql',
            'host' => Setting::getValue('database', 'hostname'),
            'database' => Setting::getValue('database', 'database'),
            'username' => Setting::getValue('database', 'username'),
            'password' => Setting::getValue('database', 'password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]]);
    }

    public function store(Request $request)
    {
        $this->connectToUvsDatabase();

        $data = $request->validate([
            'institut_id'      => 'required|integer',
            'geschlecht'       => 'required|string|in:M,W,D,S',
            'titel_kennz'      => 'nullable|string|max:10',
            'nachname'         => 'required|string|max:125',
            'vorname'          => 'required|string|max:125',
            'adresszusatz1'    => 'nullable|string|max:255',
            'adresszusatz2'    => 'nullable|string|max:255',
            'strasse'          => 'required|string|max:125',
            'lkz'              => 'nullable|string|max:5',
            'plz'              => 'required|string|max:10',
            'ort'              => 'required|string|max:125',
            'email_priv'       => 'required|email|max:255',
            'telefon1'         => 'required|string|max:100',
            'telefon2'         => 'nullable|string|max:100',
            'geburt_datum'     => 'required|date',
            'geburt_ort'       => 'required|string|max:125',
            'nationalitaet'    => 'required|string|max:50',
            'person_kz'        => 'nullable|string|max:50',
            
            // Interessentenspezifisch
            'werbetraeger'     => 'nullable|string|max:100',
            'schulbildung'     => 'nullable|string|max:15',
            'beruf_studium'    => 'nullable|string|max:60',
            'stud_richtung'    => 'nullable|string|max:50',
            'stud_semester'    => 'nullable|integer|min:0|max:99',
            'beruf_branche'    => 'nullable|string|max:4',
            'edv_vorkenntnis'  => 'nullable|string|max:10',
            'eng_vorkenntnis'  => 'nullable|string|max:10',
            'katalog_kz'       => 'nullable|string|max:20',

            // Pflichtangaben für Interessent
            'qualifiz_art'     => 'required|string|max:20',
            'frueh_beginn'     => 'required|date_format:Y-m-d',
            'datenschutz'      => 'required|string|max:2',
        ]);

        $db = DB::connection('uvs');
        $now = Carbon::now()->format('Y-m-d');
        $institut_id = $data['institut_id'];

        try {
            $db->beginTransaction();

            $exists = $db->table('person')
                ->where('institut_id', $institut_id)
                ->where('geschlecht', $data['geschlecht'])
                ->where('vorname', $data['vorname'])
                ->where('nachname', $data['nachname'])
                ->whereDate('geburt_datum', $data['geburt_datum'])
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Duplicate person found'], 409);
            }

            $max = $db->table('person')->where('person_nr', 'like', '0%')->max('person_nr');
            $person_nr = str_pad((int)$max + 1, 7, '0', STR_PAD_LEFT);
            $person_id = $institut_id . '-' . $person_nr;

            $db->table('person')->insert([
                'institut_id'     => $institut_id,
                'person_nr'       => $person_nr,
                'person_id'       => $person_id,
                'geschlecht'      => $data['geschlecht'],
                'titel_kennz'     => $data['titel_kennz'] ?? null,
                'nachname'        => $data['nachname'],
                'vorname'         => $data['vorname'],
                'strasse'         => $data['strasse'],
                'lkz'             => $data['lkz'] ?? null,
                'plz'             => $data['plz'],
                'ort'             => $data['ort'],
                'adresszusatz1'   => $data['adresszusatz1'] ?? null,
                'adresszusatz2'   => $data['adresszusatz2'] ?? null,
                'geburt_datum'    => $data['geburt_datum'],
                'geburt_ort'      => $data['geburt_ort'],
                'nationalitaet'   => $data['nationalitaet'],
                'email_priv'      => $data['email_priv'],
                'telefon1'        => $data['telefon1'],
                'telefon2'        => $data['telefon2'] ?? null,
                'person_kz'       => $data['person_kz'] ?? null,
                'upd_date'        => $now,
            ]);

            $interessent_nr = $person_nr . '00';
            $interessent_id = $institut_id . '-' . $interessent_nr;

            $interess_data = [
                'institut_id'     => $institut_id,
                'interessent_id'  => $interessent_id,
                'person_id'       => $person_id,
                'interessent_nr'  => $interessent_nr,
                'person_nr'       => $person_nr,
                'upd_date'        => $now,
                'erst_k_datum'    => $now,
                'kontakt_status'  => 'K',
                'werbetraeger'    => $data['werbetraeger'] ?? null,
                'schulbildung'    => $data['schulbildung'] ?? null,
                'beruf_studium'   => $data['beruf_studium'] ?? null,
                'stud_richtung'   => $data['stud_richtung'] ?? null,
                'stud_semester'   => $data['stud_semester'] ?? null,
                'beruf_gattung'   => $data['beruf_branche'] ?? null,
                'edv_vorkenntnis' => $data['edv_vorkenntnis'] ?? null,
                'eng_vorkenntnis' => $data['eng_vorkenntnis'] ?? null,
                'katalog_kz'      => $data['katalog_kz'] ?? null,
                'qualifiz_art'    => $data['qualifiz_art'],
                'frueh_beginn'    => $data['frueh_beginn'],
                'datenschutz'     => $data['datenschutz'],
            ];

            $db->table('interess')->insert($interess_data);
            $db->commit();

            return response()->json([
                'message' => 'Person + Interessent erfolgreich gespeichert',
                'person_id' => $person_id,
                'interessent_id' => $interessent_id,
            ]);

        } catch (\Throwable $e) {
            $db->rollBack();
            return response()->json(['error' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }
}
