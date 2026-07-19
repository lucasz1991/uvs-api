<?php

namespace App\Http\Controllers\Api;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use DateTimeInterface;

final class ParticipantContractSelector
{
    private const TIMEZONE = 'Europe/Berlin';

    /**
     * Order participant contracts chronologically and mark the first contract
     * that has not ended or been cancelled as the current one.
     *
     * A future start date deliberately does not close a contract. This allows
     * consecutive contracts to be selected without a gap between them.
     *
     * @param  iterable<array<string, mixed>|object>  $contracts
     * @return array<int, array<string, mixed>>
     */
    public static function evaluate(iterable $contracts, CarbonInterface|DateTimeInterface|string|null $asOf = null): array
    {
        $today = self::parseDate($asOf) ?? CarbonImmutable::today(self::TIMEZONE);
        $evaluated = [];

        foreach ($contracts as $contract) {
            $values = is_array($contract) ? $contract : get_object_vars($contract);
            $begin = self::parseDate($values['vertrag_beginn'] ?? null);
            $end = self::parseDate($values['vertrag_ende'] ?? null);
            $cancelledAt = self::parseDate($values['kuendig_zum'] ?? null);
            $effectiveEnd = self::earliestDate($end, $cancelledAt);

            $evaluated[] = [
                'contract' => $values,
                'begin' => $begin,
                'effective_end' => $effectiveEnd,
                'is_active' => $effectiveEnd === null || $effectiveEnd->greaterThanOrEqualTo($today),
            ];
        }

        usort($evaluated, static function (array $left, array $right): int {
            $leftSequenceDate = $left['begin'] ?? $left['effective_end'];
            $rightSequenceDate = $right['begin'] ?? $right['effective_end'];

            $comparison = self::compareNullableDates($leftSequenceDate, $rightSequenceDate);
            if ($comparison !== 0) {
                return $comparison;
            }

            $comparison = self::compareNullableDates($left['effective_end'], $right['effective_end']);
            if ($comparison !== 0) {
                return $comparison;
            }

            return strcmp(self::stableIdentifier($left['contract']), self::stableIdentifier($right['contract']));
        });

        $currentAssigned = false;

        return array_map(static function (array $item) use (&$currentAssigned): array {
            $isCurrent = ! $currentAssigned && $item['is_active'];
            if ($isCurrent) {
                $currentAssigned = true;
            }

            return array_merge($item['contract'], [
                'is_active' => $item['is_active'],
                'is_current' => $isCurrent,
            ]);
        }, $evaluated);
    }

    /**
     * @param  iterable<array<string, mixed>|object>  $contracts
     * @return array<string, mixed>|null
     */
    public static function current(iterable $contracts, CarbonInterface|DateTimeInterface|string|null $asOf = null): ?array
    {
        foreach (self::evaluate($contracts, $asOf) as $contract) {
            if ($contract['is_current']) {
                return $contract;
            }
        }

        return null;
    }

    /**
     * Select the current contract or, when every contract is closed, preserve
     * the historic API behaviour by returning the most recently ended one.
     *
     * @param  iterable<array<string, mixed>|object>  $contracts
     * @return array<string, mixed>|null
     */
    public static function select(iterable $contracts, CarbonInterface|DateTimeInterface|string|null $asOf = null): ?array
    {
        $evaluated = self::evaluate($contracts, $asOf);

        foreach ($evaluated as $contract) {
            if ($contract['is_current']) {
                return $contract;
            }
        }

        $latestClosed = null;
        $latestEffectiveEnd = null;

        foreach ($evaluated as $contract) {
            $effectiveEnd = self::earliestDate(
                self::parseDate($contract['vertrag_ende'] ?? null),
                self::parseDate($contract['kuendig_zum'] ?? null),
            );

            if ($effectiveEnd !== null && ($latestEffectiveEnd === null || $effectiveEnd->greaterThan($latestEffectiveEnd))) {
                $latestClosed = $contract;
                $latestEffectiveEnd = $effectiveEnd;
            }
        }

        return $latestClosed;
    }

    private static function earliestDate(?CarbonImmutable $left, ?CarbonImmutable $right): ?CarbonImmutable
    {
        if ($left === null) {
            return $right;
        }

        if ($right === null) {
            return $left;
        }

        return $left->lessThanOrEqualTo($right) ? $left : $right;
    }

    private static function compareNullableDates(?CarbonImmutable $left, ?CarbonImmutable $right): int
    {
        if ($left === null && $right === null) {
            return 0;
        }

        if ($left === null) {
            return 1;
        }

        if ($right === null) {
            return -1;
        }

        return $left->getTimestamp() <=> $right->getTimestamp();
    }

    /** @param array<string, mixed> $contract */
    private static function stableIdentifier(array $contract): string
    {
        return implode('|', [
            (string) ($contract['teilnehmer_id'] ?? ''),
            (string) ($contract['beratung_id'] ?? ''),
            (string) ($contract['teilnehmer_nr'] ?? ''),
        ]);
    }

    private static function parseDate(CarbonInterface|DateTimeInterface|string|null $value): ?CarbonImmutable
    {
        if ($value instanceof DateTimeInterface) {
            return CarbonImmutable::instance($value)->startOfDay();
        }

        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        foreach (['Y-m-d', 'Y/m/d', 'd/m/Y', 'd-m-Y'] as $format) {
            try {
                $date = CarbonImmutable::createFromFormat('!'.$format, $raw, self::TIMEZONE);
                if ($date !== false && $date->format($format) === $raw) {
                    return $date;
                }
            } catch (\Throwable) {
                // Try the next known UVS date format.
            }
        }

        try {
            return CarbonImmutable::parse($raw, self::TIMEZONE)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
