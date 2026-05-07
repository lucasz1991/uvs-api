<?php

namespace App\Http\Controllers\Api;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UVSApiController extends BaseUvsController
{
    /**
     * Retrieves due dates for management.
     *
     * This method handles the API request to fetch due dates related to business management.
     *
     * @param Request $request The incoming HTTP request containing any necessary parameters.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response Returns a JSON response with the due dates data or an error response.
     */
    public function getDueDatesManagement(Request $request)
    {
        $filters = $request->validate([
            'institut_ids' => 'sometimes|string|max:255',
            'participant_number' => 'sometimes|string|max:50',
            'course' => 'sometimes|string|max:50',
            'beratung_id' => 'sometimes|string|max:50',
            'from' => 'sometimes|date_format:Y-m-d',
            'to' => 'sometimes|date_format:Y-m-d|after_or_equal:from',
            'min_amount' => 'sometimes|numeric',
            'max_amount' => 'sometimes|numeric|gte:min_amount',
            'limit' => 'sometimes|integer|min:1|max:50000',
        ]);

        $this->connectToUvsDatabase();
        $db = DB::connection('uvs');

        $participantContractMap = $db->table('tvertrag as tv')
            ->selectRaw('MAX(tv.uid) as uid, tv.person_id, tv.kurzbez_mn, tv.vertrag_beginn')
            ->groupBy('tv.person_id', 'tv.kurzbez_mn', 'tv.vertrag_beginn');

        $participantMassMap = $db->table('tvertrag as tv')
            ->selectRaw('MAX(tv.uid) as uid, tv.person_id, tv.kurzbez_mn')
            ->groupBy('tv.person_id', 'tv.kurzbez_mn');

        $participantPersonMap = $db->table('tvertrag as tv')
            ->selectRaw('MAX(tv.uid) as uid, tv.person_id')
            ->groupBy('tv.person_id');

        $contracts = $db->table('ivertrag as iv')
            ->leftJoinSub($participantContractMap, 'tv_pick', function ($join) {
                $join->on('tv_pick.person_id', '=', 'iv.person_id')
                    ->on('tv_pick.kurzbez_mn', '=', 'iv.vertrag_mass')
                    ->on('tv_pick.vertrag_beginn', '=', 'iv.vertrag_beginn');
            })
            ->leftJoinSub($participantMassMap, 'tv_mass_pick', function ($join) {
                $join->on('tv_mass_pick.person_id', '=', 'iv.person_id')
                    ->on('tv_mass_pick.kurzbez_mn', '=', 'iv.vertrag_mass');
            })
            ->leftJoinSub($participantPersonMap, 'tv_person_pick', function ($join) {
                $join->on('tv_person_pick.person_id', '=', 'iv.person_id');
            })
            ->leftJoin('tvertrag as tv', 'tv.uid', '=', 'tv_pick.uid')
            ->leftJoin('tvertrag as tv_mass', 'tv_mass.uid', '=', 'tv_mass_pick.uid')
            ->leftJoin('tvertrag as tv_person', 'tv_person.uid', '=', 'tv_person_pick.uid')
            ->leftJoin('institut as inst', 'inst.institut_id', '=', 'iv.institut_id')
            ->select([
                'iv.uid',
                'iv.beratung_id',
                'iv.beratung_nr',
                'iv.interessent_nr',
                'iv.person_id',
                'iv.institut_id',
                'iv.vertrag_mass',
                'iv.vertrag_beginn',
                'iv.vertrag_ende',
                'iv.vertrag_datum',
                'iv.geb_gesamt',
                'iv.raten',
                'iv.ratenvertrag',
                'iv.raten_aa',
                'iv.raten_bfd',
                'iv.raten_tn',
                'iv.raten_so',
                'iv.prozent_aa',
                'iv.prozent_bfd',
                'iv.prozent_tn',
                'iv.prozent_so',
                'iv.dm_aa',
                'iv.dm_bfd',
                'iv.dm_tn',
                'iv.dm_so',
                'iv.zahlungsart_aa',
                'iv.zahlungsart_bfd',
                'iv.zahlungsart_tn',
                'iv.zahlungsart_so',
                'tv.teilnehmer_nr',
                'tv_mass.teilnehmer_nr as teilnehmer_nr_mass',
                'tv_person.teilnehmer_nr as teilnehmer_nr_person',
                'tv.kurzbez_mn',
                'inst.institut_co',
                'inst.ort',
            ]);

        $this->applyInstituteFilters($contracts, $filters, 'iv.institut_id');

        if (!empty($filters['participant_number'])) {
            $participantNumber = trim($filters['participant_number']);

            $contracts->where(function ($query) use ($participantNumber) {
                $query->where('tv.teilnehmer_nr', 'like', '%' . $participantNumber . '%')
                    ->orWhere('tv_mass.teilnehmer_nr', 'like', '%' . $participantNumber . '%')
                    ->orWhere('tv_person.teilnehmer_nr', 'like', '%' . $participantNumber . '%')
                    ->orWhere('iv.interessent_nr', 'like', '%' . $participantNumber . '%')
                    ->orWhereRaw("SUBSTRING(COALESCE(NULLIF(iv.beratung_nr, ''), SUBSTRING_INDEX(iv.beratung_id, '-', -1)), 1, 9) like ?", [
                        '%' . $participantNumber . '%',
                    ]);
            });
        }

        if (!empty($filters['course'])) {
            $course = trim($filters['course']);
            $contracts->where(function ($query) use ($course) {
                $query->where('tv.kurzbez_mn', 'like', '%' . $course . '%')
                    ->orWhere('iv.vertrag_mass', 'like', '%' . $course . '%');
            });
        }

        if (!empty($filters['beratung_id'])) {
            $contracts->where('iv.beratung_id', 'like', '%' . trim($filters['beratung_id']) . '%');
        }

        $contracts->orderByRaw("
                COALESCE(
                    STR_TO_DATE(NULLIF(iv.vertrag_beginn, ''), '%Y/%m/%d'),
                    STR_TO_DATE(NULLIF(iv.vertrag_datum, ''), '%Y/%m/%d'),
                    STR_TO_DATE(NULLIF(iv.vertrag_ende, ''), '%Y/%m/%d')
                ) ASC
            ")
            ->orderBy('iv.uid');

        return $this->streamCsvDownload(
            'faelligkeiten_gf.csv',
            ['Standort', 'TN Nummer', 'Kurs', 'Datum', 'Betrag'],
            function ($handle) use ($contracts, $filters) {
                $from = $this->parseFilterDate($filters['from'] ?? null);
                $to = $this->parseFilterDate($filters['to'] ?? null)?->endOfDay();
                $minAmount = isset($filters['min_amount']) ? (float) $filters['min_amount'] : null;
                $maxAmount = isset($filters['max_amount']) ? (float) $filters['max_amount'] : null;
                $limit = $filters['limit'] ?? null;
                $written = 0;

                foreach ($contracts->cursor() as $contract) {
                    foreach ($this->buildDueDateRows($contract) as $row) {
                        if (!$this->matchesDueDateRowFilters($row, $from, $to, $minAmount, $maxAmount)) {
                            continue;
                        }

                        $this->writeCsvRow($handle, $row);
                        $written++;

                        if ($limit !== null && $written >= $limit) {
                            break 2;
                        }
                    }
                }
            }
        );
    }

    public function getModuleOverview(Request $request)
    {
        $filters = $request->validate([
            'institut_ids' => 'sometimes|string|max:255',
            'class' => 'sometimes|string|max:50',
            'module' => 'sometimes|string|max:50',
            'teacher_id' => 'sometimes|string|max:50',
            'from' => 'sometimes|date_format:Y-m-d',
            'to' => 'sometimes|date_format:Y-m-d|after_or_equal:from',
            'min_std_satz' => 'sometimes|numeric',
            'max_std_satz' => 'sometimes|numeric|gte:min_std_satz',
            'limit' => 'sometimes|integer|min:1|max:50000',
        ]);

        $this->connectToUvsDatabase();
        $db = DB::connection('uvs');

        $assignmentMap = $db->table('ma_u_kla as mk')
            ->selectRaw('MAX(mk.uid) as uid, mk.klassen_id, mk.mitarbeiter_id')
            ->groupBy('mk.klassen_id', 'mk.mitarbeiter_id');

        $termMap = $db->table('termin as t')
            ->selectRaw('t.termin_id, MIN(t.beginn_baustein) as beginn_baustein')
            ->groupBy('t.termin_id');

        $classStats = $db->table('tn_u_kla as tu')
            ->leftJoin('tvertrag as tv', 'tv.teilnehmer_id', '=', 'tu.teilnehmer_id')
            ->where('tu.deleted', '0')
            ->selectRaw('
                tu.klassen_id,
                MAX(tu.kurzbez_ba) as baustein,
                AVG(COALESCE(tv.tn_std_satz, 0)) as std_satz
            ')
            ->groupBy('tu.klassen_id');

        $rows = $db->table('ma_u_kla as mk')
            ->joinSub($assignmentMap, 'assignment_pick', function ($join) {
                $join->on('assignment_pick.uid', '=', 'mk.uid');
            })
            ->leftJoinSub($classStats, 'stats', function ($join) {
                $join->on('stats.klassen_id', '=', 'mk.klassen_id');
            })
            ->leftJoinSub($termMap, 'term_pick', function ($join) {
                $join->on('term_pick.termin_id', '=', 'mk.termin_id');
            })
            ->select([
                'mk.klassen_id',
                'mk.klassen_co_ks',
                'mk.mitarbeiter_id',
                'mk.i_std',
                'mk.honorar',
                'stats.baustein',
                'stats.std_satz',
                'term_pick.beginn_baustein',
            ]);

        $this->applyInstituteFilters($rows, $filters, 'mk.institut_id_ks');

        if (!empty($filters['class'])) {
            $rows->where('mk.klassen_co_ks', 'like', '%' . trim($filters['class']) . '%');
        }

        if (!empty($filters['module'])) {
            $rows->where('stats.baustein', 'like', '%' . trim($filters['module']) . '%');
        }

        if (!empty($filters['teacher_id'])) {
            $rows->where('mk.mitarbeiter_id', 'like', '%' . trim($filters['teacher_id']) . '%');
        }

        $this->applyDateRangeFilter(
            $rows,
            'term_pick.beginn_baustein',
            $filters['from'] ?? null,
            $filters['to'] ?? null
        );

        if (isset($filters['min_std_satz'])) {
            $rows->whereRaw('COALESCE(stats.std_satz, 0) >= ?', [(float) $filters['min_std_satz']]);
        }

        if (isset($filters['max_std_satz'])) {
            $rows->whereRaw('COALESCE(stats.std_satz, 0) <= ?', [(float) $filters['max_std_satz']]);
        }

        $rows->orderByRaw("STR_TO_DATE(NULLIF(term_pick.beginn_baustein, ''), '%Y/%m/%d') ASC")
            ->orderBy('mk.klassen_id')
            ->orderBy('mk.mitarbeiter_id');

        if (isset($filters['limit'])) {
            $rows->limit((int) $filters['limit']);
        }

        return $this->streamCsvDownload(
            'baustein_uebersicht.csv',
            ['Klasse', 'Baustein', 'Dozent', 'TN-Std', 'Std. Satz', 'Doz-Kst'],
            function ($handle) use ($rows) {
                foreach ($rows->cursor() as $row) {
                    $this->writeCsvRow($handle, [
                        $this->cleanString($row->klassen_co_ks),
                        $this->cleanString($row->baustein),
                        $this->cleanString($row->mitarbeiter_id),
                        $this->formatCsvNumber($row->i_std, 0),
                        $this->formatCsvNumber($row->std_satz),
                        $this->formatCsvNumber($row->honorar),
                    ]);
                }
            }
        );
    }

    public function getParticipantRateSelection(Request $request)
    {
        $filters = $request->validate([
            'institut_ids' => 'sometimes|string|max:255',
            'participant_number' => 'sometimes|string|max:50',
            'plz' => 'sometimes|string|max:20',
            'vtz' => 'sometimes|string|max:10',
            'course' => 'sometimes|string|max:50',
            'kt_kurz' => 'sometimes|string|max:50',
            'has_cancellation' => 'sometimes|boolean',
            'from' => 'sometimes|date_format:Y-m-d',
            'to' => 'sometimes|date_format:Y-m-d|after_or_equal:from',
            'limit' => 'sometimes|integer|min:1|max:50000',
        ]);

        $this->connectToUvsDatabase();
        $db = DB::connection('uvs');

        $contractMap = $db->table('ivertrag as iv')
            ->selectRaw('MAX(iv.uid) as uid, iv.person_id, iv.vertrag_mass, iv.vertrag_beginn')
            ->groupBy('iv.person_id', 'iv.vertrag_mass', 'iv.vertrag_beginn');

        $interestMap = $db->table('interess as i')
            ->selectRaw('MAX(i.uid) as uid, i.person_id')
            ->groupBy('i.person_id');

        $rows = $db->table('tvertrag as tv')
            ->leftJoin('person as p', 'p.person_id', '=', 'tv.person_id')
            ->leftJoinSub($contractMap, 'iv_pick', function ($join) {
                $join->on('iv_pick.person_id', '=', 'tv.person_id')
                    ->on('iv_pick.vertrag_mass', '=', 'tv.kurzbez_mn')
                    ->on('iv_pick.vertrag_beginn', '=', 'tv.vertrag_beginn');
            })
            ->leftJoin('ivertrag as iv', 'iv.uid', '=', 'iv_pick.uid')
            ->leftJoinSub($interestMap, 'i_pick', function ($join) {
                $join->on('i_pick.person_id', '=', 'tv.person_id');
            })
            ->leftJoin('interess as i', 'i.uid', '=', 'i_pick.uid')
            ->select([
                'tv.uid',
                'tv.teilnehmer_nr',
                'tv.vtz_kennz_mn',
                'tv.kurzbez_mn',
                'tv.vertrag_beginn',
                'tv.vertrag_ende',
                'tv.erster_tag',
                'tv.letzter_tag',
                'tv.vertrag_baust',
                'tv.vertrag_datum as tv_vertrag_datum',
                'tv.kurzbez_kt',
                'p.plz',
                'p.geburt_datum',
                'p.geschlecht',
                'p.nationalitaet',
                'iv.vertrag_datum as iv_vertrag_datum',
                'iv.geb_gesamt',
                'iv.kt_kurz',
                'iv.kt_ort',
                'iv.kuendig_datum',
                'iv.kuendig_zum',
                'i.erst_k_datum',
            ]);

        $this->applyInstituteFilters($rows, $filters, 'tv.institut_id');

        if (!empty($filters['participant_number'])) {
            $rows->where('tv.teilnehmer_nr', 'like', '%' . trim($filters['participant_number']) . '%');
        }

        if (!empty($filters['plz'])) {
            $rows->where('p.plz', 'like', '%' . trim($filters['plz']) . '%');
        }

        if (!empty($filters['vtz'])) {
            $rows->where('tv.vtz_kennz_mn', '=', trim($filters['vtz']));
        }

        if (!empty($filters['course'])) {
            $course = trim($filters['course']);
            $rows->where(function ($query) use ($course) {
                $query->where('tv.kurzbez_mn', 'like', '%' . $course . '%')
                    ->orWhere('iv.vertrag_mass', 'like', '%' . $course . '%');
            });
        }

        if (!empty($filters['kt_kurz'])) {
            $ktKurz = trim($filters['kt_kurz']);
            $rows->where(function ($query) use ($ktKurz) {
                $query->where('iv.kt_kurz', 'like', '%' . $ktKurz . '%')
                    ->orWhere('tv.kurzbez_kt', 'like', '%' . $ktKurz . '%');
            });
        }

        if (array_key_exists('has_cancellation', $filters)) {
            $hasCancellation = filter_var($filters['has_cancellation'], FILTER_VALIDATE_BOOL);

            $rows->where(function ($query) use ($hasCancellation) {
                if ($hasCancellation) {
                    $query->where('iv.kuendig_datum', '!=', '')
                        ->orWhere('iv.kuendig_zum', '!=', '');
                } else {
                    $query->where(function ($subQuery) {
                        $subQuery->whereNull('iv.kuendig_datum')
                            ->orWhere('iv.kuendig_datum', '');
                    })->where(function ($subQuery) {
                        $subQuery->whereNull('iv.kuendig_zum')
                            ->orWhere('iv.kuendig_zum', '');
                    });
                }
            });
        }

        $this->applyDateRangeFilter(
            $rows,
            'tv.vertrag_beginn',
            $filters['from'] ?? null,
            $filters['to'] ?? null
        );

        $rows->orderByRaw("STR_TO_DATE(NULLIF(tv.vertrag_beginn, ''), '%Y/%m/%d') ASC")
            ->orderBy('tv.uid');

        if (isset($filters['limit'])) {
            $rows->limit((int) $filters['limit']);
        }

        return $this->streamCsvDownload(
            'teilnehmer_satz_auswahl.csv',
            [
                'TNNummer',
                'Plz',
                'Geburtsdatum',
                'Geschlecht',
                'Nationalitaet',
                'Vtz',
                'Massnahmekurz',
                'Bildungsbeginn',
                'Bildungsende',
                'ErsterTag',
                'LezterTag',
                'Anzbaust',
                'Vertragsdatum',
                'Gebgesamt',
                'KTkurz',
                'KTort',
                'KuendigDatum',
                'KuendigZum',
                'Beratungstermin',
                'MBkurzbez',
                'MBlangbez',
                'MBplz',
                'MBort',
            ],
            function ($handle) use ($rows) {
                foreach ($rows->cursor() as $row) {
                    $ktKurz = $this->firstFilled($row->kt_kurz, $row->kurzbez_kt);
                    $ktOrt = $this->cleanString($row->kt_ort);

                    // Im aktuellen UVS-Schema fehlen x_iv_kst/mpbuero. Die MB-Felder
                    // fallen daher auf die verfügbaren Kostenträger-Basisdaten zurück.
                    $this->writeCsvRow($handle, [
                        $this->cleanString($row->teilnehmer_nr),
                        $this->cleanString($row->plz),
                        $this->formatCsvDate($row->geburt_datum),
                        $this->cleanString($row->geschlecht),
                        $this->cleanString($row->nationalitaet),
                        $this->cleanString($row->vtz_kennz_mn),
                        $this->cleanString($row->kurzbez_mn),
                        $this->formatCsvDate($row->vertrag_beginn),
                        $this->formatCsvDate($row->vertrag_ende),
                        $this->formatCsvDate($row->erster_tag),
                        $this->formatCsvDate($row->letzter_tag),
                        $this->formatCsvNumber($row->vertrag_baust, 0),
                        $this->formatCsvDate($this->firstFilled($row->iv_vertrag_datum, $row->tv_vertrag_datum)),
                        $this->formatCsvNumber($row->geb_gesamt),
                        $ktKurz,
                        $ktOrt,
                        $this->formatCsvDate($row->kuendig_datum),
                        $this->formatCsvDate($row->kuendig_zum),
                        $this->formatCsvDate($row->erst_k_datum),
                        $ktKurz,
                        $ktKurz,
                        '',
                        $ktOrt,
                    ]);
                }
            }
        );
    }

    protected function streamCsvDownload(string $filename, array $header, callable $writer): StreamedResponse
    {
        return response()->streamDownload(
            function () use ($header, $writer) {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\xBF");
                fputcsv($handle, $header, ';');
                $writer($handle);

                fclose($handle);
            },
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }

    protected function writeCsvRow($handle, array $row): void
    {
        fputcsv($handle, $row, ';');
    }

    protected function applyInstituteFilters(Builder $query, array $filters, string $column): void
    {
        $institutIds = [];

        if (!empty($filters['institut_ids'])) {
            $institutIds = array_merge($institutIds, $this->parseIntegerList($filters['institut_ids']));
        }

        $institutIds = array_values(array_unique(array_filter($institutIds, fn ($value) => $value > 0)));

        if ($institutIds !== []) {
            $query->whereIn($column, $institutIds);
        }
    }

    protected function applyDateRangeFilter(
        Builder $query,
        string $column,
        ?string $from,
        ?string $to,
        string $sourceFormat = '%Y/%m/%d'
    ): void {
        if ($from) {
            $query->whereRaw(
                "STR_TO_DATE(NULLIF({$column}, ''), '{$sourceFormat}') >= STR_TO_DATE(?, '%Y-%m-%d')",
                [$from]
            );
        }

        if ($to) {
            $query->whereRaw(
                "STR_TO_DATE(NULLIF({$column}, ''), '{$sourceFormat}') <= STR_TO_DATE(?, '%Y-%m-%d')",
                [$to]
            );
        }
    }

    protected function parseIntegerList(string $value): array
    {
        return array_values(array_filter(array_map(
            static fn ($item) => is_numeric(trim($item)) ? (int) trim($item) : null,
            explode(',', $value)
        )));
    }

    protected function parseFilterDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function matchesDueDateRowFilters(
        array $row,
        ?Carbon $from,
        ?Carbon $to,
        ?float $minAmount,
        ?float $maxAmount
    ): bool {
        $date = $this->parseDueDateRowDate($row[3] ?? null);
        $amount = $this->parseDueDateRowAmount($row[4] ?? null);

        if ($from && (!$date || $date->lt($from))) {
            return false;
        }

        if ($to && (!$date || $date->gt($to))) {
            return false;
        }

        if ($minAmount !== null && $amount < $minAmount) {
            return false;
        }

        if ($maxAmount !== null && $amount > $maxAmount) {
            return false;
        }

        return true;
    }

    protected function parseDueDateRowDate(?string $value): ?Carbon
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('d.m.Y', trim($value))->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function parseDueDateRowAmount(mixed $value): float
    {
        if ($value === null) {
            return 0.0;
        }

        $normalized = str_replace('.', '', (string) $value);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : 0.0;
    }

    protected function buildDueDateRows(object $contract): array
    {
        $baseAmount = (float) ($contract->geb_gesamt ?? 0);
        $location = $this->formatLocation(
            $contract->institut_co ?? null,
            $contract->ort ?? null,
            $contract->institut_id ?? null
        );
        $participantNumber = $this->resolveParticipantNumber($contract);
        $course = $this->firstFilled($contract->kurzbez_mn, $contract->vertrag_mass);
        $anchorDate = $this->resolveDueDateAnchor(
            $contract->vertrag_beginn ?? null,
            $contract->vertrag_datum ?? null,
            $contract->vertrag_ende ?? null
        );

        $schedules = [
            [
                'rates' => (int) ($contract->raten_aa ?? 0),
                'payment' => $this->cleanString($contract->zahlungsart_aa ?? null),
                'amount' => $this->numericOrNull($contract->dm_aa ?? null),
                'percent' => $this->numericOrNull($contract->prozent_aa ?? null),
            ],
            [
                'rates' => (int) ($contract->raten_bfd ?? 0),
                'payment' => $this->cleanString($contract->zahlungsart_bfd ?? null),
                'amount' => $this->numericOrNull($contract->dm_bfd ?? null),
                'percent' => $this->numericOrNull($contract->prozent_bfd ?? null),
            ],
            [
                'rates' => (int) ($contract->raten_tn ?? 0),
                'payment' => $this->cleanString($contract->zahlungsart_tn ?? null),
                'amount' => $this->numericOrNull($contract->dm_tn ?? null),
                'percent' => $this->numericOrNull($contract->prozent_tn ?? null),
            ],
            [
                'rates' => (int) ($contract->raten_so ?? 0),
                'payment' => $this->cleanString($contract->zahlungsart_so ?? null),
                'amount' => $this->numericOrNull($contract->dm_so ?? null),
                'percent' => $this->numericOrNull($contract->prozent_so ?? null),
            ],
        ];

        $activeSchedules = array_values(array_filter($schedules, function (array $schedule) {
            return $schedule['rates'] > 0
                || $schedule['payment'] !== ''
                || ($schedule['amount'] !== null && abs($schedule['amount']) > 0.00001)
                || ($schedule['percent'] !== null && abs($schedule['percent']) > 0.00001);
        }));

        if ($activeSchedules === []) {
            if (abs($baseAmount) < 0.00001) {
                return [];
            }

            $activeSchedules[] = [
                'rates' => max((int) ($contract->raten ?? 0), 1),
                'payment' => $this->cleanString($contract->ratenvertrag ?? null),
                'amount' => $baseAmount,
                'percent' => null,
            ];
        }

        $assignedTotal = 0.0;
        $pendingIndexes = [];

        foreach ($activeSchedules as $index => $schedule) {
            if ($schedule['amount'] !== null && abs($schedule['amount']) > 0.00001) {
                $activeSchedules[$index]['resolved_total'] = (float) $schedule['amount'];
                $assignedTotal += (float) $schedule['amount'];
                continue;
            }

            if ($schedule['percent'] !== null && abs($schedule['percent']) > 0.00001) {
                $resolved = $baseAmount * ((float) $schedule['percent'] / 100);
                $activeSchedules[$index]['resolved_total'] = $resolved;
                $assignedTotal += $resolved;
                continue;
            }

            $pendingIndexes[] = $index;
        }

        if ($pendingIndexes !== []) {
            $remaining = $baseAmount - $assignedTotal;
            $weightSum = 0;

            foreach ($pendingIndexes as $index) {
                $weightSum += max((int) $activeSchedules[$index]['rates'], 1);
            }

            foreach ($pendingIndexes as $index) {
                $weight = max((int) $activeSchedules[$index]['rates'], 1);
                $activeSchedules[$index]['resolved_total'] = $weightSum > 0
                    ? $remaining * ($weight / $weightSum)
                    : 0.0;
            }
        }

        $rows = [];

        foreach ($activeSchedules as $schedule) {
            $totalAmount = (float) ($schedule['resolved_total'] ?? 0);
            $rateCount = max((int) ($schedule['rates'] ?? 0), 1);

            if (abs($totalAmount) < 0.00001) {
                continue;
            }

            $installmentAmount = $totalAmount / $rateCount;

            for ($i = 0; $i < $rateCount; $i++) {
                $dueDate = $anchorDate?->copy()->addMonthsNoOverflow($i);

                $rows[] = [
                    $location,
                    $participantNumber,
                    $course,
                    $dueDate?->format('d.m.Y') ?? '',
                    $this->formatCsvNumber($installmentAmount),
                ];
            }
        }

        return $rows;
    }

    protected function resolveDueDateAnchor(?string $startDate, ?string $contractDate, ?string $endDate): ?Carbon
    {
        $start = $this->parseUvsDate($startDate);
        $contract = $this->parseUvsDate($contractDate);
        $end = $this->parseUvsDate($endDate);

        if ($start && $contract) {
            return $start->greaterThan($contract) ? $start : $contract;
        }

        return $start ?: $contract ?: $end;
    }

    protected function parseUvsDate(?string $value): ?Carbon
    {
        if (!is_string($value)) {
            return null;
        }

        $raw = trim($value);
        if ($raw === '' || $raw === '//') {
            return null;
        }

        foreach (['Y/m/d', 'Y-m-d', 'd/m/Y', 'd-m-Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $raw)->startOfDay();
            } catch (\Throwable $e) {
                // try next format
            }
        }

        if (preg_match('/^\d\/(\d{2})\/(\d{2})\/(\d{2})$/', $raw, $matches)) {
            $year = ((int) $matches[1] >= 70 ? 1900 : 2000) + (int) $matches[1];

            try {
                return Carbon::create($year, (int) $matches[2], (int) $matches[3])->startOfDay();
            } catch (\Throwable $e) {
                return null;
            }
        }

        try {
            return Carbon::parse(str_replace('/', '-', $raw))->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function formatCsvDate(?string $value): string
    {
        if ($value === null || trim($value) === '') {
            return '';
        }

        return $this->parseUvsDate($value)?->format('d.m.Y') ?? trim($value);
    }

    protected function formatCsvNumber(mixed $value, int $decimals = 2): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (!is_numeric($value)) {
            return trim((string) $value);
        }

        return number_format((float) $value, $decimals, ',', '');
    }

    protected function resolveParticipantNumber(object $contract): string
    {
        return $this->firstFilled(
            $contract->teilnehmer_nr ?? null,
            $contract->teilnehmer_nr_mass ?? null,
            $contract->teilnehmer_nr_person ?? null,
            $contract->interessent_nr ?? null,
            $this->extractParticipantNumberFromConsultation(
                $contract->beratung_nr ?? null,
                $contract->beratung_id ?? null
            )
        );
    }

    protected function extractParticipantNumberFromConsultation(?string $beratungNr, ?string $beratungId): string
    {
        $candidate = $this->cleanString($beratungNr);

        if ($candidate === '') {
            $consultationId = $this->cleanString($beratungId);

            if ($consultationId !== '') {
                $parts = explode('-', $consultationId, 2);
                $candidate = $parts[1] ?? $consultationId;
            }
        }

        $digits = preg_replace('/\D+/', '', $candidate ?? '');

        if (!is_string($digits) || strlen($digits) < 9) {
            return '';
        }

        return substr($digits, 0, 9);
    }

    protected function formatLocation(?string $code, ?string $city, mixed $fallbackId): string
    {
        $code = $this->cleanString($code);
        $city = $this->cleanString($city);

        if ($code !== '' && $city !== '') {
            return $code . ' - ' . $city;
        }

        if ($code !== '') {
            return $code;
        }

        if ($city !== '') {
            return $city;
        }

        return $fallbackId !== null ? (string) $fallbackId : '';
    }

    protected function numericOrNull(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : null;
    }

    protected function cleanString(mixed $value): string
    {
        return trim((string) ($value ?? ''));
    }

    protected function firstFilled(mixed ...$values): string
    {
        foreach ($values as $value) {
            $clean = $this->cleanString($value);
            if ($clean !== '') {
                return $clean;
            }
        }

        return '';
    }
}
