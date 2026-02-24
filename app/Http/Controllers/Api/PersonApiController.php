<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PersonApiController extends BaseUvsController
{
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

        $personId = $data['person_id'];
        if (!str_contains($personId, '-')) {
            return response()->json([
                'ok'    => false,
                'error' => 'Ungueltiges person_id-Format. Erwartet: {institut_id}-{person_nr}',
            ], 422);
        }

        [$institutId, $personNr] = explode('-', $personId, 2);

        // UVS date parser for multiple input formats
        $parseDate = function ($value) {
            if (!$value) {
                return null;
            }

            $raw = trim((string) $value);
            if ($raw === '') {
                return null;
            }

            foreach (['Y-m-d', 'Y/m/d', 'd/m/Y', 'd-m-Y'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $raw)->startOfDay();
                } catch (\Throwable $e) {
                    // try next
                }
            }

            try {
                return Carbon::parse(str_replace('/', '-', $raw))->startOfDay();
            } catch (\Throwable $e) {
                return null;
            }
        };

        $today = Carbon::today();

        // Load participant contracts including cancelled ones.
        // xvertrag/ivertrag are 1:n, so we deduplicate after mapping.
        $vertragRows = DB::connection('uvs')->table('tvertrag AS tv')
            ->leftJoin('xvertrag AS xv', 'xv.teilnehmer_id', '=', 'tv.teilnehmer_id')
            ->leftJoin('ivertrag AS iv', 'iv.beratung_id', '=', 'xv.beratung_id')
            ->where('tv.person_nr', $personNr)
            ->where('tv.institut_id', $institutId)
            ->orderByDesc('tv.vertrag_ende')
            ->select([
                'tv.teilnehmer_id',
                'tv.teilnehmer_nr',
                'tv.letzter_tag',
                'tv.vertrag_ende',
                'iv.kuendig_zum',
            ])
            ->get()
            ->map(function ($row) use ($parseDate, $today) {
                $kuendigDate = $parseDate($row->kuendig_zum ?? null);
                $vertragEndeDate = $parseDate($row->vertrag_ende ?? null);

                return [
                    'teilnehmer_id' => $row->teilnehmer_id ?? null,
                    'teilnehmer_nr' => $row->teilnehmer_nr ?? null,
                    'letzter_tag' => $row->letzter_tag ?? null,
                    'vertrag_ende' => $row->vertrag_ende ?? null,
                    'kuendig_zum' => $row->kuendig_zum ?? null,
                    'is_active' => is_null($kuendigDate) || $kuendigDate->gt($today),
                    '_kuendig_ts' => $kuendigDate?->timestamp ?? 0,
                    '_vertrag_ende_ts' => $vertragEndeDate?->timestamp ?? 0,
                ];
            })
            ->groupBy(function ($row) {
                return implode('|', [
                    $row['teilnehmer_id'] ?? '',
                    $row['teilnehmer_nr'] ?? '',
                    $row['letzter_tag'] ?? '',
                    $row['vertrag_ende'] ?? '',
                ]);
            })
            ->map(function ($rows) {
                return $rows
                    ->sortByDesc(function ($row) {
                        $hasKuendig = trim((string) ($row['kuendig_zum'] ?? '')) !== '';
                        return ($hasKuendig ? 1 : 0) * 10000000000 + ($row['_kuendig_ts'] ?? 0);
                    })
                    ->first();
            })
            ->values();

        // Selection rule:
        // 1) Active contract with largest vertrag_ende
        // 2) Otherwise latest cancelled contract
        $selectedVertrag = $vertragRows
            ->where('is_active', true)
            ->sortByDesc('_vertrag_ende_ts')
            ->first();

        if (!$selectedVertrag) {
            $selectedVertrag = $vertragRows
                ->sortByDesc('_vertrag_ende_ts')
                ->first();
        }

        $teilnehmerNr = $selectedVertrag['teilnehmer_nr'] ?? null;
        $lastTnDatumStr = $selectedVertrag['letzter_tag'] ?? null;
        $vertragKuendigZum = $selectedVertrag['kuendig_zum'] ?? null;

        $absolvent = DB::connection('uvs')->table('absolven')
            ->where('person_id', $personId)
            ->orderByDesc('absolvent_id')
            ->first();

        $absolventNr = null;
        $lastAbsStr = null;
        if ($absolvent) {
            $absId = $absolvent->absolvent_id ?? null;
            if ($absId && str_contains($absId, '-')) {
                [, $absolventNr] = explode('-', $absId, 2);
            }
            $lastAbsStr = $absolvent->ab_ende ?? null;
        }

        $mitarbeiter = DB::connection('uvs')->table('mitarbei')
            ->where('person_id', $personId)
            ->first();

        $mitarbeiterNr = null;
        if ($mitarbeiter) {
            $mitarbeiterNr = $personNr . ($mitarbeiter->mitarbeiter_fnr ?? '');
        }

        $lastTnDate = $parseDate($lastTnDatumStr);
        $lastAbsDate = $parseDate($lastAbsStr);

        $tnTs = $lastTnDate?->timestamp ?? 0;
        $absTs = $lastAbsDate?->timestamp ?? 0;

        $personStatus = null;
        $personStatusShort = null;
        $interessentNr = null;

        if ($tnTs === 0 && $absTs === 0) {
            $personStatus = 'Interessent';
            $personStatusShort = 'IN';
            $interessentNr = $personNr . '00';
        } elseif ($tnTs > $absTs) {
            $personStatus = 'Teilnehmer';
            $personStatusShort = 'TN';
        } elseif ($absTs > $tnTs) {
            $personStatus = 'Absolvent';
            $personStatusShort = 'AB';
        } else {
            $personStatus = 'Absolvent';
            $personStatusShort = 'AB';
        }

        $person = DB::connection('uvs')->table('person')
            ->where('person_id', $personId)
            ->first();

        return response()->json([
            'ok'   => true,
            'data' => [
                'person_id'        => $personId,
                'institut_id'      => $institutId,
                'person_nr'        => $personNr,

                'status'           => $personStatus,
                'status_short'     => $personStatusShort,

                'teilnehmer_nr'    => $teilnehmerNr,
                'absolvent_nr'     => $absolventNr,
                'interessent_nr'   => $interessentNr,
                'mitarbeiter_nr'   => $mitarbeiterNr,

                'vertrag_kuendig_zum' => $vertragKuendigZum,
                'last_teilnehmer_tag' => $lastTnDatumStr,
                'last_absolvent_ende' => $lastAbsStr,

                'vertraege' => $vertragRows
                    ->map(fn($v) => [
                        'teilnehmer_id' => $v['teilnehmer_id'],
                        'teilnehmer_nr' => $v['teilnehmer_nr'],
                        'letzter_tag' => $v['letzter_tag'],
                        'vertrag_ende' => $v['vertrag_ende'],
                        'kuendig_zum' => $v['kuendig_zum'],
                        'is_active' => $v['is_active'],
                    ])
                    ->values(),

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
