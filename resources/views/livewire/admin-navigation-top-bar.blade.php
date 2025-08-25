<div  wire:loading.class="cursor-wait">
    <header class="z-50 bg-[#f7f6f9]  top-0 pt-4">
            <div class="flex flex-wrap items-center px-6 py-2 bg-white shadow-md min-h-[56px] rounded-md w-full relative tracking-wide w-full">
                <div class="flex items-center flex-wrap gap-x-8 gap-y-4 z-50 w-full">

                    <!-- Toggle Button (relativ zur Sidebar positioniert, sichtbar bei kleineren Bildschirmen) -->
                    <div class=" z-50 text-gray-600  burger-container {{ session('opensidebar') ?? 'open' }}" :class="{ 'open': opensidebar }"   @click="toggleSidebar">
                        <div class="burger-bar bar1"></div>
                        <div class="burger-bar bar2"></div>
                        <div class="burger-bar bar3"></div>
                    </div>


                    <!--<div class="flex items-center gap-4 py-1 outline-none border-none  ml-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="w-5 cursor-pointer fill-current">
                            <path d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z">
                            </path>
                        </svg>
                        <input type="text" placeholder="suchst du nach etwas?" class="w-full text-sm bg-transparent rounded outline-none">
                    </div> -->
                    <div class="flex items-center gap-8 ml-auto">
                        <div class="flex items-center space-x-6">
                            <!-- Home Buttons -->
                            @if (!request()->routeIs('admin.dashboard'))
                                <a href="{{ route('admin.dashboard') }}"  wire:navigate >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 cursor-pointer fill-[#333] hover:fill-[#077bff]" viewBox="0 0 511 511.999">
                                    <path d="M498.7 222.695c-.016-.011-.028-.027-.04-.039L289.805 13.81C280.902 4.902 269.066 0 256.477 0c-12.59 0-24.426 4.902-33.332 13.809L14.398 222.55c-.07.07-.144.144-.21.215-18.282 18.386-18.25 48.218.09 66.558 8.378 8.383 19.44 13.235 31.273 13.746.484.047.969.07 1.457.07h8.32v153.696c0 30.418 24.75 55.164 55.168 55.164h81.711c8.285 0 15-6.719 15-15V376.5c0-13.879 11.293-25.168 25.172-25.168h48.195c13.88 0 25.168 11.29 25.168 25.168V497c0 8.281 6.715 15 15 15h81.711c30.422 0 55.168-24.746 55.168-55.164V303.14h7.719c12.586 0 24.422-4.903 33.332-13.813 18.36-18.367 18.367-48.254.027-66.633zm-21.243 45.422a17.03 17.03 0 0 1-12.117 5.024h-22.72c-8.285 0-15 6.714-15 15v168.695c0 13.875-11.289 25.164-25.168 25.164h-66.71V376.5c0-30.418-24.747-55.168-55.169-55.168H232.38c-30.422 0-55.172 24.75-55.172 55.168V482h-66.71c-13.876 0-25.169-11.29-25.169-25.164V288.14c0-8.286-6.715-15-15-15H48a13.9 13.9 0 0 0-.703-.032c-4.469-.078-8.66-1.851-11.8-4.996-6.68-6.68-6.68-17.55 0-24.234.003 0 .003-.004.007-.008l.012-.012L244.363 35.02A17.003 17.003 0 0 1 256.477 30c4.574 0 8.875 1.781 12.113 5.02l208.8 208.796.098.094c6.645 6.692 6.633 17.54-.031 24.207zm0 0" data-original="#000000"></path>
                                </svg>
                                </a>
                            @endif
                            <!-- Inbox Button -->
                        <a href="{{ route('admin.messages') }}"  wire:navigate  class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25px" class="fill-[#333] hover:fill-[#077bff] inline" viewBox="0 0 512 512" stroke-width="4">
                                <g>
                                    <g>
                                        <g>
                                            <g>
                                                <path d="M479.568,412.096H33.987c-15,0-27.209-12.209-27.209-27.209V130.003c0-15,12.209-27.209,27.209-27.209h445.581      
                                                c15,0,27.209,12.209,27.209,27.209v255C506.661,399.886,494.568,412.096,479.568,412.096z 
                                                M33.987,114.189      
                                                c-8.721,0-15.814,7.093-15.814,15.814v255c0,8.721,7.093,15.814,15.814,15.814h445.581c8.721,0,15.814-7.093,15.814-15.814v-255      
                                                c0-8.721-7.093-15.814-15.814-15.814C479.568,114.189,33.987,114.189,33.987,114.189z"/>
                                            </g>
                                            <g>
                                                <path d="M256.894,300.933c-5.93,0-11.86-1.977-16.744-5.93l-41.977-33.14L16.313,118.491c-2.442-1.977-2.907-5.581-0.93-8.023      
                                                c1.977-2.442,5.581-2.907,8.023-0.93l181.86,143.372l42.093,33.14c5.698,4.535,13.721,4.535,19.535,0l41.977-33.14      
                                                l181.628-143.372c2.442-1.977,6.047-1.512,8.023,0.93c1.977-2.442,1.512,6.047-0.93,8.023l-181.86,143.372l-41.977,33.14      
                                                C268.755,299.072,262.708,300.933,256.894,300.933z"/>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            @if(count(Auth::user()->receivedUnreadMessages) >= 1)
                            <span class="absolute right-[-9px] -ml-1 top-[-5px] rounded-full bg-red-500 px-1 py-0 text-xs text-white">{{count(Auth::user()->receivedUnreadMessages)}}</span>
                            @endif
                        </a>
                        </div>

                        <!-- Profile Dropdown -->
                        <x-dropdown align="" width="48">
                            <x-slot name="trigger">
                                <button class="flex h-10 w-10 items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && optional(Auth::user()->currentTeam)->name === 'Super Admins')
                                    <div class="border-t border-gray-200"></div>
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Teams') }}
                                    </div>
                                    <x-dropdown-link href="{{ route('teams.show', optional(Auth::user()->currentTeam)->id) }}">
                                        {{ __('Team verwalten') }}
                                    </x-dropdown-link>
                                @endif
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    Profil
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        Abmelden
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>

                    </div>
                </div>
            </div>
        </header>
</div>
