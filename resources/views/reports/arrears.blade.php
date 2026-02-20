<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 leading-tight uppercase tracking-tighter">
                🚩 {{ __('Arrears & Default Monitor') }}
            </h2>
            <button onclick="window.print()"
                class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-4 py-2 rounded-lg text-xs font-black shadow hover:scale-105 transition no-print">
                PRINT REPORT
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- REQUIREMENT #9: Repayment Search / Filter -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] shadow-sm no-print border border-slate-100 dark:border-slate-700">
                <form method="GET" action="{{ route('reports.arrears') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2 relative">
                        <x-input-label value="Search Client Identity" />
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-s-magnifying-glass class="h-4 w-4 text-slate-400" />
                            </div>
                            <x-text-input name="search" value="{{ request('search') }}" class="w-full pl-10"
                                placeholder="Name, Phone, or Center Code..." />
                        </div>
                    </div>
                    <div>
                        <x-input-label value="Collation Center Scope" />
                        <select name="center"
                            class="w-full mt-1 border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-xl text-sm font-bold">
                            <option value="">All Branches (Admin)</option>
                            @foreach (\App\Models\CollationCenter::all() as $center)
                                <option value="{{ $center->id }}" {{ request('center') == $center->id ? 'selected' : '' }}>
                                    {{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <x-primary-button class="w-full justify-center py-2.5 bg-red-600 hover:bg-red-700">Trace
                            Delinquency</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- THE ARREARS TABLE -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div
                    class="p-6 bg-red-50 dark:bg-red-900/10 border-b border-red-100 dark:border-red-900/20 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-red-600 dark:text-red-400 uppercase tracking-widest">
                            Official Registry of Defaulters
                        </p>
                        <p class="text-xs font-bold text-slate-400">Total Delinquent Installments Found:
                            {{ $arrears->total() }}</p>
                    </div>
                    <span class="text-[9px] font-black uppercase text-slate-400 italic">Penalty Accrual: 0.005 /
                        Day</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Center / Client</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Due Date</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-orange-600 uppercase tracking-widest">
                                    Days Overdue</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Base Installment</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-red-600 uppercase tracking-widest">
                                    Penalty Accrued</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest">
                                    Total Payoff</th>
                                <th class="px-8 py-4 text-right no-print"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($arrears as $item)
                                <tr class="hover:bg-red-50/20 dark:hover:bg-red-950/10 transition group">
                                    <td class="px-8 py-5">
                                        <p
                                            class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase leading-none mb-1">
                                            {{ $item->loan->collationCenter?->center_code ?? 'HQ' }}</p>
                                        <p class="text-sm font-black dark:text-white uppercase tracking-tighter">
                                            {{ $item->loan->client?->user?->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-xs font-bold text-slate-500">
                                        {{ $item->due_date->format('d M, Y') }}</td>
                                    <td class="px-6 py-5 text-sm font-black text-orange-600 italic">
                                        {{ now()->diffInDays($item->due_date) }} DAYS</td>
                                    <td class="px-6 py-5 text-xs font-bold dark:text-slate-400">
                                        ₦{{ number_format($item->total_due, 2) }}</td>
                                    <td class="px-6 py-5 text-sm font-black text-red-600 italic">+
                                        ₦{{ number_format($item->accrued_penalty, 2) }}</td>
                                    <td class="px-6 py-5 text-sm font-black text-slate-900 dark:text-white">
                                        ₦{{ number_format($item->total_due_with_penalty, 2) }}</td>
                                    <td class="px-8 py-5 text-right no-print">
                                        <a href="{{ route('loans.show', $item->loan_id) }}"
                                            class="text-[10px] font-black uppercase text-indigo-600 border-b border-indigo-200 hover:border-indigo-600 transition">Trace
                                            File</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <x-heroicon-o-shield-check class="w-16 h-16 text-emerald-500 mb-4" />
                                            <p class="text-slate-500 font-bold uppercase tracking-[0.3em] text-xs">Registry
                                                is Clean</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-gray-50 dark:bg-slate-900 no-print">
                    {{ $arrears->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .shadow-2xl {
                box-shadow: none !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse;
            }

            th,
            td {
                border-bottom: 1px solid #eee !important;
                padding: 12px 0 !important;
            }

            .bg-slate-50 {
                background-color: transparent !important;
            }
        }
    </style>
</x-app-layout>