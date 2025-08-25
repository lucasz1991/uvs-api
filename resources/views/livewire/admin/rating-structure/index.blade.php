<div x-data="{ selectedTab: $persist('insurances').using(sessionStorage) }" class="w-full">
    <h1 class="text-xl mb-5 w-max">
        <span class="w-max">Bewertungsstruktur</span>
    </h1>
    <!-- Tab-Menü -->
    <ul class="flex w-max text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-200 rounded-lg shadow divide-gray-200 overflow-hidden">
        <!-- Versicherungen -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'insurances'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'insurances' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Versicherungen
            </button>
        </li>
        <!-- Versicherungstypen -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'types'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'types' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Versicherungstypen
            </button>
        </li>
        <!-- VersicherungsunterTypen -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'subtypes'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'subtypes' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Versicherungszweige
            </button>
        </li>
        <!-- Fragen -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'questions'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'questions' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Fragen
            </button>
        </li>
        <!-- Fragebögen -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'questionnaires'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'questionnaires' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Fragebögen
            </button>
        </li>
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'settings'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'settings' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Einstellungen
            </button>
        </li>
    </ul>
    <!-- Erfolgsmeldung -->
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-4 rounded my-6">
            {{ session('message') }}
        </div>
    @endif
    <!-- Inhalte der Tabs -->
    <div class="mt-6">
        <div x-show="selectedTab === 'insurances'" x-cloak>
            <livewire:admin.rating-structure.insurance-list lazy />
        </div>
        <div x-show="selectedTab === 'types'" x-cloak>
            <livewire:admin.rating-structure.insurance-types.insurance-types-list lazy />
        </div>
        <div x-show="selectedTab === 'subtypes'" x-cloak>
            <livewire:admin.rating-structure.insurance-subtypes.insurance-subtypes-list lazy />
        </div>
        <div x-show="selectedTab === 'questions'" x-cloak>
            <livewire:admin.rating-structure.rating-question.rating-question-list lazy />
        </div>
        <div x-show="selectedTab === 'questionnaires'" x-cloak>
            <livewire:admin.rating-structure.questionnaire.questionnaire-list lazy />
        </div>
        <div x-show="selectedTab === 'settings'" x-cloak>
            <livewire:admin.rating-structure.settings.scoring-config lazy />
            <livewire:admin.rating-structure.settings.ai-scoring-settings lazy />
        </div>
    </div>
</div>
