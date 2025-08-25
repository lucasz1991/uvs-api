<div class="" wire:loading.class="cursor-wait">
        <div class="">
            <div class="">
                <!-- Content -->
                <div class="mt-2">
                    <!-- State cards -->
                    <div class="grid grid-cols-1 gap-4  lg:grid-cols-2 xl:grid-cols-4 mb-4">
                        <!-- Neue Buchungen diesen Monat -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-md border border-gray-300 shadow-md">
                            <div>
                                <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase mb-3">
                                    Neue Bewertungen diesen Monat
                                </h6>
                                <span class="text-xl font-semibold"></span>
                            </div>
                            <div>
                                <span>
                                    <svg class="w-12 h-12 text-blue-300 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- Gesamte Verkäufe diesen Monat -->
                        <div class="flex items-center justify-between p-4 bg-white rounded-md border border-gray-300 shadow-md">
                            <div>
                                <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase  mb-3">
                                    Bewertungen insgesamt diesen Monat
                                </h6>
                                <span class="text-xl font-semibold"></span>
                            </div>
                            <div>
                                <span>
                                    <svg class="w-12 h-12 text-blue-300 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- Gesamte Benutzer -->
                        <div class="flex items-center justify-between p-4 bg-white   rounded-md border border-gray-300 shadow-md">
                            <div>
                                <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase  mb-3">
                                    Gesamte Benutzer
                                </h6>
                                <span class="text-xl font-semibold">{{ $totalUsers }}</span>
                            </div>
                            <div>
                                <span>
                                    <svg class="w-12 h-12 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- Neue Produkte diesen Monat -->
                        <div class="flex items-center justify-between p-4 bg-white   rounded-md border border-gray-300 shadow-md">
                            <div>
                                <h6 class="text-xs font-medium leading-none tracking-wider text-gray-500 uppercase  mb-3">
                                    Neue Bewertungen diesen Monat
                                </h6>
                                <span class="text-xl font-semibold"></span>
                            </div>
                            <div>
                                <span>
                                    <svg class="w-12 h-12 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-white rounded-md border border-gray-300 shadow-md">
                                <div class="relative p-4">
                                    <p class="font-semibold text-lg">Aktive Nutzer</p>
                                    <livewire:admin.charts.active-users  :height="250"/>
                                </div>
                            </div>
                            <div class="bg-white rounded-md border border-gray-300 shadow-md">
                                <div class="relative p-4">
                                    <p class="font-semibold text-lg">Bewertungen</p>
                                </div>
                            </div>
                            <div class="bg-white rounded-md border border-gray-300 shadow-md">
                                <div class="relative p-4">
                                    <p class="font-semibold text-lg">Einnahmen (€)</p>
                                </div>
                            </div>
                            <div class="bg-white rounded-md border border-gray-300 shadow-md">
                                <div class="relative p-4">
                                    <p class="font-semibold text-lg">Partner Aktivitäten</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

