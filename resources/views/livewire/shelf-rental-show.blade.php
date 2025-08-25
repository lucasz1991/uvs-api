<div x-data="{retailSpace: @entangle('retailSpace'), shelfRental: @entangle('shelfRental')}" class="pb-8 pt-3  md:py-12  antialiased bg-[#f8f2e8f2]"  wire:loading.class="cursor-wait">
    <div class="max-w-7xl mx-auto  px-5 ">
        <div class="mb-4">
        @php
            $productCount = $shelfRental->products->count();
            $soldProductsCount = $shelfRental->products->where('status', 'sold')->count();
        @endphp
        <!-- Buchungskarte -->
        <div class="bg-white shadow-lg rounded-lg  p-5 mb-8">
            <div class="grid grid-cols-2  gap-4">
                <!-- Linke Spalte: Buchungsdetails -->
                <div>
                    <x-back-button />
                    <p class="text-lg font-semibold text-gray-800 mt-5 mb-2">
                        <span class="font-medium">Regalnummer:</span> 
                        <span class="bg-green-100 text-green-800 font-medium pr-2 pl-2 py-0.5 rounded border border-green-400">
                            {{ $shelfRental->shelf->floor_number }}
                        </span>
                    </p>
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
                    @if($shelfRental->status == 8)
                        <!-- Hinweis zur Auszahlung -->
                        <div class="my-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
                            <p class="font-semibold">Wichtige Information zu deiner Auszahlung</p>
                            <p class="text-sm mt-1">
                                Dein Antrag auf Auszahlung wurde erfolgreich übermittelt und wird derzeit von unserem Team geprüft. 
                                Die Bearbeitung erfolgt in der Regel innerhalb von <strong>48 Stunden</strong>.  
                                Sobald die Überweisung durchgeführt wurde, erhältst du eine Bestätigung per E-Mail.  
                                Bitte beachte, dass es je nach Bankinstitut bis zu <strong>3 Werktage</strong> dauern kann, 
                                bis der Betrag auf deinem Konto gutgeschrieben wird.  
                                Falls du Fragen zu deiner Auszahlung hast, kannst du dich jederzeit an unseren Support wenden.
                            </p>
                        </div>
                    @endif
                    <h2 class="text-md font-semibold  md:pr-2 text-gray-800 mb-2 mt-5">Produkte</h2>
                    <div class="flex">
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
                <div class="flex justify-end">
                <!-- Lageplan mit Lightbox -->
                <x-lightbox-svg :retailSpace="$retailSpace" :shelfRental="$shelfRental" />
            </div>
        </div>
        <div class="mt-4  flex justify-between items-end">
            <div>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Gesamtumsatz:</span>
                    <span class="">{{ number_format($shelfRental->getRevenue(), 2, ',', '.') }} €</span>
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Einkünfte nach Provision:</span>
                    <span class="font-bold text-green-600">{{ number_format($shelfRental->getCustomerEarnings(), 2, ',', '.') }} €</span>
                </p>
                  <!-- Statusanzeige -->
                    @switch($shelfRental->status)
                        @case(1)
                            @break

                        @case(2)
                            @break

                        @case(3)
                            @break

                        @case(4)
                            <p class="text-sm text-green-600 font-medium flex my-2">Einkünfte ausgezahlt</p>
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
            <div x-data="{ open: false }" class="relative">
                <!-- Button für das Dropdown -->
                <button @click="open = !open" class="bg-gray-500 text-white px-3 py-1 rounded-lg flex items-center">
                    Optionen
                    <svg 
                        x-cloak
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke-width="2" 
                        stroke="currentColor" 
                        class="w-4 h-4 ml-2 transform transition-transform duration-300"
                        :class="open ? 'rotate-180' : 'rotate-90'"
                    >
                        <path 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            d="M4.5 12l7.5-7.5m0 0l7.5 7.5m-7.5-7.5V19.5" 
                        />
                    </svg>
                </button>
                <!-- Dropdown-Menü -->
                <div x-show="open" @click.away="open = false" x-cloak class="absolute object-center right-0 z-50">
                    <div @click.away="open = false"  class="relative shadow-lg bg-white w-48 rounded-md ring-1 ring-black ring-opacity-5 mt-3">
                        <ul class="divide-y divide-gray-200">
                            @switch($shelfRental->status)
                                @case(2)
                                        @php
                                            $lastDiscountChange = \Carbon\Carbon::parse($shelfRental->updated_at);
                                            $canChangeDiscount = $lastDiscountChange->diffInHours(now()) >= 24;
                                        @endphp

                                        @if($canChangeDiscount)
                                            <li>
                                                @livewire('shelfrentalcomponents.shelf-rental-discount', ['shelfRental' => $shelfRental])
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ url('/customer/shelf-rental/' . $shelfRental->id . '/extend') }}" class="px-4 py-2 text-blue-500 fill-blue-500 hover:text-blue-600 text-sm font-medium flex items-center">
                                                <svg class="w-4 h-4 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M96 32l0 32L48 64C21.5 64 0 85.5 0 112l0 48 448 0 0-48c0-26.5-21.5-48-48-48l-48 0 0-32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 32L160 64l0-32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192L0 192 0 464c0 26.5 21.5 48 48 48l352 0c26.5 0 48-21.5 48-48l0-272zM224 248c13.3 0 24 10.7 24 24l0 56 56 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-56 0 0 56c0 13.3-10.7 24-24 24s-24-10.7-24-24l0-56-56 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l56 0 0-56c0-13.3 10.7-24 24-24z"/></svg>
                                                Regalmiete verlängern 
                                            </a>                                        
                                        </li>
                                    @break

                                @case(3)
                                    <li>
                                        @livewire('shelfrentalcomponents.payout-form', ['shelfRentalId' => $shelfRental->id])
                                    </li>
                                    @break
                            @endswitch
                            @if (!empty($shelfRental->rental_bill_url))
                                <li>
                                    <a href="{{ route('invoice.download', ['filename' => $shelfRental->rental_bill_url]) }}" class="px-4 py-2 text-blue-500 fill-blue-500 hover:text-blue-600 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 464l48 0 0 48-48 0c-35.3 0-64-28.7-64-64L0 64C0 28.7 28.7 0 64 0L229.5 0c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3L384 304l-48 0 0-144-80 0c-17.7 0-32-14.3-32-32l0-80L64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/></svg>
                                        Rechnung 
                                    </a>
                                </li>
                            @endif
                            @if ($shelfRental->status == 1)
                                <li x-data="{ openModal: false }">
                                    <!-- Button zum Öffnen des Modals -->
                                    <button @click="openModal = true" class="w-full text-left px-4 py-2 text-red-500 fill-red-500 hover:text-red-600 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z"/></svg>
                                        Stornieren
                                    </button>
                                    <!-- Modal für Bestätigung -->
                                    <div x-show="openModal" @keydown.escape.window="openModal = false" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
                                        <div @click.away="openModal = false" class="bg-white p-6 rounded-lg shadow-lg w-96">
                                            <h2 class="text-lg font-semibold">Bist du sicher?</h2>
                                            <p class="mt-2 text-sm text-gray-700">
                                                Möchtest du diese Regal-Miete wirklich stornieren?<br> Dies kann nicht rückgängig gemacht werden<br> und eine <strong>Rückerstattung der Mietkosten ist nicht möglich</strong>.
                                            </p>
                                            <div class="mt-4 flex justify-between">
                                                <!-- Bestätigungsbutton, ruft die Livewire-Methode auf    $wire.cancelRental   -->
                                                <button wire:click="cancelRental" class="px-4 py-2 bg-red-500 text-white rounded-lg">
                                                    Ja, Stornieren
                                                </button>
                                                <!-- Abbrechen Button -->
                                                <button @click="openModal = false" class="px-4 py-2 bg-gray-300 text-black rounded-lg">
                                                    Abbrechen
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Rabatt-Info Alert -->
    @if ($shelfRental->discount > 0 && $shelfRental->status === 2)
        @php
            $lastDiscountChange = \Carbon\Carbon::parse($shelfRental->updated_at);
            $nextChangePossibleAt = $lastDiscountChange->addHours(24);
            $remainingHours = now()->diffInHours($nextChangePossibleAt, false);
        @endphp

        <div class="p-4 mb-6 text-sm text-red-800 bg-red-100 border-l-4 border-red-500 rounded-lg" role="alert">
            <div class="w-full text-red-500 mb-5">
                <svg class=" h-20 mr-3 w-20" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.891 15.107 15.11 8.89m-5.183-.52h.01m3.089 7.254h.01M14.08 3.902a2.849 2.849 0 0 0 2.176.902 2.845 2.845 0 0 1 2.94 2.94 2.849 2.849 0 0 0 .901 2.176 2.847 2.847 0 0 1 0 4.16 2.848 2.848 0 0 0-.901 2.175 2.843 2.843 0 0 1-2.94 2.94 2.848 2.848 0 0 0-2.176.902 2.847 2.847 0 0 1-4.16 0 2.85 2.85 0 0 0-2.176-.902 2.845 2.845 0 0 1-2.94-2.94 2.848 2.848 0 0 0-.901-2.176 2.848 2.848 0 0 1 0-4.16 2.849 2.849 0 0 0 .901-2.176 2.845 2.845 0 0 1 2.941-2.94 2.849 2.849 0 0 0 2.176-.901 2.847 2.847 0 0 1 4.159 0Z"/>
                </svg>
            </div>
            <strong class="font-semibold">Hinweis zur Rabattierung:</strong><br>
            Diese Regalmiete wurde um <strong>{{ $shelfRental->discount }}%</strong> reduziert.  
            Alle zugehörigen Produkte sind ebenfalls rabattiert.  
            Bitte beachte, dass sich der reduzierte Preis bereits in der Artikelübersicht widerspiegelt. <br>

            @if ($remainingHours > 0)
            <br>
                <span class="text-red-600 font-semibold">
                    Eine erneute Änderung ist frühestens am 
                    <strong>{{ $nextChangePossibleAt->format('d.m.Y') }}</strong> um 
                    <strong>{{ $nextChangePossibleAt->format('H:i') }} Uhr</strong> möglich.
                </span>
            @else
            <br>
                <span class="text-green-600 font-semibold">
                    Du kannst die Rabattierung jetzt wieder ändern.
                </span>
            @endif
        </div>
    @endif
   <!-- Produktliste -->
   <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @if ($shelfRental->status === 1 || $shelfRental->status === 2)
            <a  href="{{ route('product.create', ['shelfRental' => $shelfRental]) }}" 
                    class="h-auto text-gray-500 fill-gray-500 border-gray-300 text-center hover:border-blue-300 hover:fill-blue-300 hover:text-blue-300 w-full p-6 font-semibold text-base rounded h-52 flex flex-col items-center justify-center cursor-pointer border-2 border-dashed "
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 {{ $shelfRental->products->isEmpty() ? 'motion-safe:animate-pulse fill-green-500 ' : '' }}" viewBox="0 0 420 420"><defs></defs>
                        <path d="M205.19,410.39019c-113.14,0-205.19-92.05-205.19-205.2S92.05.00019,205.19.00019a204.5002,204.5002,0,0,1,68.15,11.59,8.95,8.95,0,1,1-5.25366,17.11167q-.34794-.10683-.68634-.24167a186.0999,186.0999,0,0,0-62.21-10.56c-103.27,0-187.29,84.01-187.29,187.29s84.01,187.3,187.29,187.3,187.29-84.02,187.29-187.3a187.05921,187.05921,0,0,0-9.75-59.8,8.95244,8.95244,0,1,1,16.97-5.71h0a204.94173,204.94173,0,0,1,10.68,65.51c0,113.15-92.05,205.2-205.19,205.2Zm137.37-301.15a8.95,8.95,0,0,1-8.95-8.95v-78.5c-.00381-4.41828,4.00012-8.00277,8.94309-8.00619s8.95309,3.57553,8.9569,7.99381v78.51237a8.95,8.95,0,0,1-8.95,8.95h0Zm39.26-39.25h-78.5a8.95,8.95,0,1,1,0-17.9h78.5a8.95,8.95,0,1,1,0,17.9Z"/>
                        <path d="M204.19027,227.08925a3.78382,3.78382,0,0,1-1.78061-.44507L99.41083,172.0297a3.79422,3.79422,0,0,1,.03876-6.72689l102.99913-53.1799a3.77459,3.77459,0,0,1,3.48248,0l102.99636,53.1799a3.79448,3.79448,0,0,1,.03814,6.72689L205.97,226.64418A3.78021,3.78021,0,0,1,204.19027,227.08925ZM109.38182,168.721l94.80845,50.27107L298.99472,168.721l-94.80445-48.95184Z"/>
                        <path  d="M204.19027,330.08531a3.80341,3.80341,0,0,1-3.79929-3.79868v-102.996a3.79646,3.79646,0,0,1,5.60819-3.33761l41.41232,22.43771L305.406,211.63926a3.79851,3.79851,0,0,1,5.57867,3.3539v56.67869a3.7946,3.7946,0,0,1-2.019,3.35359L205.97,329.64054A3.79813,3.79813,0,0,1,204.19027,330.08531Zm3.79836-100.41911v90.3094l95.3984-50.587V221.30419l-54.21492,28.74874a3.78693,3.78693,0,0,1-3.58891-.016Z"/>
                        <path d="M247.39211,250.498a3.79044,3.79044,0,0,1-1.80891-.46107l-43.20246-23.40875a3.79452,3.79452,0,0,1,.02892-6.6912L305.406,165.32219a3.78439,3.78439,0,0,1,3.5889.0163l43.20554,23.40845a3.795,3.795,0,0,1-.02892,6.69151L249.17211,250.05293A3.78146,3.78146,0,0,1,247.39211,250.498ZM212.229,223.326l35.18249,19.06473,94.9404-50.34151-35.18525-19.065Z"/>
                        <path  d="M204.19027,330.08531a3.8008,3.8008,0,0,1-1.78061-.44477L99.41083,275.02544a3.79518,3.79518,0,0,1-2.019-3.35359V214.987a3.79865,3.79865,0,0,1,5.579-3.3539l57.99761,30.75142,41.40616-22.43494a3.80425,3.80425,0,0,1,5.614,3.341V326.28663a3.803,3.803,0,0,1-3.79836,3.79868ZM104.9892,269.38864l95.40178,50.587V229.65943l-37.594,20.37074a3.78584,3.78584,0,0,1-3.58921.0163L104.9892,221.29773Z"/>
                        <path  d="M160.9875,250.49155a3.77938,3.77938,0,0,1-1.77969-.44508L56.20837,195.43168a3.795,3.795,0,0,1-.02891-6.69151L99.385,165.33173a3.78691,3.78691,0,0,1,3.5889-.0163l102.99944,54.61509a3.79612,3.79612,0,0,1-.07752,6.749L162.797,250.03017A3.79321,3.79321,0,0,1,160.9875,250.49155ZM66.028,192.04241l94.94041,50.34212,35.18526-19.065L101.21328,172.978Z"/>
                        <path d="M101.19729,172.46155a3.79591,3.79591,0,0,1-1.78307-.44477L56.20837,149.05a3.79763,3.79763,0,0,1,.0323-6.72412L159.23642,88.74984a3.80489,3.80489,0,0,1,3.55722.02922l43.20553,23.36969a3.79563,3.79563,0,0,1-.06459,6.71427L102.93853,172.03585A3.773,3.773,0,0,1,101.19729,172.46155ZM66.15044,145.7318,101.22313,164.377l94.84351-48.96445-35.11421-18.994Z"/>
                        <path d="M307.18571,172.46831a3.77167,3.77167,0,0,1-1.74155-.426L202.44872,118.86917a3.79458,3.79458,0,0,1-.06459-6.71365l43.20553-23.37a3.794,3.794,0,0,1,3.55661-.02922L352.14232,142.333a3.79713,3.79713,0,0,1,.0323,6.7232l-43.20523,22.96737A3.80384,3.80384,0,0,1,307.18571,172.46831Zm-94.86936-57.04962,94.84383,48.96537L342.23255,145.738,247.43087,96.4247Z"/>
                    </svg>
                    Produkt Hinzufügen
                    <p class="text-xs font-medium  mt-2">füge ein Produkt hinzu</p>
            </a>
        @endif
       @forelse ($shelfRental->products->sortBy('status') as $product)
           <div  class="bg-white shadow p-5  relative"  wire:key="{{ $product->id }}">
                <!-- Bild mit Ladeanimation -->
               <div class="w-full overflow-hidden mx-auto mb-2 flex items-center justify-center bg-gray-100 relative">
                   <!-- Spinner -->
                   <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 500)" class="relative w-full aspect-square  shadow-lg rounded-lg ">
                       <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-gray-200">
                           <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                       </div>
                       <!-- Produktbild -->
                       <img 
                           src="{{ url($product->getImageUrl(0,'m')) }}" 
                           alt="{{ $product->name }}" 
                           class="object-cover w-full h-full z-50 @if($product->status == 4) grayscale opacity-50 @endif"
                           @load.window="loading = false"    
                           loading="lazy"
                       >
                   </div>
               </div>
                  
               <div class=" @if($product->status == 4) grayscale opacity-50 @endif">
                   <div class="flex items-start justify-between">
                       <h3 class="text-lg leading-normal font-extrabold text-gray-800 break-words" style="width: 80%;">{{ $product->name }}</h3>
                       <div class="text-right rtl:text-left" style="width: 20%;">
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
                   </div>
                    <div class="mt-2 text-pretty ">
                        <p class="text-gray-600 text-sm truncate ">{{ Str::limit($product->description, 50) }}</p>
                    </div>
                    <x-product-price :product="$product" />
               </div>
                <!-- Status Badge -->
                <div  class="absolute top-2 left-2">
                    @if($product->status == 4)
                        <span class="bg-green-100 text-green-700 px-2 py-1 text-xs font-medium shadow-lg rounded border border-green-300">
                            Verkauft
                        </span>
                    @elseif($product->status == 2)
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs font-medium shadow-lg rounded border border-gray-300">
                            Im Verkauf
                        </span>
                    @elseif($product->status == 1)
                        <span class="bg-gray-200 text-gray-700 px-2 py-1 text-xs font-medium shadow-lg rounded border border-gray-300">
                            Entwurf
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 text-xs font-medium shadow-lg rounded border border-gray-300">
                            Unbekannt
                        </span>
                    @endif
                </div> 
               <!-- Dropdown Button -->
               <div x-data="{ open: false }" class="absolute top-2 right-2">
                        @if ($product->status == 1)
                        <button class="bg-gray-200 p-2 rounded-full transition-all duration-100" x-data="{ isClicked: false }" 
                                @click="open = !open; isClicked = true; setTimeout(() => isClicked = false, 100)"
                                style="transform:scale(1);"
                                :style="isClicked ? 'transform:scale(0.7);' : ''"
                        >
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden">
                            <a href="{{ route('product.edit', ['product' => $product]) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 flex items-center">
                                <!-- Bearbeiten Icon links vom Text -->
                                <svg class="w-4 h-4 text-gray-800  mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                                Bearbeiten
                            </a>
                            <a wire:click="deleteProduct({{ $product->id }})" class="block px-4 py-2 text-red-800 hover:text-white bg-red-200 hover:bg-red-500 flex items-center cursor-pointer">
                                <!-- Löschen Icon links vom Text -->
                                <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                </svg>
                                Löschen
                            </a>
                        </div>
                        @endif
                </div> 
            </div>
       @empty
          <div class="p-4 mb-4 h-full text-sm text-blue-700 bg-blue-100 rounded-lg border-l-4 border-blue-500" role="alert">
              <strong class="font-semibold">Hinweis:</strong><br>
              Du hast noch keine Produkte zu diesem Regal hinzugefügt. Bitte füge sie hinzu, damit sie pünktlich zum Mietbeginn eingeräumt und online gestellt werden können. So wird dein Regal bereit sein, sobald die Mietzeit startet!
          </div>
       @endforelse
    </div>
    <div class="my-20">
            <div class="max-w-7xl mx-auto px-6 py-16 bg-white shadow-lg rounded-lg mt-8">
                <h2 class="text-3xl  text-gray-800 mb-6">Wichtige Hinweise zur Produkterstellung</h2>
                <!-- Hinweis 1 -->
                <div class="mb-6 flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full  bg-[#65765f] text-white font-bold mr-4">1</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Vollständige und präzise Informationen</h3>
                        <p class="text-gray-600 leading-relaxed">
                        Wenn du deine Produkte einstellst, solltest du unbedingt darauf achten, dass alle wichtigen Felder ausgefüllt sind. 
                        Dazu gehören unter anderem der Produktname, der Preis und eine konkrete Beschreibung (z.B. Marke & Größe). Passende Kategorien und Schlagwörter können die Suche ebenfalls erleichtern. <br>
                        <strong>Beispiel</strong>: „Pullover“+ „Marke“ + „Größe“ + „Preis“

                        </p>
                    </div>
                </div>
                <!-- Hinweis 2 -->
                <div class="mb-6 flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full  bg-[#65765f] text-white font-bold mr-4">2</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Bilder verwenden</h3>
                        <p class="text-gray-600 leading-relaxed">
                        Um deinen potenziellen Käufern vorab schon ein Eindruck deiner Produkte zu gewähren, hast du die Möglichkeit Bilder von deinen Artikeln hochzuladen.<br> Kleiner Tipp: Achte bei deinen Bildern auf gute Lichtverhältnisse und eine klare Darstellung. 
                        </p>
                    </div>
                </div>
                <!-- Hinweis 4 -->
                <div class="flex items-start">
                    <span class="inline-flex items-center justify-center h-10 aspect-square rounded-full  bg-[#65765f] text-white font-bold mr-4">3</span>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Preisangabe und Provision beachten</h3>
                        <p class="text-gray-600 leading-relaxed">
                        Der Preis ist oft der entscheidende Faktor für Käufer. Daher solltest du sicherstellen, dass dein Preis sowohl attraktiv als auch realistisch ist. <br>
                        <strong>Wichtig</strong>: Bei MiniFinds gehen 16% Provision pro verkauftem Produkt an uns. Diese Provision deckt unsere Leistung ab, dein Produkt optimal zu inserieren und lokal erfolgreich zu verkaufen. Durch unsere Unterstützung wird dein Produkt nicht nur online sichtbar, sondern auch im Markt vor Ort, wo es von vielen Käufern entdeckt werden kann. <br>

                        </p>
                    </div>
                </div>
            </div>
        </div>
</div>