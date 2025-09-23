<!-- ========== Left Sidebar Start ========== -->
<div class="fixed bottom-0 z-10 h-screen ltr:border-r rtl:border-l vertical-menu rtl:right-0 ltr:left-0 top-[70px] pt-12 bg-slate-50 border-gray-50 print:hidden ">

    <div data-simplebar class="h-full">
        <!--- Sidemenu -->
        <div class="metismenu pb-10 pt-2.5" id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul id="side-menu">
                <li>
                    <a href="{{ url('/') }}" class="block py-2.5 px-6 text-sm font-medium text-gray-600 transition-all duration-150 ease-linear hover:text-blue-500 ">
                        <i data-feather="home" fill="#545a6d33"></i>
                        <span data-key="t-dashboard"> Dashboard</span>
                    </a>
                </li>

                <li class="px-5 py-3 text-xs font-medium text-gray-500 cursor-default leading-[18px] group-data-[sidebar-size=sm]:hidden block" data-key="t-menu">System-Verwaltung</li>


                <li>
                    <a href="{{ route('admin.config') }}"   class="block py-2.5 px-6 text-sm font-medium text-gray-600 transition-all duration-150 ease-linear hover:text-blue-500 ">
                        <i data-feather="settings" fill="#545a6d33"></i>
                        <span data-key="t-config"> Einstellungen</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.activities') }}"   class="block py-2.5 px-6 text-sm font-medium text-gray-600 transition-all duration-150 ease-linear hover:text-blue-500 ">
                        <i data-feather="activity" fill="#545a6d33"></i>
                        <span data-key="t-activities">Aktivitäten</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.logs') }}"   class="block py-2.5 px-6 text-sm font-medium text-gray-600 transition-all duration-150 ease-linear hover:text-blue-500 ">
                        <i data-feather="activity" fill="#545a6d33"></i>
                        <span data-key="t-activities">Logs</span>
                    </a>
                </li>
                <li class="px-5 py-3 text-xs font-medium text-gray-500 cursor-default leading-[18px] group-data-[sidebar-size=sm]:hidden block" data-key="t-menu">Management</li>

                <li>
                    <a href="{{ route('admin.users') }}"   class="block py-2.5 px-6 text-sm font-medium text-gray-600 transition-all duration-150 ease-linear hover:text-blue-500 ">
                        <i data-feather="users" fill="#545a6d33"></i>
                        <span data-key="t-users">Benutzer</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->