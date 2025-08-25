<div class="pb-8 pt-3 md:py-12 bg-[#f8f2e8f2] antialiased" wire:loading.class="cursor-wait">
    <x-slot name="header">
        <h1 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center">
            Jobs bei MiniFinds
            
            <svg width="80px" class="transform scale-[3]  aspect-square text-[#333] ml-6 inline opacity-10"  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                           <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                                                        </svg>
        </h1>
    </x-slot>
    <div class="max-w-7xl mx-auto px-5">
        <div class="bg-white shadow-lg rounded-lg p-6 md:p-10">

            <div class="text-lg text-gray-700 mb-8">
                <p><strong>Wir bei MiniFinds sind bereit für den großen Start!</strong></p>
                <p>Am 25. Januar 2024 ist es endlich soweit: MiniFinds öffnet seine Türen und präsentiert eine neue Art des Einkaufens. Wir bieten unseren Kunden die Möglichkeit, einzigartige Produkte aus einer Vielzahl von Kategorien zu entdecken und zu kaufen – und das sowohl vor Ort als auch online. Unsere Mission ist es, einen Ort zu schaffen, an dem Verkäufer und Käufer gleichermaßen von einer innovativen Plattform profitieren können.</p>
                
                <p><strong>Werde Teil unseres Teams!</strong></p>
                <p>Mit der bevorstehenden Eröffnung stehen wir vor spannenden Herausforderungen, und deshalb suchen wir nach motivierten, engagierten Teammitgliedern, die unser Wachstum mitgestalten wollen. Ob in der Kundenbetreuung, im Verkauf, in der Technik oder in der Logistik – wir können in jedem Bereich Verstärkung gebrauchen. Du hast eine Leidenschaft für außergewöhnliche Produkte und möchtest in einem dynamischen Umfeld arbeiten? Dann bist du bei uns genau richtig!</p>
                
                <p><strong>Gemeinsam in die Zukunft!</strong></p>
                <p>Wir sind ein junges, dynamisches Team und bieten dir nicht nur die Chance, Teil einer aufregenden Reise zu werden, sondern auch ein Umfeld, das Kreativität und Innovation fördert. Wenn du Lust hast, mit uns zusammen MiniFinds groß zu machen und unser Kundenservice auf das nächste Level zu heben, dann freuen wir uns auf deine Bewerbung. Werde Teil von MiniFinds und starte mit uns durch!</p>
            </div>

            <!-- Bewerbungsformular -->
            <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Bewirb dich jetzt!</h2>
                @if (session()->has('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif   
                <form wire:submit.prevent="submitApplication">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Dein Name</label>
                            <input type="text" id="name" wire:model="name" class="mt-1 p-2 w-full border rounded-md" placeholder="Max Mustermann" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Deine E-Mail</label>
                            <input type="email" id="email" wire:model="email" class="mt-1 p-2 w-full border rounded-md" placeholder="name@beispiel.de" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Stelle dich vor</label>
                        <textarea id="message" wire:model="message" class="mt-1 p-2 w-full border rounded-md" rows="4" placeholder="Warum möchtest du Teil von MiniFinds werden?" required></textarea>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-5 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Bewerbung absenden
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <x-features-banner />
</div>
