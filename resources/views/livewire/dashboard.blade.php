<div class="w-full relative bg-cover bg-center bg-[#f8f2e8f2] py-20"  wire:loading.class="cursor-wait">
    <div class="max-w-7xl mx-auto px-5">
    <x-slot name="header">
            <div class="mr-auto font-semibold text-2xl place-self-center lg:col-span-7">
                <h1 class="max-w-2xl mb-4  font-bold tracking-tight leading-none text-2xl xl:text-3xl">Willkommen {{ $userData->name }},</h1>
                <p class="max-w-2xl mb-6  text-gray-500 lg:mb-8 md:text-lg lg:text-xl ">Deine Buchungen, Verkäufe und Auszahlungen im Überblick</p>
                <x-bannerbuttons />
            </div>
    </x-slot>
    

        
    
        <!-- Statistiken -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Buchungen</h2>
                <p class="text-3xl font-bold text-gray-500">{{ $shelfRentalsCount }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Produkte im Verkauf</h2>
                <p class="text-3xl font-bold text-gray-500">{{ $productsCount }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Verkäufe</h2>
                <p class="text-3xl font-bold text-gray-500">{{ $salesCount }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Einkünfte</h2>
                <p class="text-3xl font-bold text-gray-500">{{ number_format($deposit, 2, ',', '.') }} €</p>
            </div>
        </div>
    
        <!-- Buchungen -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Deine Buchungen</h2>
        <div>

            <div class="grid grid-cols-1  gap-6" >
            @forelse ($shelfRentals as $shelfRental)
        @php
            $productCount = $shelfRental->products->count();
            $soldProductsCount = $shelfRental->products->where('status', 'sold')->count();
        @endphp

        <!-- Buchungskarte -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden px-5 divide-y divide-gray-300/50"  wire:key="{{ $shelfRental->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-5">
                <div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">
                        <span class="font-medium">Regalnummer:</span> 
                        <span class="bg-green-100 text-green-800 font-medium pr-2 pl-2 py-0.5 rounded border border-green-400">
                            {{ $shelfRental->shelf->floor_number }}
                        </span>
                    </p>
                </div>
                <div class=" md:text-right">
                    <p class="text-lg font-semibold text-gray-800 mb-2">
                        <span class="font-medium">Buchungsnummer:</span> 
                        <span class="text-gray-800 font-medium pr-2 pl-2 py-0.5 ">
                            # {{ $shelfRental->id }}
                        </span>
                    </p>
                </div>
            </div>
            <div class=" grid grid-cols-1 md:grid-cols-2 gap-4  py-5">
                <!-- Linke Spalte: Buchungsdetails -->
                <div>
                    
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Zeitraum:</span> 
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse($shelfRental->rental_start)->format('d.m.Y') }} - 
                            {{ \Carbon\Carbon::parse($shelfRental->rental_end)->format('d.m.Y') }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-600 my-2">
                        <span class="font-medium">Status:</span> 
                        <x-shelve-rental-status :status="$shelfRental->status" />
                    </p>

                </div>

                <div class=" md:text-right">
                    <h2 class="text-md font-semibold md:text-right md:pr-2 text-gray-800 mb-2">Produkte</h2>
                    <div class="flex md:justify-end">
                        <!-- Anzahl der Produkte -->
                        <div class=" bg-blue-100 text-blue-700 px-4 py-1 text-center first:rounded-l-lg last:rounded-r-lg">
                            <p class="text-xs font-medium">Anzahl</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->count() }}</p>
                        </div>

                        <!-- Verkauft -->
                        <div class=" bg-green-100 text-green-700 px-4 py-1 text-center first:rounded-l-lg last:rounded-r-lg">
                            <p class="text-xs font-medium">Verkauft</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 4)->count() }}</p>
                        </div>

                        <!-- Im Verkauf -->
                        <div class=" bg-yellow-100 text-yellow-700 px-4 py-1 text-center first:rounded-l-lg last:rounded-r-lg">
                            <p class="text-xs font-medium">Im Verkauf</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 2)->count() }}</p>
                        </div>

                        <!-- Entwürfe -->
                        <div class=" bg-gray-100 text-gray-700 px-4 py-1 text-center first:rounded-l-lg last:rounded-r-lg">
                            <p class="text-xs font-medium">Entwürfe</p>
                            <p class="text-sm font-bold">{{ $shelfRental->products()->where('status', 1)->count() }}</p>
                        </div>
                    </div>
                </div>

            </div>

                <div x-data="{ open: false }" class="mt-4  py-5">
                                <div class="flex justify-between">

                                    <div>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Gesamtumsatz:</span>
                                            <span class="">{{ number_format($shelfRental->getRevenue(), 2, ',', '.') }} €</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Einkünfte nach Provision:</span>
                                            <span class="font-bold text-green-600">{{ number_format($shelfRental->getCustomerEarnings(), 2, ',', '.') }} €</span>
                                        </p>
                                          
                                    </div>
                                    <div>

                                    <button 
                                        @click="open = !open" 
                                        class="flex items-center text-blue-500 hover:text-blue-600 transition duration-300"
                                    >
                                        Optionen 
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke-width="1.5" 
                                            stroke="currentColor" 
                                            class="w-6 h-6 ml-2 transform transition-transform duration-300"
                                            :class="{'rotate-180': open}" 
                                        >
                                            <path 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                d="M4.5 12l7.5-7.5m0 0l7.5 7.5m-7.5-7.5V19.5" 
                                            />
                                        </svg>
                                    </button>
                                    </div>
                                </div>

                            <div 
                                    x-show="open" 
                                    x-collapse
                                    @click.outside="open = false" 
                                    x-cloak
                                >
                                <div class="bg-gray-100 mt-2 p-3 rounded-lg shadow flex flex-wrap justify-between max-md:justify-end items-center max-md:space-y-3">
                                    <div>
                                        @switch($shelfRental->status)
                                                @case(1)
                                                    @break

                                                @case(2)
                                                    @break

                                                @case(3)
                                                    @livewire('shelfrentalcomponents.payout-form', ['shelfRentalId' => $shelfRental->id])

                                                    @break

                                                @case(4)
                                                    @livewire('shelfrentalcomponents.payout-details', ['shelfRentalId' => $shelfRental->id])
                                                    @break

                                                @case(5)
                                                    @break

                                                @case(6)
                                                    @break

                                                @case(7)
                                                    @break

                                                @case(8)
                                                    <p class="text-yellow-500 text-sm font-medium flex">
                                                        ⏳ Auszahlung wird bearbeitet
                                                    </p>
                                                    @break

                                                @default
                                                    <p class="text-sm text-gray-500 font-medium flex">Unbekannter Status</p>
                                            @endswitch
                                    </div>
                    
                                    <!-- Link auf der rechten Seite -->
                                    <div class="max-md:space-y-3 justify-end">
                    
                                        @if (!empty($shelfRental->rental_bill_url))
                                            <div class="inline-flex  mr-1" role="group">
                                                <span class="px-4 py-2 text-sm font-medium text-white bg-gray-400  rounded-s-lg">
                                                    Mietkosten Rechnung
                                                            </span>
                                                
                                                <a   href="{{ route('invoice.download', ['filename' => $shelfRental->rental_bill_url]) }}"   type="button" class="px-4 py-2 text-sm font-medium bg-blue-500 text-white  rounded-e-lg hover:bg-blue-600 hover:text-blue-700 focus:z-10 ">
                                                    <svg stroke="currentColor" class="w-5 h-5 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 134.33721 106.41"><defs xmlns="http://www.w3.org/2000/svg"><style>.b{fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:10px;}</style></defs><polyline class="b" points="99.686 62.774 67.169 86.081 34.652 62.774"/><line class="b" x1="67.16861" y1="2" x2="67.16861" y2="86.08084"/><polyline class="b" points="132.337 86.581 132.337 104.41 67.169 104.41 2 104.41 2 86.581 2 104.41 67.169 104.41"/></svg>
                                                </a>
                                            </div>
                                        @endif
                                        @if ($shelfRental->status != 7)
                                            <a   href="{{ route('shelfrental.show',['shelfRentalId' => $shelfRental->id]) }}"  wire:navigate
                                                class="inline-flex px-4 py-2 bg-blue-500 text-white  font-medium text-sm rounded-lg hover:bg-blue-600">
                                                Bearbeiten
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </div>

                        </div>
                    @empty
                        <p class="col-span-full text-gray-500">Keine Buchungen gefunden.<br>
                        
                    </p>
                    @endforelse


            </div>

        
            <!-- Pagination -->
            @if ($shelfRentals->hasPages())
                <div class="mt-6">
                    {{ $shelfRentals->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
