<div  wire:loading.class="cursor-wait">

    <!-- Ladeindikator mit Overlay und Alert -->
    <div  wire:loading wire:target="executeAction"
        x-cloak
        x-data="{ progress: @entangle('progress') }"
        class="">

        <div class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-70 z-50 cursor-wait">
            <div class="text-center bg-white p-6 rounded-lg shadow-lg max-w-xs  w-full transition transform ease-in-out"
            x-transition:enter="transition transform ease-out  duration-300"
            x-transition:enter-start="scale-75 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition transform ease-in duration-300"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-75 opacity-0" x-transition.delay.5000ms >
        
                <!-- Text für den Alert -->
                <p class="text-lg font-semibold text-gray-700">Bitte einen Moment Geduld...</p>
                <p class="text-sm text-gray-600 mt-2">Wir sind dabei, die Daten mit der Kasse zu synchronisieren. Dieser Vorgang kann aufgrund der Verarbeitung etwas Zeit in Anspruch nehmen.</p>
                <!-- Fortschrittsanzeige -->
                <div x-show="progress > 0" x-cloak>
                    <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 mt-4">
                        <div x-transition
                            class="transition duration-300 bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                            :style="`width: ${progress}%`"
                        >
                            <span x-text="progress + '%'"></span>
                        </div>
                    </div>
                </div>

                <!-- Ladeanimation -->
                <div class="loader mt-4 h-10 text-green-500"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>
            </div>
        </div>
    </div>
    <x-back-button />
    <h1 class="text-2xl font-bold my-4">Regalmiete #{{ $shelfRental->id }}</h1>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Details der Regalmiete -->
    <div class="">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h2 class="font-semibold">Kunde:</h2>
                <a  href="{{ route('admin.user-profile', ['userId' => $shelfRental->customer->user->id]) }}"  wire:navigate >

                    <div class="flex items-center space-x-4">
                            <img     class="h-10 w-10 rounded-full object-cover transition-all duration-300 " 
                            src="{{ $shelfRental->customer->user->profile_photo_url }}" alt="{{ $shelfRental->customer->user->name }}" />
                            <div>
                                <div class="text-lg font-medium">
                                    {{ $shelfRental->customer->user->name }}
                                </div>
                                <div class="text-sm text-gray-400">
                                    {{ $shelfRental->customer->first_name ?? 'Vorname' }} {{ $shelfRental->customer->last_name ?? 'Nachname' }}
                                </div>
                            </div>                    
                        </div>
                </a>
            </div>

            <div>
                <h2 class="font-semibold">Regal:</h2>
                <p class="truncate">{{ $shelfRental->shelf->floor_number ?? 'Unbekannt' }}</p>
            </div>

            <div>
                <h2 class="font-semibold">Startdatum:</h2>
                <p>{{ \Carbon\Carbon::parse($shelfRental->rental_start)->format('d.m.Y') }}</p>
            </div>

            <div>
                <h2 class="font-semibold">Enddatum:</h2>
                <p>{{ \Carbon\Carbon::parse($shelfRental->rental_end)->format('d.m.Y') }}</p>
            </div>

            <div>
                <h2 class="font-semibold">Status:</h2>
                <p class="mb-4">
                    <x-shelve-rental-status :status="$shelfRental->status" />
                </p>
                @switch($shelfRental->status)
                                                @case(1)
                                                    @break

                                                @case(2)
                                                    @break

                                                @case(3)

                                                    @break

                                                @case(4)
                                                    @livewire('shelfrentalcomponents.payout-details', ['shelfRentalId' => $shelfRental->id])
                                                    @break

                                                @case(5)
                                                    @break

                                                @case(6)
                                                    @break

                                                @case(7)
                                                    @break

                                                @case(8)
                                                    <p class="text-yellow-500 text-sm font-medium flex">
                                                        ⏳ Auszahlung wird bearbeitet
                                                    </p>
                                                    @break

                                                @default
                                                    <p class="text-sm text-gray-500 font-medium flex">Unbekannter Status</p>
                                            @endswitch
            </div>
        </div>


        @if ($shelfRental->status == 1)
        <div x-data="{ confirmCancel: false }" class="flex flex-wrap items-center justify-end gap-4 mt-4">
            <!-- Dropdown-Button -->
             <div class="relative">
                 <button @click="confirmCancel = !confirmCancel"
                     class="inline-flex justify-center bg-gray-100 text-gray-700 text-sm border border-gray-300 px-4 py-2 rounded shadow-sm focus:outline-none"
                     aria-haspopup="true" aria-expanded="true">
                     Optionen
                     <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                         <path fill-rule="evenodd"
                             d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 01.02-1.06z"
                             clip-rule="evenodd" />
                     </svg>
                 </button>
     
                 <!-- Dropdown-Menü -->
                 <div x-show="confirmCancel" @click.away="confirmCancel = false" x-transition  x-cloak
                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                     <div class="">
                         <!-- Stornieren mit Bestätigung -->
                         <button @click="$dispatch('open-modal', { id: 'confirmCancelModal' })"
                             class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-700 w-full text-left">
                             Stornieren
                         </button>
                     </div>
                 </div>
     
                 <!-- Bestätigungs-Modal -->
                 <div x-data="{ open: false }" x-show="open" @open-modal.window="open = $event.detail.id === 'confirmCancelModal'"
                     @close-modal.window="open = false" x-cloak>
                     <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
                         <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                             <h2 class="text-lg font-bold mb-4">Bist du sicher?</h2>
                             <p class="text-gray-600 mb-4">Möchtest du die Regalmiete wirklich stornieren? Diese Aktion kann nicht rückgängig gemacht werden.</p>
                             <div class="flex justify-end space-x-4">
                                 <button @click="$dispatch('close-modal')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                     Abbrechen
                                 </button>
                                 <button wire:click="cancelRental" @click="$dispatch('close-modal')"
                                     class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                     Bestätigen
                                 </button>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
        </div>
        @endif
        <p class="text-sm text-gray-600">
                    <span class="font-medium">Gesamtumsatz:</span>
                    <span class="">{{ number_format($shelfRental->getRevenue(), 2, ',', '.') }} €</span>
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Einkünfte nach Provision:</span>
                    <span class="font-bold text-green-600">{{ number_format($shelfRental->getCustomerEarnings(), 2, ',', '.') }} €</span>
                </p>
    </div>
    <hr class="border-t border-gray-400  my-5">
    <h2 class="text-lg font-semibold  md:pr-2 text-gray-800 mb-2 mt-5">Produkte</h2>
                    <div class="flex rounded shadow border border-gray-200 w-fit">
                        <!-- Anzahl der Produkte -->
                        <div class=" bg-blue-100 text-blue-700 px-4 py-1 text-center ">
                            <p class="text-xs font-medium">Anzahl</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->count() }}</p>
                        </div>
                        <!-- Verkauft -->
                        <div class=" bg-green-100 text-green-700 px-4 py-1 text-center ">
                            <p class="text-xs font-medium">Verkauft</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 4)->count() }}</p>
                        </div>
                        <!-- Im Verkauf -->
                        <div class=" bg-yellow-100 text-yellow-700 px-4 py-1 text-center">
                            <p class="text-xs font-medium">Im Verkauf</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 2)->count() }}</p>
                        </div>
                        <!-- Entwürfe -->
                        <div class=" bg-gray-100 text-gray-700 px-4 py-1 text-center">
                            <p class="text-xs font-medium">Entwürfe</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 1)->count() }}</p>
                        </div>
                    </div>
    <form  wire:submit.prevent="()" class="mt-10"  
            x-data="{ 
                downloadUrl: @entangle('downloadUrl'), 
                download() { 
                    if (this.downloadUrl) { 
                        // Warte 500ms, bevor der Download ausgelöst wird
                        setTimeout(() => {
                            console.log('download');
                            const downloadLink = document.createElement('a'); 
                            downloadLink.href = this.downloadUrl; 
                            downloadLink.download = ''; 
                            document.body.appendChild(downloadLink); 
                            downloadLink.click(); 
                            document.body.removeChild(downloadLink); 
                            this.downloadUrl = null; // Zurücksetzen nach Download
                        }, 500); // Delay in Millisekunden
                    } 
                } 
            }"
            x-init="$watch('downloadUrl', () => download())"
        >
        <!-- Massenbearbeitungs Dropdown -->
        <div class="mt-4 flex flex-wrap space-x-3 ">
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <!-- Dropdown-Button -->
                    <div>
                        <button 
                            @click="open = !open" 
                            type="button" 
                            class="inline-flex justify-center bg-gray-100 text-gray-700 text-sm border border-gray-300 px-2 py-1 rounded"
                            id="menu-button" 
                            aria-expanded="true" 
                            aria-haspopup="true">
                            <svg class=" text-gray-700" width="23px" heigth="23px" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Dropdown-Menü -->
                    <div 
                        x-show="open" 
                        @click.away="open = false" 
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="opacity-0 scale-95" 
                        x-transition:enter-end="opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="opacity-100 scale-100" 
                        x-transition:leave-end="opacity-0 scale-95" 
                        class="origin-top-left absolute left-0 mt-2 w-max rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" 
                        x-cloak
                        aria-orientation="vertical" 
                        aria-labelledby="menu-button" 
                        tabindex="-1">
                        <div class="py-1" role="none">
                            <button 
                                type="button" 
                                wire:click="selectAll" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100 hover:text-green-700 w-full text-left" 
                                role="menuitem" 
                                tabindex="-1" 
                                id="menu-item-0">
                                Alle auswählen
                            </button>
                            <button 
                                type="button" 
                                wire:click="deselectAll" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-700 w-full text-left" 
                                role="menuitem" 
                                tabindex="-1" 
                                id="menu-item-1">
                                Keine auswählen
                            </button>
                        </div>
                    </div>
                </div>
            <select wire:model.live="action" class="bg-gray-100 text-sm border border-gray-300 px-2 py-1 rounded pr-8">
                <option value="">Aktion auswählen</option>
                <option value="printLabels">Labels drucken</option>
                <option value="activateProducts">Aktivieren</option>
                <option value="deactivateProducts">Deaktivieren</option>
                <option value="showMoveProductsModal">Umziehen</option>
                <option class="bg-red-100 text-red-700" value="deleteProducts">Löschen</option>
            </select>
            <!-- Button zum Ausführen der ausgewählten Aktion -->
            <button wire:click="executeAction" wire:loading.attr="disabled" class="bg-blue-500 text-sm border text-white px-2 py-1 rounded">Aktion ausführen</button>
        </div>
        <div class="divide-y divide-gray-200 mt-5 px-2 py-4 rounded bg-gray-100 border border-gray-300 shadow">
            @foreach($products as $product)
                <label for="checkbox{{ $product->id }}" class="grid grid-cols-12 gap-4 py-2 items-center text-sm hover:bg-white cursor-pointer px-2 py-1 rounded">
                    <!-- Checkbox für Auswahl -->
                    <div class="col-span-1 flex items-center">
                        <input id="checkbox{{ $product->id }}" type="checkbox" wire:model.live="selectedProducts" value="{{ $product->id }}" class="mr-2 cursor-pointer">
                    </div>
                    <!-- Produkt-ID -->
                    <div class="col-span-2">
                        <p># {{ $product->id }}</p>
                    </div>
                    <!-- Produktname -->
                    <div class="col-span-5">
                        <p class="truncate"><strong>{{ $product->name }}</strong></p>
                    </div>
                    <!-- Preis -->
                    <div class="col-span-2 text-right">
                        <p><strong>{{ $product->price }} €</strong></p>
                    </div>
                    <div class="col-span-2 flex flex-wrap justify-end">
                        <div class=" text-right">
                            @if($product->status == 4)
                                <span class="bg-green-100 text-green-700 px-2 py-1 text-xs font-medium rounded border border-gray-300">
                                    Verkauft
                                </span>
                            @elseif($product->status == 2)
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs font-medium rounded border border-gray-300">
                                    Im Verkauf
                                </span>
                            @elseif($product->status == 1)
                                <span class="bg-gray-200 text-gray-700 px-2 py-1 text-xs font-medium rounded border border-gray-300">
                                    Entwurf
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 text-xs font-medium rounded border border-gray-300">
                                    Unbekannt
                                </span>
                            @endif
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
        <!-- Massenbearbeitungs Dropdown -->
        <div class="mt-5 flex flex-wrap space-x-3 ">
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <!-- Dropdown-Button -->
                    <div>
                        <button 
                            @click="open = !open" 
                            type="button" 
                            class="inline-flex justify-center bg-gray-100 text-gray-700 text-sm border border-gray-300 px-2 py-1 rounded"
                            id="menu-button" 
                            aria-expanded="true" 
                            aria-haspopup="true">
                            <svg class=" text-gray-700" width="23px" heigth="23px" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"  fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Dropdown-Menü -->
                    <div 
                        x-show="open" 
                        @click.away="open = false" 
                        x-transition:enter="transition ease-out duration-100" 
                        x-transition:enter-start="opacity-0 scale-95" 
                        x-transition:enter-end="opacity-100 scale-100" 
                        x-transition:leave="transition ease-in duration-75" 
                        x-transition:leave-start="opacity-100 scale-100" 
                        x-transition:leave-end="opacity-0 scale-95" 
                        class="origin-top-left absolute left-0 mt-2 w-max rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" 
                        x-cloak
                        aria-orientation="vertical" 
                        aria-labelledby="menu-button" 
                        tabindex="-1">
                        <div class="py-1" role="none">
                            <button 
                                type="button" 
                                wire:click="selectAll" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-100 hover:text-green-700 w-full text-left" 
                                role="menuitem" 
                                tabindex="-1" 
                                id="menu-item-0">
                                Alle auswählen
                            </button>
                            <button 
                                type="button" 
                                wire:click="deselectAll" 
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-700 w-full text-left" 
                                role="menuitem" 
                                tabindex="-1" 
                                id="menu-item-1">
                                Keine auswählen
                            </button>
                        </div>
                    </div>
                </div> 
            <select wire:model.live="action" class="bg-gray-100 text-sm border border-gray-300 px-2 py-1 rounded pr-8">
                <option value="" selected>Aktion auswählen</option>
                <option value="printLabels">Labels drucken</option>
                <option value="activateProducts">Aktivieren</option>
                <option value="deactivateProducts">Deaktivieren</option>
                <option value="showMoveProductsModal">Umziehen</option>
                <option class="bg-red-100 text-red-700"  value="deleteProducts">Löschen</option>
            </select>
            <!-- Button zum Ausführen der ausgewählten Aktion -->
            <button wire:click="executeAction" wire:loading.attr="disabled"  class="bg-blue-500 text-sm border text-white px-2 py-1 rounded">Aktion ausführen</button>
        </div>
    </form>
    <x-dialog-modal wire:model="moveProductsModalOpen">
        <x-slot name="title">
            Produkte in eine andere Regalmiete umziehen
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-600 mb-4">Wähle eine Ziel-Regalmiete für die ausgewählten Produkte:</p>

            <div class="mt-2">
                <label for="targetRental" class="block text-sm font-medium text-gray-700">Ziel-Regalmiete</label>
                <select wire:model="selectedRental" id="targetRental"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Wähle eine Regalmiete --</option>
                    @foreach($availableRentals as $rental)
                        <option value="{{ $rental->id }}">{{ $rental->shelf->floor_number ?? 'Regal ' . $rental->id }} ({{ \Carbon\Carbon::parse($rental->rental_start)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($rental->rental_end)->format('d.m.Y') }})</option>
                    @endforeach
                </select>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <x-button wire:click="$set('moveProductsModalOpen', false)" class="btn-xs text-sm">
                   Abbrechen
                </x-button>
                <x-button wire:click="moveProducts" class="btn-xs text-sm">
                Produkte umziehen
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
