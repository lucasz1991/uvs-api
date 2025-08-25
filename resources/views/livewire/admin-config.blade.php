<div class="" wire:loading.class="cursor-wait">
    <h1 class="text-2xl font-semibold mb-6">Konfiguration</h1>
    <!-- Tabs Navigation -->
    <div x-data="{ activeTab: 'none' }">
        <div class="border-b mb-6">
            <nav class="-mb-px flex space-x-8">
                <button 
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'none', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'none' }"
                    @click="activeTab = 'none'"
                >
                    Übersicht
                </button>
                <button 
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'basis', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'basis' }"
                    @click="activeTab = 'basis'"
                >
                    Basis
                </button>
                
                <button 
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'mails', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'mails' }"
                    @click="activeTab = 'mails'"
                >
                    Mail's
                </button>
                <button 
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'api', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'api' }"
                    @click="activeTab = 'api'"
                >
                    Api's
                </button>
            </nav>
        </div>
        <!-- Tab Content -->
        <div>
            <!-- Kein Tab ausgewählt -->
            <div x-show="activeTab === 'none'" x-cloak class="space-y-10" x-collapse.duration.400ms>
                <div class="">
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 hidden">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.625 23.625" fill="currentColor" aria-hidden="true">
                                    <path d="M11.812 0C5.289 0 0 5.289 0 11.812s5.289 11.813 11.812 11.813 11.813-5.29 11.813-11.813S18.335 0 11.812 0zm2.459 18.307c-.608.24-1.092.422-1.455.548a3.838 3.838 0 0 1-1.262.189c-.736 0-1.309-.18-1.717-.539s-.611-.814-.611-1.367c0-.215.015-.435.045-.659a8.23 8.23 0 0 1 .147-.759l.761-2.688c.067-.258.125-.503.171-.731.046-.23.068-.441.068-.633 0-.342-.071-.582-.212-.717-.143-.135-.412-.201-.813-.201-.196 0-.398.029-.605.09-.205.063-.383.12-.529.176l.201-.828c.498-.203.975-.377 1.43-.521a4.225 4.225 0 0 1 1.29-.218c.731 0 1.295.178 1.692.53.395.353.594.812.594 1.376 0 .117-.014.323-.041.617a4.129 4.129 0 0 1-.152.811l-.757 2.68a7.582 7.582 0 0 0-.167.736 3.892 3.892 0 0 0-.073.626c0 .356.079.599.239.728.158.129.435.194.827.194.185 0 .392-.033.626-.097.232-.064.4-.121.506-.17l-.203.827zm-.134-10.878a1.807 1.807 0 0 1-1.275.492c-.496 0-.924-.164-1.28-.492a1.57 1.57 0 0 1-.533-1.193c0-.465.18-.865.533-1.196a1.812 1.812 0 0 1 1.28-.497c.497 0 .923.165 1.275.497.353.331.53.731.53 1.196 0 .467-.177.865-.53 1.193z" data-original="#030104" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm">
                                    <strong class="text-lg">Achtung! Änderungen haben sofortige Auswirkungen auf den Shop!</strong> <br>
                                    Jede Änderung, die du hier vornimmst, wird umgehend in deinem Shop sichtbar und kann die Funktionsweise direkt beeinflussen. Bitte überlege sorgfältig, bevor du Anpassungen vornimmst. Wenn du dir nicht sicher bist, wie sich eine Änderung auswirkt oder welche Auswirkungen sie haben könnte, wende dich bitte an den Systemadministrator, um Missverständnisse oder unerwünschte Konsequenzen zu vermeiden. Eine gut durchdachte Entscheidung ist hier entscheidend.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Abschnitt: E-Mails -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-700">E-Mail-Einstellungen</h3>
                            <p class="text-sm text-gray-600 mt-2">
                                Konfiguriere die Haupt-E-Mail-Adresse für Systemnachrichten und lege fest, wann und wie E-Mails gesendet werden sollen.
                            </p>
                            <a  @click="activeTab = 'mails'" class="text-blue-500 mt-3 inline-block font-medium cursor-pointer">E-Mail-Konfiguration →</a>
                        </div>
                        <!-- Abschnitt: API -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-700">API-Integrationen</h3>
                            <p class="text-sm text-gray-600 mt-2">
                                Verwalte API-Schlüssel und integriere Drittanbieter-Dienste, um die Funktionalität deiner Plattform zu erweitern.
                            </p>
                            <a @click="activeTab = 'api'" class="text-blue-500 mt-3 inline-block font-medium cursor-pointer">API-Einstellungen →</a>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 text-center">
                            Stelle sicher, dass alle Einstellungen korrekt vorgenommen werden, um ein reibungsloses Nutzererlebnis zu garantieren. Bei Fragen oder Problemen kannst du den Support kontaktieren.
                        </p>
                    </div>
                </div>
            </div>
            <!-- basic Tab -->
            <div x-show="activeTab === 'basis'" x-cloak class="space-y-10" x-collapse.duration.400ms>
                @livewire('admin.config.basic-settings')
            </div>

                <!-- Mails Tab -->
                <div x-show="activeTab === 'mails'" x-cloak class="space-y-10" x-collapse.duration.400ms>
                <h2 class="text-2xl font-semibold">Mails</h2>
                    <!-- Admin E-Mail Adresse -->
                    <x-settings-collapse>
                        <x-slot name="trigger">
                            Admin E-Mail Adresse
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-blue-100 text-blue-700 p-4 rounded-md border border-blue-200 mb-4">
                                <strong>Hinweis:</strong> Hier kannst du die E-Mail-Adresse des Administrators angeben. Diese Adresse wird für Benachrichtigungen und systemweite E-Mails verwendet.
                            </div>
                            <div class="mb-4">
                                <label for="admin_email" class="block text-sm font-medium text-gray-700">Admin E-Mail Adresse</label>
                                <input 
                                    type="email" 
                                    id="admin_email" 
                                    wire:model="adminEmail" 
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required
                                >
                                @error('adminEmail')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button 
                                wire:click="saveAdminEmail" 
                                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                            >
                                E-Mail Adresse speichern
                            </button>
                        </x-slot>
                    </x-settings-collapse>
                    <!-- Automatische Admin Mails -->
                    <x-settings-collapse>
                        <x-slot name="trigger">
                            Automatische Admin E-Mails
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-blue-100 text-blue-700 p-4 rounded-md border border-blue-200 mb-4">
                                <strong>Hinweis:</strong> Wähle aus, welche automatischen Benachrichtigungen an den Admin gesendet werden sollen.
                            </div>
                            <!-- Checkboxen für Admin Mails -->
                            <div class="space-y-3">
                                @foreach ($adminEmailNotifications as $key => $value)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="admin_mail_{{ $key }}" 
                                            wire:model="adminEmailNotifications.{{ $key }}" 
                                            class="mr-2"
                                        >
                                        <label for="admin_mail_{{ $key }}" class="text-sm font-medium text-gray-700">
                                            {{ __("mails.admin.$key") }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Speichern Button -->
                            <button 
                                wire:click="saveAdminMailSettings" 
                                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mt-4"
                            >
                                Änderungen speichern
                            </button>
                        </x-slot>
                    </x-settings-collapse>
                    <!-- Benutzer Mails -->
                    <x-settings-collapse>
                        <x-slot name="trigger">
                            Benutzer E-Mails
                        </x-slot>
                        <x-slot name="content">
                            <div class="bg-blue-100 text-blue-700 p-4 rounded-md border border-blue-200 mb-4">
                                <strong>Hinweis:</strong> Wähle aus, welche automatischen E-Mails an Benutzer gesendet werden sollen.
                            </div>
                            <!-- Checkboxen für Benutzer Mails -->
                            <div class="space-y-3">
                                @foreach ($userEmailNotifications as $key => $value)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="user_mail_{{ $key }}" 
                                            wire:model="userEmailNotifications.{{ $key }}" 
                                            class="mr-2"
                                        >
                                        <label for="user_mail_{{ $key }}" class="text-sm font-medium text-gray-700">
                                            {{ __("mails.user.$key") }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Speichern Button -->
                            <button 
                                wire:click="saveUserMailSettings" 
                                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 mt-4"
                            >
                                Änderungen speichern
                            </button>
                        </x-slot>
                    </x-settings-collapse>
                </div>
                <!-- Api Tab -->
                <div x-show="activeTab === 'api'" x-cloak class="space-y-10" x-collapse.duration.400ms>
                    <h2 class="text-2xl font-semibold">API Einstellungen</h2>
                    <!-- Kassen-API -->
                    <x-settings-collapse>
                        <x-slot name="trigger">
                            Base Api
                        </x-slot>
                        <x-slot name="content">
                            
                            <!-- API URL Eingabe für Kassen-API -->
                            <div class="mb-4">
                                <label for="base_api_url" class="block text-sm font-medium text-gray-700">Base-API URL</label>
                                <input 
                                    type="url" 
                                    id="base_api_url" 
                                    wire:model="apiSettings.base_api_url" 
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required
                                >
                                @error('apiSettings.base_api_url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- API Key Eingabe für Kassen-API -->
                            <div class="mb-4">
                                <label for="base_api_key" class="block text-sm font-medium text-gray-700">Base-API Schlüssel</label>
                                <input 
                                    type="text" 
                                    id="base_api_key" 
                                    wire:model="apiSettings.base_api_key" 
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required
                                >
                                @error('apiSettings.base_api_key')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <x-action-message class="me-3" on="saveApiSettings">
                                    {{ __('Gespeichert.') }}
                                </x-action-message>
                                <!-- Speichern Button -->
                                <button 
                                    wire:click="saveApiSettings" 
                                    class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                                >
                                    speichern
                                </button>
                            </div>
                        </x-slot>
                    </x-settings-collapse>
                    <!-- API-Keys Verwaltung -->
                    <x-settings-collapse>
                        <x-slot name="trigger">API-Schlüssel verwalten</x-slot>
                        <x-slot name="content">
                            <div class="mb-4">
                                <x-button 
                                    wire:click="generateApiKey" 
                                    class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600">
                                    Neuen API-Schlüssel generieren
                                </x-button>
                            </div>
                            <ul>
                                @foreach ($apiKeys as $key => $value)
                                    <li class="flex items-center justify-between mb-2 bg-white  px-2 py-1 rounded">
                                        <span class="text-sm font-mono">{{ $value }}</span>
                                        <button 
                                            wire:click="deleteApiKey('{{ $key }}')" 
                                            class="text-red-500 hover:underline">
                                            Löschen
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </x-slot>
                    </x-settings-collapse>
                </div>
            </div>
        </div>
</div>

