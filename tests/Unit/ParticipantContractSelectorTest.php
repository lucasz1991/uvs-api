<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\ParticipantContractSelector;
use PHPUnit\Framework\TestCase;

class ParticipantContractSelectorTest extends TestCase
{
    public function test_it_selects_the_oldest_open_contract_before_a_future_follow_up_contract(): void
    {
        $contracts = [
            [
                'beratung_id' => 'BERATUNG-201',
                'teilnehmer_id' => '5-004570201',
                'vertrag_beginn' => '2026/08/03',
                'vertrag_ende' => '2028/07/19',
                'kuendig_zum' => '',
            ],
            [
                'beratung_id' => 'BERATUNG-200',
                'teilnehmer_id' => '5-004570200',
                'vertrag_beginn' => '2025/07/18',
                'vertrag_ende' => '2026/07/17',
                'kuendig_zum' => '',
            ],
        ];

        $evaluated = ParticipantContractSelector::evaluate($contracts, '2026-07-17');

        $this->assertSame(['5-004570200', '5-004570201'], array_column($evaluated, 'teilnehmer_id'));
        $this->assertTrue($evaluated[0]['is_active']);
        $this->assertTrue($evaluated[0]['is_current']);
        $this->assertTrue($evaluated[1]['is_active']);
        $this->assertFalse($evaluated[1]['is_current']);
    }

    public function test_it_switches_to_the_follow_up_contract_after_the_first_contract_ends(): void
    {
        $contracts = [
            [
                'beratung_id' => 'BERATUNG-200',
                'teilnehmer_id' => '5-004570200',
                'vertrag_beginn' => '2025-07-18',
                'vertrag_ende' => '2026-07-17',
            ],
            [
                'beratung_id' => 'BERATUNG-201',
                'teilnehmer_id' => '5-004570201',
                'vertrag_beginn' => '2026-08-03',
                'vertrag_ende' => '2028-07-19',
            ],
        ];

        $evaluated = ParticipantContractSelector::evaluate($contracts, '2026-07-18');
        $current = ParticipantContractSelector::current($contracts, '2026-07-18');

        $this->assertFalse($evaluated[0]['is_active']);
        $this->assertFalse($evaluated[0]['is_current']);
        $this->assertTrue($evaluated[1]['is_active']);
        $this->assertTrue($evaluated[1]['is_current']);
        $this->assertSame('5-004570201', $current['teilnehmer_id']);
    }

    public function test_cancellation_date_is_used_as_the_effective_end(): void
    {
        $contracts = [
            [
                'teilnehmer_id' => 'FIRST',
                'vertrag_beginn' => '2025-01-01',
                'vertrag_ende' => '2027-12-31',
                'kuendig_zum' => '17/07/2026',
            ],
            [
                'teilnehmer_id' => 'SECOND',
                'vertrag_beginn' => '2026-08-01',
                'vertrag_ende' => '2028-12-31',
            ],
        ];

        $this->assertSame('FIRST', ParticipantContractSelector::current($contracts, '2026-07-17')['teilnehmer_id']);
        $this->assertSame('SECOND', ParticipantContractSelector::current($contracts, '2026-07-18')['teilnehmer_id']);
    }

    public function test_effective_end_orders_contracts_when_start_dates_are_missing(): void
    {
        $contracts = [
            ['teilnehmer_id' => 'LATER', 'vertrag_ende' => '2028-12-31'],
            ['teilnehmer_id' => 'EARLIER', 'vertrag_ende' => '2027-12-31'],
        ];

        $evaluated = ParticipantContractSelector::evaluate($contracts, '2026-01-01');

        $this->assertSame(['EARLIER', 'LATER'], array_column($evaluated, 'teilnehmer_id'));
        $this->assertTrue($evaluated[0]['is_current']);
    }

    public function test_it_returns_no_current_contract_when_every_contract_is_closed(): void
    {
        $contracts = [
            ['teilnehmer_id' => 'OLD', 'vertrag_beginn' => '2020-01-01', 'vertrag_ende' => '2021-12-31'],
            ['teilnehmer_id' => 'NEWER', 'vertrag_beginn' => '2022-01-01', 'vertrag_ende' => '2023-12-31'],
        ];

        $evaluated = ParticipantContractSelector::evaluate($contracts, '2026-01-01');

        $this->assertNull(ParticipantContractSelector::current($contracts, '2026-01-01'));
        $this->assertSame([false, false], array_column($evaluated, 'is_current'));
        $this->assertSame('NEWER', ParticipantContractSelector::select($contracts, '2026-01-01')['teilnehmer_id']);
    }
}
