<div>
    <button wire:click="openModal" class="inline-flex px-4 py-2 bg-blue-500 text-white font-medium text-sm rounded-lg hover:bg-blue-600">
        Auszahlung beantragen
    </button>

    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Auszahlung beantragen
        </x-slot>

        <x-slot name="content">
            <!-- Hinweis zur Auszahlung -->
            <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                <p class="font-semibold">Hinweis zur Auszahlung</p>
                <p class="text-sm mt-1">
                    Dein Auszahlungsantrag wird von unserem Team geprüft und innerhalb von <strong>48 Stunden</strong> bearbeitet. 
                    Sobald die Überweisung erfolgt ist, erhältst du eine Bestätigung.
                </p>
            </div>
            <!-- Auszahlungsbetrag anzeigen -->
            <p class="text-gray-700 font-semibold mb-4">Betrag: {{ number_format($amount, 2, ',', '.') }} €</p>

            <form wire:submit.prevent="submitPayout">
                <label class="block mb-2">Kontoinhaber</label>
                <input type="text" wire:model="accountHolder" class="w-full border rounded p-2" placeholder="Name des Kontoinhabers">
                @error('accountHolder') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <label class="block mt-4">Auszahlungsmethode</label>
                <select wire:model.live="payoutMethod" class="w-full border rounded p-2">
                    <option value="">Bitte wählen...</option>
                    <option value="bank_transfer">Banküberweisung</option>
                    <!--<option value="paypal">PayPal</option>-->
                </select>
                @error('payoutMethod') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                @if($payoutMethod === 'bank_transfer')
                    <label class="block mt-4">IBAN</label>
                    <input type="text" wire:model="iban" class="w-full border rounded p-2" placeholder="IBAN">
                    @error('iban') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    
                    <label class="block mt-4">BIC</label>
                    <input type="text" wire:model="bic" class="w-full border rounded p-2" placeholder="BIC">
                    @error('bic') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @endif

                @if($payoutMethod === 'paypal')
                    <label class="block mt-4">PayPal E-Mail</label>
                    <input type="email" wire:model="paypalEmail" class="w-full border rounded p-2" placeholder="E-Mail-Adresse">
                    @error('paypalEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @endif
            </form>
        </x-slot>

        <x-slot name="footer">
            <button type="button" wire:click="closeModal" class="mr-2 bg-gray-400 text-white px-4 py-2 rounded">Abbrechen</button>
            <button type="submit" wire:click="submitPayout" class="bg-blue-500 text-white px-4 py-2 rounded">Senden</button>
        </x-slot>
    </x-dialog-modal>
</div>
