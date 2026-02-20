<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2
                    class="font-black text-2xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter leading-tight">
                    File Review: #{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span
                        class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-[9px] font-black uppercase tracking-widest border border-indigo-200">
                        {{ $loan->product->name }} Plan
                    </span>
                    <span
                        class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded text-[9px] font-black uppercase tracking-widest">
                        Center: {{ $loan->collationCenter?->center_code ?? 'HQ' }}
                    </span>
                </div>
            </div>

            <!-- REQUIREMENT #8: Governance Actions -->
            <div class="flex gap-2" x-data="{ showAdjustment: false }">
                @if ($loan->status === 'pending' && auth()->user()->role !== 'client')
                    <!-- Staff Action: Edit for Adjustment -->
                    @if($loan->approval_status === 'adjustment_needed' && auth()->user()->role === 'officer')
                        <a href="{{ route('loans.edit', $loan) }}" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg shadow-indigo-500/20">
                            Apply Adjustments
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Action: Request Adjustment Trigger -->
                        <button @click="showAdjustment = true"
                            class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg shadow-amber-200 dark:shadow-none">
                            Request Adjustment
                        </button>
                        
                        <form action="{{ route('loans.approve', $loan) }}" method="POST">
                            @csrf @method('PATCH')
                            <button
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg shadow-indigo-500/20">
                                Authorize & Disburse
                            </button>
                        </form>
                    @endif
                @endif

                @if(auth()->user()->role !== 'client')
                    <a href="{{ route('loans.print', $loan->id) }}" target="_blank" class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg shadow-indigo-500/20">
                        Print File
                    </a>
                @endif

                @if ($loan->status === 'pending' && auth()->user()->role === 'admin')
                    <!-- Adjustment Modal -->
                    <div x-show="showAdjustment"
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                        x-cloak>
                        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-2xl max-w-md w-full">
                            <h3 class="text-lg font-black uppercase mb-4">Underwriting Notes</h3>
                            <form action="{{ route('loans.adjustment', $loan) }}" method="POST">
                                @csrf @method('PATCH')
                                <textarea name="notes" rows="4" class="w-full rounded-2xl border-gray-300 dark:bg-slate-950 mb-4"
                                    placeholder="Officer: Reduce principal by 10% due to low liquidity..." required></textarea>
                                <div class="flex gap-2">
                                    <button type="button" @click="showAdjustment = false"
                                        class="flex-1 py-3 text-xs font-black uppercase text-slate-400">Cancel</button>
                                    <x-primary-button class="flex-1 justify-center bg-amber-600">Send to
                                        Staff</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- 1. HIGH-LEVEL FINANCIAL RATIOS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border-t-4 border-indigo-500">
                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">Requested Capital</p>
                    <p class="text-2xl font-black dark:text-white">₦{{ number_format($loan->amount, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border-t-4 border-indigo-500">
                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">Net Surplus Capacity</p>
                    <p class="text-2xl font-black text-emerald-600">₦{{ number_format($loan->payment_capacity, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border-t-4 border-red-500">
                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">Arrears + Penalty</p>
                    <p class="text-2xl font-black text-red-500 italic">
                        ₦{{ number_format($loan->totalArrearsAmount() + $loan->currentPenaltyAccrued(), 2) }}
                    </p>
                </div>
                <div class="bg-indigo-900 p-6 rounded-3xl shadow-xl text-white">
                    <p class="text-[10px] text-indigo-300 uppercase font-black tracking-widest">DRS (Monthly/Surplus)
                    </p>
                    <p class="text-2xl font-black">
                        {{ number_format(($loan->amount / $loan->duration_months / max($loan->payment_capacity, 1)) * 100, 1) }}%
                    </p>
                </div>
            </div>

            <!-- 2. REQUIREMENT #7: CLIENT BUSINESS ANALYSIS (FORM CF2) -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div
                    class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-sm font-black uppercase text-slate-900 dark:text-white tracking-widest">Section I:
                        Business Credit Profile</h3>
                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">Onboarding Ref:
                        {{ $loan->client->user->collationCenter?->center_code }}</span>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div>
                        <p
                            class="text-[9px] font-black text-indigo-600 uppercase mb-4 tracking-widest italic underline underline-offset-4">
                            Business Core</p>
                        <ul class="space-y-3">
                            <li class="text-sm dark:text-slate-300"><strong>Name:</strong> {{ $loan->business_name }}
                            </li>
                            <li class="text-sm dark:text-slate-300"><strong>Location:</strong>
                                {{ $loan->business_location }}</li>
                            <li class="text-sm dark:text-slate-300"><strong>Start Date:</strong>
                                {{ optional($loan->business_start_date)->format('d M, Y') ?? 'N/A' }}</li>
                            <li class="text-sm dark:text-slate-300 uppercase"><strong>Premise:</strong>
                                {{ $loan->business_premise_type }}</li>
                        </ul>
                    </div>
                    <div>
                        <p
                            class="text-[9px] font-black text-indigo-600 uppercase mb-4 tracking-widest italic underline underline-offset-4">
                            Cash Flow Recap (CF2)</p>
                        <ul class="space-y-2">
                            <li class="flex justify-between text-sm dark:text-slate-300"><span>Monthly Sales:</span>
                                <span class="font-bold">₦{{ number_format($loan->monthly_sales, 2) }}</span>
                            </li>
                            <li class="flex justify-between text-sm dark:text-slate-300"><span>Cost of Sales:</span>
                                <span
                                    class="font-bold text-red-400">₦{{ number_format($loan->cost_of_sales, 2) }}</span>
                            </li>
                            <li
                                class="flex justify-between text-sm border-t dark:border-slate-700 pt-1 font-black dark:text-white">
                                <span>Gross Profit:</span> <span>₦{{ number_format($loan->gross_profit, 2) }}</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p
                            class="text-[9px] font-black text-indigo-600 uppercase mb-4 tracking-widest italic underline underline-offset-4">
                            Internal Staff Proposal</p>
                        <p class="text-xs italic leading-relaxed text-slate-500 dark:text-slate-400">
                            {{ $loan->proposal_summary }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- 3. REQUIREMENT #7: GUARANTOR ASSESSMENT (FORM CF4) -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border-l-8 border-emerald-500 border border-slate-100 dark:border-slate-700 p-8">
                    <h3 class="text-sm font-black uppercase text-emerald-600 mb-6 tracking-widest">Section II: Guarantor
                        Assessment</h3>
                    @if ($loan->guarantor)
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p
                                        class="text-lg font-black dark:text-white leading-tight uppercase underline decoration-indigo-200">
                                        {{ $loan->guarantor->name }}</p>
                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-tighter mt-1">
                                        {{ $loan->guarantor->relationship }} • {{ $loan->guarantor->phone }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 rounded text-[9px] font-black border border-emerald-200">{{ $loan->guarantor->type }}</span>
                            </div>

                            <div
                                class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl flex justify-between items-center border border-slate-100 dark:border-slate-800">
                                <span class="text-xs font-black text-slate-400 uppercase italic leading-none">Net Take
                                    Home / Capacity</span>
                                <span
                                    class="text-xl font-black text-indigo-600">₦{{ number_format($loan->guarantor->net_monthly_income, 2) }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t dark:border-slate-700">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Spouse Name</p>
                                    <p class="text-xs dark:text-slate-300">
                                        {{ $loan->guarantor->spouse_name ?? 'None Registered' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Employment Sector</p>
                                    <p class="text-xs dark:text-slate-300 italic">
                                        {{ $loan->guarantor->job_sector ?? 'Unspecified' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-red-500 font-black italic">⚠️ CRITICAL: Missing linked assessment
                            profile.</p>
                    @endif
                </div>

                <!-- 4. REQUIREMENT #7: COLLATERAL REGISTRY (FORM CF5) -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700 p-8">
                    <h3 class="text-sm font-black uppercase text-amber-500 mb-6 tracking-widest italic">Section III:
                        Assets & Collateral (CF5)</h3>
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($loan->collaterals as $collateral)
                            <div
                                class="p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border dark:border-slate-800 flex justify-between items-center group transition">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        <span
                                            class="text-xs font-black dark:text-white uppercase">{{ $collateral->type }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 italic">{{ $collateral->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Liquidation (70%)</p>
                                    <p class="text-sm font-black text-amber-600 tracking-tighter italic">
                                        ₦{{ number_format($collateral->market_value * 0.7, 2) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 italic py-10 uppercase font-black">No
                                collateral registered</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 5. AMORTIZATION LEDGER -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div
                    class="px-8 py-6 border-b dark:border-slate-700 flex justify-between items-center bg-indigo-50/20 dark:bg-slate-950/50">
                    <h3
                        class="text-sm font-black uppercase tracking-[0.2em] dark:text-white italic underline underline-offset-4 decoration-indigo-500/50 leading-none">
                        IV. Future Repayment Amortization</h3>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Installments</p>
                            <p class="text-sm font-black dark:text-white leading-none mt-1">{{ $loan->schedules->count() }} Payments</p>
                        </div>
                        <div class="text-right border-l dark:border-slate-700 pl-6">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Repayment</p>
                            <p class="text-sm font-black text-indigo-600 dark:text-indigo-400 leading-none mt-1">₦{{ number_format($loan->schedules->sum('total_due'), 2) }}</p>
                        </div>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900">
                        <tr>
                            <th
                                class="px-8 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Installment</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Due Date</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Base Amount</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-red-500 animate-pulse">
                                Fine (0.005)</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-600 dark:text-indigo-400">
                                Installment Due</th>
                            <th class="px-8 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                        @foreach ($loan->schedules as $index => $schedule)
                            @php
                                $daysLate =
                                    now() > $schedule->due_date && $schedule->status !== 'paid'
                                        ? now()->diffInDays($schedule->due_date)
                                        : 0;
                                $penalty = $schedule->principal_amount * 0.005 * $daysLate;
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                                <td class="px-8 py-4 text-xs font-black text-slate-400">
                                    {{ $index + 1 }} of {{ $loan->schedules->count() }}
                                </td>
                                <td class="px-6 py-4 text-xs font-bold dark:text-slate-300 uppercase leading-none">
                                    {{ $schedule->due_date->format('d M, Y') }}</td>
                                <td class="px-6 py-4 text-xs font-medium dark:text-slate-400">
                                    ₦{{ number_format($schedule->total_due, 2) }}</td>
                                <td class="px-6 py-4 text-xs font-black text-red-500 italic">+
                                    ₦{{ number_format($penalty, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-black dark:text-white">
                                    ₦{{ number_format($schedule->total_due + $penalty, 2) }}</td>
                                <td class="px-8 py-4 text-right">
                                    <span
                                        class="px-2.5 py-1 text-[9px] font-black rounded-lg uppercase tracking-widest
                                        {{ $schedule->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-50 text-amber-600' }}">
                                        {{ $schedule->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Requirement #8: Display Review Notes if Adjusted -->
            @if ($loan->review_notes)
                <div
                    class="p-6 bg-amber-50 dark:bg-amber-900/20 rounded-3xl border border-amber-200 border-dashed animate-in slide-in-from-bottom duration-1000">
                    <h4 class="text-[10px] font-black uppercase text-amber-700 dark:text-amber-500 mb-2 italic">
                        Official Review Feedback</h4>
                    <p class="text-sm font-medium dark:text-slate-200">"{{ $loan->review_notes }}"</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
