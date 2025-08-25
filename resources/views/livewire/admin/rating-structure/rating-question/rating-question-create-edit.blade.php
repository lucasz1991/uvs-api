<div>
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ $questionId ? 'Frage bearbeiten' : 'Neue Frage erstellen' }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Titel, Fragetext & Typ -->
                <div class="mb-4 grid grid-cols-5 gap-4">
                    <div class="col-span-3 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Titel</label>
                        <input type="text" wire:model.defer="title" class="mt-1 block w-full border rounded px-4 py-2">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-2 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Typ</label>
                        <select wire:model.defer="type" class="mt-1 block w-full border rounded px-2 py-2 bg-white">
                            <option value="text">Text</option>
                            <option value="number">Zahl</option>
                            <option value="select">Auswahl</option>
                            <option value="boolean">Ja/Nein</option>
                            <option value="rating">Sternebewertung</option>
                            <option value="date">Datum</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Fragetext</label>
                    <input type="text" wire:model.defer="question_text" class="mt-1 block w-full border rounded px-4 py-2">
                    @error('question_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>


                <!-- Titel und Beschreibung -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Frontend-Titel</label>
                    <input type="text" wire:model.defer="frontend_title" class="mt-1 block w-full border rounded px-4 py-2">
                    @error('frontend_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Frontend-Beschreibung</label>
                    <textarea wire:model.defer="frontend_description" rows="3" class="mt-1 block w-full border rounded px-4 py-2"></textarea>
                    @error('frontend_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Hilfe, Tags, Einschränkungen -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Hilfetext</label>
                    <textarea wire:model.defer="help_text" rows="2" class="mt-1 block w-full border rounded px-4 py-2"></textarea>
                    @error('help_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tags (kommagetrennt)</label>
                    <input type="text" wire:model.defer="tags" class="mt-1 block w-full border rounded px-4 py-2">
                    @error('tags') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Eingabebeschränkungen (JSON)</label>
                    <textarea wire:model.defer="input_constraints" rows="3" class="mt-1 block w-full border rounded px-4 py-2 font-mono text-xs"></textarea>
                    @error('input_constraints') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Checkboxen -->
                <div class="flex items-center space-x-6 mt-6">
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" wire:model.defer="is_required" class="mr-2">
                        Pflichtfrage
                    </label>

                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" wire:model.defer="read_only" class="mr-2">
                        Nur lesbar
                    </label>

                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="checkbox" wire:model.defer="is_active" class="mr-2">
                        Aktiv
                    </label>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex items-center space-x-3">
                <x-button wire:click="save" class="btn-xs text-sm">
                    Speichern
                </x-button>
                <x-button wire:click="$set('showModal', false)" class="btn-xs text-sm">
                    Schließen
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
