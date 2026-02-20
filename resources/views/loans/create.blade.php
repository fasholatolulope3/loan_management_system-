<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
            📁 New Credit File Initiation
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        step: 1,
        collaterals: [{ type: 'HG', description: '', market_value: '' }],
        addCollateral() { this.collaterals.push({ type: 'HG', description: '', market_value: '' }) },
        removeCollateral(index) { this.collaterals.splice(index, 1) }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Error Display -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-lg rounded-r-xl">
                    <ul class="list-disc pl-5 text-xs font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Progress Stepper (Requirement #7 - Order) -->
            <div class="mb-8 flex items-center justify-between px-10">
                <template x-for="i in [1, 2, 3, 4, 5]">
                    <div class="flex items-center flex-1 last:flex-none">
                        <div :class="step >= i ? 'bg-indigo-600 text-white shadow-[0_0_15px_rgba(79,70,229,0.4)]' : 'bg-gray-200 dark:bg-slate-700 text-gray-500'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-black transition-all duration-300 transform"
                            :class="step === i ? 'scale-125' : ''" x-text="i"></div>
                        <div x-show="i < 5" class="flex-1 h-1 bg-gray-200 dark:bg-slate-700 mx-2">
                            <div class="h-full bg-indigo-600 transition-all duration-500"
                                :style="'width: ' + (step > i ? '100%' : '0%')"></div>
                        </div>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('loans.store') }}"
                class="bg-white dark:bg-slate-800 shadow-2xl rounded-[2.5rem] border border-slate-100 dark:border-slate-700 overflow-hidden transition-all duration-500">
                @csrf

                <!-- STEP 1: CLIENT PERSONAL & BUSINESS INFO -->
                <div x-show="step === 1" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-2xl">
                            <x-heroicon-o-user class="w-6 h-6 text-indigo-600" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part I:
                            Client Identity & Location</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label value="Select Client" />
                            <select name="client_id" required
                                class="block mt-1 w-full border-gray-300 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choose Client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->user?->name }}
                                        ({{ $client->national_id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Loan Product (Daily/Weekly/Monthly)" />
                            <select name="loan_product_id" required
                                class="block mt-1 w-full border-gray-300 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}
                                        ({{ (int) $product->interest_rate }}% Interest)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label value="Principal Amount Requested (₦)" />
                            <x-text-input name="amount" type="number" step="1000"
                                class="w-full text-3xl font-black text-indigo-600 focus:ring-4" required />
                        </div>

                        <div class="md:col-span-2 pt-4 border-t dark:border-slate-700 mt-4">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 italic">Business
                                Profile</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><x-input-label value="Business Registered/Trade Name" /><x-text-input
                                        name="business_name" class="w-full mt-1" required /></div>
                                <div><x-input-label value="Precise Business Location" /><x-text-input
                                        name="business_location" class="w-full mt-1" required /></div>
                                <div>
                                    <x-input-label value="Premise Ownership" />
                                    <select name="business_premise_type"
                                        class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-900 dark:text-white">
                                        <option value="own">Owned / Personal</option>
                                        <option value="rent">Rented / Leased</option>
                                    </select>
                                </div>
                                <div><x-input-label value="Date Est. at this location" /><x-text-input
                                        name="business_start_date" type="date" class="w-full mt-1" required /></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: CLIENT CASH FLOW & BALANCE SHEET -->
                <div x-show="step === 2" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-2xl">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-emerald-600" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part II:
                            Client Business Financials</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            class="space-y-6 bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <h4 class="text-xs font-black text-emerald-600 uppercase italic mb-2">Cash Flow (Monthly
                                Avg)</h4>
                            <div><x-input-label value="Gross Sales / Revenue" /><x-text-input name="monthly_sales"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Cost of Sales (Stock/Raw Materials)" /><x-text-input
                                    name="cost_of_sales" type="number" class="w-full" required /></div>
                            <div><x-input-label value="Monthly Operational Exp. (Rent/Staff/Power)" /><x-text-input
                                    name="operational_expenses" type="number" class="w-full" required /></div>
                            <div><x-input-label value="Client Family/Personal Expenses" /><x-text-input
                                    name="family_expenses" type="number" class="w-full" required /></div>
                        </div>

                        <div
                            class="space-y-6 bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <h4 class="text-xs font-black text-emerald-600 uppercase italic mb-2">Balance Sheet (Current
                                Position)</h4>
                            <div><x-input-label value="Current Assets (Cash & Inventory)" /><x-text-input
                                    name="current_assets" type="number" class="w-full" required /></div>
                            <div><x-input-label value="Fixed Assets (Machinery/Equipment)" /><x-text-input
                                    name="fixed_assets" type="number" class="w-full" required /></div>
                            <div><x-input-label value="Total Liabilities (Existing Debts)" /><x-text-input
                                    name="total_liabilities" type="number" class="w-full" required /></div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: GUARANTOR FINANCIALS -->
                <div x-show="step === 3" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-2xl">
                            <x-heroicon-o-shield-check class="w-6 h-6 text-amber-600" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part III:
                            Guarantor Profile & Financials</h3>
                    </div>

                    <div class="space-y-8" x-data="{ addGuarantorMode: false }">
                        <div
                            class="bg-slate-50 dark:bg-slate-900/40 p-8 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                            <x-input-label value="Backing Guarantor (Registry)" />
                            <div class="flex gap-4 mt-2">
                                <select name="guarantor_id" x-show="!addGuarantorMode" required
                                    class="flex-1 rounded-xl border-gray-300 dark:bg-slate-900 dark:text-white">
                                    <option value="">-- Select Registered Guarantor --</option>
                                    @foreach (\App\Models\Guarantor::all() as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->phone }})</option>
                                    @endforeach
                                </select>
                                <button type="button" @click="addGuarantorMode = !addGuarantorMode"
                                    :class="addGuarantorMode ? 'bg-red-500' : 'bg-amber-600 shadow-[0_4px_10px_rgba(217,119,6,0.3)]'"
                                    class="px-6 py-2 text-white text-xs font-black rounded-xl uppercase tracking-tighter">
                                    <span x-text="addGuarantorMode ? 'Cancel' : '+ New Registry'"></span>
                                </button>
                            </div>

                            <div x-show="addGuarantorMode" x-transition
                                class="mt-6 pt-6 border-t dark:border-slate-700 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><x-input-label value="Guarantor Full Name" /><x-text-input name="g_name"
                                        class="w-full" /></div>
                                <div><x-input-label value="Relationship" /><x-text-input name="g_relationship"
                                        class="w-full" /></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h4 class="text-xs font-black text-amber-600 uppercase italic mb-2">Guarantor Cash Flow
                                </h4>
                                <div><x-input-label value="Avg. Monthly Income (₦)" /><x-text-input
                                        name="g_monthly_income" type="number" class="w-full" /></div>
                                <div><x-input-label value="Estimated Monthly Exp. (₦)" /><x-text-input
                                        name="g_monthly_expenses" type="number" class="w-full" /></div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="text-xs font-black text-amber-600 uppercase italic mb-2">Guarantor Assets/Net
                                    Worth</h4>
                                <div><x-input-label value="Estimated Net Asset Value (₦)" /><x-text-input
                                        name="g_net_worth" type="number" class="w-full" /></div>
                                <div>
                                    <x-input-label value="Primary Income Source" />
                                    <select name="g_income_source"
                                        class="w-full mt-1 border-gray-300 dark:bg-slate-900 rounded-xl">
                                        <option value="business">Business Ownership</option>
                                        <option value="employment">Salary / Employment</option>
                                        <option value="investment">Investments / Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: COLLATERAL -->
                <div x-show="step === 4" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-4">
                            <div class="bg-gray-100 dark:bg-slate-700/50 p-3 rounded-2xl">
                                <x-heroicon-o-briefcase class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                            </div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part
                                IV: Collateral Assessment</h3>
                        </div>
                        <button type="button" @click="addCollateral()"
                            class="text-[10px] font-black bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-5 py-2.5 rounded-full uppercase shadow-lg hover:scale-105 transition-transform">+
                            Add Asset</button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(collateral, index) in collaterals" :key="index">
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-slate-50 dark:bg-slate-900/60 rounded-3xl relative border border-slate-100 dark:border-slate-800 shadow-sm">
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Asset
                                        Category</label>
                                    <select :name="`collaterals[${index}][type]`"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-sm">
                                        <option value="HG">Household Good</option>
                                        <option value="FA">Fixed Asset</option>
                                        <option value="INV">Inventory</option>
                                        <option value="VMG">Vehicle</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Asset
                                        Description</label>
                                    <input type="text" :name="`collaterals[${index}][description]`"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-sm"
                                        placeholder="Specific details for liquidation valuation..." required />
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Fair
                                        Market Value (₦)</label>
                                    <input type="number" :name="`collaterals[${index}][market_value]`"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-sm font-black text-indigo-600"
                                        required />
                                </div>
                                <button type="button" @click="removeCollateral(index)"
                                    class="absolute -top-2 -right-2 bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-full w-8 h-8 flex items-center justify-center text-red-500 font-bold shadow-md hover:bg-red-50 hover:scale-110 transition-all">×</button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- STEP 5: FINAL PROPOSAL -->
                <div x-show="step === 5" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg">
                            <x-heroicon-o-document-check class="w-6 h-6 text-white" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Final
                            Underwriting Proposal</h3>
                    </div>

                    <div
                        class="bg-indigo-50 dark:bg-indigo-900/20 p-8 rounded-[2rem] border border-indigo-100 dark:border-indigo-800 shadow-inner">
                        <x-input-label value="Executive Summary & Recommendation Note"
                            class="text-indigo-600 font-black mb-2" />
                        <textarea name="proposal_summary" rows="6" required
                            class="w-full mt-2 rounded-[2rem] border-white dark:border-slate-700 dark:bg-slate-950/50 shadow-sm focus:ring-indigo-500 text-slate-700 dark:text-slate-200"
                            placeholder="Provide a professional summary of your assessment... Why should this loan be approved? (MIN 50 CHARS)"></textarea>
                        <p class="mt-4 text-[10px] font-black text-indigo-400 uppercase italic tracking-widest">
                            * Clicking authorize below will send this file to the Approved Authority for review.
                        </p>
                    </div>
                </div>

                <!-- FOOTER NAVIGATION -->
                <div
                    class="bg-slate-50 dark:bg-slate-900/80 p-8 border-t dark:border-slate-700 flex justify-between items-center">
                    <div>
                        <button type="button" x-show="step > 1" @click="step--"
                            class="text-xs font-black text-slate-500 uppercase flex items-center gap-2 hover:text-slate-900 dark:hover:text-white transition">
                            <x-heroicon-m-arrow-left class="w-4 h-4" /> Go Back
                        </button>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" x-show="step < 5" @click="step++"
                            class="bg-indigo-600 text-white px-10 py-3.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-indigo-700 hover:scale-105 active:scale-95 transition-all">
                            Next Assessment Step
                        </button>
                        <button type="submit" x-show="step === 5"
                            class="bg-slate-950 dark:bg-white dark:text-slate-900 text-white px-12 py-3.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-[0_10px_25px_rgba(0,0,0,0.2)] hover:scale-105 active:scale-95 transition-all">
                            Authorize & Submit Proposal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>