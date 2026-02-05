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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- REQUIREMENT #9: Repayment Search / Filter -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm mb-8 no-print border border-slate-100 dark:border-slate-700">
                <form method="GET" action="{{ route('reports.arrears') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label value="Search Client or Loan ID" />
                        <x-text-input name="search" value="{{ request('search') }}" class="w-full mt-1"
                            placeholder="Enter name or ID..." />
                    </div>
                    <div>
                        <x-input-label value="Collation Center" />
                        <select name="center"
                            class="w-full mt-1 border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-md">
                            <option value="">All Centers</option>
                            @foreach (\App\Models\CollationCenter::all() as $center)
                                <option value="{{ $center->id }}"
                                    {{ request('center') == $center->id ? 'selected' : '' }}>{{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <x-primary-button class="w-full justify-center py-2.5">Apply Filters</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- THE ARREARS TABLE -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div class="p-6 bg-red-50 dark:bg-red-900/10 border-b border-red-100 dark:border-red-900/20">
                    <p class="text-xs font-black text-red-600 dark:text-red-400 uppercase tracking-widest">
                        Total Delinquent Installments Found: {{ $arrears->total() }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Center / Client</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Loan Type</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Due Date</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest text-orange-600">
                                    Overdue</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Principal + Int</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest text-red-600">
                                    Penalty (0.005)</th>
                                <th
                                    class="px-6 py-4 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest no-print">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($arrears as $item)
                                @php
                                    $daysLate = now()->diffInDays($item->due_date);
                                    // REQUIREMENT #1: Calculate 0.005 penalty
                                    $accruedPenalty = $item->principal_amount * 0.005 * $daysLate;
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">
                                            {{ $item->loan->collationCenter?->center_code ?? 'HQ' }}
                                        </div>
                                        <div class="text-sm font-bold dark:text-white">
                                            {{ $item->loan->client?->user?->name ?? 'Deleted Client' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="text-[10px] font-black bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded text-gray-600 dark:text-gray-300 uppercase">
                                            {{ $item->loan->product->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400">
                                        {{ $item->due_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-black text-orange-600 dark:text-orange-400">
                                            {{ $daysLate }} Days
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold dark:text-white">
                                        ₦{{ number_format($item->total_due, 2) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-black text-red-600 dark:text-red-500">
                                        ₦{{ number_format($accruedPenalty, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right no-print">
                                        <a href="{{ route('loans.show', $item->loan_id) }}"
                                            class="text-xs font-black text-indigo-600 uppercase border-b-2 border-indigo-200 hover:border-indigo-600 transition">Review
                                            Case</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <x-heroicon-o-check-badge class="w-12 h-12 text-emerald-500 mb-2" />
                                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">No
                                                active arrears found for this scope.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900 no-print">
                    {{ $arrears->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- REQUIREMENT #9: CSS Print Styling -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .shadow-xl,
            .shadow-sm {
                box-shadow: none !important;
            }

            table {
                width: 100% !important;
                border: 1px solid #eee;
            }

            tr {
                border-bottom: 1px solid #eee !important;
            }
        }
    </style>
</x-app-layout>
