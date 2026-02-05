<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-200 leading-tight">
            {{ __('Financial Operations Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">System Overview</h1>
                    <p class="text-gray-500 dark:text-slate-400 font-medium">Monitoring capital deployment and revenue
                        collection.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('loans.index') }}?status=pending"
                        class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-amber-200 dark:shadow-none text-sm flex items-center">
                        <x-heroicon-s-clock class="w-4 h-4 mr-2" />
                        {{ $stats['pending_loans'] }} Pending Approvals
                    </a>
                </div>
            </div>

            <!-- KEY FINANCIAL METRICS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- 1. Disbursement Stat (Outflow) -->
                <div class="bg-slate-900 dark:bg-indigo-900 text-white p-6 rounded-3xl shadow-xl">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-indigo-300">
                            Total Disbursed</p>
                        <x-heroicon-o-arrow-up-right class="w-4 h-4 text-red-400" />
                    </div>
                    <p class="text-2xl font-black italic">
                        ₦{{ number_format($stats['total_disbursed'] ?? 0, 2) }}
                    </p>
                    <p class="text-[10px] mt-2 opacity-60">Capital Outflow (Company → Clients)</p>
                </div>

                <!-- 2. Repayment Stat (Inflow) -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Repayments</p>
                        <x-heroicon-o-arrow-down-left class="w-4 h-4 text-emerald-500" />
                    </div>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        ₦{{ number_format($stats['total_repayments'] ?? 0, 2) }}
                    </p>
                    <p class="text-[10px] mt-2 text-gray-500 dark:text-slate-500">Revenue Inflow (Verified Payments)</p>
                </div>

                <!-- 3. Client Growth -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between items-start mb-4 text-indigo-600">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Portfolio</p>
                        <x-heroicon-o-user-group class="w-5 h-5" />
                    </div>
                    <p class="text-2xl font-black dark:text-white">
                        {{ $stats['total_clients'] }} Clients
                    </p>
                    <p class="text-[10px] mt-2 text-gray-500">Registered active borrower profiles</p>
                </div>

                <!-- 4. Operational Gate -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 border-l-4 border-l-amber-500">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Review Queue</p>
                    <p class="text-2xl font-black text-amber-600">
                        {{ $stats['pending_loans'] }} Applications
                    </p>
                    <a href="{{ route('loans.index') }}?status=pending"
                        class="text-[10px] mt-2 font-black text-indigo-600 uppercase flex items-center hover:underline">
                        Take Action Now &rarr;
                    </a>
                </div>
            </div>

            <!-- DATA TABLE: RECENT USER REGISTRATIONS -->
            <div
                class="bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 rounded-[2rem] overflow-hidden">
                <div
                    class="px-8 py-6 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white">Recent
                        System Access</h3>
                    <a href="{{ route('users.index') }}"
                        class="text-xs font-bold text-indigo-600 hover:text-indigo-700">View All Users</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead>
                            <tr class="bg-white dark:bg-slate-800">
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Identified User</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Authorized Role</th>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Created Date</th>
                                <th
                                    class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @foreach ($stats['recent_users'] as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition duration-200">
                                    <td class="px-8 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-9 w-9 rounded-xl bg-indigo-50 dark:bg-slate-900 flex items-center justify-center font-black text-indigo-600 dark:text-indigo-400 text-xs">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ $user->name }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-tighter
                                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $user->role === 'officer' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $user->role === 'client' ? 'bg-slate-100 text-slate-600' : '' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                        {{ $user->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-8 py-4 whitespace-nowrap text-right">
                                        <span
                                            class="flex justify-end items-center text-[11px] font-bold text-emerald-500">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                            {{ strtoupper($user->status ?? 'Active') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
