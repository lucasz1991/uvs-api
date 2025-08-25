<div class="pb-8 pt-3  md:py-12  antialiased bg-[#f8f2e8f2]"  wire:loading.class="cursor-wait">

    <div class="max-w-7xl mx-auto  px-5">

        <div class="px-4 mx-auto 2xl:px-0 bg-white shadow py-2 pb-4">
            <div class="mb-4  px-4 sm:px-6 md:px-4 flex items-center justify-between">
                <x-back-button />

                    <!-- Aktionen -->
                    <div class="flex space-x-4">
                        <!-- Wishlist Icon -->
                        <div  
                            @auth 
                                wire:click="toggleLikedProduct({{ $product->id }})"
                            @else 
                            @click.prevent="Livewire.dispatch('redirectLoginWishlist')"
                            @endauth
                            x-data="{ isClicked: false }" 
                            :class="{
                                'bg-gray-100 hover:bg-red-100': !{{ auth()->check() && auth()->user()->likedProducts->contains($product->id) ? 'true' : 'false' }},
                                'bg-red-400': {{ auth()->check() && auth()->user()->likedProducts->contains($product->id) ? 'true' : 'false' }}
                            }"
                            class="w-10 h-10 flex items-center justify-center {{ auth()->check() && auth()->user()->likedProducts->contains($product->id) ? 'border-red-300' : 'border-gray-300' }}  shadow border rounded-full cursor-pointer  transition-all duration-100 transform z-60"
                            @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                            style="transform:scale(1);"
                            :style="isClicked ? 'transform:scale(0.7);' : ''"
                        >
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="22px" height="22px" class="transition-colors duration-300  {{ auth()->check() && auth()->user()->likedProducts->contains($product->id) ? 'fill-white' : 'fill-red-400' }} hover:fill-red-800" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" fill="{{ auth()->check() && auth()->user()->likedProducts->contains($product->id) ? '#ffffff' : '#a8a7a7' }}" stroke-linejoin="round" stroke-width="3" d="M12.01 6.001C6.5 1 1 8 5.782 13.001L12.011 20l6.23-7C23 8 17.5 1 12.01 6.002Z"></path>
                                </svg>
                                
                            </div>
                        </div>
                            <x-share-dropdown :product="$product" />
                    </div>
            </div>
            <div class="md:grid md:grid-cols-2 md:gap-8 xl:gap-16  px-4 sm:px-6 md:px-4">
                
                <div class="shrink-0 max-w-md md:max-w-lg ">
                    <div class="w-full aspect-square bg-gray-100 shadow  rounded-lg overflow-hidden" >
                        <!-- Hauptbild -->
                        <img 
                            id="mainImage" 
                            class="w-full h-full object-cover" 
                            src="{{ $product->getImageUrl(0, 'l') }}" 
                            alt="Hauptbild"
                        />
                    </div>

                    @if(is_array(json_decode($product->images)) && count(json_decode($product->images)) > 1)
                        <div class="mt-4 flex gap-2 overflow-x-auto">
                            @foreach (json_decode($product->images) as $index => $image)
                                @php
                                    $thumbnailImage = url($product->getImageUrl($index, 's'));
                                    $mainImage = url($product->getImageUrl($index, 'l'));
                                @endphp

                                <img 
                                    onclick="setMainImage('{{ $mainImage }}', this)"
                                    class="preview-product-img w-16 h-16 border-2 border-gray-300 cursor-pointer rounded-lg transition-all duration-200"
                                    src="{{ $thumbnailImage }}" 
                                    alt="Vorschaubild"
                                />
                            @endforeach
                        </div>
                    @endif

                    
                    <!-- JavaScript -->
                    <script>
                        /**
                         * Setzt das Hauptbild und hebt das ausgewählte Vorschaubild hervor.
                         * @param {string} imageUrl - Die URL des Bildes, das als Hauptbild angezeigt werden soll.
                         * @param {HTMLElement} clickedElement - Das Vorschaubild, das geklickt wurde.
                         */
                        function setMainImage(imageUrl, clickedElement) {
                            // Hauptbild setzen
                            const mainImage = document.getElementById('mainImage');
                            mainImage.src = imageUrl;

                            // Aktives Vorschaubild hervorheben
                            const thumbnails = document.querySelectorAll('.preview-product-img');
                            thumbnails.forEach(thumbnail => {
                                thumbnail.classList.remove('border-blue-500');
                                thumbnail.classList.add('border-gray-300');
                            });

                            clickedElement.classList.remove('border-gray-300');
                            clickedElement.classList.add('border-blue-500');
                        }
                    </script>
                </div>
    
                <!-- Produktdetails -->
                <div class="mt-6 sm:mt-8 md:mt-0">
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">
                        {{ $product->name }}
                    </h1>
                    <!-- Flexbox für Kategorie und Größe -->
                    <div class="flex flex-wrap gap-2 mt-2">
                                            @if(!empty($product->category))
                                                <span class="text-md bg-gray-100 text-gray-800 font-medium px-2 py-0.5 rounded-full border border-gray-300">
                                                    {{ $product->category }}
                                                </span>
                                            @endif
                                            @if(!empty($product->size))
                                                <span class="text-md bg-gray-100 text-gray-800 font-medium px-2 py-0.5 rounded-full border border-gray-300">
                                                    Gr.: {{ $product->size }}
                                                </span>
                                            @endif
                                        </div>
                    <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                        
                            @if ($product->discount > 0)
                                <p class="text-xl text-gray-500 line-through">
                                    € {{ number_format($product->price, 2, ',', '.') }}
                                </p>
                                <h4 class="text-2xl text-red-500 font-bold">
                                    € {{ number_format($product->discount_price, 2, ',', '.') }}
                                    
                                </h4>
                            @else
                            <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                                   {{ number_format($product->price, 2, ',', ''); }}  €
                            </p>
                            @endif
                        <div class="flex items-center gap-2 mt-2 sm:mt-0">
                            <!-- Aufrufe -->
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 7c-5 0-8-4-8-7s3-7 8-7 8 4 8 7-3 7-8 7z" />
                                </svg>
                                <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">{{ $product->views }} Aufrufe</p>
                            </div>
                        </div>
                    </div>
    
                    <!-- Regalnummer und Verkäufer -->
                    <div class="mt-4">
                            <div class="flex items-center gap-2 mb-3">

                                <strong class="text-sm text-gray-600 ">Regal:</strong>
                                    <div x-data="{ open: false }" class="relative inline-block  ml-1">
                                        <!-- Button -->
                                        <button 
                                        @click.stop.prevent="open = !open" 
                                            class="transition-all duration-100 rounded-md shadow focus:ring-2 focus:ring-green-500"
                                            x-data="{ isClicked: false }" 
                                            @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                                            style="transform:scale(1);"
                                            :style="isClicked ? 'transform:scale(0.9);' : ''">
                                            <div class="flex items-center w-max rounded bg-gray-100 rounded-md overflow-hidden border border-gray-400">

                                                    <p class="text-sm px-2 py-0.5 bg-green-100  mr-2  border-r border-green-400">
                                                        
                                                        <span class="text-green-800  font-medium "> {{ $product->shelfRental->shelf->floor_number ?? '???' }}</span>
                                                    </p>
                                                    <p class="text-xs tracking-tighter text-gray-600 decoration-indigo-500 pr-2">
                                                        @if ($product->shelfRental && $product->shelfRental->rental_end)
                                                            @php
                                                                $rentalEnd = \Carbon\Carbon::parse($product->shelfRental->rental_end)->setTime(16, 0); // Mietende auf 16:00 Uhr setzen
                                                                $now = \Carbon\Carbon::now();
                    
                                                                $remainingDays = $now->diffInDays($rentalEnd, false);
                                                                $remainingHours = $now->diffInHours($rentalEnd, false); // Gesamte verbleibende Stunden
                                                            @endphp
                    
                                                            @if ($remainingDays > 0)
                                                                <span>
                                                                    Noch {{ $remainingDays }} Tag(e)
                                                                </span>
                                                            @elseif ($remainingDays === 0 && $remainingHours > 0)
                                                                <span class="text-red-600">
                                                                    Noch {{ $remainingHours }} Stunde(n)
                                                                </span>
                                                            @else
                                                                <span class="text-red-600">Nicht mehr verfügbar</span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-500">Keine Angaben</span>
                                                        @endif
                                                    </p>
                                                </div>
                                        </button>
    
                                        <!-- Dropdown Menu -->
                                        <div 
                                            x-show="open" 
                                            x-cloak
                                            @click.away="open = false" 
                                            class="absolute mt-2 bg-white border rounded shadow-lg w-auto z-10"
                                            x-transition>
                                            <a href="{{ route('shelf.show', $product->shelfRental->id) }}"  wire:navigate  class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Regal anzeigen</a>
                                        </div>
                                    </div>
                            </div>
                    </div>
                    @if(auth()->check())
                        <h4 class="text-m font-bold text-gray-700 mt-6 mb-2">Verkäufer</h4>
                        <x-seller-info  :shelfRental="$product->shelfRental" :isFollowing="$isFollowing" />
                    @endif
                    <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />
                    <!-- Produktbeschreibung -->
                    <p class="mb-6 text-gray-500 dark:text-gray-400">{{ $product->description }}</p>
                </div>
            </div>
        </div>

               <!-- Weitere Produkte aus dem Regal -->
               @if ($shelfProducts->isNotEmpty())
                   <div class="mt-16">
                       <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                           Weitere Produkte aus diesem Regal
                       </h2>
                       <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                           @foreach ($shelfProducts as $product)
                                <x-productlist-item :product="$product"  />                            
                            @endforeach
                       </div>
                   </div>
               @endif
               <!-- Ähnliche Produkte -->
               @if ($similarProducts->isNotEmpty())
                   <div class="mt-16">
                       <h2 class="text-2xl font-semibold text-gray-900  mb-4">
                           Ähnliche Produkte
                       </h2>
                       <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                           @foreach ($similarProducts as $product)
                             <x-productlist-item :product="$product"  />                               
                            @endforeach
                       </div>
                   </div>
               @endif
    </div>
</div>
