<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('clients.index') }}"
                                class="inline-flex items-center text-xs font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition">
                                Registry
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <x-heroicon-s-chevron-right class="w-3 h-3 text-slate-300 mx-2" />
                                <span
                                    class="text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-300">Member
                                    Dossier</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2
                    class="font-black text-3xl text-gray-900 dark:text-white uppercase tracking-tighter leading-none mb-2">
                    {{ $client->user?->name ?? 'Orphaned Account' }}
                </h2>
                <div class="flex flex-wrap items-center gap-3">
                    <span
                        class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20">
                        REG #{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <span
                        class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                        {{ $client->user?->collationCenter?->center_code ?? 'MAIN' }} //
                        {{ $client->user?->collationCenter?->name ?? 'HQ' }}
                    </span>
                    <span
                        class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-200 dark:border-emerald-800">
                        VERIFIED BY: {{ $client->officer?->name ?? 'SYSTEM' }}
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <a href="{{ route('clients.edit', $client) }}"
                    class="inline-flex items-center px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-900 transition shadow-sm">
                    <x-heroicon-s-pencil-square class="w-4 h-4 mr-2" /> Modify Profile
                </a>
                <a href="{{ route('loans.create', ['client_id' => $client->id]) }}"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-500/20 transition transform active:scale-95">
                    <x-heroicon-s-plus-circle class="w-4 h-4 mr-2" /> Initiate Credit Proposal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- SECTION 1: MASTER IDENTITY DOSSIER -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-12">

                    <!-- Profile Sidebar -->
                    <div
                        class="lg:col-span-4 bg-slate-50 dark:bg-slate-900/50 p-10 md:p-14 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800">
                        <div class="text-center mb-10">
                            <div class="relative inline-block">
                                <div
                                    class="w-32 h-32 bg-white dark:bg-slate-800 rounded-[2.5rem] flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-5xl shadow-2xl border-4 border-white dark:border-slate-700 mx-auto">
                                    {{ substr($client->user?->name ?? '?', 0, 1) }}
                                </div>
                                <div
                                    class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-2 rounded-2xl shadow-lg ring-4 ring-slate-50 dark:ring-slate-900">
                                    <x-heroicon-s-check-badge class="w-6 h-6" />
                                </div>
                            </div>
                            <h3
                                class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mt-6 mb-1">
                                {{ $client->user?->name }}
                            </h3>
                            <p
                                class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] italic">
                                MEMBER ACCOUNT // ACT-{{ $client->id }}
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div
                                class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                                <p
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 border-b dark:border-slate-700 pb-2">
                                    Primary Contact</p>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <x-heroicon-s-envelope class="w-4 h-4 text-slate-300" />
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 truncate">
                                            {{ $client->user?->email }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <x-heroicon-s-phone class="w-4 h-4 text-slate-300" />
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                            {{ $client->user?->phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                                <p
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 border-b dark:border-slate-700 pb-2">
                                    Residential Location</p>
                                <p
                                    class="text-[11px] font-bold text-slate-600 dark:text-slate-400 leading-relaxed italic">
                                    {{ $client->address }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Exhaustive Data Grid -->
                    <div class="lg:col-span-8 p-10 md:p-14 space-y-12">
                        <div>
                            <h4
                                class="text-xs font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-[0.2em] mb-8 flex items-center">
                                <span class="w-8 h-[1px] bg-indigo-600 mr-4"></span>
                                VERIFICATION DATA DOSSIER
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-12">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        National Identity Number</p>
                                    <p
                                        class="text-sm font-black text-slate-900 dark:text-white font-mono tracking-tighter bg-slate-50 dark:bg-slate-900 px-3 py-1 rounded-lg inline-block">
                                        {{ $client->national_id }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Bank
                                        Verification Number (BVN)</p>
                                    <p
                                        class="text-sm font-black text-slate-900 dark:text-white font-mono tracking-tighter bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-3 py-1 rounded-lg inline-block">
                                        {{ $client->bvn }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Date
                                        of Birth</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $client->date_of_birth->format('F d, Y') }} <span
                                            class="text-xs text-slate-400 ml-2">({{ $client->date_of_birth->age }}
                                            Years)</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        Employment Context</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white uppercase italic">
                                        {{ $client->employment_status }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        Reported Annual Revenue</p>
                                    <p class="text-2xl font-black text-emerald-600">
                                        ₦{{ number_format($client->income, 2) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        Registry Enrollment</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $client->created_at->format('M d, Y @ H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Officer Observations -->
                        @if($client->officer_comment)
                            <div
                                class="bg-indigo-900 rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl relative overflow-hidden">
                                <x-heroicon-s-chat-bubble-bottom-center-text
                                    class="absolute right-[-10px] top-[-10px] w-32 h-32 opacity-10" />
                                <h5 class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-300 mb-4 italic">
                                    Internal Forensic Observations</h5>
                                <p class="text-lg font-bold leading-relaxed italic relative z-10">
                                    "{{ $client->officer_comment }}"
                                </p>
                            </div>
                        @endif

                        <!-- SECTION 2: CREDIT PORTFOLIO SUMMIT -->
                        <div class="pt-10 border-t dark:border-slate-700">
                            <h4 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] mb-8">Active Credit
                                Standing</h4>
                            <div class="grid grid-cols-2 gap-6">
                                <div
                                    class="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                                    <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">
                                        Active Debt Exposure</p>
                                    <p class="text-xl font-black text-slate-900 dark:text-white italic">
                                        ₦{{ number_format($client->loans->where('status', 'active')->sum('amount'), 2) }}
                                    </p>
                                </div>
                                <div
                                    class="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                                    <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Total
                                        Arrears Value</p>
                                    <p class="text-xl font-black text-red-500 italic">
                                        ₦{{ number_format($client->loans->sum(fn($loan) => $loan->totalArrearsAmount()), 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: GUARANTOR FORENSIC ASSESSMENT -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                @foreach($client->guarantors as $guarantor)
                    <div
                        class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                        <div
                            class="px-10 py-8 bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700 flex justify-between items-center">
                            <h4
                                class="text-xs font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-widest italic">
                                Guarantor Profile: {{ $guarantor->name }}</h4>
                            <span
                                class="px-2 py-0.5 bg-indigo-600 text-white rounded text-[8px] font-black uppercase tracking-widest">{{ $guarantor->type ?? 'IDENTIFIED' }}</span>
                        </div>
                        <div class="p-10 space-y-8">
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        Relationship</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white uppercase">
                                        {{ $guarantor->relationship }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Identity & Status</p>
                                    <p class="text-[11px] font-black text-slate-700 dark:text-slate-300 uppercase">
                                        {{ $guarantor->sex === 'M' ? 'Male' : 'Female' }} // {{ $guarantor->marital_status ?? 'NOT SET' }}
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-400 mt-0.5">
                                        Born: {{ $guarantor->date_of_birth ? $guarantor->date_of_birth->format('M d, Y') : 'N/A' }} 
                                        ({{ $guarantor->dependent_persons ?? 0 }} Dependents)
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Verified
                                        Phone</p>
                                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $guarantor->phone }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Permanent
                                        Residence</p>
                                    <p
                                        class="text-[11px] font-bold text-slate-600 dark:text-slate-400 leading-relaxed italic">
                                        {{ $guarantor->address }}</p>
                                </div>
                            </div>

                            <div
                                class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800 space-y-6">
                                <p
                                    class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border-b dark:border-slate-800 pb-2 flex items-center">
                                    <x-heroicon-s-briefcase class="w-3 h-3 mr-2" /> Economic Standing
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Employer / Biz Name</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white capitalize">
                                            {{ $guarantor->employer_name ?? 'NOT RECORDED' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Job
                                            Sector</p>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                            {{ $guarantor->job_sector ?? 'GENERAL' }}</p>
                                    </div>
                                    @if($guarantor->employer_address)
                                        <div class="col-span-full">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                                Office / Biz Location</p>
                                            <p
                                                class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-start gap-2">
                                                <x-heroicon-s-map-pin class="w-3 h-3 mt-1 text-slate-400 shrink-0" />
                                                {{ $guarantor->employer_address }}
                                            </p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">
                                            Monthly Discretionary Income</p>
                                        <p class="text-lg font-black text-emerald-600">
                                            ₦{{ number_format($guarantor->net_monthly_income + $guarantor->avg_monthly_sales) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($guarantor->spouse_name)
                                <div
                                    class="px-6 py-4 bg-slate-50 dark:bg-slate-950/30 rounded-2xl border border-transparent hover:border-slate-200 transition flex justify-between items-center">
                                    <div>
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">
                                            Authorized Spouse Proxy</p>
                                        <p class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            {{ $guarantor->spouse_name }}</p>
                                    </div>
                                    <p class="text-[10px] font-black text-indigo-500">{{ $guarantor->spouse_phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($client->guarantors->count() < 1)
                    <div
                        class="col-span-full bg-red-50 dark:bg-red-900/10 p-12 rounded-[3rem] border-2 border-dashed border-red-200 dark:border-red-900/30 text-center">
                        <x-heroicon-o-shield-exclamation class="w-16 h-16 text-red-300 mx-auto mb-4" />
                        <h5 class="text-lg font-black text-red-800 dark:text-red-400 uppercase tracking-widest">CRITICAL
                            RISK ALERT</h5>
                        <p
                            class="text-sm text-red-600 dark:text-red-500/70 font-bold max-w-md mx-auto italic leading-relaxed">
                            No verified guarantor records found for this member account. This file is currently
                            non-compliant with standard credit policy.</p>
                    </div>
                @endif
            </div>

            <!-- SECTION 4: EXHAUSTIVE DOCUMENT ARCHIVE -->
            <div class="bg-slate-900 rounded-[3rem] shadow-2xl overflow-hidden mb-12">
                <div class="px-10 py-8 border-b border-white/10 flex justify-between items-center bg-white/[0.02]">
                    <h4 class="text-xs font-black uppercase text-indigo-400 tracking-[0.2em] flex items-center">
                        <x-heroicon-s-folder-open class="w-5 h-5 mr-3 text-rose-500" /> Member Document Repository
                    </h4>
                    <span
                        class="text-[10px] font-black text-white/30 uppercase tracking-widest">{{ $client->documents->count() }}
                        Files Archived</span>
                </div>

                <div class="p-10 md:p-14">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @foreach($client->documents as $doc)
                            <div class="group relative">
                                <p
                                    class="text-[9px] font-black uppercase text-white/40 mb-4 tracking-widest text-center border-b border-white/5 pb-2">
                                    {{ str_replace('_', ' ', $doc->type) }}
                                </p>

                                @if(in_array($doc->mime_type, ['image/jpeg', 'image/png', 'image/jpg']))
                                    <div
                                        class="relative aspect-square rounded-[2rem] overflow-hidden bg-white/5 ring-1 ring-white/10 group-hover:ring-indigo-500 transition-all duration-500 shadow-2xl">
                                        <img src="{{ asset('storage/' . $doc->file_path) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                        <div
                                            class="absolute inset-0 bg-indigo-950/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-sm">
                                            <div class="flex gap-3 scale-75 group-hover:scale-100 transition duration-500">
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                    class="p-4 bg-white rounded-full shadow-2xl hover:bg-slate-100 transition">
                                                    <x-heroicon-s-eye class="w-5 h-5 text-indigo-900" />
                                                </a>
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" download
                                                    class="p-4 bg-indigo-600 rounded-full shadow-2xl hover:bg-slate-900 transition">
                                                    <x-heroicon-s-arrow-down-tray class="w-5 h-5 text-white" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="aspect-square bg-white/5 rounded-[2.5rem] flex flex-col items-center justify-center ring-1 ring-white/10 group-hover:ring-rose-500 transition-all duration-500 shadow-xl">
                                        <x-heroicon-s-document-text
                                            class="w-16 h-16 text-white/10 mb-2 group-hover:text-rose-500 transition" />
                                        <span class="text-[9px] font-black text-white/20 uppercase">PDF ARCHIVE</span>
                                        <div
                                            class="absolute inset-x-4 bottom-4 flex gap-2 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition duration-500">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                class="flex-1 py-3 bg-white/10 rounded-xl text-[9px] font-black text-white text-center hover:bg-white/20 transition">VIEW</a>
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" download
                                                class="flex-1 py-3 bg-indigo-600 rounded-xl text-[9px] font-black text-white text-center hover:bg-indigo-700 transition">SAVE</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION 5: HISTORICAL LEDGER -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div class="px-10 py-6 border-b dark:border-slate-700 flex justify-between items-center">
                    <h4 class="text-xs font-black uppercase text-slate-500 dark:text-slate-400 tracking-[0.2em] italic">
                        Internal Registry Ledger</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-50 dark:divide-slate-700">
                        <thead class="bg-slate-50/50 dark:bg-slate-900/30">
                            <tr>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Protocol Type</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Approved Value</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Activation Date</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    Current Status</th>
                                <th class="px-10 py-5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($client->loans as $loan)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                                    <td class="px-10 py-6">
                                        <p
                                            class="text-sm font-black text-slate-900 dark:text-white mb-0.5 capitalize italic">
                                            {{ $loan->product->name }}</p>
                                        <p
                                            class="text-[9px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">
                                            {{ $loan->installment_count }} PAYMENT CYCLES</p>
                                    </td>
                                    <td class="px-10 py-6 font-black text-sm text-slate-700 dark:text-slate-300">
                                        ₦{{ number_format($loan->amount, 2) }}
                                    </td>
                                    <td class="px-10 py-6 text-xs font-bold text-slate-500">
                                        {{ $loan->start_date ? $loan->start_date->format('M d, Y') : 'PENDING' }}
                                    </td>
                                    <td class="px-10 py-6 text-center">
                                        <span
                                            class="px-3 py-1 text-[9px] font-black rounded-lg border uppercase {{ $loan->status === 'active' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-slate-100 dark:bg-slate-900/80 text-slate-500 border-slate-200 dark:border-slate-800' }}">
                                            {{ $loan->status }}
                                        </span>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <a href="{{ route('loans.show', $loan) }}"
                                            class="text-xs font-black text-indigo-600 hover:text-slate-900 transition underline underline-offset-4">&rarr;
                                            VIEW FILE</a>
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