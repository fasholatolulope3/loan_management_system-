<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
                ⚙️ {{ __('Modify Product Rules') }}: {{ $loanProduct->name }}
            </h2>
            <a href="{{ route('loan-products.index') }}"
                class="text-sm font-bold text-slate-500 hover:text-indigo-600 transition no-print">
                &larr; Return to Registry
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Policy Validation Feedback -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-md rounded-r-xl">
                    <div class="flex items-center mb-2">
                        <x-heroicon-s-exclamation-triangle class="w-5 h-5 mr-2" />
                        <p class="font-bold uppercase text-xs">Business Rule Violation</p>
                    </div>
                    <ul class="list-disc pl-5 text-xs font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-2xl rounded-[2.5rem] border border-slate-100 dark:border-slate-700 p-8 md:p-12">
                <div class="mb-8 border-b dark:border-slate-700 pb-6 flex justify-between items-end">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Financial
                            Parameters</h3>
                        <p class="text-sm text-slate-500 italic">Adjusting these values will affect future loan
                            applications.</p>
                    </div>
                    <span
                        class="text-[10px] font-black px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-full border border-indigo-100 dark:border-indigo-900 uppercase">
                        Product ID: #{{ str_pad($loanProduct->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('loan-products.update', $loanProduct) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <!-- REQUIREMENT #2: Descriptive names allowed -->
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Product Name/Tier" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full" type="text"
                                :value="old('name', $loanProduct->name)" placeholder="e.g. Weekly - 4 Weeks" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- REQUIREMENT #3: Specific Interest Rates (IMMUTABLE) -->
                        <div>
                            <x-input-label for="interest_rate_display" value="Annual Interest Rate (%)" />
                            <x-text-input id="interest_rate_display"
                                class="block mt-1 w-full bg-slate-100 dark:bg-slate-900 font-black text-slate-500 text-lg cursor-not-allowed"
                                type="text" :value="$loanProduct->interest_rate . '%'" readonly />
                            <p class="mt-1 text-[10px] text-red-500 uppercase font-bold tracking-tighter italic">Fixed
                                Policy Rule: This value cannot be altered via UI.</p>
                        </div>

                        <!-- REQUIREMENT #1: The 0.005 Penalty Rate (IMMUTABLE) -->
                        <div>
                            <x-input-label for="penalty_rate_display" value="Late Fee Penalty (Rate)" />
                            <x-text-input id="penalty_rate_display"
                                class="block mt-1 w-full bg-slate-100 dark:bg-slate-900 font-mono text-slate-500 text-lg cursor-not-allowed"
                                type="text" :value="$loanProduct->penalty_rate" readonly />
                            <p class="mt-1 text-[10px] text-red-500 uppercase font-bold tracking-tighter italic">Fixed
                                Policy Rule: 0.005 (Immutable)</p>
                        </div>

                        <!-- Liquidity Bounds -->
                        <div class="pt-4 border-t dark:border-slate-700 md:col-span-2 mt-4">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Capital
                                Disbursement Limits</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="min_amount" value="Min Principal (₦)" />
                                    <x-text-input id="min_amount" name="min_amount"
                                        class="block mt-1 w-full dark:bg-slate-950" type="number"
                                        :value="old('min_amount', $loanProduct->min_amount)" required />
                                </div>

                                <div>
                                    <x-input-label for="max_amount" value="Max Principal (₦)" />
                                    <x-text-input id="max_amount" name="max_amount"
                                        class="block mt-1 w-full dark:bg-slate-950 font-bold" type="number"
                                        :value="old('max_amount', $loanProduct->max_amount)" required />
                                </div>
                            </div>
                        </div>

                        <!-- Duration Logic -->
                        <div>
                            <x-input-label for="duration_months" value="Total Installments" />
                            <x-text-input id="duration_months" name="duration_months"
                                class="block mt-1 w-full dark:bg-slate-950" type="number" :value="old('duration_months', $loanProduct->duration_months)" required />
                            <p class="mt-1 text-[10px] text-slate-500 italic leading-tight">Represents the total count
                                of payments across the tenure.</p>
                        </div>

                        <!-- Lifecycle Status -->
                        <div>
                            <x-input-label for="status" value="Operational Status" />
                            <select name="status" id="status"
                                class="block mt-1 w-full border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-xl shadow-sm">
                                <option value="active" {{ old('status', $loanProduct->status) == 'active' ? 'selected' : '' }}>Available
                                    for Operations</option>
                                <option value="inactive" {{ old('status', $loanProduct->status) == 'inactive' ? 'selected' : '' }}>Registry
                                    Only (Disabled)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Final Submission -->
                    <div class="mt-12 flex items-center justify-end border-t dark:border-slate-700 pt-8 gap-4">
                        <x-secondary-button type="reset" class="px-6 py-4">
                            Reset Fields
                        </x-secondary-button>

                        <x-primary-button
                            class="px-12 py-4 bg-slate-900 dark:bg-white dark:text-slate-900 font-black uppercase tracking-widest text-xs shadow-2xl hover:scale-[1.02] transition transform active:scale-95">
                            Update Registry Information
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>