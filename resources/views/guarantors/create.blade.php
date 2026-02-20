<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
            📋 Guarantor Assessment (Form CF4)
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ gType: 'Employee' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('guarantors.store') }}" class="space-y-8">
                @csrf

                <!-- HEADER & TYPE SELECTION -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl border-t-4 border-indigo-600">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <x-input-label value="I. Type of Guarantor" />
                            <select name="type" x-model="gType"
                                class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-900">
                                <option value="Business Owner">Business Owner (>1 yr)</option>
                                <option value="Employee">Employee (>1 yr)</option>
                                <option value="With Collateral">Guarantor with Collateral</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Visit Date (Business)" />
                            <x-text-input type="date" name="date_of_visit_business" class="w-full mt-1" />
                        </div>
                        <div>
                            <x-input-label value="Visit Date (Residence)" />
                            <x-text-input type="date" name="date_of_visit_residence" class="w-full mt-1" />
                        </div>
                    </div>
                </div>

                <!-- SECTION II: PERSONAL INFORMATION -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg">
                    <h3 class="font-black text-sm uppercase text-slate-400 mb-6 tracking-widest">II. Information About
                        Guarantor</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label value="Full Name (Surname First)" />
                            <x-text-input name="name" class="w-full mt-1" required />
                        </div>
                        <div>
                            <x-input-label value="Date of Birth" />
                            <x-text-input type="date" name="date_of_birth" class="w-full mt-1" />
                        </div>
                        <div>
                            <x-input-label value="Sex" />
                            <select name="sex" class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-900">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Marital Status" />
                            <select name="marital_status"
                                class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-900">
                                <option>Single</option>
                                <option>Married</option>
                                <option>Divorced</option>
                                <option>Widow</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Number of Dependents" />
                            <x-text-input type="number" name="dependent_persons" class="w-full mt-1" min="0" required />
                        </div>
                        <div>
                            <x-input-label value="Phone Number(s)" />
                            <x-text-input name="phone" class="w-full mt-1" required />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label value="Residential Address" />
                            <x-text-input name="address" class="w-full mt-1" required />
                        </div>
                    </div>
                </div>

                <!-- SECTION III: BUSINESS OWNER ASSESSMENT (Condition: Show only for Owners) -->
                <div x-show="gType === 'Business Owner'" x-transition
                    class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border-l-4 border-amber-500">
                    <h3 class="font-black text-sm uppercase text-amber-600 mb-6 tracking-widest">III. Business Analysis
                        (Cash Flow & Balance Sheet)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Cash Flow Table -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-xs uppercase border-b pb-2">Monthly Cash Flow (₦)</h4>
                            <div class="flex justify-between items-center">
                                <span class="text-sm">Monthly Sales</span>
                                <x-text-input type="number" name="monthly_sales" class="w-32" />
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm italic">Cost of Sales</span>
                                <x-text-input type="number" name="cost_of_sales" class="w-32" />
                            </div>
                            <div class="flex justify-between items-center text-red-500">
                                <span class="text-sm font-bold">Operational Exp.</span>
                                <x-text-input type="number" name="operational_expenses" class="w-32 border-red-200" />
                            </div>
                        </div>

                        <!-- Balance Sheet Table -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-xs uppercase border-b pb-2">Balance Sheet Summary (₦)</h4>
                            <div class="flex justify-between items-center">
                                <span class="text-sm uppercase font-black">Cash at hand</span>
                                <x-text-input type="number" name="cash_val" class="w-32" />
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm uppercase font-black">Total Assets</span>
                                <x-text-input type="number" name="total_assets" class="w-32" />
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm uppercase font-black">Liabilities</span>
                                <x-text-input type="number" name="total_liabilities" class="w-32 border-red-300" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION IV: EMPLOYMENT ASSESSMENT (Condition: Show only for Employees) -->
                <div x-show="gType === 'Employee'" x-transition
                    class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border-l-4 border-indigo-500">
                    <h3 class="font-black text-sm uppercase text-indigo-600 mb-6 tracking-widest">IV. Assessment of
                        Employment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label value="Employer Name" />
                            <x-text-input name="employer_name" class="w-full mt-1" />
                        </div>
                        <div>
                            <x-input-label value="Position / Designation" />
                            <x-text-input name="position" class="w-full mt-1" />
                        </div>
                        <div>
                            <x-input-label value="Net Monthly Income (₦)" />
                            <x-text-input type="number" name="net_monthly_income" class="w-full mt-1" />
                        </div>
                        <div>
                            <x-input-label value="Job Sector (e.g. Civil Service, Private Industry)" />
                            <x-text-input name="job_sector" class="w-full mt-1" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label value="Employer Address" />
                            <x-text-input name="employer_address" class="w-full mt-1" />
                        </div>
                    </div>
                </div>

                <!-- FOOTER: SIGN OFF -->
                <div
                    class="bg-slate-900 text-white p-10 rounded-[3rem] shadow-2xl flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="max-w-md text-sm italic opacity-70">
                        "I declare that all information is truthfully presented. I ascertain the veracity of the
                        information..."
                    </div>
                    <x-primary-button
                        class="px-16 py-5 bg-indigo-600 text-lg rounded-2xl hover:scale-105 active:scale-95 transition shadow-2xl shadow-indigo-500/20">
                        Finalize Form CF4 Assessment
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>