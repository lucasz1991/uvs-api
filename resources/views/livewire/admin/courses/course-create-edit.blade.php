<div>
    <x-dialog-modal wire:model="showModal" :maxWidth="'2xl'">
        <x-slot name="title">
            Kurs-Einstellungen
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Titel</label>
                    <input type="text" wire:model="title" class="mt-1 block w-full border rounded px-4 py-2" />
                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Beschreibung</label>
                    <textarea wire:model="description" rows="3" class="mt-1 block w-full border rounded px-4 py-2"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                    <x-ui.accordion.tabs
                        :tabs="['termine' => 'Termine', 'teilnehmer' => 'Teilnehmer']"
                        default="termine"
                        class="mt-4"
                    >
                    {{-- TAB: Termine --}}
                    <x-ui.accordion.tab-panel for="termine">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start</label>
                                <input type="date" wire:model="start_time" class="mt-1 block w-full border rounded px-4 py-2" />
                                @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ende</label>
                                <input type="date" wire:model="end_time" class="mt-1 block w-full border rounded px-4 py-2" />
                                @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        {{-- FullCalendar (nur wenn Kurs vorhanden) --}}
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
                        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js" defer></script>
                        @if($courseId)
                            <div class="mt-4">
                                <x-calendar.show-dates
                                    :dates="$course->dates"
                                    :eventTitle="$course->title"
                                    dateField="date"
                                    startTimeField="start_time"
                                    endTimeField="end_time"
                                />
                            </div>
                        @endif
                    </x-ui.accordion.tab-panel>

                    {{-- TAB: Teilnehmer --}}
                    <x-ui.accordion.tab-panel for="teilnehmer">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tutor</label>
                            <select wire:model="tutor_id" class="mt-1 block w-full border rounded px-4 py-2 bg-white">
                                <option value="">— wählen —</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                                @endforeach
                            </select>
                            @error('tutor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Teilnehmer</label>
                            <select wire:model="participants" multiple class="w-full border rounded px-4 py-2 mt-1 bg-white">
                                @foreach ($possibleParticipants as $participant)
                                    <option value="{{ $participant->id }}">{{ $participant->name }} ({{ $participant->email }})</option>
                                @endforeach
                            </select>
                            @error('participants') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </x-ui.accordion.tab-panel>
                </x-ui.accordion.tabs>

            </div>
        </x-slot>

        <x-slot name="footer" >
            <div class="flex  space-x-3">
                <x-button wire:click="saveCourse" class="btn-xs text-sm bg-green-50 hover:bg-green-200 text-green-800 border-green-200" >Speichern</x-button>
                <x-button wire:click="closeModal" class="btn-xs text-sm">Schließen</x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
