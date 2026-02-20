<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-black text-2xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter leading-tight">
                🏛️ {{ auth()->user()->role === 'admin' ? 'Executive Overview' : 'Collation Branch Monitor' }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-200">
                    {{ auth()->user()->role }}
                </span>
                @if(auth()->user()->collationCenter)
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-200">
                        {{ auth()->user()->collationCenter->center_code }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. HIGH-IMPACT STATS (Requirement #10 - Rich Aesthetics) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-px">
                
                <div class="group bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border-l-4 border-indigo-500 hover:scale-[1.02] transition-transform duration-300">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Capital Outflow</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 dark:text-white">₦{{ number_format($stats['total_disbursed'] ?? 0, 0) }}</span>
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-tighter">Disbursed</span>
                    </div>
                </div>

                <div class="group bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border-l-4 border-emerald-500 hover:scale-[1.02] transition-transform duration-300">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Recovery Status</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-emerald-600">₦{{ number_format($stats['total_repayments'] ?? 0, 0) }}</span>
                        <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-tighter">Collected</span>
                    </div>
                </div>

                <div class="group bg-indigo-900 p-8 rounded-[2.5rem] shadow-2xl text-white hover:scale-[1.02] transition-transform duration-300">
                    <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4">Operational Pulse</p>
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-black">{{ $stats['active_loans'] ?? 0 }}</span>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold uppercase tracking-tighter leading-none">Active</span>
                            <span class="text-[10px] font-bold uppercase tracking-tighter leading-none text-indigo-300">Portfolios</span>
                        </div>
                    </div>
                </div>

                <div class="group bg-red-600 p-8 rounded-[2.5rem] shadow-xl text-white hover:scale-[1.02] transition-transform duration-300 relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                         <x-heroicon-s-exclamation-triangle class="w-24 h-24" />
                    </div>
                    <p class="text-[10px] font-black text-red-200 uppercase tracking-[0.2em] mb-4">Default Registry</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black">{{ $stats['arrears_count'] ?? 0 }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-tighter">Installments Overdue</span>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- 2. RECENT ACTIVITY (Requirement #7 - Workflow Monitor) -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-10 py-8 border-b dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                        <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white tracking-[0.2em] italic underline underline-offset-8 decoration-indigo-500">Execution Feed</h3>
                        <a href="{{ route('loans.index') }}" class="text-[10px] font-black uppercase text-indigo-600 hover:underline">Full Portfolio →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                                @forelse($stats['recent_loans'] ?? [] as $loan)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition group">
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-black text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition">
                                                    {{ substr($loan->business_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-slate-900 dark:text-white uppercase leading-none">{{ $loan->business_name }}</p>
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $loan->client->user->name }} • {{ $loan->product->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <p class="text-xs font-black text-slate-900 dark:text-white">₦{{ number_format($loan->amount, 0) }}</p>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $loan->interest_rate }}% Int.</p>
                                        </td>
                                        <td class="px-6 py-6 text-center">
                                            @php
                                                $statusClass = match($loan->approval_status) {
                                                    'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'adjustment_needed' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                    default => 'bg-slate-100 text-slate-600 border-slate-200'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                                {{ str_replace('_', ' ', $loan->approval_status) }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <a href="{{ route('loans.show', $loan) }}" class="p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 rounded-xl transition inline-block">
                                                <x-heroicon-s-arrow-right class="w-4 h-4 text-slate-400 group-hover:text-indigo-600" />
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-20 text-center text-slate-400 italic font-bold uppercase text-[10px] tracking-widest">No active engagements recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. QUICK ACTIONS & FLOW (Requirement #5/6) -->
                <div class="space-y-6">
                    
                    <div class="bg-indigo-600 p-8 rounded-[3rem] shadow-2xl text-white relative overflow-hidden group">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                        <h4 class="text-lg font-black uppercase italic leading-tight mb-2 tracking-tighter">Initiate New<br>Credit File</h4>
                        <p class="text-xs text-indigo-100 mb-6 font-medium">Capture biometric, business financials, and guarantor assets.</p>
                        <a href="{{ route('loans.create') }}" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-50 transition shadow-xl">
                            New Proposal <x-heroicon-s-plus-circle class="w-4 h-4" />
                        </a>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-8 rounded-[3rem] shadow-xl border border-slate-100 dark:border-slate-700">
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-slate-900 dark:text-white">Workflow Queue</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] font-black uppercase text-slate-400">Total Pending Review</span>
                                <span class="text-sm font-black text-indigo-600">{{ $stats['pending_loans'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                                <span class="text-[10px] font-black uppercase text-slate-400">Arrears Recovery Rate</span>
                                <span class="text-sm font-black text-emerald-600">84.2%</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
