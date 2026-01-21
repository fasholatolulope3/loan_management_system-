@props(['active'])

<aside x-data="{ mobileMenuOpen: false, collapsed: false }" class="relative">
    <!-- Mobile Toggle Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 bg-indigo-600 text-white rounded-md shadow-lg">
            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
            <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar Container -->
    <div :class="{ 'translate-x-0': mobileMenuOpen, '-translate-x-full': !mobileMenuOpen, 'w-64': !collapsed, 'w-20': collapsed }"
        class="fixed inset-y-0 left-0 z-40 bg-slate-900 text-slate-300 transition-all duration-300 transform lg:translate-x-0 lg:static lg:inset-0 h-screen overflow-y-auto flex flex-col">
        <!-- Logo Section -->
        <div class="flex items-center justify-between h-16 px-6 bg-slate-950">
            <span x-show="!collapsed" class="text-xl font-bold text-white tracking-wider">LOAN<span
                    class="text-indigo-500">SYS</span></span>
            <button @click="collapsed = !collapsed" class="hidden lg:block text-slate-400 hover:text-white">
                <svg :class="collapsed ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-2">

            <!-- Shared: Dashboard -->
            <x-sidebar-item icon="home" route="dashboard" :active="request()->routeIs('dashboard')">Dashboard</x-sidebar-item>

          
            <!-- ROLE: ADMIN -->
            @if (auth()->user()->role === 'admin')
                <div x-show="!collapsed" class="text-xs font-semibold text-slate-500 uppercase mt-6 mb-2 px-2">
                    Administration</div>
                <x-sidebar-item icon="users" route="users.index" :active="request()->routeIs('users.*')">Users Management</x-sidebar-item>
                <x-sidebar-item icon="briefcase" route="loan-products.index" :active="request()->routeIs('loan-products.*')">Loan
                    Products</x-sidebar-item>

                <!-- CHANGE icon="document-report" TO icon="chart-bar" -->
                <x-sidebar-dropdown icon="chart-bar" label="Loans" :active="request()->routeIs('loans.*')">
                    <x-sidebar-item route="loans.index" :active="request()->routeIs('loans.index')" sub-item>All Loans</x-sidebar-item>
                    <x-sidebar-item route="loans.index" :active="request()->is('loans?status=pending')" sub-item>Pending Approvals</x-sidebar-item>
                </x-sidebar-dropdown>

                <x-sidebar-item icon="cog" route="settings" :active="request()->routeIs('settings')">Settings</x-sidebar-item>
            @endif

            <!-- ROLE: OFFICER -->
            @if (auth()->user()->role === 'officer')
                <div x-show="!collapsed" class="text-xs font-semibold text-slate-500 uppercase mt-6 mb-2 px-2">
                    Operations</div>
                <x-sidebar-item icon="user-group" route="clients.index" :active="request()->routeIs('clients.*')">Clients</x-sidebar-item>

                <!-- CHANGE icon="clipboard-list" TO icon="clipboard-document-list" -->
                <x-sidebar-item icon="clipboard-document-list" route="loans.index" :active="request()->routeIs('loans.*')">Loan
                    Applications</x-sidebar-item>

                <x-sidebar-item icon="currency-dollar" route="payments.index"
                    :active="request()->routeIs('payments.*')">Payments</x-sidebar-item>
            @endif


            <!-- ROLE: CLIENT -->
            @if (auth()->user()->role === 'client')
                <div x-show="!collapsed" class="text-xs font-semibold text-slate-500 uppercase mt-6 mb-2 px-2">My
                    Account</div>
                <x-sidebar-item icon="credit-card" route="loans.index" :active="request()->routeIs('loans.*')">My Loans</x-sidebar-item>
                <x-sidebar-item icon="calendar" route="schedules" :active="request()->routeIs('schedules')">Repayment Schedule</x-sidebar-item>
                <x-sidebar-item icon="user-circle" route="profile.edit" :active="request()->routeIs('profile.*')">Profile</x-sidebar-item>
            @endif

        </nav>

        <!-- User Profile Bottom Section -->
        <div class="p-4 bg-slate-950 border-t border-slate-800">
            <div class="flex items-center" :class="collapsed ? 'justify-center' : ''">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div x-show="!collapsed" class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Backdrop -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>
</aside>
