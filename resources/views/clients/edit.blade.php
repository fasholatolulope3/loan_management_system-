<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tight">
                📂 {{ __('Full Client Credit Assessment') }}
            </h2>
            <div
                class="hidden md:flex items-center gap-2 px-4 py-1 bg-slate-100 dark:bg-slate-900 rounded-full border border-slate-200 dark:border-slate-800">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic leading-none">Branch:
                    {{ auth()->user()->collationCenter?->name ?? 'Headquarters' }}</span>
            </div>
        </div>
    </x-slot>

    <!-- FORM STEPPER LOGIC -->
    <div class="py-12" x-data="{
        step: 1,
        gType: 'Employee',
        next() { if (this.step < 5) this.step++;
            window.scrollTo(0, 0); },
        prev() { if (this.step > 1) this.step--;
            window.scrollTo(0, 0); }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Global Error Feed -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-2xl shadow-xl">
                    <h3
                        class="text-xs font-black text-red-800 dark:text-red-200 uppercase tracking-widest flex items-center gap-2">
                        <x-heroicon-s-x-circle class="w-4 h-4" /> Validation Errors In Assessment
                    </h3>
                    <ul class="text-[10px] text-red-700 dark:text-red-400 font-bold uppercase mt-2 list-none space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Stepper Visualizer -->
            <div class="mb-10 flex items-center justify-between px-6 relative max-w-2xl mx-auto">
                <div class="absolute left-6 right-6 top-5 h-0.5 bg-slate-200 dark:bg-slate-800 -z-0"></div>
                <div class="absolute left-6 top-5 h-0.5 bg-indigo-600 transition-all duration-700"
                    :style="'width: ' + ((step - 1) * 25) + '%'"></div>

                <template x-for="i in [1, 2, 3, 4, 5]">
                    <div class="z-10 flex flex-col items-center">
                        <div :class="step >= i ? 'bg-indigo-600 text-white scale-110 shadow-lg shadow-indigo-200' :
                            'bg-white dark:bg-slate-900 text-slate-300 border-2'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-black transition-all duration-300"
                            x-text="i"></div>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('clients.store') }}"
                class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden transition-all duration-500">
                @csrf

                <!-- STEP 1: PERSONAL IDENTITY (CF2 Section I) -->
                <div x-show="step === 1" x-transition.opacity.duration.400ms class="p-10 md:p-14">
                    <h3
                        class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mb-8 uppercase tracking-tighter italic">
                        01. Applicant Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <x-input-label value="Full Name (Surname First)" />
                            <x-text-input name="name" class="w-full mt-1 text-lg font-bold" :value="old('name')"
                                required />
                        </div>
                        <div><x-input-label value="Email Address" /><x-text-input type="email" name="email"
                                class="w-full" required /></div>
                        <div><x-input-label value="Phone Number" /><x-text-input name="phone" class="w-full"
                                required /></div>
                        <div><x-input-label value="National ID (NIN)" /><x-text-input name="national_id"
                                class="w-full font-mono" required maxlength="11" /></div>
                        <div><x-input-label value="BVN" /><x-text-input name="bvn" class="w-full font-mono"
                                required maxlength="11" /></div>
                        <div><x-input-label value="Date of Birth" /><x-text-input type="date" name="date_of_birth"
                                class="w-full" required /></div>
                        <div><x-input-label value="Marital Status" /><select name="marital_status"
                                class="w-full border-slate-200 dark:bg-slate-900 rounded-xl">
                                <option>Single</option>
                                <option>Married</option>
                                <option>Widowed</option>
                            </select></div>
                        <div class="md:col-span-2"><x-input-label value="Home Address" /><x-text-input name="address"
                                class="w-full" required /></div>
                    </div>
                </div>

                <!-- STEP 2: BUSINESS & CASH FLOW (CF2 Section II & Page 2) -->
                <div x-show="step === 2" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-emerald-600 mb-8 uppercase tracking-tighter italic">02. Business
                        & Cash Flow Analysis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2"><x-input-label value="Business Activities" /><x-text-input
                                name="business_activity" class="w-full" placeholder="e.g. Retail Distribution" /></div>
                        <div><x-input-label value="Business Location" /><x-text-input name="business_location"
                                class="w-full" /></div>
                        <div><x-input-label value="Business Premises" /><select name="premises"
                                class="w-full border-slate-200 dark:bg-slate-900 rounded-xl">
                                <option value="own">Owned</option>
                                <option value="rent">Rented</option>
                            </select></div>
                        <div
                            class="pt-6 border-t dark:border-slate-700 md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div><label class="text-[10px] font-black uppercase text-slate-400">Monthly Sales
                                    (₦)</label><x-text-input type="number" name="income" class="w-full" /></div>
                            <div><label class="text-[10px] font-black uppercase text-slate-400">Cost of Sales
                                    (₦)</label><x-text-input type="number" name="cost_sales" class="w-full" /></div>
                            <div><label class="text-[10px] font-black uppercase text-slate-400">Operational Exp.
                                    (₦)</label><x-text-input type="number" name="op_expenses" class="w-full" /></div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: ASSET ASSESSMENT (CF2 Balance Sheet) -->
                <div x-show="step === 3" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-amber-500 mb-8 uppercase tracking-tighter italic">03. Balance
                        Sheet Verification</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div><x-input-label value="Cash (Home/Hand/Bank)" /><x-text-input type="number" name="cash_val"
                                class="w-full border-amber-200" /></div>
                        <div><x-input-label value="Receivables/Prepayment" /><x-text-input type="number"
                                name="receivables" class="w-full border-amber-200" /></div>
                        <div><x-input-label value="Stock/Inventory" /><x-text-input type="number" name="inventory"
                                class="w-full border-amber-200" /></div>
                        <div class="md:col-span-3 border-t dark:border-slate-700 pt-8 grid grid-cols-2 gap-8">
                            <div><x-input-label value="Total Fixed Assets (₦)" /><x-text-input type="number"
                                    name="fixed_assets" class="w-full" /></div>
                            <div><x-input-label value="Total Liabilities (₦)" /><x-text-input type="number"
                                    name="liabilities" class="w-full text-red-500 border-red-100" /></div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: GUARANTOR IDENTITY (CF4 Section I & II) -->
                <div x-show="step === 4" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3
                        class="text-2xl font-black text-indigo-900 dark:text-white mb-8 uppercase tracking-tighter italic">
                        04. Guarantor Primary Profile</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <x-input-label value="I. Type of Guarantor" />
                            <select name="g_type" x-model="gType"
                                class="w-full mt-1 border-none bg-slate-100 dark:bg-slate-900 rounded-xl font-bold">
                                <option value="Employee">Employee (>1 year employment)</option>
                                <option value="Business Owner">Business Owner (>1 year)</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 pt-4 border-t dark:border-slate-700">
                            <x-input-label value="Guarantor Full Name" /><x-text-input name="g_name"
                                class="w-full mt-1 font-bold" required />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><x-input-label value="Phone Number" /><x-text-input name="g_phone" class="w-full"
                                    required /></div>
                            <div><x-input-label value="Relation" /><x-text-input name="g_relationship" class="w-full"
                                    required /></div>
                        </div>
                        <div><x-input-label value="Address" /><x-text-input name="g_address" class="w-full"
                                required /></div>
                    </div>
                </div>

                <!-- STEP 5: GUARANTOR ASSESSMENT (CF4 Section III / IV) -->
                <div x-show="step === 5" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-indigo-900 dark:text-white mb-4 uppercase tracking-tighter italic"
                        x-text="gType === 'Employee' ? '05. Guarantor Employment Analysis' : '05. Guarantor Business Analysis'">
                    </h3>

                    <div class="space-y-8">
                        <!-- IF EMPLOYEE -->
                        <div x-show="gType === 'Employee'"
                            class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-indigo-50 dark:bg-slate-900/50 p-8 rounded-[2.5rem]">
                            <div><x-input-label value="Employer Name" /><x-text-input name="g_employer"
                                    class="w-full mt-1" /></div>
                            <div><x-input-label value="Position" /><x-text-input name="g_position"
                                    class="w-full mt-1" /></div>
                            <div><x-input-label value="Sector" /><x-text-input name="g_sector" class="w-full mt-1" />
                            </div>
                            <div><x-input-label value="Net Monthly Income (₦)" /><x-text-input type="number"
                                    name="g_net_income" class="w-full mt-1 font-black text-indigo-700" /></div>
                        </div>

                        <!-- IF BIZ OWNER -->
                        <div x-show="gType === 'Business Owner'"
                            class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-amber-50 dark:bg-amber-900/10 p-8 rounded-[2.5rem]">
                            <div><x-input-label value="Business Activity" /><x-text-input name="g_biz_act"
                                    class="w-full mt-1" /></div>
                            <div><x-input-label value="Monthly Sales" /><x-text-input type="number" name="g_sales"
                                    class="w-full mt-1" /></div>
                            <div class="md:col-span-2 text-xs font-bold text-amber-600 italic tracking-wider">Balance
                                sheet and inventory checks for guarantors performed by Loan Officer manually as per form
                                CF4.</div>
                        </div>

                        <div
                            class="p-6 border-t-2 border-dashed border-slate-200 dark:border-slate-700 italic text-xs text-slate-500 text-center">
                            "I, {{ auth()->user()->name }}, certify that all client and guarantor documents have been
                            physically verified."
                        </div>
                    </div>
                </div>

                <!-- NAVIGATION ACTIONS -->
                <div
                    class="bg-slate-900 text-white p-8 md:p-10 flex justify-between items-center transition-all duration-500">
                    <button type="button" x-show="step > 1" @click="prev()"
                        class="text-sm font-black uppercase tracking-[0.2em] opacity-60 hover:opacity-100 transition">
                        &larr; Return
                    </button>
                    <div x-show="step === 1"></div>

                    <button type="button" x-show="step < 5" @click="next()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-5 rounded-2xl font-black text-xs tracking-widest uppercase shadow-xl hover:scale-105 active:scale-95 transition transform">
                        Continue Evaluation &rarr;
                    </button>

                    <button type="submit" x-show="step === 5"
                        class="bg-white text-slate-900 px-12 py-5 rounded-2xl font-black text-xs tracking-widest uppercase hover:bg-indigo-50 active:scale-95 transition shadow-2xl">
                        Authorize Member Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
