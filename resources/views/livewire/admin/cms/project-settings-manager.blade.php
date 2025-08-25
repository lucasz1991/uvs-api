<div>
    <!-- Modal für die Projektbearbeitung -->
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            Modul Einstellungen
        </x-slot>
        <x-slot name="content">
                <!-- Eingabefelder -->
                <div class="mb-4 grid grid-cols-5 gap-4">
                    <div class="col-span-4 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" wire:model="name" class="mt-1 block w-full border rounded px-4 py-2">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <!-- Sprache -->
                    <div class="col-span-1 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sprache</label>
                        <select wire:model="lang" id="langSelect" class="w-full mt-1 block border rounded px-4 py-2 bg-white">
                            <option value="">Alle</option>
                            <option value="DE">Deutsch</option>
                            <option value="EN">Englisch</option>
                        </select>
                        @error('lang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <!-- Veröffentlichungszeitraum -->
                <div class="mb-4 grid grid-cols-5 gap-4">
                    <!-- Status -->
                    <div class="col-span-1 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select wire:model.live="status" class="mt-1 block w-full bg-white border rounded px-1 py-2 {{ $status == 0 ? 'border-gray-100 text-gray-700' : ($status == 1 ? 'border-green-100 text-green-700' : 'border-red-100 text-red-700') }}">
                            <option class="bg-white text-gray-700" value="0">Entwurf</option>
                            <option class="bg-white text-gray-700" value="1">Veröffentlicht</option>
                            <option class="bg-white text-gray-700" value="2">Archiviert</option>
                        </select>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Veröffentlicht von</label>
                        <input type="datetime-local" wire:model="published_from" class="mt-1 block w-full border rounded px-4 py-2">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Veröffentlicht bis</label>
                        <input type="datetime-local" wire:model="published_until" class="mt-1 block w-full border rounded px-4 py-2">
                    </div>
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <!-- Seiten Auswahl -->
                    <div class="mb-4" x-data="{ selectedPages: @entangle('page') }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seiten</label>
                        <select id="pageSelect" multiple 
                            x-init="
                                let choices = new Choices($el, { removeItemButton: true, shouldSort: false });
                                $nextTick(() => {
                                    if (selectedPages.length > 0) {
                                        choices.setChoiceByValue(selectedPages);
                                    }
                                });
                                $el.addEventListener('change', () => {
                                    selectedPages = [...$el.selectedOptions].map(option => option.value);
                                });
                            "
                            class="mt-1">

                            <option value="all">Alle Seiten</option>
                            @foreach($webpages as $webpage)
                                <option value="{{ $webpage->slug }}">{{ $webpage->title }}</option>
                            @endforeach
                        </select>
                        @error('page') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <!-- Positionen Auswahl -->
                    <div class="mb-4" x-data="{ selectedPositions: @entangle('position') }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Positionen</label>
                        <select id="positionSelect" multiple
                            x-init="
                                let choices = new Choices($el, { removeItemButton: true, shouldSort: false });
                                $nextTick(() => {
                                    if (selectedPositions.length > 0) {
                                        choices.setChoiceByValue(selectedPositions);
                                    }
                                });
                                $el.addEventListener('change', () => {
                                    selectedPositions = [...$el.selectedOptions].map(option => option.value);
                                });
                            "
                            class="mt-1">
                            <option value="top_banner">Über Banner</option>
                            <option value="banner">Haupt Banner</option>
                            <option value="bottom_banner">Unter Banner</option>
                            <option value="content">Main Content</option>
                            <option value="footer">Footer</option>
                            <option value="modal">Modal Popup</option>
                        </select>
                        @error('position') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

            </x-slot>
            <x-slot name="footer">
                <div class="flex items-center space-x-3">
                    <x-button wire:click="saveProject" class="btn-xs text-sm">
                        Speichern
                    </x-button>
                    <x-button wire:click="closeModal" class="btn-xs text-sm">
                        Schließen
                    </x-button>
                </div>
                @section('css')
                    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
                    <link href="{{ URL::asset('build/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
                @endsection
                @section('scripts')
                    <script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
                @endsection
            </x-slot>
        </x-dialog-modal>
</div>
