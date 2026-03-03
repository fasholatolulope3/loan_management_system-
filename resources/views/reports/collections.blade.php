<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 leading-tight uppercase tracking-tighter">
                📅 {{ __('Daily Collections') }}
            </h2>
            <button onclick="window.print()"
                class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-4 py-2 rounded-lg text-xs font-black shadow hover:scale-105 transition no-print">
                PRINT REPORT
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Section -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] shadow-sm no-print border border-slate-100 dark:border-slate-700">
                <form method="GET" action="{{ route('reports.collections') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <div>
                        <x-input-label value="Start Date" />
                        <x-text-input type="date" name="start_date" value="{{ request('start_date', $startDate) }}" class="w-full mt-1" />
                    </div>

                    <div>
                        <x-input-label value="End Date" />
                        <x-text-input type="date" name="end_date" value="{{ request('end_date', $endDate) }}" class="w-full mt-1" />
                    </div>

                    <div class="relative">
                        <x-input-label value="Search Client" />
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-s-magnifying-glass class="h-4 w-4 text-slate-400" />
                            </div>
                            <x-text-input name="search" value="{{ request('search') }}" class="w-full pl-10"
                                placeholder="Name or Phone..." />
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label value="Payment Status" />
                        <select name="status"
                            class="w-full mt-1 border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-xl text-sm font-bold">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <div class="flex items-end md:col-span-4">
                        <x-primary-button class="w-full justify-center py-2.5 bg-indigo-600 hover:bg-indigo-700">Filter Collections</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Collections Table -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div
                    class="p-6 bg-indigo-50 dark:bg-indigo-900/10 border-b border-indigo-100 dark:border-indigo-900/20 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                            Collections from {{ \Carbon\Carbon::parse($startDate)->format('d M, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}
                        </p>
                        <p class="text-xs font-bold text-slate-400">Total Installments Listed:
                            {{ $schedules->total() }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Client & Center</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Contact</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Expected Amount</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    Status</th>
                                <th class="px-8 py-4 text-right no-print"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($schedules as $schedule)
                                <tr class="hover:bg-indigo-50/20 dark:hover:bg-indigo-950/10 transition group">
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-black dark:text-white uppercase tracking-tighter">
                                            {{ $schedule->loan->client?->user?->name ?? 'N/A' }}</p>
                                        <p class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase leading-none mt-1">
                                            {{ $schedule->loan->collationCenter?->center_code ?? 'HQ' }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm font-bold text-slate-500">
                                        {{ $schedule->loan->client?->user?->phone ?? 'N/A' }}</td>
                                    <td class="px-6 py-5 text-sm font-black text-slate-900 dark:text-white">
                                        ₦{{ number_format($schedule->total_due, 2) }}</td>
                                    <td class="px-6 py-5">
                                        @if($schedule->status === 'paid')
                                            <span class="px-2.5 py-1 text-[9px] font-black rounded-lg uppercase tracking-widest bg-emerald-100 text-emerald-700">PAID</span>
                                        @else
                                            <span class="px-2.5 py-1 text-[9px] font-black rounded-lg uppercase tracking-widest bg-amber-50 text-amber-600">PENDING</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right no-print">
                                        <a href="{{ route('loans.show', $schedule->loan_id) }}"
                                            class="text-[10px] font-black uppercase text-indigo-600 border-b border-indigo-200 hover:border-indigo-600 transition">View File</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <x-heroicon-o-calendar class="w-16 h-16 text-slate-500 mb-4" />
                                            <p class="text-slate-500 font-bold uppercase tracking-[0.3em] text-xs">No collections due today</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-gray-50 dark:bg-slate-900 no-print">
                    {{ $schedules->links() }}
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
