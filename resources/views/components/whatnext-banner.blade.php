<section class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800">So geht’s weiter </h2>
        </div>
        <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center bg-white shadow p-6">
                <img src="{{ asset('site-images/whatnext3.jpg') }}" alt="Bequem" class="mx-auto w-full mb-4 aspect-[4/3] object-cover">
                <h3 class="text-lg font-semibold text-gray-800">Produkte anlegen 
                </h3>
                <p class="mt-2 text-gray-600">Logge dich in deinen MiniFinds Account ein und füge deine Produkte hinzu. 
                </p>
            </div>
            <div class="text-center bg-white shadow p-6">
                <img src="{{ asset('site-images/whatnext2.jpg') }}" alt="Nachhaltig" class="mx-auto w-full mb-4 aspect-[4/3] object-cover">
                <h3 class="text-lg font-semibold text-gray-800">Regal einräumen </h3>
                <p class="mt-2 text-gray-600">Komm zu uns in den Laden, lass dir deine Etiketten ausdrucken und hänge deine Artikel in dein Regal. Bügel, Etikettiermaschinen und Warensicherungen stellen wir dir bereit. 
                </p>
            </div>
            <div class="text-center bg-white shadow p-6">
                <img src="{{ asset('site-images/whatnext1.jpg') }}" alt="Transparent" class="mx-auto w-full mb-4 aspect-[4/3] object-cover object-top">
                <h3 class="text-lg font-semibold text-gray-800">Verkaufen </h3>
                <p class="mt-2 text-gray-600">
                    Verfolge deine Verkäufe über dein Profil. Erhalte nach Mietzeitende deinen Erlös, 
                    abzüglich einer <span class="mt-1 px-1 py-0.5 bg-yellow-100 border border-yellow-400  text-yellow-700 rounded">16 % Verkaufsprovision</span>
                </p>
            </div>
        </div>
        <div class="text-center mt-8">
            <x-button  href="/howto" wire:navigate >
                So funktioniert's
            </x-button>
        </div>
    </div>
</section>