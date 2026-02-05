<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
            📁 Create New Credit Proposal
        </h2>
    </x-slot>

    <!-- Corrected: Stringify the objects to avoid compilation issues -->
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

            <!-- Progress Stepper -->
            <div class="mb-8 flex items-center justify-between px-10">
                <template x-for="i in [1, 2, 3, 4]">
                    <div class="flex items-center">
                        <div :class="step >= i ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-500'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-black transition-colors"
                            x-text="i"></div>
                        <div x-show="i < 4" class="w-12 h-1 bg-gray-200 dark:bg-slate-700 mx-2">
                            <div class="h-full bg-indigo-600 transition-all duration-500"
                                :style="'width: ' + (step > i ? '100%' : '0%')"></div>
                        </div>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('loans.store') }}"
                class="bg-white dark:bg-slate-800 shadow-2xl rounded-[2.5rem] border border-slate-100 dark:border-slate-700 overflow-hidden">
                @csrf

                <!-- STEP 1: IDENTITY -->
                <div x-show="step === 1" class="p-8 md:p-12">
                    <h3 class="text-xl font-black text-indigo-600 dark:text-indigo-400 mb-8 uppercase italic">Step 1:
                        Information About Applicant</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label value="Select Client" />
                            <select name="client_id"
                                class="block mt-1 w-full border-gray-300 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->user?->name }}
                                        ({{ $client->national_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Repayment Frequency" />
                            <select name="loan_product_id"
                                class="block mt-1 w-full border-gray-300 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}
                                        ({{ $product->interest_rate }}% Int.)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label value="Principal Requested (₦)" />
                            <x-text-input name="amount" type="number" class="w-full text-2xl font-black" required />
                        </div>
                        <div><x-input-label value="Business Name" /><x-text-input name="business_name"
                                class="w-full mt-1" required /></div>
                        <div><x-input-label value="Location" /><x-text-input name="business_location"
                                class="w-full mt-1" required /></div>
                        <div>
                            <x-input-label value="Premise Type" />
                            <select name="business_premise_type"
                                class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-900">
                                <option value="own">Owned</option>
                                <option value="rent">Rented</option>
                            </select>
                        </div>
                        <div><x-input-label value="Date Started" /><x-text-input name="business_start_date"
                                type="date" class="w-full mt-1" required /></div>
                    </div>
                </div>

                <!-- STEP 2: FINANCIALS -->
                <div x-show="step === 2" class="p-8 md:p-12">
                    <h3 class="text-xl font-black text-emerald-600 mb-8 uppercase italic">Step 2: Financial Assessment
                        (Monthly)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div><x-input-label value="Sales Revenue" /><x-text-input name="monthly_sales" type="number"
                                class="w-full" required /></div>
                        <div><x-input-label value="Cost of Sales" /><x-text-input name="cost_of_sales" type="number"
                                class="w-full" required /></div>
                        <div><x-input-label value="Operational Exp." /><x-text-input name="operational_expenses"
                                type="number" class="w-full" required /></div>
                        <div><x-input-label value="Personal/Family Exp." /><x-text-input name="family_expenses"
                                type="number" class="w-full" required /></div>
                        <div
                            class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t dark:border-slate-700">
                            <div><x-input-label value="Current Assets" /><x-text-input name="current_assets"
                                    type="number" class="w-full" required /></div>
                            <div><x-input-label value="Fixed Assets" /><x-text-input name="fixed_assets" type="number"
                                    class="w-full" required /></div>
                            <div><x-input-label value="Total Liabilities" /><x-text-input name="total_liabilities"
                                    type="number" class="w-full" required /></div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: COLLATERAL (CRITICAL FIX: Removed :prefix logic) -->
                <div x-show="step === 3" class="p-8 md:p-12">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-black text-amber-600 uppercase italic">Step 3: Collateral Evaluation
                        </h3>
                        <button type="button" @click="addCollateral()"
                            class="text-xs font-black bg-amber-500 text-white px-4 py-2 rounded-full uppercase shadow">+
                            Add Asset</button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(collateral, index) in collaterals" :key="index">
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 p-5 bg-slate-50 dark:bg-slate-900 rounded-3xl relative border border-slate-100 dark:border-slate-800">
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400">Type</label>
                                    <select :name="`collaterals[${index}][type]`"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-sm">
                                        <option value="HG">Household Good</option>
                                        <option value="FA">Fixed Asset</option>
                                        <option value="INV">Inventory</option>
                                        <option value="VMG">Vehicle</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-black uppercase text-slate-400">Description</label>
                                    <input type="text" :name="`collaterals[${index}][description]`"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-sm"
                                        placeholder="Model, Year, Condition..." required />
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400">Market Value
                                        (₦)</label>
                                    <input type="number" :name="`collaterals[${index}][market_value]`"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800 text-sm font-bold"
                                        required />
                                </div>
                                <button type="button" @click="removeCollateral(index)"
                                    class="absolute -top-2 -right-2 bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-full w-8 h-8 flex items-center justify-center text-red-500 font-bold shadow-lg hover:bg-red-50">×</button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- STEP 4: GUARANTOR -->
                <!-- STEP 4: GUARANTOR & PROPOSAL -->
                <div x-show="step === 4" class="p-8 md:p-12">
                    <h3
                        class="text-xl font-black text-indigo-600 mb-8 uppercase italic underline decoration-4 underline-offset-8">
                        Step 4: Guarantor Assessment (Form CF4)</h3>

                    <div class="space-y-8" x-data="{ addMode: false }">
                        <!-- 1. Selection Mechanism -->
                        <div>
                            <x-input-label value="Which Guarantor is backing this loan?" />
                            <div class="flex gap-4 mt-2">
                                <select name="guarantor_id" x-show="!addMode"
                                    class="flex-1 rounded-xl border-gray-300 dark:bg-slate-900 dark:text-white">
                                    <option value="">-- Choose Existing --</option>
                                    @foreach (\App\Models\Guarantor::all() as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->phone }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" @click="addMode = !addMode"
                                    :class="addMode ? 'bg-red-500' : 'bg-emerald-600'"
                                    class="px-4 py-2 text-white text-xs font-black rounded-xl uppercase tracking-tighter shadow-lg">
                                    <span x-text="addMode ? 'Cancel New' : '+ Register New Guarantor'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- 2. New Guarantor Integrated Form (Matching CF4) -->
                        <div x-show="addMode" x-transition
                            class="bg-slate-50 dark:bg-slate-900/50 p-8 rounded-[2rem] border border-emerald-100 dark:border-emerald-900 space-y-6">
                            <h4 class="font-bold text-emerald-600 uppercase text-xs">New Guarantor Registration</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label value="Full Name" />
                                    <x-text-input name="g_name" placeholder="Surname First" class="w-full" />
                                </div>
                                <div>
                                    <x-input-label value="Relationship to Applicant" />
                                    <x-text-input name="g_relationship" placeholder="e.g. Boss, Sister"
                                        class="w-full" />
                                </div>
                                <div>
                                    <x-input-label value="Guarantor Type" />
                                    <select name="g_type"
                                        class="w-full rounded-xl border-gray-300 dark:bg-slate-800">
                                        <option>Employee</option>
                                        <option>Business Owner</option>
                                        <option>Collateral Provider</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Net Monthly Income (₦)" />
                                    <x-text-input name="g_income" type="number" class="w-full" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label value="Physical Address" />
                                    <textarea name="g_address" class="w-full border-gray-300 dark:bg-slate-800 rounded-xl" rows="2"></textarea>
                                </div>
                            </div>
                            <p class="text-[10px] text-emerald-600 font-bold uppercase italic">* This guarantor will be
                                saved and automatically linked to this proposal.</p>
                        </div>

                        <!-- 3. Final Summary Recommendation -->
                        <div class="pt-6 border-t dark:border-slate-700">
                            <x-input-label value="Final Underwriting Recommendation (Proposal Summary)" />
                            <textarea name="proposal_summary" rows="4" required
                                class="w-full mt-2 rounded-[1.5rem] border-gray-300 dark:bg-slate-900 shadow-sm"
                                placeholder="Staff: Summarize why you recommend this approval based on client and guarantor analysis."></textarea>
                        </div>
                    </div>
                </div>

                <!-- FOOTER NAVIGATION -->
                <div class="bg-slate-50 dark:bg-slate-900/50 p-8 flex justify-between">
                    <button type="button" x-show="step > 1" @click="step--"
                        class="bg-gray-400 text-white px-8 py-3 rounded-xl font-black uppercase shadow">Back</button>
                    <div x-show="step === 1"></div>
                    <button type="button" x-show="step < 4" @click="step++"
                        class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-black uppercase shadow-xl">Continue</button>
                    <button type="submit" x-show="step === 4"
                        class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-12 py-3 rounded-xl font-black uppercase shadow-2xl">Authorize
                        Submission</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
