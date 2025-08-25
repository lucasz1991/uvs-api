<div class="pb-8 pt-3  md:py-12  antialiased bg-[#f8f2e8f2]"  wire:loading.class="cursor-wait">

    <div class="max-w-7xl mx-auto  px-5">
            
            <div class="bg-white shadow p-5 mb-6 rounded-lg">
            <div class="mb-4   flex items-center justify-between">
                     <x-back-button />
                    
                    <x-share-dropdown :shelfRental="$shelfRental" />
            </div>
            <div class="flex  justify-between">
                <!-- Linke Seite: Verkäufer- und Regaldetails -->
                <div class="w-2/3 pr-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Regaldetails</h2>
                    <p class="text-sm text-gray-600 mb-2">
                        Regalnummer: <span class=" bg-green-100 text-green-800 font-medium pr-2 pl-2 py-0.5 rounded border border-green-400 ">{{ $shelfRental->shelf->floor_number ?? '???' }}</span>

                    </p>
                    <p class="text-sm text-gray-600">   
                        Verfügbar bis:  
                        @if ($shelfRental->rental_end)
                            <span class="font-semibold text-green-600">
                                {{ \Carbon\Carbon::parse($shelfRental->rental_end)->format('d.m.Y') }} um 16:00 Uhr
                            </span>
                        @else
                            <span class="text-gray-500">Keine Angaben</span>
                        @endif
                    </p>
                    <h4 class="text-m font-bold text-gray-700 mt-6 mb-2">Verkäufer</h4>
                    <x-seller-info  :shelfRental="$shelfRental" :isFollowing="$isFollowing" />
                </div>

                <!-- Rechte Seite: Verkaufsfläche -->
                <div class="w-1/3 flex justify-end">
                    <!-- Lageplan mit Lightbox -->
                    <x-lightbox-svg :retailSpace="$retailSpace" :shelfRental="$shelfRental" />
                </div>
            </div>
        </div>


            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($products as $product)
                    <x-productlist-item :product="$product" :shelfRental="$shelfRental" />
                @empty
                <div class=" p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg border-l-4 border-blue-500" role="alert">
                    <strong class="font-semibold">Hinweis:</strong><br> Keine Produkte gefunden.
                </div>
                @endforelse
            </div>

    </div>
</div>
