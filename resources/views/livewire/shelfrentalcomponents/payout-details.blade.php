<div>
    <!-- Button zum Öffnen des Modals -->
    <button wire:click="openModal" class="inline-flex px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded-lg hover:bg-blue-600">
        Auszahlung einsehen
    </button>

    <!-- Modal für die Auszahlungsdetails -->
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Auszahlungsdetails
        </x-slot>

        <x-slot name="content">
            @if ($payout)
                <!-- Hinweis zur Auszahlung -->
                <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                    <p class="font-semibold">Hinweis zur Auszahlung</p>
                    <p class="text-sm mt-1">
                        Deine Auszahlung wurde erfolgreich verarbeitet. Hier findest du alle relevanten Details.
                    </p>
                </div>

                <!-- Auszahlungsbetrag anzeigen -->
                <p class="text-gray-700 font-semibold mb-4">Betrag: {{ number_format($payout->amount, 2, ',', '.') }} €</p>
                <p class="text-gray-700 mb-4">Auszahlung angefordert am: {{ $payout->created_at->format('d.m.Y H:i') }}<br><small>genehmigt am: {{ $payout->updated_at->format('d.m.Y H:i') }}</small></p>

                <!-- Auszahlungsmethode -->
                <p class="text-gray-700 font-semibold mb-2">Auszahlungsmethode:</p>
                @if(isset($payout->payout_details['paypal_email']))
                    <p class="text-gray-700">💳 PayPal: {{ $payout->payout_details['paypal_email'] }}</p>
                @elseif(isset($payout->payout_details['iban']))
                    <p class="text-gray-700">🏦 IBAN: {{ $payout->payout_details['iban'] }}</p>
                    <p class="text-gray-700">🔢 BIC: {{ $payout->payout_details['bic'] }}</p>
                @else
                    <p class="text-gray-700 text-red-500">❌ Keine Auszahlungsdetails verfügbar.</p>
                @endif
                <!-- Verkaufte Produkte Tabelle -->
                <div class="mt-4">
                    <h3 class="font-semibold text-gray-700">{{ $payout->shelfRental->sales->count() }} Verkaufte Produkte</h3>
                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-2 mt-2">
                        @if ($payout->shelfRental && $payout->shelfRental->sales->count() > 0)
                            <table class="w-full text-sm text-gray-600">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-2 text-left">Produkt-Nr</th>
                                        <th class="p-2 text-left">Produktname</th>
                                        <th class="p-2 text-right">Preis</th>
                                        <th class="p-2 text-right">Einkünfte (nach Provision)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalEarnings = 0;
                                    @endphp
                                    @foreach ($payout->shelfRental->sales as $sale)
                                        @php
                                            $earningsAfterCommission = $sale->sale_price * 0.84; // 16% Provision abgezogen
                                            $totalEarnings += $earningsAfterCommission;
                                        @endphp
                                        <tr class="border-b">
                                            <td class="p-2">{{ $sale->product->id }}</td>
                                            <td class="p-2">{{ $sale->product->name }}</td>
                                            <td class="p-2 text-right">{{ number_format($sale->sale_price, 2, ',', '.') }} €</td>
                                            <td class="p-2 text-right">{{ number_format($earningsAfterCommission, 2, ',', '.') }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-200 font-semibold">
                                        <td colspan="3" class="p-2 text-right">Gesamtsumme der Auszahlung:</td>
                                        <td class="p-2 text-right">{{ number_format($totalEarnings, 2, ',', '.') }} €</td>
                                    </tr>
                                </tfoot>
                            </table>
                        @else
                            <p class="text-gray-500">Keine Verkäufe gefunden.</p>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-red-500">❌ Keine Auszahlung gefunden.</p>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                @if ($payout)
                    <x-button wire:click="downloadPayoutPdf" class="btn-xs text-sm">
                        📄 Beleg herunterladen
                    </x-button>
                    @endif
                <x-button wire:click="closeModal" class="btn-xs text-sm">
                    Schließen
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
