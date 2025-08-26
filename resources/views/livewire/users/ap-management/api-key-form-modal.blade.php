<div>
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            API-Schlüssel erstellen/bearbeiten
        </x-slot>

        <x-slot name="content">
            <div class="mb-4 grid grid-cols-5 gap-4">
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full border rounded px-4 py-2">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="col-span-1">
                    <label for="active" class="flex items-center cursor-pointer">
                        <input 
                            id="active" 
                            name="active" 
                            type="checkbox" 
                            @change="changed = true"
                            wire:model.live="active" 
                            class="sr-only peer" 
                        />
                        <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Aktiv</span>
                    </label>
                </div>
            </div>

            <div class="mb-4 grid grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Gültig bis</label>
                    <input type="datetime-local" wire:model="expires_at" class="mt-1 block w-full border rounded px-4 py-2">
                </div>



            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Notizen</label>
                <textarea wire:model="notes" class="mt-1 block w-full border rounded px-4 py-2"></textarea>
            </div>
            <div class="mb-4">
                {{-- Abilities mit Choices.js (Mehrfach-Select, stabil ohne $this->id) --}}
                <div x-data="{ selectedAbilities: @entangle('abilities') }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Abilities</label>

                    <select
                        x-ref="abilitiesSelect"
                        multiple
                        wire:ignore
                        x-init="
                            let choices = new Choices($el, { removeItemButton: true, shouldSort: false });
                            $nextTick(() => {
                                if (selectedAbilities.length > 0) {
                                    choices.setChoiceByValue(selectedAbilities);
                                }
                            });
                            $el.addEventListener('change', () => {
                                selectedAbilities = [...$el.selectedOptions].map(option => option.value);
                            });
                        "
                        class="mt-1"
                        >
                        @foreach($availableAbilities as $ability)
                        <option value="{{ $ability }}">{{ $ability }}</option>
                        @endforeach
                    </select>
                    @error('abilities') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            @if ($plainToken)
                <div class="bg-yellow-50 border border-yellow-200 text-sm p-3 rounded">
                    <strong>Neues Token (nur einmal sichtbar):</strong>
                    <div class="font-mono break-all mt-1">{{ $plainToken }}</div>
                    <p class="mt-2 text-xs">Bitte jetzt kopieren, später nicht mehr einsehbar!</p>
                </div>
            @elseif ($apiKeyId)
                <div class="text-sm text-gray-500">
                    Das Token wird nur bei der Erstellung oder beim Erneuern eines Schlüssels angezeigt.
                </div>
                <button type="button" class="mt-2 text-sm text-blue-600 hover:underline" wire:click="generateNewToken">
                    Schlüssel neu generieren
                </button>
            @else
                <div class="text-sm text-gray-500">
                    Das Token wird nach dem ersten Speichern automatisch angezeigt.
                </div>
            @endif

        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center space-x-3">
                <x-button wire:click="saveKey" class="btn-xs text-sm">Speichern</x-button>
                <x-button wire:click="closeModal" class="btn-xs text-sm">Schließen</x-button>
            </div>

        </x-slot>
    </x-dialog-modal>
    {{-- Styles & Scripts für Choices.js (wie in deinem Beispiel) --}}
    @section('css')
        <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
        <link href="{{ URL::asset('build/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
    @endsection
    @section('scripts')
        <script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    @endsection
</div>
