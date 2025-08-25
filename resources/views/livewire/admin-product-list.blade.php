<div x-data="{  search: @entangle('search') }"  wire:loading.class="cursor-wait">
    <h1 class="text-2xl font-semibold mb-4">Produktliste</h1>
    <div class="flex rounded shadow border border-gray-200 w-fit my-4">
        <!-- Anzahl der Produkte -->
        <div class=" bg-blue-100 text-blue-700 px-4 py-1 text-center ">
            <p class="text-xs font-medium">Anzahl</p>
            <p class="text-sm font-bold">{{ $allProducts->count() }}</p>
        </div>
        <!-- Verkauft -->
        <div class=" bg-green-100 text-green-700 px-4 py-1 text-center ">
            <p class="text-xs font-medium">Verkauft</p>
            <p class="text-sm font-bold">{{ $allProducts->where('status', 4)->count() }}</p>
        </div>
        <!-- Im Verkauf -->
        <div class=" bg-yellow-100 text-yellow-700 px-4 py-1 text-center">
            <p class="text-xs font-medium">Im Verkauf</p>
            <p class="text-sm font-bold">{{ $allProducts->where('status', 2)->count() }}</p>
        </div>
        <!-- Entwürfe -->
        <div class=" bg-gray-100 text-gray-700 px-4 py-1 text-center">
            <p class="text-xs font-medium">Entwürfe</p>
            <p class="text-sm font-bold">{{ $allProducts->where('status', 1)->count() }}</p>
        </div>
    </div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
        <div x-data="{ focused: false }" @click.away="focused = false" x-cloak class="relative mt-5">
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
    </div>
    <!-- Tabellenüberschrift -->
    <div class="grid grid-cols-12 bg-gray-100 p-2 font-semibold text-gray-700 border-b border-gray-300 mt-5">
        <div class="col-span-1 flex items-center">
            <button wire:click="sortByField('id')" class="text-left flex items-center">
                ID
                @if ($sortBy === 'id')
                    <span class="ml-2 text-xl">
                        <svg class="w-4 h-4 ml-2 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-6">
            <button wire:click="sortByField('name')" class="text-left flex items-center">
                Produktname
                @if ($sortBy === 'name')
                    <span class="ml-2 text-xl">
                        <svg class="w-4 h-4 ml-2 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-2">
            <button wire:click="sortByField('price')" class="text-left flex items-center">
                Preis
                @if ($sortBy === 'price')
                    <span class="ml-2 text-xl">
                        <svg class="w-4 h-4 ml-2 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-2">
            <button wire:click="sortByField('created_at')" class="text-left flex items-center">
                Erstellt am
                @if ($sortBy === 'created_at')
                    <span class="ml-2 text-xl">
                        <svg class="w-4 h-4 ml-2 transition-transform transform" style="transform: rotate({{ $sortDirection === 'asc' ? '0deg' : '180deg' }}); transition: transform 0.3s ease;" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7" />
                        </svg>
                    </span>
                @endif
            </button>
        </div>
        <div class="col-span-1"></div>
    </div>

    <!-- Produkte -->
    <div>
        @foreach ($products as $product)
            <div class="grid grid-cols-12 items-center p-2 border-b text-left">
                <div class="col-span-1">{{ $product->id }}</div>
                
                <div class="col-span-6 flex items-center space-x-4">                    
                    <img class="h-10 w-10 rounded-full aspect-square object-cover" src="{{ url($product->getImageUrl(0,'m')) }}" alt="{{ $product->name }}" loading="lazy">
                    <div class="text-sm font-medium flex justify-between w-full pr-3">
                        <span>{{ $product->name }}</span>
                        @if ($product->status == 4)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Verkauft</span>
                        @elseif ($product->status == 2)
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Im Verkauf</span>
                        @elseif ($product->status == 1)
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Entwurf</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Unbekannt</span>
                        @endif
                    </div>
                </div>
                <div class="col-span-2 admin-product-list"><x-product-price :product="$product" /></div>
                <div class="col-span-2">{{ $product->created_at->format('d.m.Y') }}</div>
                <div class="col-span-1 flex justify-end ">
                    <a href="{{ route('admin.shelf-rental', $product->shelf_rental_id) }}" wire:navigate  class="text-blue-500 btn-xs h-8 w-8" >
                    🔍
                        </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
