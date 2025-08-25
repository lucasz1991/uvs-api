<div class="pt-3 md:pt-12 bg-[#f8f2e8f2] antialiased" wire:loading.class="cursor-wait">
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
            Über uns
            <svg width="80px" class="transform scale-[3]  aspect-square text-[#333] ml-6 inline opacity-10"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
            
        </h1>
    </x-slot>

    <div class="max-w-7xl mx-auto px-5 pb-12">
        <div class="bg-white shadow-lg rounded-lg p-6 md:p-10 max-md:space-y-8 md:space-x-8 md:flex">
            <!-- Text Section -->
            <div class="space-y-4 w-full md:w-4/6">
                <p class="text-gray-600 leading-relaxed">
                    Wir sind die drei Gesichter hinter MiniFinds: Carrie, Andrea und Joana. Wir selbst sind Mütter bzw. Oma und erfreuen uns sehr daran, unseren Kindern/Enkelkindern schöne Kleidung zu kaufen oder die leuchtenden Augen zu sehen, wenn ein neues Buch oder Spielzeug im Kinderzimmer aufgetaucht ist.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Beim Kauf von Kinderkleidung, -zubehör und Spielzeugen ist man schnell viel Geld los. Deshalb haben wir uns die Frage gestellt, ob es wirklich immer neu sein muss!?
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Mit MiniFinds haben wir ein Herzensprojekt ins Leben gerufen, das den Geldbeutel schont und eine nachhaltige Alternative bietet, um anderen Familien mit neuen, gebrauchten Schätzen eine Freude zu bereiten und gleichzeitig dafür sorgt, dass sich Kellerinhalt reduziert und Platz für Neues geschaffen wird.
                </p>
            </div>

            <!-- Image Section (Right-aligned on md+) -->
            <div class="mt-8  md:mt-0 w-full md:w-2/6 flex justify-center">
                <div class="w-full rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('site-images/about_us.jpg') }}" alt="Team Photo" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </div>

    <!-- Features Banner -->
    <x-features-banner />
</div>
