<nav x-data="" class="h-full"  wire:loading.class="cursor-wait">
    <div class="relative flex flex-col h-full">
        <!-- Logo -->
        <div class="shrink-0 flex items-center py-2 mb-6 h-10 mt-[50px] 2xl:mt-[0px]" >
            <a href="{{ route('home') }}" wire:navigate class="flex items-center h-10">
                <x-application-mark class="h-10 w-auto" />
            </a>
        </div>
        <hr class="my-6"  />
        <!-- Navigation Links -->
        <div>
            <h4 class="text-sm text-gray-400 mb-4">System-Verwaltung</h4>
            <ul class="space-y-4 px-2 flex-1">
                <!-- Settings -->
                <li>
                    <a href="{{ route('admin.config') }}"  wire:navigate class="{{ request()->routeIs('admin.config') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Einstellungen</span>
                    </a>
                </li>
                <!-- Sicherheit -->
                <li>
                    <a href="{{ route('admin.safety') }}" wire:navigate class="{{ request()->routeIs('admin.safety') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span >Sicherheit & Aktivitäten</span>
                    </a>
                </li>
                <!-- Exporte -->
                <li>
                    <a href="{{ route('admin.exports') }}" wire:navigate class="{{ request()->routeIs('admin.exports') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Exporte</span>
                    </a>
                </li>
                <!-- Mitarbeiter -->
                <li>
                    <a href="{{ route('admin.employees') }}" wire:navigate class="{{ request()->routeIs('admin.employees') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Mitarbeiter</span>
                    </a>
                </li>
                <!-- Web Inhalte -->
                <li>
                    <a href="{{ route('admin.webcontentmanager') }}" wire:navigate class="{{ request()->routeIs('admin.webcontentmanager') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Web Inhalte</span>
                    </a>
                </li>
                <!-- Standorte -->
                <li>
                    <a href="{{ route('admin.locations') }}" wire:navigate  class="{{ request()->routeIs('admin.locations') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all" >
                        <span >Standorte</span>
                    </a>
                </li>
                <!-- Gutscheine und Boni -->
                <li>
                    <a href="{{ route('admin.couponboni') }}" wire:navigate  class="{{ request()->routeIs('admin.couponboni') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Gutscheine & Boni</span>
                    </a>
                </li>
            </ul>
            <h4 class="text-sm text-gray-400 my-4">Shop Management</h4>
            <ul class="space-y-4 px-2 flex-1">
                <!-- Benutzerverwaltung -->
                <li>
                    <a href="{{ route('admin.mails') }}" wire:navigate  class="{{ request()->routeIs('admin.mails') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Mails</span>
                    </a>
                </li>
                <!-- Benutzer -->
                <li>
                    <a href="{{ route('admin.users') }}" wire:navigate  class="{{ request()->routeIs('admin.users') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Benutzer</span>
                    </a>
                </li>
                <!-- Regalbuchungen -->
                <li>
                    <a href="{{ route('admin.shelfrentals') }}" wire:navigate  class="{{ request()->routeIs('admin.shelfrentals') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Regalbuchungen</span>
                    </a>
                </li>
                <!-- Produkte -->
                <li>
                    <a href="{{ route('admin.products') }}" wire:navigate  class="{{ request()->routeIs('admin.products') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Produkte</span>
                    </a>
                </li>
                <!-- Verkäufe -->
                <li>
                    <a href="{{ route('admin.sales') }}" wire:navigate  class="{{ request()->routeIs('admin.sales') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all">
                        <span>Verkäufe</span>
                    </a>
                </li>
                <!-- Aufgaben -->
                <li>
                    <a href="{{ route('admin.tasks') }}" wire:navigate class="{{ request()->routeIs('admin.tasks') ? 'text-blue-600' : 'text-[#333]' }} text-sm flex items-center hover:text-blue-600 transition-all relative w-max pr-4">
                        <span>Aufgaben</span>
                        @php
                            $unassignedTasksCount = \App\Models\AdminTask::whereNull('assigned_to')->count();
                        @endphp
                        @if($unassignedTasksCount > 0)
                            <span class="relative ml-2 flex justify-center align-middle   rounded-full bg-red-500 px-1.5 py-0  text-white">
                                <div class="animate-ping absolute h-full w-full rounded-full bg-red-500 z-9"></div>
                                <div class="z-10">
                                    {{ $unassignedTasksCount }}
                                </div>
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>
