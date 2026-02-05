<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
            🏦 {{ __('Define New Loan Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Validation Error Feedback -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-md rounded-r-xl">
                    <div class="flex items-center mb-2">
                        <x-heroicon-s-x-circle class="w-5 h-5 mr-2" />
                        <p class="font-bold uppercase text-xs">Configuration Errors detected</p>
                    </div>
                    <ul class="list-disc pl-5 text-xs font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-2xl rounded-[2rem] border border-slate-100 dark:border-slate-700 p-8 md:p-12">
                <div class="mb-8 border-b dark:border-slate-700 pb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Product Configuration</h3>
                    <p class="text-sm text-slate-500">Define the core financial rules for this lending category. Note:
                        This will be accessed by approved authorities only.</p>
                </div>

                <form method="POST" action="{{ route('loan-products.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <!-- REQUIREMENT #2: Name Category Selection -->
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Loan Product Category" />
                            <select name="name" id="name" required
                                class="block mt-1 w-full border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choose Category --</option>
                                <option value="Daily" {{ old('name') == 'Daily' ? 'selected' : '' }}>Daily Loan
                                    (Interval: 1 Day)</option>
                                <option value="Weekly" {{ old('name') == 'Weekly' ? 'selected' : '' }}>Weekly Loan
                                    (Interval: 7 Days)</option>
                                <option value="Monthly" {{ old('name') == 'Monthly' ? 'selected' : '' }}>Monthly Loan
                                    (Interval: 1 Month)</option>
                            </select>
                            <p class="mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Choose
                                'Daily' (10%), 'Weekly' (20%), or 'Monthly' (30%)</p>
                        </div>

                        <!-- REQUIREMENT #3: Interest Rate -->
                        <div>
                            <x-input-label for="interest_rate" value="Fixed Interest Rate (%)" />
                            <x-text-input name="interest_rate"
                                class="block mt-1 w-full dark:bg-slate-950 font-black text-indigo-600" type="number"
                                step="0.01" :value="old('interest_rate')" placeholder="e.g. 10.00" required />
                        </div>

                        <!-- REQUIREMENT #1: Penalty Rate -->
                        <div>
                            <x-input-label for="penalty_rate" value="Daily Arrears Penalty (Rate)" />
                            <x-text-input name="penalty_rate"
                                class="block mt-1 w-full dark:bg-slate-950 font-mono text-red-500" type="number"
                                step="0.0001" :value="old('penalty_rate', 0.005)" required />
                            <p class="mt-1 text-[10px] text-slate-400 italic">Default set to required 0.005</p>
                        </div>

                        <!-- Principal Limits -->
                        <div>
                            <x-input-label for="min_amount" value="Min Principal Allowed (₦)" />
                            <x-text-input name="min_amount" class="block mt-1 w-full dark:bg-slate-950" type="number"
                                :value="old('min_amount')" required placeholder="0.00" />
                        </div>

                        <div>
                            <x-input-label for="max_amount" value="Max Principal Allowed (₦)" />
                            <x-text-input name="max_amount"
                                class="block mt-1 w-full dark:bg-slate-950 font-bold text-slate-900" type="number"
                                :value="old('max_amount')" required placeholder="0.00" />
                        </div>

                        <!-- Repayment Period -->
                        <div>
                            <x-input-label for="duration_months" value="Number of Repayments" />
                            <x-text-input name="duration_months" class="block mt-1 w-full dark:bg-slate-950"
                                type="number" :value="old('duration_months')" required placeholder="Total installments" />
                            <p class="mt-1 text-[10px] text-slate-500">e.g. 12 days for Daily, 4 weeks for Weekly.</p>
                        </div>

                        <!-- Operational Status -->
                        <div>
                            <x-input-label for="status" value="Operational Status" />
                            <select name="status"
                                class="block mt-1 w-full border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-xl shadow-sm">
                                <option value="active">Available for Disbursement</option>
                                <option value="inactive">Paused / Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-12 flex items-center justify-between border-t dark:border-slate-700 pt-8">
                        <a href="{{ route('loan-products.index') }}"
                            class="text-sm font-bold text-slate-500 hover:text-indigo-600 transition">
                            &larr; Return to Registry
                        </a>
                        <x-primary-button
                            class="px-10 py-4 bg-indigo-600 dark:bg-white dark:text-slate-900 font-black uppercase tracking-widest text-xs shadow-2xl hover:scale-105 transition active:scale-95">
                            Establish Product Rules
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
