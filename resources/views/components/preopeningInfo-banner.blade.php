<section class="py-12 bg-gray-100 overflow-hidden"
        x-data="{ shown: false }" 
        x-intersect.once="setTimeout(() => shown = true, 500)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div 
            x-transition
            x-cloak
            :class="{ 'translate-y-0 opacity-100': shown, 'translate-y-[2000px] opacity-0': !shown }" 
            class="mt-10 bg-white shadow p-6 pt-10 text-center transform transition-all duration-500 ease-out" >
            <h3 class="text-lg font-semibold text-gray-800">Bereit für die Eröffnung</h3>
            <p class="mt-2 text-gray-600">
                Wir freuen uns darauf, gemeinsam mit euch am <strong>25.01.2025</strong> zu starten! 
                Für alle, die bereits ein Regal gemietet haben: Ihr könnt eure Regale schon ab dem <strong>22.01.2025</strong> während unserer Öffnungszeiten von <strong>9:00 bis 18:00 Uhr</strong> einräumen. <br>
                Noch kein Regal gebucht?<br> Sichert euch euren Platz und werdet Teil von MiniFinds!
            </p>
            <div class="absolute -top-10 left-2  text-center mt-4 mr-4 h-20 w-20">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 409.11258 409.11283"><defs><style>.a{fill:#00a9fe;}</style></defs><path class="a" d="M206.06879.00543C94.83219-.80007,2.87589,88.06073.06689,199.26463A203.75308,203.75308,0,0,0,27.395,306.87273l-20.1655,75.2594a16.12672,16.12672,0,0,0,19.7512,19.7512l75.2593-20.1657a203.75392,203.75392,0,0,0,107.6123,27.3282c111.2046-2.8113,200.0639-94.771,199.2548-206.0083C408.29439,91.267,317.84009.81493,206.06879.00543Zm-1.5124,86.7674a31.2108,31.2108,0,1,1-31.211,31.2108A31.21065,31.21065,0,0,1,204.55639,86.77283Zm44.5869,232.3452a3.22206,3.22206,0,0,1-3.2222,3.222h-82.7297a3.22208,3.22208,0,0,1-3.222-3.222v-30.7118a3.22206,3.22206,0,0,1,3.222-3.2219h9.1614a3.22216,3.22216,0,0,0,3.2221-3.222v-95.3627a3.22216,3.22216,0,0,0-3.2221-3.222h-9.1614a3.22206,3.22206,0,0,1-3.222-3.2219v-15.1064a3.22208,3.22208,0,0,1,3.222-3.222h67.1242a3.22206,3.22206,0,0,1,3.2222,3.222v116.913a3.222,3.222,0,0,0,3.2219,3.222h9.1614a3.222,3.222,0,0,1,3.2222,3.2219Z"/></svg>
            </div>
        </div>
    </div>
</section>