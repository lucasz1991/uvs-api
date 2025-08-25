<div x-cloak class="space-y-6"  x-data="{ changed: false }" x-init="initColorPickers()">
    <!-- Überschrift & Wartungsmodus -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-semibold">Basis Einstellungen</h2>
        <div class="flex items-center space-x-3 mt-2 md:mt-0 ml-3">
            <label for="maintenanceMode" class="flex items-center cursor-pointer">
                <input 
                    id="maintenanceMode" 
                    name="maintenanceMode" 
                    type="checkbox" 
                    @change="changed = true"
                    wire:model.live="maintenanceMode" 
                    class="sr-only peer" 
                />
                <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Wartungsmodus</span>
            </label>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-8">
        <x-settings-collapse>
            <x-slot name="trigger">
            Datenbank UVS
            </x-slot>
            <x-slot name="content">
                <!-- Bild-Uploads -->
                <div class="">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Hostname -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hostname</label>
                        <input type="text" wire:model.defer="hostname" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('hostname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Datenbank -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Datenbank</label>
                        <input type="text" wire:model.defer="database" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('database') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Benutzername -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Benutzername</label>
                        <input type="text" wire:model.defer="username" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Passwort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Passwort</label>
                        <input type="password" wire:model.defer="password" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                </div>
            </x-slot>
        </x-settings-collapse>
        <div class="text-right">
            <x-button wire:click="saveSettings">
                Speichern
            </x-button>
        </div>
    </div>
</div>
