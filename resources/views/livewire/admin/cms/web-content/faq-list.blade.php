<div>
        <div class="flex justify-between mb-4">
            <h1 class="flex items-center justify-center text-lg px-2 py-1">FAQ-Verwaltung 
                <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-auto leading-none">
                {{ $contents->count() }}
                </span>
            </h1>
            <button @click="faqModalOpen = true" class="bg-green-500 text-white px-4  rounded hover:bg-green-600 mb-6 btn-xs py-3 ">
                Neue FAQ hinzufügen
            </button>
        </div>
    <div x-data="{ faqModalOpen: @entangle('faqModalOpen') }">
        
        <!-- Modal für das Erstellen einer neuen FAQ -->
        <x-dialog-modal wire:model="faqModalOpen">
            <x-slot name="title">
                <h3 class="text-xl font-semibold">Neue FAQ hinzufügen</h3>
            </x-slot>
            <x-slot name="content">
                <form wire:submit.prevent="addContent" class="space-y-4">
                    <div>
                        <label for="newKey" class="block text-sm font-medium text-gray-700">Frage</label>
                        <input type="text" id="newKey" wire:model="newKey" required class="mt-1 block w-full border rounded px-4 py-2">
                        @error('newKey') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="newValue" class="block text-sm font-medium text-gray-700">Antwort</label>
                        <textarea id="newValue" wire:model="newValue" rows="4" required class="mt-1 block w-full border rounded px-4 py-2"></textarea>
                        @error('newValue') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="hidden" wire:model="newType" value="faq">
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
                <button wire:click="addContent" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Speichern
                </button>
                <button @click="faqModalOpen = false" class="text-gray-500 hover:text-gray-700 px-4 py-2">Abbrechen</button>
            </x-slot>
        </x-dialog-modal>
    </div>
    <!-- Tabelle mit FAQ-WebContents -->
    <table class="table-auto w-full mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left border">Frage</th>
                <th class="px-4 py-2 text-left border">Antwort</th>
                <th class="px-4 py-2 border">Aktionen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents as $content)
                <tr>
                    <td class="border px-4 py-2">{{ $content->key }}</td>
                    <td class="border px-4 py-2">{{ $content->value }}</td>
                    <td class="border px-4 py-2 text-center">
                        <button wire:click="deleteContent({{ $content->id }})" class="text-red-500 hover:text-red-700">Löschen</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
