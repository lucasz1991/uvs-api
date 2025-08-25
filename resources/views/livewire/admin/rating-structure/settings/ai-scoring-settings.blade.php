<x-settings-collapse>
    <x-slot name="trigger">
        AI-Scoring Einstellungen
    </x-slot>
    <x-slot name="content">
        <div class="">
            @if (session()->has('success'))
                <div class="text-green-600 mb-4">{{ session('success') }}</div>
            @endif
            <form wire:submit.prevent="saveSettings">
                
                <div class="mb-4 flex justify-between space-x-3">
                    <div class="flex items-center space-x-3 mt-2 ">
                        <label for="status" class="flex items-center cursor-pointer">
                            <input 
                                id="status" 
                                name="status" 
                                type="checkbox" 
                                wire:model.live="status" 
                                class="sr-only peer" 
                            />
                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium ">Aktiv</span>
                        </label>
                    </div>
                    <x-button type="submit" class="btn-xs text-xs">Speichern</x-button>
                </div>
                @if($status)

                    <div class="mb-4">
                        <label class="block font-medium">API Url:</label>
                        <input type="url" wire:model="apiUrl" class="border p-2 w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">API Key:</label>
                        <input type="text" wire:model="apiKey" class="border p-2 w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">AI Model:</label>
                        <input type="text" wire:model="aiModel" class="border p-2 w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">Modell Titel:</label>
                        <input type="text" wire:model="modelTitle" class="border p-2 w-full">
                    </div>
                    <div class="mb-4">
                        <label class="block font-medium">Referer URL:</label>
                        <input type="url" wire:model="refererUrl" class="border p-2 w-full">
                    </div>
                @endif
            </form>
        </div>
    </x-slot>
</x-settings-collapse>