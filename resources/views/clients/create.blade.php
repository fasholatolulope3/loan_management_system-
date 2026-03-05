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
        previews: {
            nin: null,
            selfie: null,
            nepa_bill: null,
            shop_picture: null,
            house_picture: null
        },
        fileNames: {
            nin: '',
            selfie: '',
            nepa_bill: '',
            shop_picture: '',
            house_picture: '',
            collateral_document: '',
            statement_of_account: ''
        },
        handleFileChange(event, type) {
            const file = event.target.files[0];
            if (!file) return;
            
            this.fileNames[type] = file.name;
            
            if (['nin', 'selfie', 'nepa_bill', 'shop_picture', 'house_picture'].includes(type)) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews[type] = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        validateStep(targetStep) {
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
                    :style="'width: ' + ((step - 1) * 20) + '%'"></div>

                <template x-for="i in [1, 2, 3, 4, 5, 6]">
                    <div class="z-10 flex flex-col items-center">
                        <div :class="step >= i ? 'bg-indigo-600 text-white' :
                            'bg-white dark:bg-slate-900 text-slate-300 border-2 border-slate-200 dark:border-slate-800'"
                            class="w-10 h-10 rounded-full flex items-center justify-center font-black transition-colors duration-300"
                            x-text="i"></div>
                        <span class="text-[9px] mt-2 font-black uppercase tracking-tighter text-center w-max"
                            :class="step >= i ? 'text-indigo-600' : 'text-slate-400'"
                            x-text="i == 1 ? 'Account' : (i == 2 ? 'Applicant' : (i == 3 ? 'Guarantor' : (i == 4 ? 'Credit' : (i == 5 ? 'Docs' : 'Final'))))"></span>
                    </div>
                </template>
            </div>

            <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data"
                class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden transition-all duration-500">
                @csrf

                <!-- STEP 1: ACCOUNT ACCESS -->
                <div x-show="step === 1" x-transition.opacity.duration.400ms class="p-10 md:p-14">
                    <h3 class="text-2xl font-black text-indigo-600 mb-8 tracking-tighter uppercase italic">01.
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
                        <div class="mt-10 flex justify-end">
                            <button type="button" @click="validateStep(2)"
                                class="group flex items-center gap-3 bg-indigo-600 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                                CONTINUE
                                <x-heroicon-s-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: APPLICANT IDENTITY -->
                <div x-show="step === 2" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-indigo-600 mb-8 tracking-tighter uppercase italic">02.
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
                            <textarea name="address"
                                class="w-full mt-2 border-slate-200 dark:border-slate-700 dark:bg-slate-900 rounded-2xl"
                                rows="3">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-10 flex justify-between items-center">
                        <button type="button" @click="step--"
                            class="text-sm font-black uppercase text-slate-400 hover:text-slate-600 transition">
                            &larr; Previous Step
                        </button>
                        <button type="button" @click="validateStep(3)"
                            class="group flex items-center gap-3 bg-indigo-600 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                            CONTINUE
                            <x-heroicon-s-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition" />
                        </button>
                    </div>
                </div>

                <!-- STEP 3: GUARANTOR IDENTITY (Section II CF4) -->
                <div x-show="step === 3" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-emerald-600 mb-8 tracking-tighter uppercase italic">03.
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
                    <div class="mt-10 flex justify-between items-center">
                        <button type="button" @click="step--"
                            class="text-sm font-black uppercase text-slate-400 hover:text-slate-600 transition">
                            &larr; Previous Step
                        </button>
                        <button type="button" @click="validateStep(4)"
                            class="group flex items-center gap-3 bg-indigo-600 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                            CONTINUE
                            <x-heroicon-s-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition" />
                        </button>
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
                            <textarea name="g_address"
                                class="w-full mt-2 border-slate-200 dark:bg-slate-900 rounded-2xl" rows="3"
                                required></textarea>
                        </div>
                        <div class="mt-10 flex justify-between items-center">
                            <button type="button" @click="step--"
                                class="text-sm font-black uppercase text-slate-400 hover:text-slate-600 transition">
                                &larr; Previous Step
                            </button>
                            <button type="button" @click="validateStep(5)"
                                class="group flex items-center gap-3 bg-indigo-600 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                                CONTINUE
                                <x-heroicon-s-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: DOCUMENT REPOSITORY -->
                <div x-show="step === 5" x-transition.opacity.duration.400ms class="p-10 md:p-14" x-cloak>
                    <h3 class="text-2xl font-black text-rose-600 mb-8 tracking-tighter uppercase italic">05.
                        Document Repository</h3>

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
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Select
                                                PDF File</span>
                                            <template x-if="fileNames[field.id]">
                                                <p class="mt-2 text-[10px] font-black text-indigo-600 truncate"
                                                    x-text="fileNames[field.id]"></p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="col-span-full mt-10 flex justify-between items-center">
                            <button type="button" @click="step--"
                                class="text-sm font-black uppercase text-slate-400 hover:text-slate-600 transition">
                                &larr; Previous Step
                            </button>
                            <button type="button" @click="validateStep(6)"
                                class="group flex items-center gap-3 bg-indigo-600 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                                CONTINUE
                                <x-heroicon-s-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition" />
                            </button>
                        </div>
                    </div>
                </div>

        </div>
    </div>


    </form>
    </div>
    </div>
</x-app-layout>