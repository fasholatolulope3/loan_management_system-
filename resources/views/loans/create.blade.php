<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
            📁 New Credit File Initiation
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        step: 1,
        collaterals: [{ type: 'HG', description: '', market_value: '' }],
        daily_sales: [0, 0, 0],
        purchase_history: [{ item: '', cost: 0 }],
        inventory: [{ item: '', value: 0 }],
        risks: [{ factor: '', mitigation: '' }],
        references: [{ name: '', phone: '' }],
        addCollateral() { this.collaterals.push({ type: 'HG', description: '', market_value: '' }) },
        removeCollateral(index) { this.collaterals.splice(index, 1) },
        previews: { nin: null, selfie: null, nepa_bill: null, shop_picture: null, house_picture: null },
        fileNames: { nin: '', selfie: '', nepa_bill: '', shop_picture: '', house_picture: '', collateral_document: '', statement_of_account: '' },
        handleFileChange(event, type) {
            const file = event.target.files[0];
            if (!file) return;
            this.fileNames[type] = file.name;
            if (['nin', 'selfie', 'nepa_bill', 'shop_picture', 'house_picture'].includes(type)) {
                const reader = new FileReader();
                reader.onload = (e) => { this.previews[type] = e.target.result; };
                reader.readAsDataURL(file);
            }
        }
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
                <template x-for="i in [1, 2, 3, 4, 5, 6, 7]">
                    <div class="flex items-center flex-1 last:flex-none">
                        <div :class="step >= i ? 'bg-indigo-600 text-white shadow-[0_0_15px_rgba(79,70,229,0.4)]' : 'bg-gray-200 dark:bg-slate-700 text-gray-500'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-black transition-all duration-300 transform"
                            :class="step === i ? 'scale-125' : ''" x-text="i"></div>
                        <div x-show="i < 7" class="flex-1 h-1 bg-gray-200 dark:bg-slate-700 mx-2">
                            <div class="h-full bg-indigo-600 transition-all duration-500"
                                :style="'width: ' + (step > i ? '100%' : '0%')"></div>
                        </div>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('loans.store') }}" enctype="multipart/form-data"
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
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 italic">
                                Applicant Demographics</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><x-input-label value="Residence Since" /><x-text-input name="residence_since"
                                        class="w-full mt-1" placeholder="e.g. 15 Years" required /></div>
                                <div><x-input-label value="No. of Dependents" /><x-text-input name="dependent_count"
                                        type="number" class="w-full mt-1" required /></div>
                                <div>
                                    <x-input-label value="Home Ownership" />
                                    <select name="home_ownership" x-model="homeType"
                                        class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-900 dark:text-white">
                                        <option value="owned">Personal Home</option>
                                        <option value="renting">Renting</option>
                                        <option value="family">Family Home</option>
                                        <option value="mortgage">Mortgage</option>
                                    </select>
                                </div>
                                <div x-show="homeType === 'renting'" x-transition>
                                    <x-input-label value="Next Rent Amount (₦)" /><x-text-input name="next_rent_amount"
                                        type="number" class="w-full mt-1" />
                                </div>
                                <div x-show="homeType === 'renting'" x-transition>
                                    <x-input-label value="Next Rent Due Date" /><x-text-input name="next_rent_date"
                                        type="date" class="w-full mt-1" />
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 pt-4 border-t dark:border-slate-700 mt-4">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 italic">Business
                                Profile</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><x-input-label value="Business Trade Name" /><x-text-input name="business_name"
                                        class="w-full mt-1" required /></div>
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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div
                            class="space-y-6 bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <h4 class="text-xs font-black text-emerald-600 uppercase italic mb-2">Cash Flow (Monthly
                                Avg)</h4>
                            <div><x-input-label value="Gross Sales / Revenue" /><x-text-input name="monthly_sales"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Cost of Sales" /><x-text-input name="cost_of_sales" type="number"
                                    class="w-full" required /></div>
                            <div><x-input-label value="Operational Exp." /><x-text-input name="operational_expenses"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Client Family Exp." /><x-text-input name="family_expenses"
                                    type="number" class="w-full" required /></div>
                        </div>

                        <div
                            class="space-y-6 bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <h4 class="text-xs font-black text-emerald-600 uppercase italic mb-2">Balance Sheet Position
                            </h4>
                            <div><x-input-label value="Current Assets (Cash/INV)" /><x-text-input name="current_assets"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Fixed Assets (Machinery)" /><x-text-input name="fixed_assets"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Total Liabilities (Debts)" /><x-text-input
                                    name="total_liabilities" type="number" class="w-full" required /></div>
                        </div>

                        <div
                            class="space-y-6 bg-indigo-50 dark:bg-indigo-900/30 p-6 rounded-3xl border border-indigo-100 dark:border-indigo-800">
                            <h4 class="text-xs font-black text-indigo-600 uppercase italic mb-2">Business Metadata</h4>
                            <div><x-input-label value="Total Employees" /><x-text-input name="employee_count"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Points of Sale (POS)" /><x-text-input name="point_of_sale_count"
                                    type="number" class="w-full" required /></div>
                            <div class="pt-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="has_co_owners" value="1"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                    <span
                                        class="ml-2 text-sm text-slate-600 font-bold uppercase tracking-tighter">Business
                                        has Co-Owners</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: EVALUATION TABLES (JSON DATA) -->
                <div x-show="step === 3" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-2xl">
                            <x-heroicon-o-list-bullet class="w-6 h-6 text-indigo-600" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part III:
                            Assessment Evaluation Logs</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Daily Sales Log -->
                        <div
                            class="bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <h4 class="text-xs font-black text-indigo-600 uppercase mb-4">3-Day Sales Log (₦)</h4>
                            <div class="space-y-3">
                                <template x-for="(sale, i) in daily_sales" :key="i">
                                    <div class="flex gap-2 items-center">
                                        <span class="text-[10px] font-black w-12 text-slate-400"
                                            x-text="'Day ' + (i+1)"></span>
                                        <input type="number" :name="'daily_sales_logs['+i+']'"
                                            class="flex-1 rounded-xl border-gray-300 dark:bg-slate-800 text-sm"
                                            required>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Inventory Breakdown -->
                        <div
                            class="bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xs font-black text-indigo-600 uppercase">Inventory Breakdown</h4>
                                <button type="button" @click="inventory.push({item:'', value:0})"
                                    class="text-[9px] font-black uppercase text-indigo-600 hover:underline">+
                                    Add</button>
                            </div>
                            <div class="space-y-3 overflow-y-auto max-h-40 pr-2">
                                <template x-for="(inv, i) in inventory" :key="i">
                                    <div class="flex gap-2">
                                        <input type="text" :name="'inventory_details['+i+'][item]'"
                                            placeholder="Item Name"
                                            class="flex-1 rounded-xl border-gray-300 dark:bg-slate-800 text-xs"
                                            required>
                                        <input type="number" :name="'inventory_details['+i+'][value]'"
                                            placeholder="Value"
                                            class="w-24 rounded-xl border-gray-300 dark:bg-slate-800 text-xs" required>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Business References -->
                        <div
                            class="bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xs font-black text-indigo-600 uppercase">Business References</h4>
                                <button type="button" @click="references.push({name:'', phone:''})"
                                    class="text-[9px] font-black uppercase text-indigo-600 hover:underline">+
                                    Add</button>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(ref, i) in references" :key="i">
                                    <div class="flex gap-2">
                                        <input type="text" :name="'business_references['+i+'][name]'" placeholder="Name"
                                            class="flex-1 rounded-xl border-gray-300 dark:bg-slate-800 text-xs"
                                            required>
                                        <input type="text" :name="'business_references['+i+'][phone]'"
                                            placeholder="Phone"
                                            class="flex-1 rounded-xl border-gray-300 dark:bg-slate-800 text-xs"
                                            required>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Risk Mitigation -->
                        <div
                            class="bg-slate-50 dark:bg-slate-900/40 p-6 rounded-3xl border border-slate-100 dark:border-slate-700">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xs font-black text-indigo-600 uppercase">Risk Analysis</h4>
                                <button type="button" @click="risks.push({factor:'', mitigation:''})"
                                    class="text-[9px] font-black uppercase text-indigo-600 hover:underline">+
                                    Add</button>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(risk, i) in risks" :key="i">
                                    <div
                                        class="flex flex-col gap-1 border-b border-slate-200 pb-2 dark:border-slate-700">
                                        <input type="text" :name="'risk_mitigation['+i+'][factor]'"
                                            placeholder="Risk Factor"
                                            class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-xs"
                                            required>
                                        <input type="text" :name="'risk_mitigation['+i+'][mitigation]'"
                                            placeholder="Mitigation Plan"
                                            class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-xs"
                                            required>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: GUARANTOR FINANCIALS -->
                <div x-show="step === 4" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-2xl">
                            <x-heroicon-o-shield-check class="w-6 h-6 text-amber-600" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part IV:
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
                                        class="w-full" />
                                </div>
                                <div><x-input-label value="Relationship" /><x-text-input name="g_relationship"
                                        class="w-full" />
                                </div>
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

                <!-- STEP 5: COLLATERAL -->
                <div x-show="step === 5" x-transition.opacity.duration.400ms class="p-8 md:p-12">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-4">
                            <div class="bg-gray-100 dark:bg-slate-700/50 p-3 rounded-2xl">
                                <x-heroicon-o-briefcase class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                            </div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part
                                V: Collateral Assessment</h3>
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

                <!-- STEP 6: DOCUMENT REPOSITORY -->
                <div x-show="step === 6" x-transition.opacity.duration.400ms class="p-8 md:p-12" x-cloak>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="bg-rose-100 dark:bg-rose-900/30 p-3 rounded-2xl">
                            <x-heroicon-o-folder-arrow-down class="w-6 h-6 text-rose-600" />
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Part VI:
                            KYC & Verification Archive</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Image Uploads -->
                        <template x-for="field in [
                                        {id: 'nin', label: 'National ID (NIN)', capture: 'environment'},
                                        {id: 'selfie', label: 'Applicant Selfie', capture: 'user'},
                                        {id: 'nepa_bill', label: 'NEPA / Utility Bill', capture: 'environment'},
                                        {id: 'shop_picture', label: 'Business Shop Picture', capture: 'environment'},
                                        {id: 'house_picture', label: 'Residential House Picture', capture: 'environment'}
                                    ]">
                            <div class="space-y-3">
                                <label :for="field.id"
                                    class="block text-[10px] font-black uppercase text-slate-500 tracking-widest"
                                    x-text="field.label"></label>
                                <div class="relative group">
                                    <input type="file" :name="field.id" :id="field.id" accept="image/*"
                                        :capture="field.capture" @change="handleFileChange($event, field.id)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div
                                        class="p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl bg-slate-50 dark:bg-slate-900/50 group-hover:border-indigo-500 transition-colors text-center">
                                        <template x-if="!previews[field.id]">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Click
                                                    to upload image</span>
                                            </div>
                                        </template>
                                        <template x-if="previews[field.id]">
                                            <div class="relative inline-block mt-2">
                                                <img :src="previews[field.id]"
                                                    class="w-full max-h-40 object-cover rounded-xl shadow-lg">
                                                <div
                                                    class="absolute -top-2 -right-2 bg-indigo-600 text-white p-1 rounded-full shadow-lg">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="fileNames[field.id]">
                                            <p class="mt-2 text-[10px] font-black text-indigo-600 truncate"
                                                x-text="fileNames[field.id]"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- PDF Uploads -->
                        <template x-for="field in [
                                        {id: 'collateral_document', label: 'Collateral Document (PDF)'},
                                        {id: 'statement_of_account', label: 'Bank Statement (PDF)'}
                                    ]">
                            <div class="space-y-3">
                                <label :for="field.id"
                                    class="block text-[10px] font-black uppercase text-slate-500 tracking-widest"
                                    x-text="field.label"></label>
                                <div class="relative group">
                                    <input type="file" :name="field.id" :id="field.id" accept=".pdf"
                                        @change="handleFileChange($event, field.id)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div
                                        class="p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl bg-slate-50 dark:bg-slate-900/50 group-hover:border-indigo-500 transition-colors text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Upload
                                                PDF Document</span>
                                            <template x-if="fileNames[field.id]">
                                                <p class="mt-2 text-[10px] font-black text-indigo-600 truncate"
                                                    x-text="fileNames[field.id]"></p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- STEP 7: FINAL PROPOSAL -->
                <div x-show="step === 7" x-transition.opacity.duration.400ms class="p-8 md:p-12">
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
                        <button type="button" x-show="step < 7" @click="step++"
                            class="bg-indigo-600 text-white px-10 py-3.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-indigo-700 hover:scale-105 active:scale-95 transition-all">
                            Next Assessment Step
                        </button>
                        <button type="submit" x-show="step === 7"
                            class="bg-slate-950 dark:bg-white dark:text-slate-900 text-white px-12 py-3.5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-[0_10px_25px_rgba(0,0,0,0.2)] hover:scale-105 active:scale-95 transition-all">
                            Authorize & Submit Proposal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>