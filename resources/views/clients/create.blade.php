<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tight">
                🏢 {{ __('New Member Registration Pipeline') }}
            </h2>
            <div
                class="hidden md:flex items-center gap-2 px-4 py-1 bg-slate-100 dark:bg-slate-900 rounded-full border border-slate-200 dark:border-slate-800">
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest italic">Authorized
                    Authority: {{ auth()->user()->collationCenter?->name ?? 'HQ' }}</span>
            </div>
        </div>
    </x-slot>

    <!-- FORM STEPPER STATE -->
    <div class="py-12" x-data="{
        step: 1,
        gType: 'Employee',
        validateStep(targetStep) {
            // Optional: Add custom validation logic here before moving
            this.step = targetStep;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- ERROR NOTIFICATION BANNER -->
            @if ($errors->any())
                <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-2xl shadow-xl">
                    <div class="flex items-center mb-2">
                        <x-heroicon-s-x-circle class="w-5 h-5 text-red-600 mr-2" />
                        <h3 class="text-xs font-black text-red-800 dark:text-red-200 uppercase tracking-widest">Entry
                            Error Found in Registry Data</h3>
                    </div>
                    <ul class="text-[10px] text-red-700 dark:text-red-400 font-bold uppercase list-none space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- STEP INDICATOR -->
            <div class="mb-12 flex items-center justify-between px-6 relative">
                <!-- Background Line -->
                <div class="absolute left-10 right-10 top-5 h-1 bg-slate-200 dark:bg-slate-800 -z-0"></div>
                <div class="absolute left-10 top-5 h-1 bg-indigo-600 transition-all duration-500 -z-0"
                    :style="'width: ' + ((step - 1) * 33.33) + '%'"></div>

                <template x-for="i in [1, 2, 3, 4]">
                    <div class="z-10 flex flex-col items-center">
                        <div :class="step >= i ? 'bg-indigo-600 text-white' :
                            'bg-white dark:bg-slate-900 text-slate-300 border-2 border-slate-200 dark:border-slate-800'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-black transition-colors duration-300"
                            x-text="i"></div>
                        <span class="text-[9px] mt-2 font-black uppercase tracking-tighter"
                            :class="step >= i ? 'text-indigo-600' : 'text-slate-400'"
                            x-text="i == 1 ? 'Account' : (i == 2 ? 'Applicant' : (i == 3 ? 'Guarantor' : 'Review'))"></span>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('clients.store') }}"
                class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden transition-all duration-500">
                @csrf

                <!-- STEP 1: ACCOUNT ACCESS -->
                <div x-show="step === 1" x-transition.opacity.duration.400ms class="p-10 md:p-14">
                    <h3 class="text-2xl font-black text-indigo-600 mb-8 tracking-tighter uppercase italic italic">01.
                        Account Creation</h3>
                    <div class="space-y-6">
                        <div>
                            <x-input-label value="Full Legal Name" />
                            <x-text-input name="name" class="w-full mt-2 text-lg font-bold" :value="old('name')"
                                required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label value="Email Address" />
                                <x-text-input type="email" name="email" class="w-full mt-1" :value="old('email')"
                                    required />
                            </div>
                            <div>
                                <x-input-label value="Mobile Phone" />
                                <x-text-input name="phone" class="w-full mt-1" :value="old('phone')" required />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: APPLICANT IDENTITY -->
                <div x-show="step === 2" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-indigo-600 mb-8 tracking-tighter uppercase italic italic">02.
                        Applicant Verification</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 space-y-2">
                        <div><x-input-label value="National ID (NIN)" /><x-text-input name="national_id" class="w-full"
                                :value="old('national_id')" required /></div>
                        <div><x-input-label value="Bank BVN" /><x-text-input name="bvn" class="w-full"
                                :value="old('bvn')" required /></div>
                        <div><x-input-label value="Date of Birth" /><x-text-input type="date" name="date_of_birth"
                                class="w-full" required /></div>
                        <div><x-input-label value="Est. Annual Income" /><x-text-input type="number" name="income"
                                class="w-full font-bold" placeholder="₦0.00" /></div>
                        <div class="md:col-span-2">
                            <x-input-label value="Current Residential Address" />
                            <textarea name="address" class="w-full mt-2 border-slate-200 dark:border-slate-700 dark:bg-slate-900 rounded-2xl"
                                rows="3">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: GUARANTOR IDENTITY (Section II CF4) -->
                <div x-show="step === 3" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-emerald-600 mb-8 tracking-tighter uppercase italic italic">03.
                        Guarantor Profiling</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3"><x-input-label value="Guarantor Full Name" /><x-text-input
                                name="g_name" class="w-full mt-1" required /></div>
                        <div>
                            <x-input-label value="Legal Relationship" />
                            <select name="g_relationship"
                                class="w-full mt-1 rounded-xl border-slate-200 dark:bg-slate-900">
                                <option>Spouse</option>
                                <option>Sibling</option>
                                <option>Employer</option>
                                <option>Business Associate</option>
                            </select>
                        </div>
                        <div><x-input-label value="Phone" /><x-text-input name="g_phone" class="w-full mt-1" /></div>
                        <div><x-input-label value="Gender" /><select name="g_sex"
                                class="w-full mt-1 rounded-xl border-slate-200 dark:bg-slate-900">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select></div>

                        <div class="md:col-span-2 pt-4 border-t dark:border-slate-700">
                            <x-input-label value="Spouse Full Name (Optional)" /><x-text-input name="g_spouse_name"
                                class="w-full mt-1 text-xs" />
                        </div>
                        <div class="pt-4 border-t dark:border-slate-700">
                            <x-input-label value="Spouse Phone" /><x-text-input name="g_spouse_phone"
                                class="w-full mt-1 text-xs" />
                        </div>
                    </div>
                </div>

                <!-- STEP 4: GUARANTOR ASSESSMENT (Section III/IV CF4) -->
                <div x-show="step === 4" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-black text-emerald-600 tracking-tighter uppercase italic">04. Final
                            Credit Gate</h3>
                        <select x-model="gType" name="g_type"
                            class="text-xs font-black uppercase tracking-widest border-2 border-emerald-500 rounded-lg dark:bg-slate-950">
                            <option value="Employee">Assess as Employee</option>
                            <option value="Business Owner">Assess as Biz Owner</option>
                        </select>
                    </div>

                    <div class="space-y-8">
                        <!-- EMPLOYEE BLOCK -->
                        <div x-show="gType === 'Employee'"
                            class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 dark:bg-slate-950/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                            <div><x-input-label value="Employer Name" /><x-text-input name="g_employer"
                                    class="w-full" /></div>
                            <div><x-input-label value="Job Sector" /><x-text-input name="g_sector" class="w-full" />
                            </div>
                            <div><x-input-label value="Position" /><x-text-input name="g_position" class="w-full" />
                            </div>
                            <div><x-input-label value="Verified Monthly Net Income" /><x-text-input type="number"
                                    name="g_net_income" class="w-full font-black text-indigo-600" /></div>
                        </div>

                        <!-- BIZ OWNER BLOCK -->
                        <div x-show="gType === 'Business Owner'"
                            class="space-y-6 bg-amber-50 dark:bg-amber-900/10 p-6 rounded-3xl border border-amber-100 dark:border-amber-900/30">
                            <div class="grid grid-cols-2 gap-4">
                                <div><x-input-label value="Business Activity" /><x-text-input name="g_biz_activity"
                                        class="w-full" /></div>
                                <div><x-input-label value="Monthly Sales (Avg)" /><x-text-input type="number"
                                        name="g_biz_sales" class="w-full" /></div>
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Guarantor Permanent Home Address" />
                            <textarea name="g_address" class="w-full mt-2 border-slate-200 dark:bg-slate-900 rounded-2xl" rows="3"
                                required></textarea>
                        </div>
                    </div>
                </div>

                <!-- FOOTER NAVIGATION BAR -->
                <div
                    class="bg-slate-50 dark:bg-slate-900/80 px-10 py-8 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <button type="button" x-show="step > 1" @click="step--"
                        class="text-sm font-black uppercase text-slate-400 hover:text-slate-600 transition">
                        &larr; Previous Step
                    </button>
                    <div x-show="step === 1"></div> <!-- Spacer -->

                    <button type="button" x-show="step < 4" @click="validateStep(step + 1)"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black shadow-xl shadow-indigo-500/30 transition transform active:scale-95">
                        CONTINUE &rarr;
                    </button>

                    <button type="submit" x-show="step === 4"
                        class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-12 py-4 rounded-2xl font-black uppercase tracking-widest shadow-2xl hover:scale-105 active:scale-95 transition transform">
                        DEPLOY MEMBER FILE
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
