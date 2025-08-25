<div x-data="{ openModal: false }"  @discount-applied.window="openModal = false">
    <!-- Button zum Öffnen des Modals -->
    <button @click="openModal = true" class="w-full text-left px-4 py-2 text-red-500 fill-red-500 hover:text-red-600 text-sm font-medium flex items-center">
        <svg class="w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.891 15.107 15.11 8.89m-5.183-.52h.01m3.089 7.254h.01M14.08 3.902a2.849 2.849 0 0 0 2.176.902 2.845 2.845 0 0 1 2.94 2.94 2.849 2.849 0 0 0 .901 2.176 2.847 2.847 0 0 1 0 4.16 2.848 2.848 0 0 0-.901 2.175 2.843 2.843 0 0 1-2.94 2.94 2.848 2.848 0 0 0-2.176.902 2.847 2.847 0 0 1-4.16 0 2.85 2.85 0 0 0-2.176-.902 2.845 2.845 0 0 1-2.94-2.94 2.848 2.848 0 0 0-.901-2.176 2.848 2.848 0 0 1 0-4.16 2.849 2.849 0 0 0 .901-2.176 2.845 2.845 0 0 1 2.941-2.94 2.849 2.849 0 0 0 2.176-.901 2.847 2.847 0 0 1 4.159 0Z"/>
        </svg>
        Rabattieren
    </button>

    <!-- Modal für Rabattierung -->
    <div x-show="openModal" @keydown.escape.window="openModal = false" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
        <div @click.away="openModal = false" class="bg-white p-6 rounded-lg shadow-lg w-96">
            <div class="w-full flex justify-center text-red-300 mb-5">
                <svg class=" h-20 mr-3 w-20" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.891 15.107 15.11 8.89m-5.183-.52h.01m3.089 7.254h.01M14.08 3.902a2.849 2.849 0 0 0 2.176.902 2.845 2.845 0 0 1 2.94 2.94 2.849 2.849 0 0 0 .901 2.176 2.847 2.847 0 0 1 0 4.16 2.848 2.848 0 0 0-.901 2.175 2.843 2.843 0 0 1-2.94 2.94 2.848 2.848 0 0 0-2.176.902 2.847 2.847 0 0 1-4.16 0 2.85 2.85 0 0 0-2.176-.902 2.845 2.845 0 0 1-2.94-2.94 2.848 2.848 0 0 0-.901-2.176 2.848 2.848 0 0 1 0-4.16 2.849 2.849 0 0 0 .901-2.176 2.845 2.845 0 0 1 2.941-2.94 2.849 2.849 0 0 0 2.176-.901 2.847 2.847 0 0 1 4.159 0Z"/>
                </svg>
            </div>
            <h2 class="text-lg font-semibold">Rabatt auf Regal-Miete</h2>
            <p class="mt-2 text-sm text-gray-700">
                Wähle die gewünschte Rabattierung aus und bestätige die Änderung. <br>
                <strong>Die Rabattierung gilt für alle Produkte dieses Regals.</strong>  
                Der Preis wird automatisch für jedes Produkt angepasst. <br>
                <span class="text-red-500 font-semibold">Die Rabattierung kann nur alle 24 Stunden geändert werden.</span>
            </p>

            <!-- Auswahl der Rabattierung -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Rabatt wählen:</label>
                <select wire:model="discount" name="discount" class="w-full mt-1 p-2 border rounded-lg">
                    <option value="0">Kein Rabatt</option>
                    <option value="25">25 % Rabatt</option>
                    <option value="50">50 % Rabatt</option>
                </select>
                @error('discount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            </div>

            <div class="mt-4 flex justify-between">
                <!-- Bestätigungsbutton ruft die Livewire-Methode auf -->
                <x-button @click="$wire.applyDiscount()" class="">
                    Bestätigen
                </x-button>
                <!-- Abbrechen Button -->
                <x-secondary-button @click="openModal = false" class="">
                    Abbrechen
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>
