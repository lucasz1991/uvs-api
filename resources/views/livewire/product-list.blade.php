<div x-data="{ 
    openFilterDropdown: false,
    openSortDropdown: false,
  }" 
  class="w-full relative bg-cover bg-center backgroundimageOverlay bg-[#f8f2e8f2] pt-10"  wire:loading.class="cursor-wait">

        <x-slot name="header">
                    
                <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
                     Produkte 
                     <svg xmlns="http://www.w3.org/2000/svg" width="80px" class="aspect-square text-[#333] ml-10  inline opacity-30" fill="none" viewBox="0 0 24 24">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M15.583 8.445h.01M10.86 19.71l-6.573-6.63a.993.993 0 0 1 0-1.4l7.329-7.394A.98.98 0 0 1 12.31 4l5.734.007A1.968 1.968 0 0 1 20 5.983v5.5a.992.992 0 0 1-.316.727l-7.44 7.5a.974.974 0 0 1-1.384.001Z"/>
                     </svg>
                     
                </h1>
        </x-slot>
    @persist('scrollbar')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 " >
        @if(session('message'))
        
            <div class=" p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg border-l-4 border-blue-500" role="alert">
                <strong class="font-semibold">Hinweis:</strong><br> {!! nl2br(session('message')) !!}
            </div>
        @endif
        <!-- Filter- und Suchbereich -->
        <div class="flex justify-between items-center mb-6  flex-wrap">
            <div class="flex  mb-3">

                <!-- Filter Dropdown -->
                <div class="relative" @click.away="openFilterDropdown = false" @close.stop="openFilterDropdown = false">
                    <!-- Filter Button -->
                    <button 
                        type="button" 
                        class="flex items-center justify-center rounded-lg mr-3 border border-gray-300 bg-white h-[40px] px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700"
                        @click="openFilterDropdown = !openFilterDropdown"
                    >
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                        Filter
                    </button>
                    <!-- Filter Dropdown -->
                    <div 
                        x-show="openFilterDropdown" 
                        class="absolute left-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 p-4"
                        x-cloak
                    >
                        <!-- Kategorien Filter -->
                        <div class="mb-4">
                            <label for="selectedCategory" class="block text-sm font-medium text-gray-700">Kategorie</label>
                            @php
                                function renderCategoryOptions($categories, $level = 0) {
                                    foreach ($categories as $category) {
                                        // Prüfen, ob die Kategorie oder eine ihrer Unterkategorien Produkte mit status = 2 hat
                                        $hasValidProducts = $category->products->where('status', 2)->isNotEmpty()
                                            || $category->children->pluck('products')->flatten()->where('status', 2)->isNotEmpty();

                                        if (!$hasValidProducts) {
                                            continue; // Überspringe Kategorien ohne gültige Produkte
                                        }

                                        echo '<option value="' . $category->id . '">' . str_repeat('— ', $level) . e($category->name) . '</option>';

                                        if ($category->children->isNotEmpty()) {
                                            renderCategoryOptions($category->children, $level + 1);
                                        }
                                    }
                                }
                            @endphp

                    <select id="selectedCategory" wire:model.lazy="selectedCategory" class="mt-1 block w-full max-h-[300px] rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" style="overflow-y: auto;scrollbar-width: thin;" size="10">
                        <option value="">Alle Kategorien</option>
                        @php renderCategoryOptions($categories); @endphp
                    </select>
                        </div>
                            <!-- Tags Filter -->
                        <div class="flex flex-col mb-4">
                            <label for="tag" class="mb-1 text-sm font-medium text-gray-600">Schlagwörter</label>
                            <select id="tag" wire:model.lazy="selectedTag"  size="6" style="overflow-y: auto;scrollbar-width: thin;"
                                    class="p-2 border rounded-lg text-sm  border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Altersgruppen Filter -->
                        <div class="mb-4">
                            <label for="ageGroup" class="block text-sm font-medium text-gray-700">Altersgruppe</label>
                            <select 
                                id="ageGroup" 
                                wire:model.lazy="ageGroup" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Alle Altersgruppen</option>
                                <option value="0-2 Jahre">0-2 Jahre</option>
                                <option value="3-5 Jahre">3-5 Jahre</option>
                                <option value="6-10 Jahre">6-10 Jahre</option>
                                <option value="11+ Jahre">11+ Jahre</option>
                            </select>
                        </div>
                        <label  class="mb-1 text-sm font-medium text-gray-600">Mindestpreis & Höchstpreis</label>

                        <div class="mb-4 mt-12">
                            <div class="w-full pl-4 pr-6"
                                x-data="rangeSlider"
                                x-init="
                                        setTimeout(() => {
                                            try {
                                                init(min, max, from, to);
                                            } catch (error) {
                                                console.error('Initialization failed:', error);
                                            }
                                        }, 100);

                                        $watch('from', value => {
                                            let input = document.querySelector('input[name=min_price]');
                                            if (input) {
                                                input.value = value;
                                                input.dispatchEvent(new Event('input', { bubbles: true })); 
                                            }
                                        });
                                        $watch('to', value => {
                                            let input = document.querySelector('input[name=max_price]');
                                            if (input) {
                                                input.value = value;
                                                input.dispatchEvent(new Event('input', { bubbles: true })); 
                                            }
                                        });
                                    ">
                                <input class="hidden"  type="number" name="min_price" x-model="from"  wire:model.lazy="minPrice" min="{{ $initialMinPrice }}" max="{{ $initialMaxPrice }}">
                                <input class="hidden" type="number" name="max_price" x-model="to"  wire:model.lazy="maxPrice"  min="{{ $initialMinPrice }}" max="{{ $initialMaxPrice }}">
                            
                                    <!-- Slider -->
                                <div class="relative w-full h-2 rounded-full" id="priceSlider" x-ref="slider" :style="'background: linear-gradient(to right, #d1d5db ' + getFromPos() + ', #baf4f7 ' + getFromPos() + ', #85b0f4 ' + getToPos() + ', #d1d5db ' + getToPos() + ')';">

                                    
                                    <!-- Left Handle -->
                                    <div class="absolute -ml-2 -translate-y-1 cursor-pointer rounded-full bg-blue-600 w-4 h-4 z-10 border border-gray-400"
                                        :style="'left:' + getFromPos()" 
                                        id="leftHandle"
                                        @mousedown="startDrag($event, 'from')" 
                                        @touchstart="startDrag($event, 'from', true)">
                                        <span class="absolute -top-8 left-1/2 transform  bg-gray-400 text-white text-xs px-2 py-1 rounded transition-all duration-300 ease-out"
                                            x-text="from + '&nbsp;€'"
                                            :class="areLabelsTooClose() ? '-translate-x-6' : '-translate-x-1/2'"
                                            ></span>
                                    </div>
                                    
                                    <!-- Right Handle -->
                                    <div class="absolute -ml-2 -translate-y-1 cursor-pointer rounded-full bg-blue-600 w-4 h-4 z-10 border border-gray-400"
                                        :style="'left:' + getToPos()" 
                                        id="rightHandle"
                                        @mousedown="startDrag($event, 'to')" 
                                        @touchstart="startDrag($event, 'to', true)">
                                        <span class="absolute -top-8 left-1/2 transform  bg-gray-400 text-white text-xs px-2 py-1 rounded transition-all duration-300 ease-out"
                                            x-text="to + '&nbsp;€'"
                                            :class="areLabelsTooClose() ? 'translate-x-1' : '-translate-x-1/2'"
                                            ></span>
                                    </div>
                                </div>

                                <!-- Static Indicators -->
                                <div class="flex justify-between w-full text-xs mt-1 text-gray-600">
                                    <span>{{ $initialMinPrice }} €</span>
                                    <span>{{ ($initialMinPrice + $initialMaxPrice) / 2 }} €</span>
                                    <span>{{ $initialMaxPrice }} €</span>
                                </div>
                            </div>
                        </div>


                        <script>
                        window.rangeSlider = {
                            min: {{ $initialMinPrice }},
                            max: {{ $initialMaxPrice }},
                            from: {{ $minPrice }},
                            to: {{ $maxPrice }},

                            activeHandle: null,

                            init(min, max, from, to) {
                                const slider = document.querySelector('#priceSlider');
                                if (!slider) {
                                    console.error('Slider reference is missing.');
                                    return;
                                }

                                this.min = min || this.min;
                                this.max = max || this.max;

                                this.from = this.clamp(from || this.from, this.min, this.max);
                                this.to = this.clamp(to || this.to, this.min, this.max);

                                const leftHandle = document.getElementById('leftHandle');
                                const rightHandle = document.getElementById('rightHandle');

                                if (leftHandle) {
                                    leftHandle.addEventListener('mousedown', (e) => this.startDrag(e, 'from'));
                                    leftHandle.addEventListener('touchstart', (e) => this.startDrag(e, 'from', true));
                                }

                                if (rightHandle) {
                                    rightHandle.addEventListener('mousedown', (e) => this.startDrag(e, 'to'));
                                    rightHandle.addEventListener('touchstart', (e) => this.startDrag(e, 'to', true));
                                }

                                window.addEventListener('mousemove', (e) => this.drag(e));
                                window.addEventListener('touchmove', (e) => this.drag(e));
                                window.addEventListener('mouseup', () => this.dragEnd());
                                window.addEventListener('touchend', () => this.dragEnd());
                            },
                            clamp(value, min, max) {
                                return Math.max(min, Math.min(value, max));
                            },
                            getSliderRef() {
                                return document.querySelector('#priceSlider');
                            },
                            getFromPos() {
                                return ((this.from - this.min) / (this.max - this.min) * 100) + '%';
                            },
                            getToPos() {
                                return ((this.to - this.min) / (this.max - this.min) * 100) + '%';
                            },
                            getWidth() {
                                return ((Math.max(this.to, this.from) - Math.min(this.to, this.from)) / (this.max - this.min) * 100) + '%';
                            },
                            startDrag(event, handle) {
                                event.preventDefault(); // Prevent scrolling on touch devices
                                this.activeHandle = handle;
                            },
                            drag(event) {
                                if (!this.activeHandle) return;

                                let x = event.type === 'touchmove' ? event.changedTouches[0].clientX : event.clientX;
                                const sliderRect = this.getSliderRef().getBoundingClientRect();
                                let pos = Math.round((this.max - this.min) * (x - sliderRect.left) / sliderRect.width) + this.min;

                                // Clamp the position within the allowed range
                                pos = this.clamp(pos, this.min, this.max);

                                // Perform validation before updating the position
                                if (this.activeHandle === 'from') {
                                    if (pos < this.to) { // Validation: "from" must be less than "to"
                                        this.from = pos;
                                    }
                                } else if (this.activeHandle === 'to') {
                                    if (pos > this.from) { // Validation: "to" must be greater than "from"
                                        this.to = pos;
                                    }
                                }
                            },
                            dragEnd() {
                                if (!this.activeHandle) return;

                                this.activeHandle = null;

                                // Trigger Livewire event to update the products
                                Livewire.dispatch('priceRangeUpdated');
                            },
                            areLabelsTooClose() {
                                return Math.abs(this.to - this.from) < 270; // Adjust threshold as needed
                            },
                        };

                        function resetRangePicker() {
                            const rangeSlider = window.rangeSlider;

                            if (rangeSlider) {
                                // Setze die Werte zurück
                                rangeSlider.from = rangeSlider.min;
                                rangeSlider.to = rangeSlider.max;

                                // Aktualisiere die Reglerpositionen und den Hintergrund
                                const leftHandle = document.getElementById('leftHandle');
                                const rightHandle = document.getElementById('rightHandle');
                                const slider = document.getElementById('priceSlider');

                                if (leftHandle) leftHandle.style.left = `${((rangeSlider.min - rangeSlider.min) / (rangeSlider.max - rangeSlider.min)) * 100}%`;
                                if (rightHandle) rightHandle.style.left = `${((rangeSlider.max - rangeSlider.min) / (rangeSlider.max - rangeSlider.min)) * 100}%`;

                                if (slider) {
                                    slider.style.background = `linear-gradient(to right, #d1d5db 0%, #3b82f6 0%, #3b82f6 100%, #d1d5db 100%)`;
                                }
                            }
                        }

                        </script>







                    </div>
                </div>

                  <!-- Search Bar -->
                <div x-data="{ focused: false }" @click="focused = true" @click.away="focused = false" x-cloak class="relative">
                    <form  wire:submit.prevent="performSearch" >
                        <div class="flex items-center border border-gray-300 mr-3 rounded-lg overflow-hidden transition-all duration-300  text-gray-900 bg-white hover:bg-gray-100 hover:text-primary-700"
                            :class="focused ? 'w-[200px]' : 'w-[40px]'">
                            <input type="text" name="query" placeholder="Suchen..."
                                wire:model.defer="search"  
                                x-ref="searchInput"
                                class="w-full px-4 py-1 text-sm font-medium text-gray-900 focus:outline-none bg-transparent border-none outline-none"
                                x-ref="search" @click="focused = true" :class="focused ? 'block' : 'hidden'" />
                            <button type="submit" 
                                class="flex items-center justify-center w-[40px] h-[38px] text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="h-4 w-4" stroke="currentColor">
                                    <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            


            <div class="flex  mb-3">
                <button x-data="{ isClicked: false }"  wire:click="toggleLayout" class="flex items-center mr-3 justify-center rounded-lg border border-gray-300 bg-white overflow-hidden text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary-700 transition-all duration-100"
                @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                style="transform:scale(1);"
                :style="isClicked ? 'transform:scale(0.9);' : ''"
                >  
                    <div class="{{ $productIsList ? 'bg-gray-300' : '' }}  px-2 py-1 h-[38px] flex items-center justify-center">
                        <svg class="w-5  aspect-square" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5"/>
                        </svg>
                    </div>
                    <div class="{{ $productIsList ? '' : 'bg-gray-300' }}  px-2 py-1 h-[38px] flex items-center justify-center">
                        <svg class="w-5  aspect-square" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z"/>
                        </svg>
                    </div>
                </button>
            
            
                <!-- Sort Dropdown -->
                <div class="relative" @click.away="openSortDropdown = false" @close.stop="openSortDropdown = false">
                    <button 
                        type="button" 
                        class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 h-[40px] text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700"
                        @click="openSortDropdown = !openSortDropdown"
                        
                    >
                        Sortieren
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div 
                        x-show="openSortDropdown" 
                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                        x-cloak
                    >
                        <div class="py-1">
                            <a href="#" wire:click.prevent="sort('popular_asc')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Beliebteste: Aufsteigend</a>
                            <a href="#" wire:click.prevent="sort('popular_desc')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Beliebteste: Absteigend</a>
                            <a href="#" wire:click.prevent="sort('price_asc')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Preis: Niedrig zu Hoch</a>
                            <a href="#" wire:click.prevent="sort('price_desc')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Preis: Hoch zu Niedrig</a>
                            <a href="#" wire:click.prevent="sort('newest_first')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Neue Produkte zuerst</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Aktive Filter -->
        @if ($selectedCategory || !empty($tags) || ($minPrice !== $initialMinPrice) || ($maxPrice !== $initialMaxPrice) || $search != "" || $ageGroup)
        <div class="flex items-center flex-wrap gap-2 mb-4">
            @foreach ([
                'category' => $selectedCategory ? ($selectedCategoryObj->name ?? 'Unbekannt') : null,
                'tags' => $selectedTag,
                'price' => (($minPrice !== $initialMinPrice) || ($maxPrice !== $initialMaxPrice)) ? 
                    ( (($minPrice !== $initialMinPrice) ? 'ab ' . number_format($minPrice, 2) : '') .
                    (($maxPrice !== $initialMaxPrice) ? ' € bis ' . number_format($maxPrice, 2).' €' : '') ) : null,
                'search' => $search ?: null,
                'ageGroup' => $ageGroup ?: null,
            ] as $filter => $value)
                @if ($filter === 'tags' && !empty($selectedTag))
                    <div class="flex items-center bg-gray-200 px-3 py-1 rounded-lg text-sm">
                        <span>Schlagwort : {{ $selectedTag }}</span>
                        <button 
                            type="button" 
                            wire:click="clearFilter('tag', '{{ $selectedTag }}')" 
                            class="ml-2 text-red-500 hover:text-red-700"
                        >
                            &times;
                        </button>
                    </div>
                @elseif ($filter === 'price' && $value)
                    <div class="flex items-center bg-gray-200 px-3 py-1 rounded-lg text-sm">
                        <span>Preis: {{ $value }}</span>
                        <button 
                            type="button" 
                            wire:click="clearFilter('{{ $filter }}')" 
                            onclick="resetRangePicker()" 
                            class="ml-2 text-red-500 hover:text-red-700"
                        >
                            &times;
                        </button>
                    </div>
                @elseif ($value)
                    <div class="flex items-center bg-gray-200 px-3 py-1 rounded-lg text-sm">
                        <span>
                            @if ($filter === 'category')
                                Kategorie: {{ $value }}
                            @elseif ($filter === 'price')
                                Preis: {{ $value }}
                            @elseif ($filter === 'search')
                                Suche: "{{ $value }}"
                            @elseif ($filter === 'ageGroup')
                                Altersgruppe: {{ $value }}
                            @endif
                        </span>
                        <button 
                            type="button" 
                            wire:click="clearFilter('{{ $filter }}')" 
                            class="ml-2 text-red-500 hover:text-red-700"
                        >
                            &times;
                        </button>
                    </div>
                @endif
            @endforeach
        </div>
        @endif

        @if ($products->isEmpty())
            <div>
        @elseif (!$products->isEmpty())
            <div class="{{ !$productIsList ? 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4' : 'grid-cols-1' }} grid  gap-6 snap-x">
        @endif


        <!-- Produktliste -->
            @forelse ($products as $product)
            <div class="relative  snap-center ">
                    <button  
                        x-data="{ isClicked: false }"  
                        @click="isClicked = true;  setTimeout(() => isClicked = false, 100); window.location.href = '{{ route('product.show', $product->id) }}'" 
                        wire:navigate  
                        class="w-full h-full  text-left transition-all duration-100 bg-white shadow  cursor-pointer hover:-translate-y-2  hover:bg-gray-100 active:bg-gray-100 focus:outline-none focus:ring focus:ring-violet-300 "  
                        wire:key="{{ $product->id }}"
                        style="transform:scale(1);"
                        :style="isClicked ? 'transform:scale(0.98);' : ''"   
                        >
                       <div class="h-full {{ !$productIsList ? 'grid grid-cols-1 content-between' : 'grid grid-cols-10 gap-x-4' }} " style="{{ !$productIsList ? 'grid-template-rows: auto 1fr;' : '' }} " >
                            <div class="overflow-hidden mx-auto  w-full h-auto {{ !$productIsList ? '' : 'col-span-3 md:col-span-2 ' }}">
                                <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 500)" class="relative w-full aspect-square bg-gray-100 shadow   overflow-hidden">
                                    <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-gray-200">
                                        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                                    </div>
                                    <img 
                                        src="{{ url($product->getImageUrl(0,'m')) }}" 
                                        alt="{{ $product->name }}" 
                                        class="object-cover w-full  aspect-square z-50"
                                        @load.window="loading = false"
                                        loading="lazy"
                                    >
                                </div>
                            </div>
                            <div class="w-full p-2 md:p-3 pt-2 {{ !$productIsList ? ' grid grid-cols-1 content-between' : 'col-span-7 md:col-span-8 grid content-between ' }}" >
                                <div class="{{ !$productIsList ? '' : 'flex items-start justify-between' }} max-sm:block">
                                    <div class="{{ !$productIsList ? '' : 'order-2' }} text-right rtl:text-left flex" style="width: max-content;"> 
                                        <p class="text-sm text-gray-600  mb-2">
                                            <span class="bg-green-100 text-green-800  font-medium pr-1 pl-1 py-0.5 rounded  border border-green-400"> {{ $product->shelfRental->shelf->floor_number ?? '???' }}</span>
                                        </p>
                                        <p class="text-xs tracking-tighter text-gray-600 decoration-indigo-500">
                                            @if ($product->shelfRental && $product->shelfRental->rental_end)
                                                @php
                                                    $rentalEnd = \Carbon\Carbon::parse($product->shelfRental->rental_end)->setTime(16, 0); // Mietende auf 16:00 Uhr setzen
                                                    $now = \Carbon\Carbon::now();
                    
                                                    $remainingDays = $now->diffInDays($rentalEnd, false);
                                                    $remainingHours = $now->diffInHours($rentalEnd, false); // Gesamte verbleibende Stunden
                                                @endphp
                                                @if ($remainingDays > 0)
                                                    <span class="ml-2">
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
                                    <h3 class="{{ !$productIsList ? '' : 'order-1' }} max-md:order-2 text-md md:text-lg leading-normal font-extrabold text-gray-800 break-all md:break-words">{{ $product->name }}</h3>
                                </div>
                                <!-- Flexbox für Kategorie und Größe -->
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @if(!empty($product->category))
                                            <span class="text-xs bg-gray-100 text-gray-800 font-medium px-2 py-0.5 rounded-full border border-gray-300">
                                                {{ $product->category }}
                                            </span>
                                        @endif
                                        @if(!empty($product->size))
                                            <span class="text-xs bg-gray-100 text-gray-800 font-medium px-2 py-0.5 rounded-full border border-gray-300">
                                                Gr.: {{ $product->size }}
                                            </span>
                                        @endif
                                    </div>
                                <div class="mt-2 ">
                                    <p class="text-gray-600 text-sm {{ $productIsList ? '' : 'truncate' }} ">{{ Str::limit($product->description, 50) }}</p>
                                </div>
                                <x-product-price :product="$product" />
                            </div>
                       </div>
                    </button>
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
                        class="w-8 h-8 flex items-center justify-center {{ auth()->check() && auth()->user()->likedProducts->contains($product->id) ? 'border-red-300' : 'border-gray-300' }}  {{ !$productIsList ? 'left-auto top-3 right-3' : 'left-2 top-2' }} shadow border rounded-full cursor-pointer absolute  transition-all duration-100 transform z-60"
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
                </div>
                @empty
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" role="alert">
                        <div class="font-bold text-lg">Keine Produkte gefunden</div>
                        <p class="mt-2">
                            Es scheint, als hätte deine Suche keine passenden Produkte ergeben. 🛒
                        </p>
                        <p class="mt-2">
                            Bitte überprüfe deine Eingabe oder probiere es mit einem anderen Suchbegriff. Du kannst auch unsere Kategorien durchstöbern, um interessante Artikel zu entdecken. ✨
                        </p>
                        <p class="mt-4 font-medium">
                            Viel Spaß beim Stöbern! <br>
                            Euer MiniFinds-Team ❤️
                        </p>
                    </div>
                @endforelse

        </div>

        <!-- Weitere Produkte -->
        @if ($products->hasMorePages())
            <div class="text-center mt-10"
            x-data="{ isClicked: false }" 
            @click="isClicked = true; setTimeout(() => isClicked = false,100)">
                <button :style="isClicked ? 'transform:scale(0.9)' : 'transform:scale(1)'" wire:click="loadMore" class=" transition-all duration-100 transform py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                    Weitere Produkte anzeigen
                </button>
            </div>
        @endif
       
    </div>
    @endpersist

    <div class="mt-12">

        <x-features-banner />
    </div>
</div>
