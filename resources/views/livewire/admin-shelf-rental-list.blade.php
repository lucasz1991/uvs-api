<div x-data="{ search: @entangle('search') }" wire:loading.class="cursor-wait">
    <h1 class="text-2xl font-bold mb-4">Regalbuchungen verwalten</h1>
    <p class="mb-4">Gesamtanzahl Regalbuchungen: <strong>{{ $shelfRentalsCount }}</strong></p>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <!-- Suchfeld -->
            <div x-data="{ focused: false }" @click.away="focused = false" x-cloak class="relative">
            <div class="flex items-center border border-gray-300 rounded-full  transition duration-300"
                :class="{
                    'w-[300px]': (focused || search.length > 0),
                    'w-[40px]': !(focused || search.length > 0)
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
    </div>
    <!-- Tabellenkopf -->
    <div class="grid grid-cols-12 bg-gray-100 p-4 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1">
            <button wire:click="sortByField('id')" class="flex items-center">
                #
                @if ($sortBy === 'id')
                    <span class="ml-2">
                        <svg class="w-4 h-4 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-3">
            <div class="flex items-center">
                Kunde
                @if ($sortBy === 'customer_name')
                    <span class="ml-2">
                        <svg class="w-4 h-4 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-span-3">
            <div class="flex items-center">
                Produkte
            </div>
        </div>
        <div class="col-span-1">
            <div class="flex items-center">
                Regal
                @if ($sortBy === 'shelf_number')
                    <span class="ml-2">
                        <svg class="w-4 h-4 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-span-3 flex justify-start content-center">
            <button wire:click="sortByField('rental_start')" class="flex items-center">
                Start
                @if ($sortBy === 'rental_start')
                    <span class="ml-2">
                        <svg class="w-4 h-4 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
            <span>&nbsp;-&nbsp;</span>
            <button wire:click="sortByField('rental_end')" class="flex items-center">
                Ende
                @if ($sortBy === 'rental_end')
                    <span class="ml-2">
                        <svg class="w-4 h-4 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-1 flex justify-end">
            <button wire:click="sortByField('status')" class="flex items-center">
                Status
                @if ($sortBy === 'status')
                    <span class="ml-2">
                        <svg class="w-4 h-4 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
    </div>

    <!-- Tabelleninhalt -->
    <div class="divide-y divide-gray-200">
        @forelse ($shelfRentals as $shelfRental)
            <div href="{{ route('admin.shelf-rental', $shelfRental->id) }}" wire:navigate  class="grid grid-cols-12 p-4 items-center cursor-pointer hover:bg-gray-100">
                <!-- Buchungsnummer -->
                <div class="col-span-1">{{ $shelfRental->id }}</div>

                <!-- Kunde -->
                <div class="col-span-3 font-bold pl-1 " >
                    <div class="flex items-center space-x-4">
                        <img     class="h-10 w-10 rounded-full object-cover transition-all duration-300" 
                        src="{{ $shelfRental->customer->user->profile_photo_url }}" alt="{{ $shelfRental->customer->user->name }}" />
                        <div>
                            <div class="text-sm font-medium">
                                {{ $shelfRental->customer->user->name }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $shelfRental->customer->first_name ?? 'Vorname' }} {{ $shelfRental->customer->last_name ?? 'Nachname' }}
                            </div>
                        </div>                    
                    </div>
                </div>
                <div class="col-span-3">
                    <div class="flex rounded shadow border border-gray-200 w-fit">
                        <!-- Anzahl der Produkte -->
                        <div class="min-w-12 bg-blue-100 text-blue-700 px-4 py-1 text-center" title="Anzahl der Produkte">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->count() }}</p>
                        </div>
                        <!-- Verkauft -->
                        <div class="min-w-12 bg-green-100 text-green-700 px-4 py-1 text-center" title="Verkaufte Produkte">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 4)->count() }}</p>
                        </div>
                        <!-- Im Verkauf -->
                        <div class="min-w-12 bg-yellow-100 text-yellow-700 px-4 py-1 text-center" title="Produkte im Verkauf">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 2)->count() }}</p>
                        </div>
                        <!-- Entwürfe -->
                        <div class="min-w-12 bg-gray-100 text-gray-700 px-4 py-1 text-center" title="Entwurfsprodukte">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 1)->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Regal -->
                <div class="col-span-1">{{ $shelfRental->shelf->floor_number ?? 'Unbekannt' }}</div>

                <!-- Zeitraum -->
                <div class="col-span-3">{{ \Carbon\Carbon::parse($shelfRental->rental_start)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($shelfRental->rental_end)->format('d.m.Y') }}</div>
                <!-- Status -->
                <div class="col-span-1 flex justify-end">
                    <x-shelve-rental-status :status="$shelfRental->status" />
                </div>

            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                Keine bevorstehenden Regalbuchungen gefunden.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $shelfRentals->links() }}
    </div>
</div>
