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
                                {{ $client->employment_status }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Phone</span>
                                <span
                                    class="text-sm font-bold dark:text-slate-300">{{ $client->user?->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Date of Birth</span>
                                <span
                                    class="text-sm font-bold dark:text-slate-300">{{ $client->date_of_birth->format('d M, Y') }}
                                    ({{ $client->date_of_birth->age }} yrs)</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase">National ID</span>
                                <span
                                    class="text-sm font-bold dark:text-slate-300 font-mono tracking-tighter">{{ $client->national_id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase">BVN Status</span>
                                <span
                                    class="text-sm font-bold dark:text-slate-300 font-mono tracking-tighter text-indigo-600">{{ $client->bvn }}</span>
                            </div>
                            <div class="border-t dark:border-slate-700 pt-4">
                                <span class="text-[10px] font-black text-slate-400 uppercase block mb-1">Residential
                                    Address</span>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 leading-relaxed italic">
                                    {{ $client->address }}
                                </p>
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
                                    class="group p-5 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border border-transparent hover:border-indigo-200 dark:hover:border-indigo-900 transition">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p
                                                class="text-sm font-black text-slate-900 dark:text-white uppercase leading-tight">
                                                {{ $guarantor->name }}
                                            </p>
                                            <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">
                                                {{ $guarantor->relationship }} • {{ $guarantor->phone }}
                                            </p>
                                        </div>
                                        <span
                                            class="text-[8px] font-black bg-indigo-600 text-white px-2 py-0.5 rounded uppercase leading-none">{{ $guarantor->type ?? 'Standard' }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-y-2 mb-3 border-t dark:border-slate-800 pt-3">
                                        <div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase block">Gender</span>
                                            <span
                                                class="text-[10px] font-bold dark:text-slate-300 uppercase">{{ $guarantor->sex ?? 'N/A' }}</span>
                                        </div>
                                        @if($guarantor->net_monthly_income > 0)
                                            <div>
                                                <span class="text-[8px] font-black text-emerald-500 uppercase block">Verified
                                                    Income</span>
                                                <span
                                                    class="text-[10px] font-black text-emerald-600">₦{{ number_format($guarantor->net_monthly_income) }}</span>
                                            </div>
                                        @endif
                                        @if($guarantor->employer_name)
                                            <div class="col-span-2">
                                                <span class="text-[8px] font-black text-slate-400 uppercase block">Employer /
                                                    Position</span>
                                                <span
                                                    class="text-[10px] font-bold dark:text-slate-300 capitalize">{{ $guarantor->employer_name }}
                                                    ({{ $guarantor->position ?? 'N/A' }})</span>
                                            </div>
                                        @endif
                                        @if($guarantor->business_activity)
                                            <div class="col-span-2">
                                                <span class="text-[8px] font-black text-slate-400 uppercase block">Business
                                                    Activity</span>
                                                <span
                                                    class="text-[10px] font-bold dark:text-slate-300 capitalize">{{ $guarantor->business_activity }}
                                                    (Monthly Sales:
                                                    ₦{{ number_format($guarantor->avg_monthly_sales ?? 0) }})</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($guarantor->spouse_name)
                                        <div
                                            class="bg-white dark:bg-slate-800/50 p-3 rounded-2xl mb-3 border dark:border-slate-700">
                                            <span class="text-[8px] font-black text-slate-400 uppercase block mb-1">Guarantor
                                                Spouse</span>
                                            <p class="text-[10px] font-bold text-slate-600 dark:text-slate-300">
                                                {{ $guarantor->spouse_name }} ({{ $guarantor->spouse_phone ?? 'No Phone' }})
                                            </p>
                                        </div>
                                    @endif

                                    <div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase block">Residential
                                            Address</span>
                                        <p class="text-[9px] text-slate-500 dark:text-slate-400 italic leading-tight">
                                            {{ $guarantor->address ?? 'No address on file' }}
                                        </p>
                                    </div>
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
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition duration-300">
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ $loan->product->name }} Plan
                                                </div>
                                                <div class="text-[10px] text-indigo-500 font-black uppercase">
                                                    {{ $loan->installment_count }} Installments
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="text-sm font-black text-slate-900 dark:text-white">
                                                    ₦{{ number_format($loan->amount, 2) }}</div>
                                                <div class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">
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

                    <!-- Client Document Repository (Requirement #1 & #6) -->
                    <div
                        class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden mt-8">
                        <div
                            class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700 flex justify-between items-center">
                            <h4
                                class="text-sm font-black uppercase text-slate-900 dark:text-white tracking-widest italic flex items-center">
                                <x-heroicon-s-folder-open class="w-5 h-5 mr-3 text-rose-500" />
                                KYC Document Repository
                            </h4>
                            <div class="text-[10px] font-black uppercase text-slate-400">
                                Verified by: <span
                                    class="text-indigo-600">{{ $client->officer?->name ?? 'System' }}</span>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                @forelse($client->documents as $doc)
                                    <div
                                        class="bg-slate-50 dark:bg-slate-950/50 p-4 rounded-3xl border border-slate-100 dark:border-slate-800 group transition">
                                        <p class="text-[9px] font-black uppercase text-slate-400 mb-3 tracking-widest">
                                            {{ str_replace('_', ' ', $doc->type) }}
                                        </p>

                                        @if(in_array($doc->mime_type, ['image/jpeg', 'image/png', 'image/jpg']))
                                            <div class="relative overflow-hidden rounded-2xl aspect-square mb-3">
                                                <img src="{{ asset('storage/' . $doc->file_path) }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                                <div
                                                    class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                        class="p-2 bg-white rounded-full shadow-xl">
                                                        <x-heroicon-s-eye class="w-4 h-4 text-indigo-600" />
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div
                                                class="aspect-square bg-slate-100 dark:bg-slate-900 rounded-2xl flex flex-col items-center justify-center mb-3">
                                                <x-heroicon-s-document-text class="w-10 h-10 text-slate-300 mb-2" />
                                                <span class="text-[8px] font-black text-slate-500 uppercase">PDF DOCUMENT</span>
                                            </div>
                                        @endif

                                        <a href="{{ asset('storage/' . $doc->file_path) }}" download
                                            class="flex items-center justify-center w-full py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[9px] font-black uppercase text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition">
                                            <x-heroicon-s-arrow-down-tray class="w-3 h-3 mr-1.5" /> Download
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-span-full py-12 text-center">
                                        <x-heroicon-o-cloud-arrow-up
                                            class="w-12 h-12 text-slate-200 dark:text-slate-700 mx-auto mb-3" />
                                        <p class="text-xs text-slate-400 font-black uppercase tracking-widest">No verified
                                            archives present.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div
                            class="px-8 py-4 bg-slate-50 dark:bg-slate-900/50 border-t dark:border-slate-700 flex justify-between items-center text-[9px] font-black uppercase text-slate-400">
                            <span>Registration Date: {{ $client->created_at->format('d M, Y H:i') }}</span>
                            <span>Total Archives: {{ $client->documents->count() }}</span>
                        </div>
                    </div>

                    <!-- Officer Assessment (Requirement #5) -->
                    @if($client->officer_comment)
                        <div
                            class="bg-indigo-50 dark:bg-slate-900 p-8 rounded-[2.5rem] border-2 border-indigo-100 dark:border-indigo-900/30 mt-8">
                            <h4
                                class="text-xs font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-widest mb-4 flex items-center">
                                <x-heroicon-s-chat-bubble-bottom-center-text class="w-4 h-4 mr-2" />
                                Internal Officer Assessment
                            </h4>
                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium italic leading-relaxed">
                                "{{ $client->officer_comment }}"
                            </p>
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-[10px] font-black">
                                        {{ substr($client->officer?->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase">
                                            {{ $client->officer?->name ?? 'System' }}
                                        </p>
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tight italic">Field
                                            Verification Officer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>