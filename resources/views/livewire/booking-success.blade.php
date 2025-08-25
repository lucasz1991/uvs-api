<div class="w-full relative bg-cover bg-center backgroundimageOverlay bg-[#f8f2e8f2] pt-20"  wire:loading.class="cursor-wait">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="grow-in-container">
            <div class="p-6 bg-white rounded shadow mb-12">
                <div class="flex items-center justify-start mb-6">
                    <div 
                        class="w-10  aspect-square flex items-center justify-center bg-green-500 text-white rounded-full animate-scale-bounce"
                        style="animation-duration: 1s; animation-timing-function: ease-out;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 ml-4">Buchung erfolgreich abgeschlossen!</h2>
                </div>
                
                <p class="mb-6 text-gray-600">
                        Vielen Dank, dass du dich für MiniFinds entschieden hast. Deine Buchung wurde erfolgreich abgeschlossen! 
                        Wir haben dir eine Bestätigungs-E-Mail mit den Details deiner Buchung zugesandt. Bitte überprüfe dein Postfach 
                        (ggf. auch den Spam-Ordner), um sicherzustellen, dass du alle wichtigen Informationen erhalten hast.
                    </p>
                
                <div id="a" class="p-4 mb-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 " role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Hinweis</span>
                        <h3 class="text-lg font-medium">Hinweis:</h3>
                    </div>
                    <div class="mt-2 mb-4 text-sm">
                        Stelle sicher, dass alle eingepflegten Produkte den Verkaufsbedingungen von MiniFinds entsprechen, 
                        um den Verkaufserfolg zu maximieren. Solltest du Fragen haben, stehen wir dir gerne zur Verfügung.
                    </div>
                </div>
                
    
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700">Buchungsdetails:</h3>
                    <ul class="mt-4 space-y-2">
                        <li>
                            <strong>Zeitraum: </strong>{{ \Carbon\Carbon::parse($shelfRental->rental_start)->format('d.m.Y') }} - 
                            {{ \Carbon\Carbon::parse($shelfRental->rental_end)->format('d.m.Y') }}
                        </li>
                        <li>
                            <strong>Regalnummer:</strong> {{ $shelfRental->shelf->floor_number }}
                        </li>
                        <li>
                            <strong>Preis:</strong> {{ number_format($shelfRental->total_price, 2, ',', '.') }} €
                        </li>
                    </ul>
                </div>
                @auth
                <div class="text-center mb-6">
                    <div class="flex">
                        <a  href="{{ route('dashboard') }}" wire:navigate  class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                            <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                        </svg>
                        Mein Konto
                        </a>
                        <a  href="{{ route('shelfrental.show', ['shelfRentalId' => $shelfRental->id]) }}" wire:navigate  class="text-blue-800 bg-transparent border border-blue-800 hover:bg-blue-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-blue-600 dark:border-blue-600 dark:text-blue-400 dark:hover:text-white dark:focus:ring-blue-800" data-dismiss-target="#alert-additional-content-1" aria-label="Close">
                        Jetzt Produkte hinzufügen
                        </a>
                    </div>
                    
                </div>
                @else
                <div  class="p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 " role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Erfolg</span>
                        <h3 class="text-lg font-medium">Erfolg:</h3>
                    </div>
                    <div class="mt-2 mb-4 text-sm">
    Du hast ein Regal ohne Benutzerkonto gebucht. Wir haben dir eine E-Mail geschickt, um dein Passwort zurückzusetzen, damit du dich in deinem neuen Account anmelden kannst. Bitte überprüfe deinen Posteingang und folge den Anweisungen.
</div>
                </div>
                @endauth
            </div>
        </div>
    </div>


           <!-- Features Section -->
           <section class="py-12 bg-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-800">Warum MiniFinds?</h2>
                        <p class="mt-4 text-gray-600">Entdecke die Vorteile unseres Marktplatzes</p>
                    </div>
                    <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center bg-white shadow p-6">
                            <img src="{{ asset('site-images/1.jpg') }}" alt="Bequem" class="mx-auto w-full mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Bequem und stressfrei</h3>
                            <p class="mt-2 text-gray-600">Bring deine Artikel vorbei und richte dein Regal ein. Um den Rest kümmern wir uns.</p>
                        </div>
                        <div class="text-center bg-white shadow p-6">
                            <img src="{{ asset('site-images/3.jpg') }}" alt="Nachhaltig" class="mx-auto w-full mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Nachhaltig</h3>
                            <p class="mt-2 text-gray-600">Gib gut erhaltener Kinderkleidung und Spielzeug eine neue Chance.</p>
                        </div>
                        <div class="text-center bg-white shadow p-6">
                            <img src="{{ asset('site-images/2.jpg') }}" alt="Transparent" class="mx-auto w-full mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Transparent</h3>
                            <p class="mt-2 text-gray-600">Behalte jederzeit den Überblick über deine Verkäufe und Einnahmen.</p>
                        </div>
                    </div>
                </div>
            </section>
        <!-- Features Section END-->
</div>

