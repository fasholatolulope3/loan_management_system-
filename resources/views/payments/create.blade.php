<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-200 leading-tight">
            {{ auth()->user()->role === 'client' ? __('Submit Payment Notification') : __('Record Loan Collection') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <!-- Payment Info Summary Card -->
            <div
                class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">

                <div
                    class="mb-8 p-6 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xs font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-widest">
                            Transaction for Loan #{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        <span
                            class="px-2 py-1 bg-white dark:bg-slate-900 rounded text-[10px] font-bold dark:text-indigo-300 italic border border-indigo-100 dark:border-indigo-900">
                            {{ optional($schedule->due_date)->format('M Y') }} Installment
                        </span>
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm text-slate-600 dark:text-slate-400"><strong>Beneficiary:</strong> SmartLoan
                            Inc.</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400"><strong>Payment Due:</strong>
                            {{ optional($schedule->due_date)->format('d M, Y') }}</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white mt-3">
                            Expected: <span
                                class="text-indigo-600 dark:text-indigo-400">₦{{ number_format($schedule->total_due, 2) }}</span>
                        </p>
                    </div>
                </div>

                <!-- Main Payment Form (Added enctype for file support) -->
                <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                    <div class="space-y-6">

                        <!-- 1. Amount Paid -->
                        <div>
                            <x-input-label for="amount_paid" :value="auth()->user()->role === 'client' ? __('Exact Amount Transferred (₦)') : __('Amount Collected (₦)')" />
                            <x-text-input id="amount_paid"
                                class="block mt-1 w-full font-black text-xl dark:bg-slate-950 dark:border-slate-700"
                                type="number" step="0.01" name="amount_paid" :value="old('amount_paid', $schedule->total_due)" required />
                            <x-input-error :messages="$errors->get('amount_paid')" class="mt-2" />
                        </div>

                        <!-- 2. Method Selection -->
                        <div>
                            <x-input-label for="method" :value="__('Payment Channel')" />
                            <select name="method" id="method"
                                class="block mt-1 w-full border-gray-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm">
                                <option value="transfer" {{ old('method') == 'transfer' ? 'selected' : '' }}>Bank
                                    Transfer / USSD</option>
                                <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash (Over the
                                    Counter)</option>
                                <option value="card" {{ old('method') == 'card' ? 'selected' : '' }}>POS Terminal
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('method')" class="mt-2" />
                        </div>

                        <!-- 3. Reference/Receipt No. -->
                        <div>
                            <x-input-label for="reference" :value="__('Transaction Reference / ID')" />
                            <x-text-input id="reference"
                                class="block mt-1 w-full dark:bg-slate-950 dark:border-slate-700" type="text"
                                name="reference" :placeholder="auth()->user()->role === 'client' ? 'e.g. TRF/99800122' : 'Bank Slip No.'" :value="old('reference', 'PAY-' . strtoupper(Str::random(6)) . '-' . time())" required />
                            <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                        </div>

                        <!-- 4. NEW: Receipt Upload Field -->
                        <div>
                            <x-input-label for="receipt" :value="__('Upload Evidence (Screenshot or Photo)')" />
                            <div
                                class="mt-1 flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-slate-700 border-dashed rounded-xl hover:border-indigo-400 transition cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-slate-400">
                                        <label for="receipt"
                                            class="relative cursor-pointer rounded-md font-bold text-indigo-600 hover:text-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="receipt" name="receipt" type="file" class="sr-only" required
                                                accept="image/*,application/pdf">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-slate-500">PNG, JPG, PDF up to 2MB</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('receipt')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t dark:border-slate-700">
                            <x-primary-button
                                class="w-full justify-center py-4 bg-indigo-600 dark:bg-indigo-500 font-black tracking-widest uppercase shadow-xl hover:scale-[1.01] transition transform active:scale-95">
                                {{ auth()->user()->role === 'client' ? __('Submit Notification') : __('Verify & Complete Record') }}
                            </x-primary-button>

                            <div class="mt-6 text-center">
                                <a href="{{ route('loans.show', $loan->id) }}"
                                    class="text-xs font-bold text-slate-500 dark:text-slate-500 hover:text-indigo-600 uppercase tracking-widest">
                                    &larr; Return to Loan Overview
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Client Notification Info -->
            @if (auth()->user()->role === 'client')
                <div
                    class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/50 rounded-2xl flex items-start gap-4">
                    <x-heroicon-o-shield-check class="w-6 h-6 text-amber-600" />
                    <div>
                        <h4 class="text-sm font-black text-amber-800 dark:text-amber-500 uppercase">Verification
                            Protocol</h4>
                        <p class="text-xs leading-relaxed text-amber-700 dark:text-amber-600 font-medium">
                            Our team will verify this upload against our bank statement. This process typically takes
                            1-2 hours during business hours. You will receive a notification once verified.
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
