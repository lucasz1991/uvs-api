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
                    <label class="block text-sm font-medium text-gray-700">Sprache</label>
                    <select wire:model="lang" class="mt-1 block w-full border rounded px-4 py-2 bg-white">
                        <option value="">Alle</option>
                        <option value="DE">Deutsch</option>
                        <option value="EN">Englisch</option>
                    </select>
                    @error('lang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4 grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="active" class="mt-1 block w-full bg-white border rounded px-1 py-2">
                        <option value="1">Aktiv</option>
                        <option value="0">Inaktiv</option>
                    </select>
                </div>

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
            @if($plainToken)
                <div class="bg-yellow-50 border border-yellow-200 text-sm p-3 rounded">
                    <strong>Neues Token (nur einmal sichtbar):</strong>
                    <div class="font-mono break-all mt-1">{{ $plainToken }}</div>
                    <p class="mt-2 text-xs">Bitte jetzt kopieren, später nicht mehr einsehbar!</p>
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
