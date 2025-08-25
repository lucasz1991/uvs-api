<nav  x-data="{ 
    focused: false, 
    isMobileMenuOpen: false, 
    screenWidth: window.innerWidth, 
    navHeight: 0 
}" 
x-init="navHeight = $el.offsetHeight" 
x-resize="screenWidth = $width; " 
class="fixed max-h-24 top-0 w-screen bg-white border-b border-gray-100 shadow-lg px-4  lg:px-8 z-40"  
wire:loading.class="cursor-wait"
>
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center">
            <div class="shrink-0 flex items-center h-full py-2" >
                <a href="/" wire:navigate   class="h-full flex items-center max-sm:max-w-[120px]">
                    <x-application-mark />
                </a>
            </div>
            <div class="flex items-center space-x-4  md:order-2" >
                @if ($currentUrl !== url('/products') && false)
                    <!-- Search Bar -->
                    <div x-data="{ focused: false }" @click.away="focused = false" x-cloak class="relative">
                        <form action="/search" method="GET"
                            class="flex items-center border border-gray-300 rounded-full overflow-hidden transition-all duration-300"
                            :class="focused ? 'w-[300px] border-gray-500' : 'w-[40px] border-gray-300'">
                            <input type="text" name="query" placeholder="Suchen..."
                                class="w-full px-2 py-1 text-sm focus:outline-none bg-transparent border-none outline-none"
                                x-ref="searchInput" @click="focused = true" :class="focused ? 'block' : 'hidden'" />
                            <button type="button" @click="focused = true; $refs.searchInput.focus()"
                                class="flex items-center justify-center w-[40px] h-[40px] text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="h-4 w-4">
                                    <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endif
                <!-- Likes and Inbox Buttons -->
                <div class="flex items-center space-x-6 mr-2">
                    <!-- Likes Button -->
                    @if (optional(Auth::user())->role === 'guest' && $currentUrl !== url('/liked-products'))
                        <div class="" x-data="{ wishListOpen: false }">

                            <a  @click.prevent="wishListOpen = !wishListOpen"  @click.away="open = false"  class="block">
                                <span class="relative" style="top:-2px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23px" class="cursor-pointer fill-gray-800 inline" viewBox="0 0 512 512" stroke-width="1.5">
                                        <g id="Layer_20">
                                            <path d="M397.254,60.047C349.52,49.268,290.07,62.347,256,113.182c-34.07-50.835-93.52-63.9-141.254-53.135   
                                            C60.405,72.295,5.57,118.771,5.57,194.978c0,139.283,235.088,267.75,245.096,273.151c3.329,1.795,7.338,1.795,10.667,0   
                                            c10.008-5.401,245.096-133.867,245.096-273.151C506.43,118.771,451.595,72.295,397.254,60.047z 
                                            M256,445.364   
                                            C221.151,425.504,28.044,310.14,28.044,194.978c0-68.17,49.367-103.483,91.647-113.012c8.981-2.003,18.156-3.008,27.358-2.996   
                                            c42.374-1.183,81.322,23.172,98.809,61.787c2.671,5.602,9.377,7.978,14.979,5.307c2.325-1.109,4.199-2.982,5.307-5.307   
                                            c27.627-58.139,85.25-67.997,126.166-58.768c42.28,9.506,91.647,44.827,91.647,112.989C483.957,310.14,290.849,425.504,256,445.364   
                                            z"/>
                                        </g>
                                    </svg>
                                    <!-- Anzahl der geliketen Produkte anzeigen -->
                                    @if(Auth::check() && Auth::user()->likedProducts->count() > 0)
                                        <span class="absolute right-[-6px] -ml-1 top-[-5px] rounded-full bg-red-400 px-1.5 py-0.2 text-xs text-white">
                                            {{ Auth::user()->likedProducts->count() }}
                                        </span>
                                    @endif
                                </span>
                            </a>
                            <!-- sidebar wunschliste -->
                            <div class="">
                                    <div x-show="wishListOpen" 
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    @click="wishListOpen = false"
                                    x-cloak
                                    :style="navHeight > 0 ? 'top: ' + navHeight + 'px;' : ''"
                                    class="fixed  inset-0 bg-black bg-opacity-50 z-20"></div>
                                <div  
                                    class="bg-white min-w-80 w-80 md:min-w-96 md:w-96 max-w-full transition-transform transition-all ease-out duration-200  absolute right-0  z-30"
                                    :class="wishListOpen ? 'translate-x-0' : 'translate-x-full'"
                                    :style="{ top: navHeight > 0 ? navHeight + 'px' : '', height: 'calc(100vh - ' + navHeight + 'px)' }"
                                    x-cloak
                                    >
                                    
                                    <div class="p-4   flex items-center justify-between">
                                        <div class="flex items-center">
                                            <h3>Deine Wunschliste</h3>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" class="ml-2 aspect-square  transition-colors duration-300 fill-red-800 text-red-800" viewBox="0 0 64 64">
                                                <path d="M45.5 4A18.53 18.53 0 0 0 32 9.86 18.5 18.5 0 0 0 0 22.5C0 40.92 29.71 59 31 59.71a2 2 0 0 0 2.06 0C34.29 59 64 40.92 64 22.5A18.52 18.52 0 0 0 45.5 4ZM32 55.64C26.83 52.34 4 36.92 4 22.5a14.5 14.5 0 0 1 26.36-8.33 2 2 0 0 0 3.27 0A14.5 14.5 0 0 1 60 22.5c0 14.41-22.83 29.83-28 33.14Z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex space-x-4">
                        

                                        <div x-data="{ open: false }" class="relative">
                                            <!-- Button zum Öffnen des Dropdowns -->
                                            <button @click="open = !open"
                                                    class="flex items-center justify-center p-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-100">
                                                <svg class="w-5 aspect-square" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M49.54,44.34a7.81327,7.81327,0,0,0-6.59,3.61L21.69,35.67a7.797,7.797,0,0,0-.22-6.48l-.0011-.002L43.3233,16.57391A7.81769,7.81769,0,1,0,41.71,11.83a7.56556,7.56556,0,0,0,.61,3.01l.00128.00268L20.35907,27.519A7.837,7.837,0,1,0,20.69,37.4L42.09,49.76a7.69578,7.69578,0,0,0-.38,2.41,7.83,7.83,0,1,0,7.83-7.83Z"/>
                                                </svg>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div x-show="open" @click.away="open = false" class="absolute right-4 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                                <ul>
                                                    @php
                                                     $wishlistUrl = route('wishlist.show', Auth::user()->id);
                                                    @endphp
                                                    <!-- WhatsApp Share -->
                                                    <li>
                                                    <a href="https://api.whatsapp.com/send?text={{ urlencode('Meine Wunschliste - Minifinds: ' . $wishlistUrl) }}" 
                                                    target="_blank"
                                                        class="flex items-center  px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <svg class="w-5 h-5 mr-1 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path fill="currentColor" stroke-width="1.5" fill-rule="evenodd" d="M12 4a8 8 0 0 0-6.895 12.06l.569.718-.697 2.359 2.32-.648.379.243A8 8 0 1 0 12 4ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 0 1-5.016-1.347l-4.948 1.382 1.426-4.829-.006-.007-.033-.055A9.958 9.958 0 0 1 2 12Z" clip-rule="evenodd"/>
                                                            <path fill="currentColor" stroke-width="1.5" d="M16.735 13.492c-.038-.018-1.497-.736-1.756-.83a1.008 1.008 0 0 0-.34-.075c-.196 0-.362.098-.49.291-.146.217-.587.732-.723.886-.018.02-.042.045-.057.045-.013 0-.239-.093-.307-.123-1.564-.68-2.751-2.313-2.914-2.589-.023-.04-.024-.057-.024-.057.005-.021.058-.074.085-.101.08-.079.166-.182.249-.283l.117-.14c.121-.14.175-.25.237-.375l.033-.066a.68.68 0 0 0-.02-.64c-.034-.069-.65-1.555-.715-1.711-.158-.377-.366-.552-.655-.552-.027 0 0 0-.112.005-.137.005-.883.104-1.213.311-.35.22-.94.924-.94 2.16 0 1.112.705 2.162 1.008 2.561l.041.06c1.161 1.695 2.608 2.951 4.074 3.537 1.412.564 2.081.63 2.461.63.16 0 .288-.013.4-.024l.072-.007c.488-.043 1.56-.599 1.804-1.276.192-.534.243-1.117.115-1.329-.088-.144-.239-.216-.43-.308Z"/>
                                                            </svg>
                                                            WhatsApp
                                                        </a>
                                                    </li>
                                                    <!-- Instagram Share -->
                                                    <li>
                                                    <a href="javascript:void(0)" onclick="copyToClipboard('{{ $wishlistUrl }}')" 
                                                    target="_blank"
                                                        class="flex items-center  px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <svg class="w-5 h-5 mr-1 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path fill="currentColor" stroke-width="1.5" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Instagram
                                                        </a>
                                                    </li>
                                                    <!-- Facebook Share -->
                                                    <li>
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($wishlistUrl) }}" 
                                                    target="_blank"
                                                        class="flex items-center  px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <svg class="w-5 h-5 mr-1 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" stroke-width="1.5" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Facebook
                                                        </a>
                                                    </li>
                                                    <!-- Copy Link -->
                                                    <li>
                                                        <a href="javascript:void(0)" 
                                                        @click="copyToClipboard('{{ $wishlistUrl }}')"
                                                        class="flex items-center  px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg class="w-5 h-5 mr-1 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961"/>
                                                        </svg>

                                                            Link kopieren
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Script zum Kopieren des Links -->
                                        <script>
                                            function copyToClipboard(text) {
                                                navigator.clipboard.writeText(text).then(() => {
                                                    alert('Link wurde in die Zwischenablage kopiert!');
                                                });
                                            }
                                        </script>

                                            <button type="button" @click="wishListOpen = false" class="md:hidden flex items-center justify-center p-2  text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 aspect-square fill-[#333]  hover:fill-[#077bff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-200"></div>
                                    @if($likedProducts->isNotEmpty())
                                        <div class="p-4">
                                            <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 " role="alert">
                                                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                                </svg>
                                                <span class="sr-only">Info</span>
                                                <div>
                                                    <span class="font-medium">Deine Wunschliste enthält {{ $likedProducts->count() }} Produkt(e). 
                                                    Teile die Liste mit Freunden und Familie, damit sie dir eine Freude machen können! 🎁</span> 
                                                </div>
                                            </div>
                                        </div>
                                         <!-- "Alle ansehen"-Button -->
                                         <!--<div class="p-4">
                                            <a  href="{{ route('liked.products') }}"  wire:navigate 
                                                class="pointer-events-auto rounded-md px-4 py-2 text-center font-medium ring-1 shadow-xs ring-slate-700/10 hover:bg-slate-50 block">
                                                Komplette Wunschliste ansehen
                                            </a>
                                        </div>-->
                                        <div class="p-4">
                                            <div class="scroll-smooth scroll-container snap-mandatory h-96 overflow-y-auto overflow-x-hidden will-change-scroll snap-y touch-pan-y">

                                                @foreach($likedProducts as $likedProduct)
                                                <div class="snap-start h-24 snap-always grid grid-cols-12  gap-4  content-center border-b border-gray-200">
                                                    <!-- Produktbild -->
                                                    <div class="col-span-3 block w-full">
                                                        <img 
                                                            src="{{ url($likedProduct->getImageUrl(0,'m')) }}" 
                                                            alt="{{ $likedProduct->name }}" 
                                                            class="aspect-square w-full object-cover rounded-full" />
                                                    </div>
                                                    <!-- Produktdetails -->
                                                    <div class="col-span-7 grid content-center">
                                                        <div class="flex items-center mb-2 ">
        
                                                            <p class="text-xs  px-2 py-0.5 bg-green-100  rounded-md mr-2  border border-green-700">
                                                                
                                                                <span class="text-green-800  font-medium "> {{ $likedProduct->shelfRental->shelf->floor_number ?? '???' }}</span>
                                                            </p>
                                                            <p class="text-xs tracking-tighter text-gray-600 decoration-indigo-500 pr-2">
                                                                @if ($likedProduct->shelfRental && $likedProduct->shelfRental->rental_end)
                                                                    @php
                                                                        $rentalEnd = \Carbon\Carbon::parse($likedProduct->shelfRental->rental_end)->setTime(16, 0); // Mietende auf 16:00 Uhr setzen
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
                                                        
                                                        <div class="flex-none font-medium text-gray-900 truncate">{{ Str::limit(strip_tags($likedProduct->name), 30) }}</div>
                                                        
                                                    </div>
                                                    <div x-data="{ open: false }" class="col-span-2 relative">
                                                        <button @click="open = !open" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                                            <svg class="w-6 h-10 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M12 6h.01M12 12h.01M12 18h.01"/>
                                                            </svg>
        
                                                        </button>
                                                        <!-- Dropdown-Menü -->
                                                        <div x-show="open" @click.away="open = false" class="absolute right-full top-1/2 transform -translate-y-1/2 object-center shadow-lg bg-white  rounded-md ring-1 ring-black ring-opacity-5 z-50">
                                                            <div class="py-1">
                                                                <!-- Ansehen-Option -->
                                                                <a href="{{ route('product.show', $likedProduct->id) }}" wire:navigate  class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <svg class="w-5 aspect-square mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-width="1.5" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                                                    <path stroke="currentColor" stroke-width="1.5" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                                </svg>
                                                                    Ansehen
                                                                </a>
                                                                <div class="border-t border-gray-100"></div>
                                                                <a  wire:click="toggleLikedProduct({{ $likedProduct->id }})"  class="flex items-center block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                                <svg class="w-5 aspect-square mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                                                </svg>
        
                                                                    Entfernen
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                       
                                       
                                    @else
                                    <div class="p-4">
                                        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 " role="alert">
                                            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                            </svg>
                                            <span class="sr-only">Info</span>
                                            <div>
                                                <span class="font-medium">Deine Wunschliste enthält noch keine Produkte. 
                                                Stöbere weiter und füge welche hinzu – teile deine Liste mit Freunden und Familie, damit sie dir etwas Besonderes schenken können! 🎁</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
        
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (optional(Auth::user())->role === 'guest' && $currentUrl !== url('/messages'))
                   
                    <div class="relative" x-data="{ open: false, modalOpen: false, selectedMessage: null  }">
                        <!-- Button zum Öffnen des Popups -->
                        <button @click="open = !open" class="block">
                            <span class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="30px" class="fill-[#333] hover:fill-[#077bff] stroke-2 inline" viewBox="0 0 512 512" stroke-width="106">
                                                    <g>
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path d="M479.568,412.096H33.987c-15,0-27.209-12.209-27.209-27.209V130.003c0-15,12.209-27.209,27.209-27.209h445.581      
                                                                    c15,0,27.209,12.209,27.209,27.209v255C506.661,399.886,494.568,412.096,479.568,412.096z 
                                                                    M33.987,114.189      
                                                                    c-8.721,0-15.814,7.093-15.814,15.814v255c0,8.721,7.093,15.814,15.814,15.814h445.581c8.721,0,15.814-7.093,15.814-15.814v-255      
                                                                    c0-8.721-7.093-15.814-15.814-15.814C479.568,114.189,33.987,114.189,33.987,114.189z"/>
                                                                </g>
                                                                <g>
                                                                    <path d="M256.894,300.933c-5.93,0-11.86-1.977-16.744-5.93l-41.977-33.14L16.313,118.491c-2.442-1.977-2.907-5.581-0.93-8.023      
                                                                    c1.977-2.442,5.581-2.907,8.023-0.93l181.86,143.372l42.093,33.14c5.698,4.535,13.721,4.535,19.535,0l41.977-33.14      
                                                                    l181.628-143.372c2.442-1.977,6.047-1.512,8.023,0.93c1.977-2.442,1.512,6.047-0.93,8.023l-181.86,143.372l-41.977,33.14      
                                                                    C268.755,299.072,262.708,300.933,256.894,300.933z"/>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                @if($unreadMessagesCount >= 1)
                                    <span class="absolute right-[-9px] -ml-1 top-[-5px] rounded-full bg-red-400 px-1.5 py-0.2 text-xs text-white">
                                        {{ $unreadMessagesCount }}
                                    </span>
                                @endif
                            </span>
                        </button>

                        <!-- Popup -->
                        <div 
                            x-show="open" 
                            x-cloak
                            class="absolute md:p-4 right-0 md:mt-2 md:w-[24.5rem] max-md:fixed max-md:inset-0 max-md:w-full max-md:top-0 max-md:flex max-md:items-center max-md:justify-center max-md:bg-black max-md:bg-opacity-50 max-md:z-50"
                            x-transition>
                            <div @click.away="open = false" class="relative max-w-full max-md:pt-10 divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem]/5 text-slate-900 ring-1 shadow-xl shadow-black/5 ring-slate-700/10 z-50">
                                        <button type="button" @click="open = false; selectedMessage = null;" class="md:hidden absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                <!-- Nachrichtenliste -->
                                @forelse($receivedMessages as $message)
                                <div 
                                    @click="modalOpen = true; open = false; selectedMessage = { subject: '{{ $message->subject }}', body: '{!! addslashes($message->message) !!}', createdAt: '{{ $message->created_at->diffForHumans() }}' }; $wire.setMessageStatus({{ $message->id }}); " 
                                    class="flex items-center p-4 hover:bg-slate-50 cursor-pointer @if($message->status == 1) bg-blue-200 @endif">
                                    <div class="block h-10 w-10 size-4 flex-none rounded-full">
                                        <x-application-logo class="w-10" />
                                    </div>
                                    <div class="ml-4 flex-auto">
                                        
                                        <div class="font-medium">{{ $message->subject }}</div>
                                        <div class="mt-1 text-slate-700">
                                            {{ Str::limit(strip_tags($message->message), 40) }}
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-slate-700">
                                    Keine  Nachrichten
                                </div>
                                @endforelse
    
                                <!-- "Alle ansehen"-Button -->
                                <div class="p-4">
                                    <a href="{{ route('messages') }}" 
                                        class="pointer-events-auto rounded-md px-4 py-2 text-center font-medium ring-1 shadow-xs ring-slate-700/10 hover:bg-slate-50 block">
                                        Alle Nachrichten ansehen
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div 
                            x-show="modalOpen" 
                            x-cloak
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                            x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0">
                            <div @click.away="modalOpen = false"  class="bg-white w-[90%] max-w-md rounded-lg shadow-lg p-6 relative">
                                <div>
                                    <button type="button" @click="modalOpen = false; selectedMessage = null;" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <div>
                                        <div class="flex">
                                            <span class="inline-block  text-xs font-medium text-gray-700 mb-2 bg-green-100 px-2 py-1 rounded-full" x-text="selectedMessage?.createdAt"></span>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2" x-text="selectedMessage?.subject"></h3>
                                    <div class="my-6">
                                        <p class="text-gray-800" x-html="selectedMessage?.body"></p>
                                    </div>
                                    </div>
                                    <div class="flex justify-end mt-4">
                                        <button type="button" @click="modalOpen = false; isClicked = true; setTimeout(() => isClicked = false, 100)" 
                                        x-data="{ isClicked: false }" 
                                        :style="isClicked ? 'transform:scale(0.7);' : 'transform:scale(1);'"
                                        class="transition-all duration-100 py-2.5 px-5  text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Schließen</button>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                    @endif
                </div>



                @auth
                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Konto verwalten') }}
                                </div>
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                 <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                                 </svg>

                                    {{ __('Profil') }}
                                </x-dropdown-link>
                                
                                <div class="border-t border-gray-200"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>

                                        {{ __('Abmelden') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <!-- Guest Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5" viewBox="0 0 512 512">
                                        <path
                                        d="M337.711 241.3a16 16 0 0 0-11.461 3.988c-18.739 16.561-43.688 25.682-70.25 25.682s-51.511-9.121-70.25-25.683a16.007 16.007 0 0 0-11.461-3.988c-78.926 4.274-140.752 63.672-140.752 135.224v107.152C33.537 499.293 46.9 512 63.332 512h385.336c16.429 0 29.8-12.707 29.8-28.325V376.523c-.005-71.552-61.831-130.95-140.757-135.223zM446.463 480H65.537V376.523c0-52.739 45.359-96.888 104.351-102.8C193.75 292.63 224.055 302.97 256 302.97s62.25-10.34 86.112-29.245c58.992 5.91 104.351 50.059 104.351 102.8zM256 234.375a117.188 117.188 0 1 0-117.188-117.187A117.32 117.32 0 0 0 256 234.375zM256 32a85.188 85.188 0 1 1-85.188 85.188A85.284 85.284 0 0 1 256 32z"
                                        data-original="#000000"></path>
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link href="/login">
                                    <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14v3m4-6V7a3 3 0 1 1 6 0v4M5 11h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                                    </svg>

                                    {{ __('Anmelden') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-200"></div>
                                <x-dropdown-link href="/register">
                                    <svg class="w-5 h-5  mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                         <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5" d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z"/>
                                    </svg>
                                    {{ __('Registrieren') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
                
                <a class="inline-flex items-center p-2 ml-1  md:hidden focus:outline-none "   @click="isMobileMenuOpen = !isMobileMenuOpen;">
                    <div class=" z-50 text-gray-600 text-sm text-gray-500 rounded-lg hover:bg-gray-100  burger-container "  :class="isMobileMenuOpen ? 'is-open' : ''" >
                            <div class="burger-bar bar1"></div>
                            <div class="burger-bar bar2"></div>
                            <div class="burger-bar bar3"></div>
                    </div>
                    <span class="sr-only">Öffnen Hauptmenü</span>
                </a>

               
            </div>


            <!-- Navigation Links -->
             

                 <div x-show="isMobileMenuOpen || screenWidth >= 768" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 "
                    x-transition:leave-end="opacity-0"
                    x-data="{
                          isPadded:  (navHeight > 0 && screenWidth <= 768 ? true : false)              
                    }"
                    :style="isPadded ? 'top: ' + navHeight + 'px; height: calc(100vh - ' + navHeight + 'px);' : ''"
                    x-cloak   class="md:order-1 max-md:fixed  max-md:inset-0   max-md:block  max-md:bg-black max-md:bg-opacity-50 max-md:z-30" >
                    
                    <div @click.away="isMobileMenuOpen = false"  
                                    :class="isMobileMenuOpen ? 'max-md:translate-x-0' : 'max-md:translate-x-full'"
                                    :style="isPadded ? 'top: ' + navHeight + 'px; height: calc(100vh - ' + navHeight + 'px);' : ''"
                            x-cloak  class="grid  content-between transition-transform  ease-out duration-400  max-md:bg-white max-md:min-w-80 max-md:right-0  max-md:fixed max-md:overflow-y-auto max-md:py-5 max-md:px-3  max-md:border-r max-md:border-gray-200">
                        <div  class="md:space-x-8 max-md:block   max-md:space-y-4 md:-my-px md:mx-4 max-md:gap-3 md:flex  w-full  " >
                           <!-- Gäste-Spezifische Navigation -->
                           <x-nav-link href="/" wire:navigate  :active="request()->is('/')">
                                <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
                                </svg>

                               {{ __('Home') }}
                           </x-nav-link>
                          
                           <x-nav-link href="/products" wire:navigate :active="$currentUrl === url('/products')">
                            <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.583 8.445h.01M10.86 19.71l-6.573-6.63a.993.993 0 0 1 0-1.4l7.329-7.394A.98.98 0 0 1 12.31 4l5.734.007A1.968 1.968 0 0 1 20 5.983v5.5a.992.992 0 0 1-.316.727l-7.44 7.5a.974.974 0 0 1-1.384.001Z"/>
                            </svg>



                               {{ __('Produkte') }}
                           </x-nav-link>
                           <x-nav-link href="/booking" wire:navigate  :active="request()->is('booking')">
                            <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/>
                            </svg>

                               {{ __('Stand buchen') }}
                           </x-nav-link>
                            <div x-data="{ open: false }" @click.away="open = false"  class="relative md:px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 md:hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" >
                                    <div class="flex items-center cursor-pointer max-md:text-lg max-md:px-3" @click="open = !open">
                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                            {{ __('Über uns') }}
                                        <svg class="w-4 h-4 ml-2  transition-all ease-in duration-200" :class="open ? 'transform rotate-180' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7"/>
                                        </svg>

                                    </div>
                                    <div x-show="open" x-transition x-cloak class="md:bg-white md:right-0 mt-3  z-30"
                                                                            :class="screenWidth <= 768 ? 'relative' : 'absolute rounded-lg shadow w-44 z-10 overflow-hidden'">
                                            <ul class=" max-md:space-y-4 max-md:pt-4 text-sm text-gray-500 hover:text-gray-700" :class="screenWidth <= 768 ? '' : 'divide-y divide-gray-100'">
                                                <li>
                                                    <a href="/aboutus" wire:navigate class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 12c.263 0 .524-.06.767-.175a2 2 0 0 0 .65-.491c.186-.21.333-.46.433-.734.1-.274.15-.568.15-.864a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 12 9.736a2.4 2.4 0 0 0 .586 1.591c.375.422.884.659 1.414.659.53 0 1.04-.237 1.414-.659A2.4 2.4 0 0 0 16 9.736c0 .295.052.588.152.861s.248.521.434.73a2 2 0 0 0 .649.488 1.809 1.809 0 0 0 1.53 0 2.03 2.03 0 0 0 .65-.488c.185-.209.332-.457.433-.73.1-.273.152-.566.152-.861 0-.974-1.108-3.85-1.618-5.121A.983.983 0 0 0 17.466 4H6.456a.986.986 0 0 0-.93.645C5.045 5.962 4 8.905 4 9.736c.023.59.241 1.148.611 1.567.37.418.865.667 1.389.697Zm0 0c.328 0 .651-.091.94-.266A2.1 2.1 0 0 0 7.66 11h.681a2.1 2.1 0 0 0 .718.734c.29.175.613.266.942.266.328 0 .651-.091.94-.266.29-.174.537-.427.719-.734h.681a2.1 2.1 0 0 0 .719.734c.289.175.612.266.94.266.329 0 .652-.091.942-.266.29-.174.536-.427.718-.734h.681c.183.307.43.56.719.734.29.174.613.266.941.266a1.819 1.819 0 0 0 1.06-.351M6 12a1.766 1.766 0 0 1-1.163-.476M5 12v7a1 1 0 0 0 1 1h2v-5h3v5h7a1 1 0 0 0 1-1v-7m-5 3v2h2v-2h-2Z"/>
                                                        </svg>
                                                        Unternehmen
                                                    </a>
                                                </li>
                                                <li >
                                                    <a  href="/faqs" wire:navigate  class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.529 9.988a2.502 2.502 0 1 1 5 .191A2.441 2.441 0 0 1 12 12.582V14m-.01 3.008H12M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>

                                                        
                                                    FAQ's
                                                    </a>
                                                </li>
                                                <li >
                                                    <a  href="/howto" wire:navigate class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>


                                                    So funktionierts
                                                    </a>
                                                </li>
                                                <li >
                                                    <a  href="/prices" wire:navigate class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                    <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.891 15.107 15.11 8.89m-5.183-.52h.01m3.089 7.254h.01M14.08 3.902a2.849 2.849 0 0 0 2.176.902 2.845 2.845 0 0 1 2.94 2.94 2.849 2.849 0 0 0 .901 2.176 2.847 2.847 0 0 1 0 4.16 2.848 2.848 0 0 0-.901 2.175 2.843 2.843 0 0 1-2.94 2.94 2.848 2.848 0 0 0-2.176.902 2.847 2.847 0 0 1-4.16 0 2.85 2.85 0 0 0-2.176-.902 2.845 2.845 0 0 1-2.94-2.94 2.848 2.848 0 0 0-.901-2.176 2.848 2.848 0 0 1 0-4.16 2.849 2.849 0 0 0 .901-2.176 2.845 2.845 0 0 1 2.941-2.94 2.849 2.849 0 0 0 2.176-.901 2.847 2.847 0 0 1 4.159 0Z"/>
                                                    </svg>



                                                    Preise
                                                    </a>
                                                </li>
                                                <!--<li >
                                                    <a  href="/jobs" wire:navigate  class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                           <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                                                        </svg>
 
                                                    Karriere
                                                    </a>
                                                </li>-->
                                                <li >
                                                    <a  href="/contact" wire:navigate  class='max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100'>
                                                        <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                                        </svg>

                                                    Kontakt
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                            </div>
                           @auth
                           <!-- Kunden-Spezifische Navigation -->
                           @if (optional(Auth::user())->role === 'guest')
                               <x-nav-link href="/dashboard" wire:navigate  :active="request()->is('dashboard')">
                                <svg class="w-5 max-md:w-6 aspect-square mr-1 max-md:mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                                    </svg>
                                   {{ __('Mein Konto') }}
                               </x-nav-link>
                           @endif   
                           @endauth
                        </div>
                        <div class="md:hidden max-md:flex self-end  bottom-0 left-0 justify-center p-4 space-x-4 w-full bg-white  z-20 border-t border-gray-200">
                            <ul class="mt-10 flex space-x-5">
                                <li>
                                <a href='https://www.facebook.com/share/187uXEMmkP/?mibextid=LQQJ4d' target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="fill-gray-300 hover:fill-gray-500 w-10 h-10"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7v-7h-2v-3h2V8.5A3.5 3.5 0 0 1 15.5 5H18v3h-2a1 1 0 0 0-1 1v2h3v3h-3v7h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"
                                        clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Facebook Link</span>
                                </a>
                                </li>
                                <li>
                                <a href='https://www.instagram.com/minifinds.de/profilecard/?igsh=bzd1eW5ybm82bmo5' target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    class="fill-gray-300 hover:fill-gray-500 w-10 h-10" viewBox="0 0 24 24">
                                    <path
                                        d="M12 9.3a2.7 2.7 0 1 0 0 5.4 2.7 2.7 0 0 0 0-5.4Zm0-1.8a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Zm5.85-.225a1.125 1.125 0 1 1-2.25 0 1.125 1.125 0 0 1 2.25 0ZM12 4.8c-2.227 0-2.59.006-3.626.052-.706.034-1.18.128-1.618.299a2.59 2.59 0 0 0-.972.633 2.601 2.601 0 0 0-.634.972c-.17.44-.265.913-.298 1.618C4.805 9.367 4.8 9.714 4.8 12c0 2.227.006 2.59.052 3.626.034.705.128 1.18.298 1.617.153.392.333.674.632.972.303.303.585.484.972.633.445.172.918.267 1.62.3.993.047 1.34.052 3.626.052 2.227 0 2.59-.006 3.626-.052.704-.034 1.178-.128 1.617-.298.39-.152.674-.333.972-.632.304-.303.485-.585.634-.972.171-.444.266-.918.299-1.62.047-.993.052-1.34.052-3.626 0-2.227-.006-2.59-.052-3.626-.034-.704-.128-1.18-.299-1.618a2.619 2.619 0 0 0-.633-.972 2.595 2.595 0 0 0-.972-.634c-.44-.17-.914-.265-1.618-.298-.993-.047-1.34-.052-3.626-.052ZM12 3c2.445 0 2.75.009 3.71.054.958.045 1.61.195 2.185.419A4.388 4.388 0 0 1 19.49 4.51c.457.45.812.994 1.038 1.595.222.573.373 1.227.418 2.185.042.96.054 1.265.054 3.71 0 2.445-.009 2.75-.054 3.71-.045.958-.196 1.61-.419 2.185a4.395 4.395 0 0 1-1.037 1.595 4.44 4.44 0 0 1-1.595 1.038c-.573.222-1.227.373-2.185.418-.96.042-1.265.054-3.71.054-2.445 0-2.75-.009-3.71-.054-.958-.045-1.61-.196-2.185-.419A4.402 4.402 0 0 1 4.51 19.49a4.414 4.414 0 0 1-1.037-1.595c-.224-.573-.374-1.227-.419-2.185C3.012 14.75 3 14.445 3 12c0-2.445.009-2.75.054-3.71s.195-1.61.419-2.185A4.392 4.392 0 0 1 4.51 4.51c.45-.458.994-.812 1.595-1.037.574-.224 1.226-.374 2.185-.419C9.25 3.012 9.555 3 12 3Z" />
                                    </svg>
                                    <span class="sr-only">Instagram Link</span>
                                </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                 </div>
    </div>
</nav>
