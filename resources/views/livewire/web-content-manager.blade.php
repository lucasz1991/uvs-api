<div x-data="{ selectedTab: $persist('webpages').using(sessionStorage) }" class="w-full">
    <!-- Tab-Menü -->
    <ul class="flex w-max text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-200 rounded-lg shadow divide-gray-200 overflow-hidden">
        <!-- webpages Tab -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'webpages'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'webpages' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Seiten 
            </button>
        </li>
        <!-- Module Tab -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'module'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'module' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Module
            </button>
        </li>
        <!-- FAQ Tab -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'faq'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'faq' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                FAQ's
            </button>
        </li>
        <!-- Ai Assist -->
        <li class="border-l border-gray-200">
            <button 
                @click="selectedTab = 'tools'" 
                :class="{ 'text-blue-600 bg-white border-b-2 border-blue-600': selectedTab === 'tools' }" 
                class="w-full px-4 py-2 transition-all duration-200 bg-gray-100 hover:bg-blue-100 hover:text-blue-600 focus:outline-none"
            >
                Tool's
            </button>
        </li>
    </ul>
    <!-- Erfolgsmeldung -->
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-4 rounded my-6">
            {{ session('message') }}
        </div>
    @endif
    <!-- Inhalt der Tabs -->
    <div class="mt-6">
        <div x-show="selectedTab === 'webpages'" x-cloak>
            <livewire:admin.cms.webpages.webpages-list lazy />
        </div>
        <!-- Module Inhalt -->
        <div x-show="selectedTab === 'module'" x-cloak>
            <livewire:admin.cms.projekt-list  />
        </div>
        <!-- FAQ Inhalt -->
        <div x-show="selectedTab === 'faq'" x-cloak>
            <livewire:admin.cms.web-content.faq-list lazy />
        </div>
        <!-- tools Inhalt -->
        <div x-show="selectedTab === 'tools'" x-cloak>
            <h1 class=" text-lg px-2 py-1 w-max mb-10">
                <span class="w-max">Tool's</span>
            </h1>
            <div class="space-y-5">
                <livewire:admin.cms.tools.ai-assistant-config lazy />
            </div>
        </div>
    </div>
</div>
