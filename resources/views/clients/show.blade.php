<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2
                    class="font-black text-2xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter leading-none">
                    Member Dossier: {{ $client->user?->name ?? 'Orphaned Account' }}
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-[9px] font-black uppercase tracking-widest">
                        Registry ID: #{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span
                        class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded text-[9px] font-black uppercase tracking-widest">
                        Branch: {{ $client->user?->collationCenter?->center_code ?? 'Main' }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('clients.edit', $client) }}"
                    class="inline-flex items-center px-6 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-700 dark:text-white rounded-xl font-black text-xs uppercase tracking-widest transition">
                    Modify Profile
                </a>
                <a href="{{ route('loans.create', ['client_id' => $client->id]) }}"
                    class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition transform active:scale-95">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2 stroke-[3px]" /> Initiate Loan Proposal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- SIDEBAR: IDENTITY CARD (L4 - R8) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Profile Stats -->
                    <div
                        class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700">
                        <div class="text-center border-b dark:border-slate-700 pb-6 mb-6">
                            <div
                                class="w-24 h-24 bg-indigo-50 dark:bg-indigo-900/30 rounded-3xl flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-black text-4xl mx-auto mb-4 border-2 border-indigo-100 dark:border-indigo-800 shadow-inner">
                                {{ substr($client->user?->name ?? '?', 0, 1) }}
                            </div>
                            <h3
                                class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight leading-tight">
                                {{ $client->user?->name ?? 'Deleted/Unknown' }}
                            </h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                                {{ $client->employment_status }}</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Phone</span>
                                <span
                                    class="text-sm font-bold dark:text-slate-300">{{ $client->user?->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase">National ID</span>
                                <span
                                    class="text-sm font-bold dark:text-slate-300 font-mono tracking-tighter">{{ $client->national_id }}</span>
                            </div>
                            <div class="flex justify-between border-t dark:border-slate-700 pt-4">
                                <span
                                    class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Self-Reported
                                    Income</span>
                                <span
                                    class="text-sm font-black text-emerald-600">₦{{ number_format($client->income, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Guarantors Assessment List (Requirement #7) -->
                    <div
                        class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700">
                        <h4 class="font-black text-xs uppercase text-slate-400 tracking-[0.2em] mb-6 flex items-center">
                            <x-heroicon-s-shield-check class="w-4 h-4 mr-2 text-emerald-500" /> Active Guarantors (Form
                            CF4)
                        </h4>

                        <div class="space-y-4">
                            @forelse($client->guarantors as $guarantor)
                                <div
                                    class="group p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-transparent hover:border-indigo-200 dark:hover:border-indigo-900 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <p
                                            class="text-sm font-black text-slate-900 dark:text-white uppercase leading-tight">
                                            {{ $guarantor->name }}</p>
                                        <span
                                            class="text-[8px] font-black bg-indigo-600 text-white px-2 py-0.5 rounded uppercase">{{ $guarantor->type ?? 'Standard' }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 italic">
                                        {{ $guarantor->relationship }} • {{ $guarantor->phone }}
                                    </p>
                                    @if ($guarantor->net_monthly_income > 0)
                                        <div class="mt-2 text-[10px] font-bold text-emerald-600">Verified Monthly:
                                            ₦{{ number_format($guarantor->net_monthly_income) }}</div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-xs text-slate-400 font-medium italic italic">No verified assessments
                                        linked.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- MAIN CONTENT: CREDIT LEDGER & HISTORY -->
                <div class="lg:col-span-8 space-y-8">

                    <!-- Credit Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-indigo-900 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                            <h4 class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-2">Portfolio
                                Total Disbursed</h4>
                            <p class="text-3xl font-black italic">
                                ₦{{ number_format($client->loans->where('status', 'active')->sum('amount'), 2) }}</p>
                            <x-heroicon-o-arrow-up-right class="absolute right-6 top-6 w-10 h-10 opacity-10" />
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-slate-700 relative overflow-hidden">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Net
                                Financial Arrears</h4>
                            <p class="text-3xl font-black text-red-500 italic">
                                @php
                                    $arrears = $client->loans->sum(fn($loan) => $loan->totalArrearsAmount());
                                @endphp
                                ₦{{ number_format($arrears, 2) }}
                            </p>
                            <x-heroicon-o-exclamation-triangle
                                class="absolute right-6 top-6 w-10 h-10 text-red-100 dark:text-red-900/30" />
                        </div>
                    </div>

                    <!-- Master Loan Ledger Table -->
                    <div
                        class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                        <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700">
                            <h4
                                class="text-sm font-black uppercase text-slate-900 dark:text-white tracking-widest italic">
                                Historical Credit Performance</h4>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Protocol Type</th>
                                        <th
                                            class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Approved Value</th>
                                        <th
                                            class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Date Started</th>
                                        <th
                                            class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Current Status</th>
                                        <th class="px-8 py-4 text-right"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                                    @forelse($client->loans as $loan)
                                        <tr
                                            class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition duration-300">
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ $loan->product->name }} Plan</div>
                                                <div class="text-[10px] text-indigo-500 font-black uppercase">
                                                    {{ $loan->duration_months }} Installments</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-sm font-black text-slate-900 dark:text-white">
                                                    ₦{{ number_format($loan->amount, 2) }}</div>
                                                <div
                                                    class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">
                                                    Rate: {{ $loan->interest_rate }}%</div>
                                            </td>
                                            <td
                                                class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                {{ $loan->start_date ? $loan->start_date->format('d M, Y') : 'Pre-Approval' }}
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span
                                                    class="px-3 py-1 text-[9px] font-black rounded-lg uppercase
                                                    {{ $loan->status === 'active' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                                    {{ $loan->status }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <a href="{{ route('loans.show', $loan) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 font-black uppercase text-[10px] border-b-2 border-indigo-100 dark:border-indigo-900 hover:border-indigo-600 transition-all duration-300">
                                                    Manage Assessment &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-20 text-center">
                                                <x-heroicon-o-document-magnifying-glass
                                                    class="w-12 h-12 text-slate-200 dark:text-slate-700 mx-auto mb-2" />
                                                <p class="text-xs text-slate-400 uppercase font-black tracking-widest">
                                                    No previous credit files detected.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
