<div 
    x-data="{ selectedTab: $persist('userApiKey').using(sessionStorage) }" 
    class="space-y-4"
>
  <!-- Header-Bild -->
  <div class="rounded-t-lg h-32 overflow-hidden bg-gray-200 relative">
        <!-- Status Badge (links oben) -->
        <div class="absolute top-2 left-2 px-3 py-1 rounded-full text-xs font-semibold text-white" 
             :class="{ 'bg-green-500': {{ $user->isActive() ? 'true' : 'false' }}, 'bg-red-500': {{ !$user->isActive() ? 'true' : 'false' }} }">
            {{ $user->isActive() ? 'Aktiv' : 'Inaktiv' }}
        </div>

        <!-- Erstellungsdatum Badge (rechts oben) -->
        <div class="absolute top-2 right-2 px-3 py-1 rounded-full text-xs font-semibold bg-gray-700 text-white">
            Registriert: {{ $user->created_at->format('d.m.Y') }}
        </div>
    </div>

    <!-- Profilbild -->
    <div class="mx-auto w-32 h-32 relative -mt-16 border-4 border-white rounded-full overflow-hidden">
        <img 
            class="object-cover object-center h-32  aspect-square" 
            src="{{ $user->profile_photo_url ?? 'https://via.placeholder.com/150' }}" 
            alt="{{ $user->name }}"
        >
    </div>

    <!-- Benutzerdetails -->
    <div class="text-center mt-2">
        <h2 class="font-semibold text-lg">{{ $user->name }}</h2>
    </div>

    <!-- Benutzerstatistiken -->
    <ul class="py-4 mt-2 text-gray-700 flex items-center justify-around">
        <li class="flex flex-col items-center justify-around flex-1">
            <svg class="w-6 h-6 fill-current text-blue-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
            </svg>
            <div class="text-center">0 Keys</div>
        </li>
        <li class="flex flex-col items-center justify-around flex-1">
            <svg class="w-6 h-6 fill-current text-blue-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z" />
            </svg>
            <div class="text-center">0 Aktivitäten</div>
        </li>
    </ul>


    <!-- Weitere Aktionen -->
    <div class="p-4 border-t mt-2 flex flex-wrap justify-end gap-4">
        

        <!-- Weitere Optionen mit Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <x-button 
                @click="open = !open" 
                class=""
            >
                Optionen
            </x-button>

            <!-- Dropdown-Menü -->
            <div 
                x-show="open" 
                @click.away="open = false" 
                x-cloak 
                class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg"
            >
                <ul>
                    <li>
                        @if ($user->status)
                            <button wire:click="deactivateUser()" class="block w-full px-4 py-2 text-left hover:bg-yellow-100">
                                Deaktivieren
                            </button>
                        @else
                            <button wire:click="activateUser()" class="block w-full px-4 py-2 text-left hover:bg-green-100">
                                Aktivieren
                            </button>
                        @endif
                    </li>
                    <li>
                        <button 
                            wire:click="openMailModal" 
                            class="block w-full px-4 py-2 text-gray-700 hover:bg-blue-100 text-left"
                        >
                            Mail senden
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<!-- Tab-Menü -->
<ul class="flex w-full text-sm font-medium text-center text-gray-500 bg-gray-100 rounded-lg shadow divide-gray-200">
            <!-- Details Tab -->
        <li class="w-full">
            <button 
                @click="selectedTab = 'userApiKey'" 
                :class="{ 'text-blue-600 bg-gray-100 border-b-2 border-blue-600': selectedTab === 'userApiKey' }" 
                class="w-full p-4 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Api-Schlüssel
            </button>
        </li>
        <!-- Details Tab -->
        <li class="w-full">
            <button 
                @click="selectedTab = 'userDetails'" 
                :class="{ 'text-blue-600 bg-gray-100 border-b-2 border-blue-600': selectedTab === 'userDetails' }" 
                class="w-full p-4 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Details
            </button>
        </li>
        
        <!-- Buchungen Tab -->
        <li class="w-full border-l border-gray-200">
            <button 
                @click="selectedTab = 'activities'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'activities' }" 
                class="w-full p-4 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Aktivitäten
            </button>
        </li>

    </ul>

    <!-- Benutzer- und Kundendetails -->
    <div>
        <div  x-show="selectedTab === 'userApiKey'" x-collapse  x-cloak>
            <div class="w-full bg-gray-100 shadow rounded-lg p-6 mt-4">
                  <livewire:users.ap-management.user-api-keys-panel :user-id="$user->id" lazy/>
                  

            </div>
        </div>
        <div  x-show="selectedTab === 'userDetails'" x-collapse  x-cloak>
            <div class="w-full bg-gray-100 shadow rounded-lg p-6 mt-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Benutzerprofil</h2>

                <!-- Benutzerinformationen -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Benutzerdetails</h3>
                        <p><strong>Benutzername:</strong> {{ $user->name }}</p>
                        <p>
                        
                        <div class="col-span-4  flex items-center space-x-2" >
                            <span><strong>E-Mail:</strong> {{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <span 
                                    class="h-2 w-2 rounded-full bg-green-300" 
                                    title="Verifiziert am: {{ $user->email_verified_at->format('d.m.Y H:i') }}">
                                </span>
                            @else
                                <span 
                                    class="h-2 w-2 rounded-full bg-red-300" 
                                    title="E-Mail nicht verifiziert">
                                </span>
                            @endif
                        </div>
                        </p>
                        <p><strong>Registriert am:</strong> {{ $user->created_at->format('d.m.Y') }}</p>
                    </div>

                    <div>
                    </div>
                </div>
            </div>
        </div>
        <div  x-show="selectedTab === 'activities'" x-collapse  x-cloak>
            <div class="w-full bg-gray-100 shadow rounded-lg p-6 mt-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Aktivitäten</h2>

            </div>
        </div>
    </div>



    <div x-data="{ showModal: @entangle('showMailModal') }" x-init="$watch('showModal', value => { if (!value) $wire.set('mailUserId', {{ $user->id }}); })">
        <!-- Modal für Mail-Verfassen -->
        <x-dialog-modal wire:model="showMailModal">
            <x-slot name="title">
                Mail verfassen
                <!-- Anzeige der E-Mail des aktuellen Benutzers -->
                <span class="text-sm text-gray-500 block mt-1">
                    An: {{ $user->email }}
                </span>
            </x-slot>
            <x-slot name="content">
                <!-- Alert Hinweis -->
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Wichtiger Hinweis</p>
                    <p>Bitte stelle sicher, dass die E-Mail sorgfältig und überlegt verfasst ist. Überprüfe insbesondere den Betreff, die Überschrift und die Nachricht auf Rechtschreibung und Relevanz, da sie direkt an den Benutzer gesendet wird.</p>
                </div>
                <div>
                    <label for="mailSubject" class="block text-sm font-medium text-gray-700">Betreff</label>
                    <input type="text" id="mailSubject" wire:model="mailSubject" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <x-input-error for="mailSubject" class="mt-2" />
                </div>
                <div class="mt-4">
                    <label for="mailHeader" class="block text-sm font-medium text-gray-700">Überschrift</label>
                    <input type="text" id="mailHeader" wire:model="mailHeader" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <x-input-error for="mailHeader" class="mt-2" />
                </div>
                <div class="mt-4">
                    <label for="mailBody" class="block text-sm font-medium text-gray-700">Nachricht</label>
                    <textarea id="mailBody" rows="6" wire:model="mailBody" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    <x-input-error for="mailBody" class="mt-2" />
                </div>
                <div class="mt-4">
                    <label for="mailLink" class="block text-sm font-medium text-gray-700">Link (optional)</label>
                    <input type="url" id="mailLink" wire:model="mailLink" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <x-input-error for="mailLink" class="mt-2" />
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="resetMailModal" wire:loading.attr="disabled">
                    Abbrechen
                </x-secondary-button>
                <x-button wire:click="sendMail" wire:loading.attr="disabled" class="ml-2">
                    Senden
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </div>

</div>
