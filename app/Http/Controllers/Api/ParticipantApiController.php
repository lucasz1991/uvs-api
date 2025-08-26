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
            'institut_id' => 'required|string|max:255',
            'geschlecht' => 'required|string|max:10',
            'titel_kennz' => 'nullable|string|max:10',
            'vorname' => 'required|string|max:255',
            'nachname' => 'required|string|max:255',
            'strasse' => 'nullable|string|max:255',
            'lkz' => 'nullable|string|max:5',
            'plz' => 'nullable|string|max:20',
            'ort' => 'nullable|string|max:255',
            'adresszusatz1' => 'nullable|string|max:255',
            'adresszusatz2' => 'nullable|string|max:255',
            'email_priv' => 'nullable|email|max:255',
            'telefon1' => 'nullable|string|max:100',
            'telefon2' => 'nullable|string|max:100',
            'geburt_datum' => 'nullable|date',
            'geburt_ort' => 'nullable|string|max:255',
            'nationalitaet' => 'nullable|string|max:100',
            'person_kz' => 'nullable|string|max:50',

            // Optional bei Interessent
            'interess_new_data' => 'boolean',
            'ext_in_data' => 'nullable|array',
        ]);

        $db = DB::connection('uvs');
        $now = Carbon::now()->format('Y-m-d');
        $institut_id = $data['institut_id'];

        try {
            $db->beginTransaction();

            // Prüfen auf doppelte Person
            $exists = $db->table('person')
                ->where('institut_id', $institut_id)
                ->where('geschlecht', $data['geschlecht'])
                ->where('vorname', $data['vorname'])
                ->where('nachname', $data['nachname'])
                ->whereDate('geburt_datum', $data['geburt_datum'] ?? null)
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Duplicate person found'], 409);
            }

            // Neue person_nr ermitteln
            $max = $db->table('person')
                ->where('person_nr', 'like', '0%')
                ->max('person_nr');

            $person_nr = str_pad((int)$max + 1, 7, '0', STR_PAD_LEFT);
            $person_id = $institut_id . '-' . $person_nr;

            // Person speichern
            $db->table('person')->insert([
                'institut_id' => $institut_id,
                'person_nr' => $person_nr,
                'person_id' => $person_id,
                'geschlecht' => $data['geschlecht'] ?? null,
                'titel_kennz' => $data['titel_kennz'] ?? null,
                'vorname' => $data['vorname'] ?? null,
                'nachname' => $data['nachname'] ?? null,
                'strasse' => $data['strasse'] ?? null,
                'lkz' => $data['lkz'] ?? null,
                'plz' => $data['plz'] ?? null,
                'ort' => $data['ort'] ?? null,
                'adresszusatz1' => $data['adresszusatz1'] ?? null,
                'adresszusatz2' => $data['adresszusatz2'] ?? null,
                'geburt_datum' => $data['geburt_datum'] ?? null,
                'geburt_ort' => $data['geburt_ort'] ?? null,
                'nationalitaet' => $data['nationalitaet'] ?? null,
                'email_priv' => $data['email_priv'] ?? null,
                'telefon1' => $data['telefon1'] ?? null,
                'telefon2' => $data['telefon2'] ?? null,
                'person_kz' => $data['person_kz'] ?? null,
                'upd_date' => $now,
            ]);

            // Interessent erzeugen
            $interessent_nr = $person_nr . '00';
            $interessent_id = $institut_id . '-' . $interessent_nr;

            $interess_data = [
                'institut_id' => $institut_id,
                'interessent_id' => $interessent_id,
                'person_id' => $person_id,
                'interessent_nr' => $interessent_nr,
                'person_nr' => $person_nr,
                'upd_date' => $now,
                'erst_k_datum' => $now,
                'kontakt_status' => 'K',
            ];

            if (!empty($data['interess_new_data']) && filled($data['ext_in_data'])) {
                $interess_data = array_merge($interess_data, $data['ext_in_data']);
            }

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
