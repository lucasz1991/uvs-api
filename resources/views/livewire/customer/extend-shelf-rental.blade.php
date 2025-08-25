<div class="pt-3 md:pt-12 bg-[#f8f2e8f2] antialiased" wire:loading.class="cursor-wait pointer-events-none"
    x-data="{
        step: 1,
        showPaymentForm: false,
        authorizationID: @entangle('authorizationID')
        }">

    <x-slot name="header">
        <h2 class="text-xl font-semibold mb-4">Regalmiete verlängern</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-5 pb-12">
        <div class="bg-white shadow-lg rounded-lg p-6 md:p-10">

            <!-- Fortschrittsanzeige -->
            <div class="mb-6 flex items-center">
                <div class="flex items-center space-x-4">
                    <span class="w-8 h-8 flex items-center justify-center rounded-full"
                        :class="step == 1 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'">1</span>
                    <span class="w-8 h-8 flex items-center justify-center rounded-full"
                        :class="step == 2 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'">2</span>
                </div>
            </div>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-800 p-4 rounded mb-6">
                <p class="font-semibold">Hinweis zur Verlängerung der Regalmiete</p>
                <p class="text-sm mt-1">
                    Sollte eine Verlängerung nicht verfügbar sein, können Sie ein neues Regal buchen. 
                    Ihre vorhandenen Produkte können dann in die neue Regalmiete übertragen werden.
                </p>
            </div>
            <!-- Regalmiete anzeigen -->
            <div class="bg-gray-100 p-4 rounded">
                <p class="font-medium">Regal #{{ $shelfRental->shelf->floor_number ?? 'Unbekannt' }}</p>
                <p class="text-gray-600 text-sm">Aktuelles Enddatum: {{ \Carbon\Carbon::parse($shelfRental->rental_end)->format('d.m.Y') }}</p>
            </div>

            <!-- Verlängerungsauswahl (Schritt 1) -->
            <div x-show="step === 1" x-cloak  x-collapse.duration.1000ms>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($availableExtensions as $days)
                        @php
                            $validDate = \Carbon\Carbon::parse($shelfRental->rental_end);
                            $addedDays = 0;
                            
                            while ($addedDays < $days) {
                                $validDate->addDay();
                                if (!in_array($validDate->format('Y-m-d'), $publicHolidays) && !$validDate->isSunday()) {
                                    $addedDays++;
                                }
                            }

                            $isAvailable = in_array($days, $validExtensions);

                            $priceMapping = [
                                7 => '26,00 €',
                                14 => '46,00 €',
                                21 => '66,00 €',
                                28 => '85,00 €',
                            ];
                            
                            $price = $priceMapping[$days] ?? 'Preis nicht verfügbar';
                        @endphp

                        <div class="bg-white shadow-lg rounded-3xl p-6 text-center 
                                    {{ !$isAvailable ? 'opacity-50 grayscale pointer-events-none' : '' }}
                                    {{ $days == $this->period ? 'border-4 border-blue-500' : 'border border-gray-200' }}">
                            <div class="justify-center items-baseline my-5">
                                <h4 class="text-gray-800 font-semibold text-5xl">{{ $days }}</h4>
                                <span class="text-md">Tage</span>
                            </div>
                            <h3 class="text-gray-800 font-semibold text-2xl mt-4">{{ $price }}</h3>
                            
                            <div class="mt-2 text-gray-600 text-sm">
                                Neues Enddatum: <span class="font-semibold">{{ $validDate->format('d.m.Y') }}</span>
                            </div>

                            <button type="button"
                                @click="$wire.setPeriod({{ $days }}, '{{ $validDate->format('Y-m-d') }}'); step = 2; showPaymentForm = true;"
                                class="bg-blue-500 hover:bg-blue-600 text-white w-full mt-6 px-5 py-2 rounded-full transition duration-200 ease-in-out">
                                auswählen
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <script src="https://www.paypal.com/sdk/js?client-id={{ urlencode($this->apiSettings['paypal_api_client_id'] ?? 'default_client_id') }}&currency=EUR&components=buttons&enable-funding=card&disable-funding=venmo,paylater&intent=authorize" ></script>    
            <!-- Bezahlmöglichkeiten (Schritt 2) -->
            <div x-show="step === 2" class="mt-6" x-cloak  x-collapse.duration.1000ms>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Links: Verlängerungsübersicht -->
                    <div class="bg-gray-100 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Verlängerungsdetails</h3>
                        <table class="w-full text-sm">
                            <tr>
                                <td class="font-medium text-gray-700">Regalnummer:</td>
                                <td class="text-gray-900">{{ $shelfRental->shelf->floor_number }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Neuer Mietzeitraum:</td>
                                <td class="text-gray-900">
                                    {{ \Carbon\Carbon::parse($shelfRental->rental_start)->format('d.m.Y') }} -
                                    {{ \Carbon\Carbon::parse($newEndDate)->format('d.m.Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Verlängerungsdauer:</td>
                                <td class="text-gray-900">{{ $this->period }} Tage</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Gesamtpreis:</td>
                                <td class="text-gray-900 font-semibold">
                                    {{ number_format($this->finalPrice, 2, ',', '.') }} €
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Rechts: PayPal-Zahlung -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 x-show="!authorizationID" class="text-lg font-semibold text-gray-800 mb-4">Bezahlmöglichkeiten</h3>
                        <!-- Anzeige des grünen Häkchens, wenn Zahlung autorisiert -->
                        <div x-show="authorizationID" x-cloak  x-collapse.duration.1000ms class="inline-flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 4.293a1 1 0 010 1.414L8 14.414 3.293 9.707a1 1 0 111.414-1.414L8 11.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Zahlung Autorisiert </span>
                        </div>
                        <div class="space-y-4" x-data="{
                            initPaypal() {
                                const paypalContainer = document.getElementById('paypal-button-container');
                                if (paypalContainer) {
                                    paypalContainer.innerHTML = ''; 
                                }
                                window.paypal.Buttons({
                                    style: {
                                        shape: 'rect',
                                        layout: 'vertical',
                                        color: 'gold',
                                        label: 'paypal',
                                    },
                                    async createOrder() {
                                        try {
                                            const response = await fetch('{{ route('paypal.createOrder') }}', {
                                                method: 'POST',
                                                headers: { 
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                },
                                                body: JSON.stringify({
                                                    cart: [
                                                        {
                                                            id: '01',
                                                            name: 'Regalmiete verlängern',
                                                            quantity: '1',                          
                                                        }
                                                    ],
                                                    totalPrice: $wire.get('finalPrice'),
                                                }),
                                            });
                                            const orderData = await response.json();
                                            if (orderData.id) {
                                                return orderData.id;
                                            }
                                            throw new Error(orderData.message || 'Unbekannter Fehler');
                                        } catch (error) {
                                            console.error('Fehler beim Erstellen der Bestellung:', error);
                                            alert('Fehler beim Erstellen der Bestellung. Bitte versuche es erneut.');
                                        }
                                    },
                                    async onApprove(data, actions) {
                                        try {
                                            const authorizeResponse = await fetch('{{ route('paypal.authorizeOrder', ':orderID') }}'.replace(':orderID', data.orderID),{
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                                                },
                                            });

                                            const orderData = await authorizeResponse.json();
                                            const transaction =
                                                orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
                                                orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
                                            const errorDetail = orderData?.details?.[0];

                                            if (!transaction || transaction.status === 'DECLINED') {
                                                let errorMessage;
                                                if (transaction) {
                                                    errorMessage = `Transaction ${transaction.status}: ${transaction.id}`;
                                                } else if (errorDetail) {
                                                    errorMessage = `${errorDetail.description} (${orderData.debug_id})`;
                                                } else {
                                                    errorMessage = JSON.stringify(orderData);
                                                }
                                                console.log('errorMessage:',errorMessage);
                                                throw new Error(errorMessage);
                                            } else {
                                                $wire.set('authorizationID', orderData['purchase_units'][0]['payments']['authorizations'][0]['id'] ?? null); 
                                                console.log('Zahlung erfolgreich autorisiert!',orderData['purchase_units'][0]['payments']['authorizations'][0]['id'] ?? null);
                                                // PayPal-Button ausblenden, indem wir den Container leeren
                                                const paypalContainer = document.getElementById('paypal-button-container');
                                                if (paypalContainer) {
                                                    paypalContainer.style.display = 'none'; // Button ausblenden
                                                }
                                            }
                                        } catch (error) {
                                            console.error('Fehler bei der Zahlung:', error);
                                            alert('Fehler beim Abschluss der Zahlung. Bitte versuche es erneut.');
                                        }
                                    },
                                }).render('#paypal-button-container');
                            }
                        }"
                        x-init="() => { 
                            $watch('step', value => { 
                                if(value == 2){
                                    setTimeout(() => { 
                                        initPaypal();
                                    }, 300);
                                } 
                            });
                        }">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verlängerung speichern -->
            <div class="mt-10" >
                <!-- AGB und Datenschutz Checkbox -->
                <div class="mt-6">
                    <label for="terms" class="inline-flex  items-center mb-5 cursor-pointer">
                        <input id="terms" name="terms" wire:model="terms" type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-9 h-5 min-w-9 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300  rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all  peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 ">Ich akzeptiere die <a href="/termsandconditions" wire:navigate  class="text-blue-600 hover:underline">AGB's</a> und die <a href="/privacypolicy" wire:navigate  class="text-blue-600 hover:underline">Datenschutzerklärung</a>.</span>
                    </label>
                    <x-input-error for="terms" class="mt-2" />
                </div>
                <!-- Buchung abschließen -->
                <div class="mt-6 flex justify-end">
                    <button type="button" href="/dashboard" wire:navigate   x-data="{ isClicked: false }" 
                            @click="isClicked = true; setTimeout(() => isClicked = false, 100)"
                            :style="isClicked ? 'transform:scale(0.9);' : 'transform:scale(1);'"
                            class="transition-all duration-100 text-gray-500 hover:text-gray-700 mr-4">
                            Abbrechen
                        </button>
                    <button 
                        type="button" 
                        wire:click="finalizePayment" 
                        x-bind:disabled="!authorizationID"
                        wire:loading.attr="disabled"
                        :class="authorizationID ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-4 py-2 text-white rounded-lg transition-all duration-100"
                    >
                        Kostenpflichtig buchen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
