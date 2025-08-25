<div x-data="{ selectedUsers: @entangle('selectedUsers'), search: @entangle('search'), hasUsers: @entangle('hasUsers') }"  wire:loading.class="cursor-wait">
    @persist('scrollbar')
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 23.625 23.625" fill="currentColor" aria-hidden="true">
                    <path
                        d="M11.812 0C5.289 0 0 5.289 0 11.812s5.289 11.813 11.812 11.813 11.813-5.29 11.813-11.813S18.335 0 11.812 0zm2.459 18.307c-.608.24-1.092.422-1.455.548a3.838 3.838 0 0 1-1.262.189c-.736 0-1.309-.18-1.717-.539s-.611-.814-.611-1.367c0-.215.015-.435.045-.659a8.23 8.23 0 0 1 .147-.759l.761-2.688c.067-.258.125-.503.171-.731.046-.23.068-.441.068-.633 0-.342-.071-.582-.212-.717-.143-.135-.412-.201-.813-.201-.196 0-.398.029-.605.09-.205.063-.383.12-.529.176l.201-.828c.498-.203.975-.377 1.43-.521a4.225 4.225 0 0 1 1.29-.218c.731 0 1.295.178 1.692.53.395.353.594.812.594 1.376 0 .117-.014.323-.041.617a4.129 4.129 0 0 1-.152.811l-.757 2.68a7.582 7.582 0 0 0-.167.736 3.892 3.892 0 0 0-.073.626c0 .356.079.599.239.728.158.129.435.194.827.194.185 0 .392-.033.626-.097.232-.064.4-.121.506-.17l-.203.827zm-.134-10.878a1.807 1.807 0 0 1-1.275.492c-.496 0-.924-.164-1.28-.492a1.57 1.57 0 0 1-.533-1.193c0-.465.18-.865.533-1.196a1.812 1.812 0 0 1 1.28-.497c.497 0 .923.165 1.275.497.353.331.53.731.53 1.196 0 .467-.177.865-.53 1.193z"
                        data-original="#030104" />
                </svg>
            </div>
            <div class="ml-3">
                <div class="text-sm">
                    <p>In der Benutzerverwaltung können Sie folgende Aktionen ausführen:</p>
                    <ul class="list-disc pl-5">
                        <li>Benutzer durchsuchen und filtern</li>
                        <li>Benutzer nach Name, E-Mail oder Registrierungsdatum sortieren</li>
                        <li>Mehrere Benutzer auswählen und Massenaktionen wie Aktivieren, Deaktivieren oder Löschen durchführen</li>
                        <li>E-Mails an ausgewählte Benutzer senden</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4 flex flex-wrap  justify-between gap-4">
        <div class="mb-6 max-w-md">
            <h1 class="text-2xl font-bold text-gray-700">Benutzer</h1>
            <p class="text-gray-500">Es gibt insgesamt {{ $users->total() }} Benutzer.</p>
        </div>
        <div class="">
            <livewire:admin.charts.active-users :height="150"/>
        </div>
    </div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
        <div x-data="{ focused: false }" @click.away="focused = false" x-cloak class="relative">
            <div class="flex items-center border border-gray-300 rounded-full ring  ring-offset-4 transition-all duration-300"
                :class="{
                    'w-[300px]': (focused || search.length > 0),
                    'w-[40px]': !(focused || search.length > 0),
                    'ring ring-green-200': (search.length > 0 && hasUsers),
                    'ring ring-red-200': (search.length > 0 && !hasUsers)
                }">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Suchen..."
                    class="w-full px-2 py-1 text-sm focus:ring-none bg-transparent border-none ring-none"
                    x-ref="searchInput" 
                    @click="focused = true" 
                    :class="(focused || search.length > 0) ? 'block' : 'hidden'" />
                <div @click="focused = true; $refs.searchInput.focus()"
                    class="flex items-center justify-center w-[40px] h-[40px] text-gray-400 hover:text-gray-500 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="h-4 w-4">
                        <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="mt-4 relative" x-data="{ open: false }">
            <div class="">
                @php
                    $isDisabled = count($selectedUsers) === 0;
                    $buttonClass = $isDisabled 
                        ? 'cursor-not-allowed opacity-50 bg-gray-100 text-sm border px-3 py-1 rounded relative flex items-center justify-center'
                        : 'cursor-pointer bg-gray-100 text-sm border px-3 py-1 rounded relative flex items-center justify-center';
                @endphp

                <x-actionbutton 
                    class="{{ $buttonClass }}" 
                    @click="{{ !$isDisabled ? 'open = !open' : '' }}"
                >
                    <svg class="w-4 h-4 text-gray-600 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.005 11.19V12l6.998 4.042L19 12v-.81M5 16.15v.81L11.997 21l6.998-4.042v-.81M12.003 3 5.005 7.042l6.998 4.042L19 7.042 12.003 3Z"/>
                    </svg>
                    @if(count($selectedUsers) > 0)
                        <span
                            class="ml-2 bg-yellow-400 text-white text-xs font-bold px-2 py-0.5 rounded-full"
                        >
                            {{ count($selectedUsers) }}
                        </span>
                    @endif
                </x-actionbutton>
            </div>
            <div 
                x-show="open" 
                @click.away="open = false" 
                x-cloak 
                x-collapse
                class="absolute bg-white right-0 border mt-1 float-left rounded shadow-lg text-left z-40" 
            >
                <ul>
                    <li>
                        <button wire:click="activateUsers" class="text-left w-full block px-4 py-2 text-gray-700 hover:bg-green-100">
                            Aktivieren
                        </button>
                    </li>
                    <li>
                        <button wire:click="deactivateUsers" class="text-left w-full block px-4 py-2 text-gray-700 hover:bg-yellow-100">
                            Deaktivieren
                        </button>
                    </li>
                    <li>
                        <button wire:click="openMailModal" class="text-left w-full h-full block px-4 py-2 text-gray-700 hover:bg-blue-100">
                            Mail senden
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300">
      
            <div class="col-span-5 flex items-center">
            <x-button 
                wire:click="toggleSelectAll" 
                class="btn-xs mr-3"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
            </svg>
            </x-button>
            <button wire:click="sortByField('name')" class="text-left flex items-center">
                Name
                @if ($sortBy === 'name')
                    <span class="ml-2 text-xl"
                        style="display: inline-block;">
                        <svg class="w-4 h-4 ml-2  transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-4 flex items-center">
            <button wire:click="sortByField('email')" class="text-left flex items-center">
                E-Mail
                @if ($sortBy === 'email')
                    <span class="ml-2 text-xl"
                        style="display: inline-block;">
                        <svg class="w-4 h-4 ml-2  transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-3 flex items-center">
            <button wire:click="sortByField('created_at')" class="text-left flex items-center">
                Registriert am
                @if ($sortBy === 'created_at')
                    <span class="ml-2 text-xl"
                        style="display: inline-block;">
                        <svg class="w-4 h-4 ml-2  transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
    </div>
    <div>
        @foreach ($users as $user)
            <div class="grid grid-cols-12 items-center p-2 border text-left hover:bg-blue-100 text-sm">
                
                <div class="col-span-5 font-bold pl-1 cursor-pointer"  wire:click="toggleUserSelection({{ $user->id }})">
                    <div class="flex items-center space-x-4">
                        <img     class="h-10 w-10 rounded-full object-cover transition-all duration-300 {{ in_array($user->id, $selectedUsers) ? 'ring-4 ring-green-300' : '' }}" 
                        src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                        <div>
                            <div class="text-sm font-medium">
                                {{ $user->name }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $user->customer->first_name ?? 'Vorname' }} {{ $user->customer->last_name ?? 'Nachname' }}
                            </div>
                        </div>                    
                    </div>
                </div>
                <div class="col-span-4 cursor-pointer flex items-center space-x-2" wire:click="toggleUserSelection({{ $user->id }})">
                    <span>{{ $user->email }}</span>
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

                <div 
                    class="col-span-2 text-gray-600 cursor-pointer" 
                    wire:click="toggleUserSelection({{ $user->id }})" 
                    title="{{ $user->created_at->format('d.m.Y H:i') }}">
                    {{ $user->created_at->format('d.m.Y') }}
                </div>
                <div class="col-span-1 text-gray-600 relative">
                    <!-- Status-Punkt -->
                    <div class="flex items-center space-x-2 justify-between">
                        <span title="{{ $user->status ? 'Aktiv' : 'Inaktiv' }}" class="h-4 w-4 rounded-full flex items-center justify-center {{ $user->status ? 'bg-green-400' : 'bg-red-400' }}" >    
                            @if ($user->status)
                                <!-- SVG für Aktiv (Haken) -->
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-3 w-3 text-white" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke-width="4" 
                                    stroke="currentColor"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        d="M5 13l4 4L19 7" 
                                    />
                                </svg>
                            @else
                                <!-- SVG für Inaktiv (X) -->
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-3 w-3 text-white" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke-width="4" 
                                    stroke="currentColor"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        d="M6 18L18 6M6 6l12 12" 
                                    />
                                </svg>
                            @endif

                        </span>
                        <!-- Dropdown -->
                        <div x-data="{ open: false }" class="relative " >
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-800 transition duration-200 scale-100 hover:scale-120 hover:bg-gray-100 focus:bg-gray-100 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                            </button>
                            <!-- Dropdown-Menü -->
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-lg z-50"
                                x-cloak
                            >
                                <ul class="text-sm text-gray-700">
                                    <li>
                                    <a href="{{ route('admin.user-profile', ['userId' => $user->id]) }}"  wire:navigate
                                      class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                        Profil
                                    </a>
                                    </li>
                                    <li>
                                        @if ($user->status)
                                            <button wire:click="deactivateUser({{ $user->id }})" class="block w-full px-4 py-2 text-left hover:bg-yellow-100">
                                                Deaktivieren
                                            </button>
                                        @else
                                            <button wire:click="activateUser({{ $user->id }})" class="block w-full px-4 py-2 text-left hover:bg-green-100">
                                                Aktivieren
                                            </button>
                                        @endif
                                    </li>
                                    <li>
                                        <button  wire:click="openMailModal({{ $user->id }})"  class="block w-full px-4 py-2 text-left  hover:bg-blue-100">
                                            Mail senden
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">
    {{ $users->links() }}
    </div>
    @endpersist
    <livewire:admin.users.messages.message-form />
</div>
