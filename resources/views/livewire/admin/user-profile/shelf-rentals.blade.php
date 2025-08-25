<div  wire:loading.class="cursor-wait" class="border border-gray-300 rounded-lg">
    <div class="grid grid-cols-12 bg-gray-100 p-4 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1">
            <span>
                #
            </span>
        </div>
        <div class="col-span-6">
            <span>
                Produkte
            </span>
        </div>
        <div class="col-span-1">
            <span>
                Regal
            </span>
        </div>
        <div class="col-span-3">
            <span>
                Startdatum
            </span>
        </div>
        <div class="col-span-1">
            <span>
                Status
            </span>
        </div>
    </div>

    <!-- Tabelleninhalt -->
    <div class="divide-y divide-gray-200">
        @forelse ($shelfRentals as $shelfRental)
            <div href="{{ route('admin.shelf-rental', $shelfRental->id) }}" wire:navigate  class="grid grid-cols-12 px-4 py-2 items-center cursor-pointer bg-white hover:bg-blue-100">
                <!-- Buchungsnummer -->
                <div class="col-span-1">{{ $shelfRental->id }}</div>

                <!-- Kunde -->
                <div class="col-span-6">
                    <div class="flex rounded shadow border border-gray-200 w-fit">
                        <!-- Anzahl der Produkte -->
                        <div class="bg-blue-100 text-blue-700 px-4 py-1 text-center" title="Anzahl der Produkte">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->count() }}</p>
                        </div>
                        <!-- Verkauft -->
                        <div class="bg-green-100 text-green-700 px-4 py-1 text-center" title="Verkaufte Produkte">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 4)->count() }}</p>
                        </div>
                        <!-- Im Verkauf -->
                        <div class="bg-yellow-100 text-yellow-700 px-4 py-1 text-center" title="Produkte im Verkauf">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 2)->count() }}</p>
                        </div>
                        <!-- Entwürfe -->
                        <div class="bg-gray-100 text-gray-700 px-4 py-1 text-center" title="Entwurfsprodukte">
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 1)->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Regal -->
                <div class="col-span-1">{{ $shelfRental->shelf->floor_number ?? 'Unbekannt' }}</div>

                <!-- Startdatum -->
                <div class="col-span-3">{{ \Carbon\Carbon::parse($shelfRental->rental_start)->format('d.m.Y') }}</div>

               <!-- Status -->
                <div class="col-span-1 text-xs">
                    <x-shelve-rental-status :status="$shelfRental->status" />
                </div>

            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                Keine Regalbuchungen gefunden.
            </div>
        @endforelse
    </div>

</div>
