<div>
    <div class=" bg-white rounded-lg  border border-gray-300 h-full shadow-md overflow-hidden">
        <div class="p-4 font-semibold text-gray-700 flex justify-between items-center bg-sky-50/30">
            <h3 class="flex items-center justify-center text-lg">
                Check-In's
                <span class="ml-2 bg-white text-sky-600 text-xs shadow border border-sky-200 font-bold aspect-square px-2 py-1 flex items-center justify-center rounded-full h-auto leading-none">
                    {{ $checkIns->count() }}
                </span>
            </h3>

            <!-- Auswahlfeld für den Zeitraum -->
            <select wire:model.live="filterDate" class="border border-sky-200 text-xs rounded-lg py-1 px-2 bg-white text-gray-700 focus:ring focus:ring-blue-300">
                <option value="today">Heute</option>
                <option value="yesterday">Gestern</option>
                <option value="tomorrow">Morgen</option>
                <option value="week">Ganze Woche</option>
            </select>
        </div>

        <div class="grid grid-cols-12  p-4  pt-1 font-semibold text-gray-700  border-b border-sky-200 bg-sky-50/30">
            <div class="col-span-1 truncate">#</div>
            <div class="col-span-7 truncate">Kunde</div>
            <div class="col-span-2 truncate">Regal</div>
            <div class="col-span-2 truncate text-right pr-2">Status</div>
        </div>
        <div class="overflow-y-auto max-h-80 divide-y bg-white divide-gray-200 scroll-container">
            @forelse ($checkIns as $checkIn)
                <a href="{{ route('admin.shelf-rental', $checkIn->id) }}" class="grid grid-cols-12 p-4 items-center cursor-pointer hover:bg-blue-50 odd:bg-white  even:bg-gray-50">
                    <div class="col-span-1 truncate">{{ $checkIn->id }}</div>
                    <div class="col-span-7 truncate">{{ $checkIn->customer->user->name ?? 'Unbekannt' }}</div>
                    <div class="col-span-2 truncate">{{ $checkIn->shelf->floor_number ?? 'Unbekannt' }}</div>
                    <div class="col-span-2   flex justify-end">
                        <x-shelve-rental-status :status="$checkIn->status" />
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-gray-500">
                    Keine Check-Ins für heute gefunden.
                </div>
            @endforelse
        </div>
    </div>
</div>
