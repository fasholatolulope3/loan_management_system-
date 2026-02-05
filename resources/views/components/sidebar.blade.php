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
        class="fixed inset-y-0 left-0 z-40 bg-slate-900 text-slate-300 transition-all duration-300 transform lg:translate-x-0 lg:static lg:inset-0 h-screen overflow-y-auto flex flex-col border-r border-slate-800 shadow-2xl">

        <!-- Header / Logo -->
        <div
            class="flex items-center justify-between h-20 px-6 bg-slate-950/50 backdrop-blur-sm border-b border-white/5">
            <div x-show="!collapsed" class="flex items-center gap-2 overflow-hidden transition-all">
                <div
                    class="flex-shrink-0 w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <x-heroicon-s-building-library class="w-5 h-5 text-white" />
                </div>
                <span class="text-xl font-black text-white tracking-tighter uppercase whitespace-nowrap">LMS<span
                        class="text-indigo-500">PRO</span></span>
            </div>
            <button @click="collapsed = !collapsed"
                class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg bg-slate-800/50 text-slate-400 hover:text-white hover:bg-indigo-600 transition-all duration-300">
                <svg :class="collapsed ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar overflow-x-hidden">

            <!-- Global Section -->
            <x-sidebar-item icon="home" route="dashboard" :active="request()->routeIs('dashboard')">General Overview</x-sidebar-item>

            <!-- ROLE: ADMIN (Authority Control) -->
            @if (auth()->user()->role === 'admin')
                <div x-show="!collapsed"
                    class="text-[10px] font-black text-slate-500 uppercase mt-8 mb-2 px-4 tracking-[0.2em] animate-pulse">
                    Infrastructure</div>

                <x-sidebar-item icon="building-office-2" route="admin.centers.index" :active="request()->routeIs('admin.centers.*')">Collation
                    Centers</x-sidebar-item>
                <x-sidebar-item icon="users" route="users.index" :active="request()->routeIs('users.*')">Staff Management</x-sidebar-item>
                <x-sidebar-item icon="squares-2x2" route="loan-products.index" :active="request()->routeIs('loan-products.*')">Credit
                    Products</x-sidebar-item>

                <div x-show="!collapsed"
                    class="text-[10px] font-black text-slate-500 uppercase mt-8 mb-2 px-4 tracking-[0.2em]">Compliance
                </div>

                <x-sidebar-dropdown icon="shield-check" label="Approval Face" :active="request()->routeIs('loans.*')">
                    <x-sidebar-item route="loans.index" :active="request()->routeIs('loans.index')" sub-item>Master Portfolio</x-sidebar-item>
                    <x-sidebar-item route="loans.index" :active="request()->is('loans?status=pending')" sub-item>Pending Proposals</x-sidebar-item>
                </x-sidebar-dropdown>

                <x-sidebar-item icon="exclamation-circle" route="reports.arrears" :active="request()->routeIs('reports.arrears')">Arrears
                    Monitor</x-sidebar-item>
                <x-sidebar-item icon="finger-print" route="audit-logs.index" :active="request()->routeIs('audit-logs.*')">Trace Audit
                    Logs</x-sidebar-item>
                <x-sidebar-item icon="adjustments-horizontal" route="settings" :active="request()->routeIs('settings')">Global
                    Settings</x-sidebar-item>
            @endif

            <!-- ROLE: OFFICER (Field & Operations) -->
            @if (auth()->user()->role === 'officer')
                <div x-show="!collapsed"
                    class="text-[10px] font-black text-slate-500 uppercase mt-8 mb-2 px-4 tracking-[0.2em]">Assessment
                    Registry</div>

                <x-sidebar-item icon="user-plus" route="clients.index" :active="request()->routeIs('clients.*')">Client
                    Records</x-sidebar-item>

                <!-- REQUIREMENT: New dedicated page for Form CF4 -->
                <x-sidebar-item icon="identification" route="guarantors.index" :active="request()->routeIs('guarantors.*')">Guarantor
                    Assessment</x-sidebar-item>

                <div x-show="!collapsed"
                    class="text-[10px] font-black text-slate-500 uppercase mt-8 mb-2 px-4 tracking-[0.2em]">Loan
                    Preparation</div>

                <x-sidebar-item icon="document-text" route="loans.create" :active="request()->routeIs('loans.create')">New Credit
                    Proposal</x-sidebar-item>
                <x-sidebar-item icon="clock" route="loans.index" :active="request()->is('loans?status=pending')">Active Proposals</x-sidebar-item>

                <div x-show="!collapsed"
                    class="text-[10px] font-black text-slate-500 uppercase mt-8 mb-2 px-4 tracking-[0.2em]">Journaling
                </div>

                <x-sidebar-item icon="banknotes" route="payments.index" :active="request()->routeIs('payments.*')">Repayment
                    Journal</x-sidebar-item>
                <x-sidebar-item icon="calendar-days" route="reports.arrears" :active="request()->routeIs('reports.arrears')">Arrears
                    List</x-sidebar-item>
            @endif

        </nav>

        <!-- Dynamic User/Center Profile Card -->
        <div class="p-4 bg-slate-950/80 border-t border-slate-800/50 mt-auto">
            <div class="flex items-center group cursor-pointer" :class="collapsed ? 'justify-center' : ''">
                <div class="relative">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-400 flex items-center justify-center text-white font-black text-xs shadow-lg group-hover:scale-105 transition-transform duration-300">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span
                        class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div x-show="!collapsed" class="ml-3 overflow-hidden">
                    <p class="text-xs font-black text-white truncate uppercase tracking-tighter leading-none">
                        {{ auth()->user()->name }}</p>
                    <div class="flex items-center gap-1 mt-1">
                        <x-heroicon-m-map-pin class="w-3 h-3 text-indigo-500 flex-shrink-0" />
                        <p class="text-[9px] text-slate-500 truncate uppercase font-bold tracking-tight">
                            {{ auth()->user()->collationCenter?->center_code ?? 'MAIN BRANCH' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-30 lg:hidden"></div>
</aside>
