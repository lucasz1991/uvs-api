<div>
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Fragebogen für:<br> {{ $insuranceSubType->name ?? '–' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Dropdown zum Hinzufügen -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frage hinzufügen</label>
                    <div class="flex space-x-2">
                        <select wire:model="questionToAdd" class="w-full border rounded px-2 py-1">
                            <option value="">Bitte auswählen</option>
                            @foreach ($availableQuestions as $q)
                                <option value="{{ $q->id }}">{{ $q->title }} ({{ $q->type }})</option>
                            @endforeach
                        </select>
                        <x-button wire:click="addQuestion">+</x-button>
                    </div>
                </div>

                <!-- Sortierbare Fragenliste -->
                <label class="block text-sm font-medium text-gray-700">Zugeordnete Fragen</label>
                <div class="min-w-max lg:min-w-full" x-sort="$dispatch('reorder', { item: $item, position: $position })">
                    @foreach ($assignedQuestions as $q)
                        <div  x-sort:item="{ id: {{ $q['id'] }}, title: '{{ $q['title'] }}' }">
                            <div class="bg-gray-100 px-3 py-2 rounded flex justify-between items-center border mb-2">
                                <span class="text-sm">{{ $q['title'] }} <span class="text-xs text-gray-400">({{ $q['type'] }})</span></span>
                                <button type="button" class="text-red-500 text-xs" wire:click="removeQuestion({{ $q['id'] }})">
                                    Entfernen
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center space-x-3">
                <x-button wire:click="save" class="btn-xs text-sm">Speichern</x-button>
                <x-button wire:click="$set('showModal', false)" class="btn-xs text-sm">Schließen</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
