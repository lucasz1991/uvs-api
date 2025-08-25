<div>
    <h1 class="text-2xl font-bold mb-4">Verkäufe</h1>

    <!-- Tabellenkopf -->
    <div class="grid grid-cols-12 bg-gray-100 p-4 font-semibold text-gray-700 border-b border-gray-300">
        <div class="col-span-1 truncate">#</div>
        <div class="col-span-3 truncate">Produkt</div>
        <div class="col-span-3 truncate">Kunde</div>
        <div class="col-span-2 truncate">Verkaufsdatum</div>
        <div class="col-span-2 truncate">€</div>
        <div class="col-span-1 truncate">Details</div>
    </div>

    <!-- Tabelleninhalt -->
    <div class="divide-y divide-gray-200">
        @forelse ($sales as $sale)
            <div class="grid grid-cols-12 p-4 items-center">
                <!-- Verkaufs-ID -->
                <div class="col-span-1 truncate">{{ $sale->id }}</div>

                <!-- Produktname -->
                <div class="col-span-3 truncate  flex items-center space-x-4">
                    <img class="h-10 w-10 rounded-full aspect-square object-cover" src="{{ url($sale->product->getImageUrl(0,'m')) }}"  loading="lazy">
                    <span>{{ $sale->product->name ?? 'Unbekannt' }}</span>
                </div>

                <!-- Kunde -->
                <div class="col-span-3 truncate">{{ $sale->customer->name ?? 'Unbekannt' }}</div>

                <!-- Verkaufsdatum -->
                <div class="col-span-2 truncate">{{ \Carbon\Carbon::parse($sale->created_at)->format('d.m.Y H:i') }}</div>

                <!-- Verkaufspreis -->
                <div class="col-span-2 truncate">{{ number_format($sale->sale_price, 2, ',', '.') }} €</div>

                <!-- Details -->
                <div class="col-span-1 truncate">
                    <a href="{{ route('admin.shelf-rental', $sale->product->shelf_rental_id) }}"  wire:navigate  disabled class="text-blue-500 hover:underline">Ansehen</a>
                </div>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                Keine Verkäufe gefunden.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
