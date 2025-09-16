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
        try {
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
                'werbetraeger'     => 'nullable|string|max:100',
                'schulbildung'     => 'nullable|string|max:15',
                'beruf_studium'    => 'nullable|string|max:60',
                'stud_richtung'    => 'nullable|string|max:50',
                'stud_semester'    => 'nullable|integer|min:0|max:99',
                'beruf_branche'    => 'nullable|string|max:4',
                'edv_vorkenntnis'  => 'nullable|string|max:10',
                'eng_vorkenntnis'  => 'nullable|string|max:10',
                'katalog_kz'       => 'nullable|string|max:20',
                'qualifiz_art'     => 'required|string|max:20',
                'foreign_id'       => 'nullable|string|max:50',

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // optional: Business-Activity fürs Validierungsversagen
            activity('uvs')
                ->causedBy($request->user())
                ->withProperties([
                    'event'   => 'participant.validation_failed',
                    'errors'  => $e->errors(),
                    'api_key_id' => optional($request->attributes->get('apiKey'))->id,
                ])->log('Participant Data validation failed');
    
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        }

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
                activity('uvs')
                ->causedBy($request->user())
                ->withProperties([
                    'event'         => 'participant.duplicate',
                    'institut_id'   => $institut_id,
                    'vorname'       => $data['vorname'],
                    'nachname'      => $data['nachname'],
                    'geburt_datum'  => $data['geburt_datum'],
                    'email_masked'  => $this->maskEmail($data['email_priv']),
                    // Optional hilfreich: referenziere den genutzten ApiKey **ohne** Token
                    'api_key_id'    => optional($request->attributes->get('apiKey'))->id,
                ])
                ->log('Duplicate participant detected');
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
                'foreign_id'      => $data['foreign_id'] ?? null,
                'referrer'       => optional($request->attributes->get('apiKey'))->name ?? null,
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
            ];

            $db->table('interess')->insert($interess_data);
            $db->commit();

            activity('uvs')
            ->causedBy($request->user())
            ->withProperties([
                'event'           => 'participant.created',
                'institut_id'     => $institut_id,
                'person_id'       => $person_id,
                'interessent_id'  => $interessent_id,
                'email_masked'    => $this->maskEmail($data['email_priv']),
                'telefon1_suffix' => $this->suffix($data['telefon1']),
                'api_key_id'      => optional($request->attributes->get('apiKey'))->id, // kein Token!
            ])
            ->log('Participant and Interessent created');

            return response()->json([
                'message' => 'Person + Interessent erfolgreich gespeichert',
                'person_id' => $person_id,
                'interessent_id' => $interessent_id,
            ]);

        } catch (\Throwable $e) {
            $db->rollBack();
            activity('uvs')
            ->causedBy($request->user())
            ->withProperties([
                'event'         => 'participant.create_failed',
                'institut_id'   => $data['institut_id'] ?? null,
                'error_class'   => get_class($e),
                'error_message' => $e->getMessage(),
                'api_key_id'    => optional($request->attributes->get('apiKey'))->id,
            ])
            ->log('Participant creation failed');
            return response()->json(['error' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function get(Request $request)
    {
        $this->connectToUvsDatabase();
        $person_mail = $request->query('mail');
        if (!$person_mail) {
            return response()->json(['message' => 'mail is required'], 400);
        }

        $db = DB::connection('uvs');
        $person = $db->table('person')->where('email_priv', $person_mail)->first();
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }

        activity('uvs')
            ->causedBy($request->user())
            ->withProperties([
                'event'         => 'participant.retrieved',
                'institut_id'   => $person->institut_id,
                'person_id'     => $person->person_id,
            ])
            ->log('Participant data retrieved');

        return response()->json(['person' => $person]);
    }

    public function getParticipantAndQualiprogram(Request $request, string $person_id)
    {
        $this->connectToUvsDatabase();
        $db = DB::connection('uvs');

        // Person laden
        $person = $db->table('person')->where('person_id', $person_id)->first();
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }

        // Falls eine bestimmte Beratung gewünscht ist (Query-Param), sonst "neueste" Beratung ermitteln
        $beratungId = $request->query('beratung_id');

        if (!$beratungId) {
            $beratungRow = $db->table('xvertrag AS xv')
                ->leftJoin('ivertrag AS iv', 'iv.beratung_id', '=', 'xv.beratung_id')
                ->leftJoin('tvertrag AS tv', 'tv.teilnehmer_id', '=', 'xv.teilnehmer_id')
                ->leftJoin('person AS p', 'p.person_id', '=', 'iv.person_id')
                ->where('p.person_id', $person_id)
                ->orderByDesc('tv.vertrag_beginn')
                ->select('xv.beratung_id')
                ->first();
            if (!$beratungRow) {
                return response()->json([
                    'person' => $person,
                    'quali_data' => null,
                    'message' => 'No contract found for this person',
                ], 200);
            }
            $beratungId = $beratungRow->beratung_id;
        }

        // Haupt-Qualiprogramm + Vertragsdaten laden (entspricht deinem $query_str)
        $qualiBase = $db->table('xvertrag AS xv')
            ->leftJoin('ivertrag AS iv', 'iv.beratung_id', '=', 'xv.beratung_id')
            ->leftJoin('tvertrag AS tv', 'tv.teilnehmer_id', '=', 'xv.teilnehmer_id')
            ->leftJoin('person AS p', 'p.person_id', '=', 'iv.person_id')
            ->leftJoin('interess AS i', 'i.person_id', '=', 'p.person_id')
            ->leftJoin('massnahm AS mn', 'mn.massnahme_id', '=', 'tv.massnahme_id')
            ->where('xv.beratung_id', $beratungId)
            ->where('p.person_id', $person_id)
            ->selectRaw("
                xv.teilnehmer_nr,
                p.geschlecht,
                CONCAT(p.nachname, ', ', p.vorname) AS name,
                p.geburt_datum,
                p.kunden_nr,
                tv.stammklasse,
                tv.vtz_kennz_mn AS vtz,
                iv.vertrag_uform AS uform_kurz,
                tv.kurzbez_mn AS massn_kurz,
                tv.vertrag_beginn,
                tv.vertrag_ende,
                tv.vertrag_datum,
                tv.vertrag_baust,
                mn.langbez_w,
                mn.langbez_m,
                iv.rechnung_nr,
                i.test_punkte,
                tv.teilnehmer_id,
                p.stamm_nr_kst,
                iv.storno_zum,
                iv.kuendig_zum,
                p.email_priv
            ")
            ->first();

        if (!$qualiBase) {
            return response()->json([
                'person' => $person,
                'quali_data' => null,
                'message' => 'Contract not found for given beratung_id',
            ], 200);
        }

        // Kostenträger-Info (entspricht deinem $kst_str)
        $kostData = $db->table('x_iv_kst')
            ->leftJoin('mpperson AS mp', 'mp.mpperson_id', '=', 'x_iv_kst.mpperson_id')
            ->leftJoin('mpbuero AS mpb', 'mpb.mpbuero_id', '=', 'x_iv_kst.mpbuero_id')
            ->where('x_iv_kst.beratung_id', $beratungId)
            ->select([
                'mpb.langbez AS mp_langbez',
                'mpb.plz AS mp_plz',
                'mpb.ort AS mp_ort',
                'mp.nachname AS mp_nachname',
                'mp.vorname AS mp_vorname',
            ])
            ->first();

        $qualiprog = [];
        // Basis-Felder aus Vertrag
        $qualiprog['teilnehmer_id']   = $qualiBase->teilnehmer_id;
        $qualiprog['teilnehmer_nr']   = $qualiBase->teilnehmer_nr;
        $qualiprog['geschlecht']      = $qualiBase->geschlecht;
        $qualiprog['name']            = $qualiBase->name;
        $qualiprog['geburt_datum']    = $this->dateToDotted($qualiBase->geburt_datum);
        $qualiprog['kunden_nr']       = $qualiBase->kunden_nr;
        $qualiprog['stammklasse']     = $qualiBase->stammklasse;
        $qualiprog['vtz']             = $qualiBase->vtz; // Kurzcode; Langtext folgt unten
        $qualiprog['uform_kurz']      = $qualiBase->uform_kurz;
        $qualiprog['massn_kurz']      = $qualiBase->massn_kurz;
        $qualiprog['vertrag_beginn']  = $this->dateToDotted($qualiBase->vertrag_beginn);
        $qualiprog['vertrag_ende']    = $this->dateToDotted($qualiBase->vertrag_ende);
        $qualiprog['vertrag_datum']   = $this->dateToDotted($qualiBase->vertrag_datum);
        $qualiprog['vertrag_baust']   = $qualiBase->vertrag_baust;
        $qualiprog['langbez_w']       = $qualiBase->langbez_w;
        $qualiprog['langbez_m']       = $qualiBase->langbez_m;
        $qualiprog['rechnung_nr']     = $qualiBase->rechnung_nr;
        $qualiprog['test_punkte']     = $qualiBase->test_punkte;
        $qualiprog['stamm_nr_kst']    = $qualiBase->stamm_nr_kst;
        $qualiprog['storno_zum']      = $this->dateToDotted($qualiBase->storno_zum);
        $qualiprog['kuendig_zum']     = $this->dateToDotted($qualiBase->kuendig_zum);
        $qualiprog['email_priv']      = $qualiBase->email_priv;

        // Kostenträger-Block
        $qualiprog['mp_langbez']  = $kostData->mp_langbez  ?? '';
        $qualiprog['mp_plz']      = $kostData->mp_plz      ?? '';
        $qualiprog['mp_ort']      = $kostData->mp_ort      ?? '';
        $qualiprog['mp_nachname'] = $kostData->mp_nachname ?? '';
        $qualiprog['mp_vorname']  = $kostData->mp_vorname  ?? '';

        // U-Form (Langtext) & VTZ (Langtext) aus keydefs
        $qualiprog['uform'] = '';
        if (!empty($qualiBase->uform_kurz)) {
            $qualiprog['uform'] = (string) $db->table('keydefs')
                ->where('schluessel_wert', $qualiBase->uform_kurz)
                ->where('schluessel_name', 'UFOR')
                ->where('deleted', 0)
                ->value('text1') ?? '';
        }

        $qualiprog['vtz_lang'] = (string) $db->table('keydefs')
            ->where('schluessel_wert', $qualiBase->vtz)
            ->where('schluessel_name', 'VTZK')
            ->where('deleted', 0)
            ->value('text1') ?? '';

        // Termin-Institut (entspricht deiner Logik)
        $vertragBeginnRaw = $qualiBase->vertrag_beginn;
        $terminInstId = ($vertragBeginnRaw && $vertragBeginnRaw < '2025-01-01')
            ? $person->institut_id
            : '1';

        // Teilnehmer-Baustein-Kette laden (entspricht deinem $tn_baust_str)
        $bausteine = $db->table('tn_baust')
            ->leftJoin('baustein', 'baustein.baustein_id', '=', 'tn_baust.baustein_id')
            ->leftJoin('termin', 'termin.termin_id', '=', 'tn_baust.termin_id_ham')
            ->leftJoin('tn_u_kla', function ($join) {
                $join->on('tn_u_kla.baustein_id', '=', 'tn_baust.baustein_id')
                    ->on('tn_u_kla.teilnehmer_id', '=', 'tn_baust.teilnehmer_id');
            })
            ->where('tn_baust.teilnehmer_id', $qualiBase->teilnehmer_id)
            ->where('tn_baust.deleted', '0')
            ->where('termin.institut_id', $terminInstId) // früher: $qprog_inst_id = '1'
            ->groupBy('tn_baust.tn_baustein_id')
            ->orderBy('termin.beginn_baustein')
            ->select([
                'termin.beginn_baustein',
                'termin.ende_baustein',
                'termin.baustein_tage',
                'tn_u_kla.klassen_co_ks',
                'tn_baust.kurzbez_ba',
                'baustein.langbez',
                'tn_baust.tn_fehltage',
                'termin.termin_id',
                'tn_u_kla.klassen_id',
                'baustein.unterricht_pfl',
                'baustein.baustein_id',
                'tn_baust.tn_baustein_id',
            ])
            ->get();

        $tn_baust = [];
        $kl_punkte_ges = 0;
        $kl_punkte_count = 0;
        $tn_punkte_ges = 0;
        $fehltage_ges = 0;
        $u_tage = 0;
        $u_bausteine = 0;
        $prak_tage = 0;

        foreach ($bausteine as $row) {
            $currBausteinId = $row->baustein_id;
            $currTerminId   = $row->termin_id;

            $item = [
                'beginn_baustein' => $this->dateToDotted($row->beginn_baustein),
                'ende_baustein'   => $this->dateToDotted($row->ende_baustein),
                'baustein_tage'   => (int)($row->baustein_tage ?? 0),
                'klassen_co_ks'   => $row->klassen_co_ks,
                'kurzbez'         => $row->kurzbez_ba,
                'langbez'         => trim(($row->kurzbez_ba ?? '') . ' - ' . ($row->langbez ?? ''), ' -'),
                'fehltage'        => 0,
                'klassenschnitt'  => 0,
                'tn_punkte'       => 0,
            ];

            // Fehl-Tage pro Termin (ohne TA)
            $fehltage = (int) $db->table('tn_fehl')
                ->where('termin_id', $currTerminId)
                ->where('teilnehmer_id', $qualiBase->teilnehmer_id)
                ->where('fehl_grund', '!=', '')
                ->where('fehl_grund', '!=', 'TA')
                ->count();

            $item['fehltage'] = $fehltage;
            $fehltage_ges += $fehltage;

            // Klassen-Schnitt (AVG) – Filter wie im Altcode
            $klSchnitt = $db->table('tn_p_kla')
                ->where('klassen_id', $row->klassen_id)
                ->where('baustein_id', $currBausteinId)
                ->whereNotIn('kurzbez_ba', ['PRAK','FERI'])
                ->where('kurzbez_ba', 'NOT LIKE', 'PRUE%')
                ->whereNotIn('pruef_kennz', ['B','D','X','E','XO'])
                ->avg('pruef_punkte');

            if ($klSchnitt !== null) {
                $klSchnittRounded = (int) round($klSchnitt);
                $item['klassenschnitt'] = (int) floor($klSchnittRounded);
                $kl_punkte_ges += $klSchnittRounded;
                if ($klSchnittRounded > 0) {
                    $kl_punkte_count++;
                }
            } else {
                $item['klassenschnitt'] = 0;
            }

            // TN-Punkte für diesen Baustein (nimmt 1 Datensatz – wie Altcode)
            $tnRow = $db->table('tn_p_kla')
                ->where('tn_baustein_id', $row->tn_baustein_id)
                ->select(['pruef_punkte AS tn_punkte','pruef_kennz'])
                ->first();

            if ($tnRow) {
                $tn_punkte_baust = (int) ($tnRow->tn_punkte ?? 0);
                $pruef_kennz     = (string) ($tnRow->pruef_kennz ?? '');

                if ($pruef_kennz === 'I') {
                    // wie im Altcode: I reduziert den Klassenpunkte-Counter wieder
                    $kl_punkte_count = max(0, $kl_punkte_count - 1);
                }

                if ($tn_punkte_baust > 0) {
                    $item['tn_punkte'] = $tn_punkte_baust;
                    $tn_punkte_ges += $tn_punkte_baust;
                }

                // Spezialfälle – 1:1 übernommen
                if ($pruef_kennz === 'B') { $item['klassenschnitt'] = 'extern'; $item['tn_punkte'] = 'passed'; }
                elseif ($pruef_kennz === 'D') { $item['klassenschnitt'] = 'extern'; $item['tn_punkte'] = 'failed'; }
                elseif ($pruef_kennz === 'X') { $item['klassenschnitt'] = 'extern'; $item['tn_punkte'] = 'not att'; }
                elseif ($pruef_kennz === 'E') { $item['klassenschnitt'] = 'extern'; $item['tn_punkte'] = 'pending'; }
                elseif ($pruef_kennz === 'XO') { $item['klassenschnitt'] = 'extern'; $item['tn_punkte'] = 'pending'; }
                elseif ($pruef_kennz === 'I') { $item['tn_punkte'] = '---'; }
            }

            // FERI/PRUE/PRAK überschreibt Anzeige
            if (in_array($row->kurzbez_ba, ['FERI','PRUE','PRAK'], true)) {
                $item['tn_punkte'] = '---';
                $item['klassenschnitt'] = '---';
            }
            if ($row->kurzbez_ba === 'FERI') {
                $item['fehltage'] = '-';
            }

            // Summenblöcke wie im Altcode
            if (($row->unterricht_pfl ?? '') === 'J') {
                $u_tage += (int)($row->baustein_tage ?? 0);
                $u_bausteine++;
            }
            if ($row->kurzbez_ba === 'PRAK') {
                $prak_tage += (int)($row->baustein_tage ?? 0);
            }

            $tn_baust[] = $item;
        }

        // Relevante Bausteine zählen (entspricht deinem $relev_bausteine_str)
        $relev_bausteine = (int) $db->table('tn_p_kla')
            ->where('teilnehmer_id', $qualiBase->teilnehmer_id)
            ->whereNotIn('kurzbez_ba', ['PRAK','FERI'])
            ->where('kurzbez_ba', 'NOT LIKE', 'PRUE%')
            ->whereNotIn('pruef_kennz', ['B','D','E','X','XO','I',''])
            ->count();

        $tn_schnitt = 0;
        $klassen_schnitt = 0;
        if ($relev_bausteine > 0) {
            $tn_schnitt       = (int) ceil($tn_punkte_ges / $relev_bausteine);
            $klassen_schnitt  = (int) floor($kl_punkte_ges / $relev_bausteine);
        }

        $qualiprog['tn_baust'] = $tn_baust;

        // Summenblock wie im Altcode
        $qualiprog['summen'] = [
            'note_lang'       => $this->ergebnisLang($tn_schnitt), // frei anpassbar
            'klassen_schnitt' => $klassen_schnitt,
            'tn_schnitt'      => $tn_schnitt,
            'fehltage'        => $fehltage_ges,
            'u_tage'          => $u_tage,
            'u_std'           => $u_bausteine * 80,
            'prak_tage'       => $prak_tage,
            'prak_std'        => $prak_tage * 8,
        ];

        activity('uvs')
            ->causedBy($request->user())
            ->withProperties([
                'event'       => 'participant.qualiprograms_retrieved',
                'institut_id' => $person->institut_id,
                'person_id'   => $person->person_id,
                'beratung_id' => $beratungId,
            ])->log('Participant and Qualiprogram data retrieved');

        return response()->json([
            'person'     => $person,
            'quali_data' => $qualiprog,
        ]);
    }

    /** ---------- Helper ---------- */

    private function dateToDotted(?string $ymd): ?string
    {
        if (!$ymd) return null;
        // unterstützt ggf. 'Y-m-d H:i:s'
        $date = substr($ymd, 0, 10);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return $ymd;
        [$y,$m,$d] = explode('-', $date);
        return sprintf('%02d.%02d.%04d', (int)$d, (int)$m, (int)$y);
    }

    private function ergebnisLang(int $points): string
    {
        // Platzhalter-Logik – passe sie deiner internen "ergebnis_lang"-Definition an
        if ($points >= 90) return 'sehr gut';
        if ($points >= 80) return 'gut';
        if ($points >= 67) return 'befriedigend';
        if ($points >= 50) return 'ausreichend';
        if ($points > 0)   return 'mangelhaft';
        return '—';
    }

    protected function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        $localMasked = strlen($local) > 1 ? substr($local, 0, 1) . str_repeat('*', max(1, strlen($local) - 1)) : '*';
        return $localMasked . '@' . $domain;
    }

    protected function suffix(?string $phone, int $len = 3): ?string
    {
        if (!$phone) return null;
        $digits = preg_replace('/\D+/', '', $phone);
        return strlen($digits) > $len ? substr($digits, -$len) : $digits;
    }
}
