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
                Shop Details
            </x-slot>
            <x-slot name="content">
                <!-- Allgemeine Einstellungen -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Firma</label>
                        <input type="text" wire:model.live="companyName" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('companyName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Shop-Name</label>
                        <input type="text" wire:model.live="shopName" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('shopName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kontakt E-Mail</label>
                        <input type="email" wire:model.live="contactEmail" @change="changed = true" class="border rounded px-4 py-2 w-full">
                        @error('contactEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Währung</label>
                        <select wire:model.live="currency" @change="changed = true" class="border bg-white rounded px-4 py-2 w-full">
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">US-Dollar ($)</option>
                            <option value="GBP">Britisches Pfund (£)</option>
                        </select>
                        @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Zeitzone</label>
                        <select wire:model.live="timezone" @change="changed = true" class="border bg-white rounded px-4 py-2 w-full">
                            <option value="Europe/Berlin">Berlin</option>
                            <option value="America/New_York">New York</option>
                            <option value="Asia/Tokyo">Tokio</option>
                        </select>
                        @error('timezone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mehrwertsteuer (%)</label>
                        <input type="number" wire:model.live="vatRate" @change="changed = true" class="border rounded px-4 py-2 w-full" min="0" max="100">
                        @error('vatRate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </x-slot>
        </x-settings-collapse>
        <x-settings-collapse>
            <x-slot name="trigger">
                Grundfarben
            </x-slot>
            <x-slot name="content">
                <!-- Grundfarben Auswahl -->
                <div class="">
                    <div wire:ignore class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Primärfarbe -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Primärfarbe
                                <input id="primaryColor" type="text" wire:model.live="primaryColor" @change="changed = true" value="{{ $primaryColor }}" class="hidden">
                                <div class="flex items-center mt-1">
                                    <div class="classic-colorpicker" data-target="primaryColor"></div>
                                    <div class="bg-white rounded-e px-4 py-1">
                                        {{ $primaryColor }}
                                    </div>
                                </div>
                            </label>
                            @error('primaryColor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sekundärfarbe -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Sekundärfarbe
                                <input id="secondaryColor" type="text" wire:model.live="secondaryColor" @change="changed = true" value="{{ $secondaryColor }}" class="hidden">
                                <div class="flex items-center mt-1">
                                    <div class="classic-colorpicker" data-target="secondaryColor"></div>
                                    <div class="bg-white rounded-e px-4 py-1">
                                        {{ $secondaryColor }}
                                    </div>
                                </div>
                            </label>
                            @error('secondaryColor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Akzentfarbe -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Akzentfarbe
                                <input id="accentColor" type="text" wire:model.live="accentColor" @change="changed = true" value="{{ $accentColor }}" class="hidden">
                                <div class="flex items-center mt-1">
                                    <div class="classic-colorpicker" data-target="accentColor"></div>
                                    <div class="bg-white rounded-e px-4 py-1">
                                        {{ $accentColor }}
                                    </div>
                                </div>
                            </label>
                            @error('accentColor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Hintergrundfarbe -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Hintergrundfarbe
                                <input id="backgroundColor" type="text" wire:model.live="backgroundColor" @change="changed = true" value="{{ $backgroundColor }}" class="hidden">
                                <div class="flex items-center mt-1">
                                    <div class="classic-colorpicker" data-target="backgroundColor"></div>
                                    <div class="bg-white rounded-e px-4 py-1">
                                        {{ $backgroundColor }}
                                    </div>
                                </div>
                            </label>
                            @error('backgroundColor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Textfarbe -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Textfarbe
                                <input id="textColor" type="text" wire:model.live="textColor" @change="changed = true" value="{{ $textColor }}" class="hidden">
                                <div class="flex items-center mt-1">
                                    <div class="classic-colorpicker" data-target="textColor"></div>
                                    <div class="bg-white rounded-e px-4 py-1">
                                        {{ $textColor }}
                                    </div>
                                </div>
                            </label>
                            @error('textColor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-settings-collapse>
        <x-settings-collapse>
            <x-slot name="trigger">
            Logos & Favicon
            </x-slot>
            <x-slot name="content">
                <!-- Bild-Uploads -->
                <div class="">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Favicon -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Favicon</label>
                            <input type="file" wire:model.live="favicon"  @change="changed = true" class="border rounded px-4 py-2 w-full">
                            @if ($faviconPreview)
                                <img src="{{ $faviconPreview }}" class="mt-2 h-12 w-12 object-contain">
                            @endif
                            @error('favicon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Logo Square -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Logo (Quadratisch)</label>
                            <input type="file" wire:model.live="logoSquare" @change="changed = true" class="border rounded px-4 py-2 w-full">
                            @if ($logoSquarePreview)
                                <img src="{{ $logoSquarePreview }}" class="mt-2 h-16 w-16 object-contain">
                            @endif
                            @error('logoSquare') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Logo Horizontal -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Logo (Horizontal)</label>
                            <input type="file" wire:model.live="logoHorizontal" @change="changed = true" class="border rounded px-4 py-2 w-full">
                            @if ($logoHorizontalPreview)
                                <img src="{{ $logoHorizontalPreview }}" class="mt-2 h-16 object-contain">
                            @endif
                            @error('logoHorizontal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Logo Vertical -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Logo (Vertikal)</label>
                            <input type="file" wire:model.live="logoVertical" @change="changed = true" class="border rounded px-4 py-2 w-full">
                            @if ($logoVerticalPreview)
                                <img src="{{ $logoVerticalPreview }}" class="mt-2 h-16 object-contain">
                            @endif
                            @error('logoVertical') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
    @section('css')
            <!-- color picker css -->
            <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/classic.min.css') }}" />
            <!-- 'classic' theme -->
            <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    @endsection
    @section('scripts')
        <script src="{{ URL::asset('build/libs/@simonwep/pickr/pickr.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
    @endsection
</div>
