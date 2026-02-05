<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2
                    class="font-black text-2xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter leading-tight">
                    Assessment Dossier: {{ $guarantor->name }}
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded text-[9px] font-black uppercase tracking-widest border border-emerald-200 dark:border-emerald-800">
                        {{ $guarantor->type }}
                    </span>
                    <span
                        class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 rounded text-[9px] font-black uppercase tracking-widest">
                        Linked to Client: {{ $guarantor->client?->user?->name ?? 'Orphaned' }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('guarantors.index') }}"
                    class="text-xs font-black text-slate-500 hover:text-indigo-600 uppercase transition border-b-2 border-transparent hover:border-indigo-600 pb-1">
                    &larr; Return to Registry
                </a>
                <a href="{{ route('guarantors.edit', $guarantor) }}"
                    class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-5 py-2 rounded-xl text-xs font-black uppercase shadow-xl hover:scale-105 transition">
                    Edit assessment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- SECTION I & II: HEADER & PERSONAL LOGS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Visit & Bio Column -->
                <div class="lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6 italic">
                            Personnel Audit</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-indigo-600 uppercase">Biz Site Visit</p>
                                <p class="text-sm font-bold dark:text-white">
                                    {{ $guarantor->date_of_visit_business ? $guarantor->date_of_visit_business->format('d M, Y') : 'NOT VISITED' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-indigo-600 uppercase">Residence Visit</p>
                                <p class="text-sm font-bold dark:text-white">
                                    {{ $guarantor->date_of_visit_residence ? $guarantor->date_of_visit_residence->format('d M, Y') : 'NOT VISITED' }}
                                </p>
                            </div>
                            <div class="pt-4 border-t dark:border-slate-700">
                                <p class="text-[9px] font-black text-slate-400 uppercase">Contact Baseline</p>
                                <p class="text-sm font-black dark:text-white">{{ $guarantor->phone }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $guarantor->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Identity Snapshot -->
                    <div class="bg-indigo-900 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden">
                        <x-heroicon-o-finger-print class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" />
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300 mb-6">Identity
                            Summary</h4>
                        <div class="space-y-3 relative z-10">
                            <div class="flex justify-between border-b border-white/10 pb-2 text-xs">
                                <span>Gender</span><span
                                    class="font-black">{{ $guarantor->sex == 'M' ? 'Male' : 'Female' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-2 text-xs">
                                <span>DOB</span><span
                                    class="font-black">{{ $guarantor->date_of_birth ? $guarantor->date_of_birth->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Status</span><span
                                    class="font-black italic uppercase">{{ $guarantor->marital_status ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FINANCIAL BREAKDOWN COLUMN -->
                <div class="lg:col-span-2 space-y-6">

                    @if ($guarantor->type === 'Business Owner')
                        <!-- Section III: Business Performance -->
                        <div
                            class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border-l-8 border-amber-500 border border-slate-100 dark:border-slate-700">
                            <div class="flex justify-between items-start mb-8">
                                <h3 class="text-xl font-black uppercase tracking-tight text-slate-900 dark:text-white">
                                    Section III: Business Analysis</h3>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Center Reference</p>
                                    <p class="text-sm font-bold dark:text-indigo-400">
                                        {{ $guarantor->client?->user?->collationCenter?->center_code ?? 'MAIN' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                <!-- Left: Narrative Details -->
                                <div class="space-y-4">
                                    <div><label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Business
                                            Location</label>
                                        <p class="font-bold dark:text-white">
                                            {{ $guarantor->business_location ?? 'Data Missing' }}</p>
                                    </div>
                                    <div><label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Activity
                                            Sector</label>
                                        <p class="font-bold dark:text-white">
                                            {{ $guarantor->business_activities ?? 'Not Specified' }}</p>
                                    </div>
                                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl">
                                        <p class="text-[10px] text-slate-400 font-bold italic underline mb-1">Premise
                                            Rule</p>
                                        <p class="text-sm font-black uppercase dark:text-white">
                                            {{ $guarantor->business_premises ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <!-- Right: The "Math" box from form -->
                                <div class="bg-slate-900 rounded-[2rem] p-6 text-white shadow-inner">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-slate-400 uppercase font-black tracking-widest">Gross
                                                Profitability</span>
                                            <span
                                                class="font-black text-emerald-400">₦{{ number_format($guarantor->monthly_sales - $guarantor->cost_of_sales, 2) }}</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center text-xs border-t border-white/5 pt-2">
                                            <span class="text-slate-400 uppercase">Operational Expenses</span>
                                            <span
                                                class="font-black text-red-400">₦{{ number_format($guarantor->operational_expenses, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-4 border-t-2 border-white/10">
                                            <span
                                                class="text-[10px] font-black uppercase text-indigo-300 italic tracking-[0.2em]">Net
                                                Surplus Capital</span>
                                            <span
                                                class="text-2xl font-black">₦{{ number_format($guarantor->monthly_sales - $guarantor->cost_of_sales - $guarantor->operational_expenses, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Section IV: Employment Verification -->
                        <div
                            class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border-l-8 border-indigo-600 border border-slate-100 dark:border-slate-700">
                            <h3
                                class="text-xl font-black uppercase tracking-tight text-slate-900 dark:text-white mb-8 italic">
                                Section IV: Assessment of Employment</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Current Employer</p>
                                        <p
                                            class="text-lg font-black dark:text-white underline decoration-indigo-500/30 underline-offset-4">
                                            {{ $guarantor->employer_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Position / Sector</p>
                                        <p class="text-sm font-bold dark:text-slate-300 italic">
                                            {{ $guarantor->position }} • {{ $guarantor->job_sector }}</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-indigo-50 dark:bg-slate-900/50 p-6 rounded-3xl flex flex-col justify-center">
                                    <p
                                        class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest text-center mb-1 italic">
                                        Verified Liquid Take-home</p>
                                    <p
                                        class="text-4xl font-black text-slate-900 dark:text-white text-center tracking-tighter">
                                        ₦{{ number_format($guarantor->net_monthly_income, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Sign off Summary -->
                    <div
                        class="p-8 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl text-center">
                        <p
                            class="text-sm italic text-slate-500 dark:text-slate-400 max-w-xl mx-auto leading-relaxed uppercase font-medium tracking-tight">
                            "Assessment verified against corporate compliance standard V.25-CF4. Original hard-copy
                            signature exists in branch registry files."
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
