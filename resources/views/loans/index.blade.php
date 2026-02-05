<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2
                    class="font-black text-2xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter leading-tight">
                    💼 {{ __('Loan Portfolio Management') }}
                </h2>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-1">
                    Internal Underwriting Control Group
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="hidden md:inline-flex items-center px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-200 dark:border-amber-800">
                    <x-heroicon-s-clock class="w-3 h-3 mr-1" />
                    Queue: {{ $loans->where('status', 'pending')->count() }} Proposals
                </span>

                @if (auth()->user()->role === 'officer')
                    <a href="{{ route('loans.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition transform active:scale-95 flex items-center">
                        <x-heroicon-o-plus class="w-4 h-4 mr-2 stroke-[3px]" /> New Proposal
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Requirement #9: Reporting & Arrears Filtering -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700">
                <form method="GET" action="{{ route('loans.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <x-text-input type="text" name="search"
                            placeholder="Search by Loan ID, Client Name, or Business..." value="{{ request('search') }}"
                            class="w-full dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl" />
                    </div>
                    <div>
                        <select name="status"
                            class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400 rounded-xl shadow-sm text-sm font-bold">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                Review</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                Disbursed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-4 py-2 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-white transition shadow-md">
                        Update View
                    </button>
                </form>
            </div>

            <!-- THE MASTER LEDGER TABLE -->
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-2xl rounded-[2.5rem] border border-slate-100 dark:border-slate-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-50/50 dark:bg-slate-950/50">
                            <tr>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Application Tracking</th>
                                <th
                                    class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Borrower File</th>
                                <th
                                    class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Deployment Code</th>
                                <th
                                    class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400">
                                    Financial Recap</th>
                                <th
                                    class="px-6 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Lifecycle Status</th>
                                <th
                                    class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Governance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($loans as $loan)
                                <tr
                                    class="hover:bg-slate-50/50 dark:hover:bg-slate-900/40 transition duration-200 group">

                                    <!-- ID & Date -->
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <p class="text-sm font-black text-slate-900 dark:text-white">
                                            #{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">
                                            {{ $loan->created_at->format('d M, Y') }}</p>
                                    </td>

                                    <!-- Client Details -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-9 w-9 rounded-xl bg-slate-100 dark:bg-slate-950 flex items-center justify-center font-black text-slate-400 dark:text-slate-600 border dark:border-slate-800 text-xs">
                                                {{ substr($loan->client?->user?->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <p
                                                    class="text-sm font-bold text-slate-900 dark:text-white leading-none">
                                                    {{ $loan->client?->user?->name ?? 'N/A' }}
                                                </p>
                                                <p class="text-[10px] text-indigo-500 font-black uppercase mt-1">
                                                    {{ $loan->business_name ?? 'Individual' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Requirement #5: Collation Center -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 bg-slate-100 dark:bg-slate-950 text-slate-600 dark:text-slate-400 rounded text-[9px] font-black uppercase border dark:border-slate-800">
                                            {{ $loan->collationCenter?->center_code ?? 'HQ-ADMIN' }}
                                        </span>
                                    </td>

                                    <!-- Principal & Rate Info -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <p
                                            class="text-sm font-black text-slate-900 dark:text-white tracking-tighter italic">
                                            ₦{{ number_format($loan->amount, 2) }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                            {{ $loan->product?->name }}
                                            ({{ number_format($loan->interest_rate, 0) }}%)
                                        </p>
                                    </td>

                                    <!-- Refined Badges: Requirement #8 (Adjustment logic) -->
                                    <td class="px-6 py-6 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter border
                                            {{ $loan->approval_status === 'adjustment_needed' ? 'bg-orange-50 text-orange-600 border-orange-200 animate-pulse' : '' }}
                                            {{ $loan->status === 'active' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                                            {{ $loan->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                            {{ $loan->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                            {{ $loan->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                        ">
                                            @if ($loan->approval_status === 'adjustment_needed')
                                                <x-heroicon-s-exclamation-circle class="w-3 h-3 mr-1" />
                                                Needs Adjustment
                                            @else
                                                {{ $loan->status }}
                                            @endif
                                        </span>
                                    </td>

                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <a href="{{ route('loans.show', $loan) }}"
                                            class="p-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-white transition duration-300 shadow-lg group-hover:scale-110">
                                            <span class="text-[9px] font-black uppercase px-2">Access File</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <x-heroicon-o-folder-open
                                                class="w-16 h-16 text-slate-100 dark:text-slate-700 mb-4" />
                                            <h3 class="text-sm font-black uppercase tracking-widest text-slate-500">
                                                Registry Is Currently Clean</h3>
                                            <p class="text-xs text-slate-400 mt-1 italic italic">New loan proposals
                                                submitted by staff will appear here for underwriting review.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Requirement #9: High-Density Navigation -->
                @if ($loans->hasPages())
                    <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-900/50 border-t dark:border-slate-700">
                        {{ $loans->links() }}
                    </div>
                @endif
            </div>

            <!-- Policy Footer Badge -->
            <div
                class="flex justify-center opacity-40 hover:opacity-100 transition duration-500 grayscale hover:grayscale-0">
                <span
                    class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] flex items-center gap-4 italic underline underline-offset-4 decoration-indigo-500/20">
                    Authority Credentials verified • Internal Policy V2.25 • 0.005 Penalty Logic Enabled
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
