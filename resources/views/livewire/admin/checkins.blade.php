<div>
    <h1 class="text-2xl font-bold mb-4">Bevorstehende Check-Ins</h1>

    <!-- Tabellenkopf -->
    <div class="grid grid-cols-12  bg-gray-100 p-4 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1 truncate">#</div>
        <div class="col-span-6 truncate">Kunde</div>
        <div class="col-span-1 truncate">Regal</div>
        <div class="col-span-3 truncate">Startdatum</div>
        <div class="col-span-1 truncate">Status</div>
    </div>

    <!-- Tabelleninhalt -->
    <div class="divide-y divide-gray-200">
        @forelse ($checkIns as $checkIn)
            <a href="{{ route('admin.shelf-rental', $checkIn->id) }}" wire:navigate  class="grid grid-cols-12 p-4 items-center cursor-pointer hover:bg-gray-100">
                <!-- Buchungsnummer -->
                <div class="col-span-1 truncate">{{ $checkIn->id }}</div>

                <!-- Kunde -->
                <div class="col-span-6 truncate">{{ $checkIn->customer->user->name ?? 'Unbekannt' }}</div>

                <!-- Regal -->
                <div class="col-span-1 truncate">{{ $checkIn->shelf->floor_number ?? 'Unbekannt' }}</div>

                <!-- Startdatum -->
                <div class="col-span-3 truncate">{{ \Carbon\Carbon::parse($checkIn->rental_start)->format('d.m.Y') }}</div>

                <!-- Status -->
                <div class="col-span-1 truncate">
                    @switch($checkIn->status)
                        @case(1)
                            <span class="text-green-500 font-semibold">Bereit</span>
                            @break
                        @case(5)
                            <span class="text-blue-500 font-semibold">Eingecheckt</span>
                            @break
                        @default
                            <span class="text-gray-500">Unbekannt</span>
                    @endswitch
                </div>
            </a>
        @empty
            <div class="p-4 text-center text-gray-500">
                Keine bevorstehenden Check-Ins gefunden.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $checkIns->links('vendor.pagination.tailwind') }}
    </div>
</div>
