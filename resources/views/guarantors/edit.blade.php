<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
                ⚙️ Modify Assessment: {{ $guarantor->name }}
            </h2>
            <a href="{{ route('guarantors.show', $guarantor) }}"
                class="text-sm font-bold text-slate-500 hover:text-indigo-600 transition">
                &larr; Cancel and Return
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ gType: '{{ old('type', $guarantor->type) }}' }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Validation Error Feedback -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-md rounded-xl">
                    <p class="font-black text-xs uppercase tracking-widest mb-2">Sync Errors Detected:</p>
                    <ul class="list-disc pl-5 text-[10px] font-bold uppercase">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('guarantors.update', $guarantor) }}" class="space-y-8">
                @csrf
                @method('PATCH')

                <!-- 1. IDENTIFICATION DATA (Form CF4 Section II) -->
                <div
                    class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700">
                    <h3 class="text-indigo-600 font-black uppercase text-xs tracking-[0.2em] mb-8 italic">Personnel
                        Identification</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <x-input-label value="Surname / Organization Name" />
                            <x-text-input name="name" class="w-full mt-1 dark:bg-slate-900 font-bold"
                                :value="old('name', $guarantor->name)" required />
                        </div>
                        <div>
                            <x-input-label value="Phone Number(s)" />
                            <x-text-input name="phone" class="w-full mt-1 dark:bg-slate-900" :value="old('phone', $guarantor->phone)"
                                required />
                        </div>
                        <div>
                            <x-input-label value="Relationship to Client" />
                            <x-text-input name="relationship" class="w-full mt-1 dark:bg-slate-900 italic"
                                :value="old('relationship', $guarantor->relationship)" required />
                        </div>
                        <div>
                            <x-input-label value="Update Status Type" />
                            <select name="type" x-model="gType"
                                class="w-full mt-1 rounded-xl border-gray-300 dark:bg-slate-950 font-bold text-indigo-600">
                                <option value="Employee">Employee (>1 year)</option>
                                <option value="Business Owner">Business Owner (>1 year)</option>
                                <option value="With Collateral">Guarantor with Collateral</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label value="Official Residential Address" />
                            <textarea name="address" class="w-full mt-1 border-gray-300 dark:border-slate-700 dark:bg-slate-900 rounded-2xl"
                                rows="2" required>{{ old('address', $guarantor->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. CONDITIONAL ANALYSIS (Switch based on Type) -->
                <div class="relative">
                    <!-- EMPLOYEE REVISION -->
                    <div x-show="gType === 'Employee'" x-transition
                        class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border-l-8 border-indigo-600 border border-slate-100 dark:border-slate-700">
                        <h3 class="text-indigo-600 font-black uppercase text-xs tracking-widest mb-6">Revised Employment
                            Data</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <x-input-label value="Current Employer Name" />
                                <x-text-input name="employer_name" class="w-full mt-1" :value="old('employer_name', $guarantor->employer_name)" />
                            </div>
                            <div>
                                <x-input-label value="Net Monthly Salary (₦)" />
                                <x-text-input type="number" step="0.01" name="net_monthly_income"
                                    class="w-full mt-1 font-black text-emerald-600" :value="old('net_monthly_income', $guarantor->net_monthly_income)" />
                            </div>
                        </div>
                    </div>

                    <!-- BUSINESS OWNER REVISION -->
                    <div x-show="gType === 'Business Owner'" x-transition
                        class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border-l-8 border-amber-500 border border-slate-100 dark:border-slate-700">
                        <h3 class="text-amber-600 font-black uppercase text-xs tracking-widest mb-6">Revised Business
                            Analytics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <div class="space-y-4">
                                <div><x-input-label value="Average Monthly Sales" /><x-text-input type="number"
                                        name="monthly_sales" class="w-full" :value="old('monthly_sales', $guarantor->monthly_sales)" /></div>
                                <div><x-input-label value="Cost of Sales" /><x-text-input type="number"
                                        name="cost_of_sales" class="w-full" :value="old('cost_of_sales', $guarantor->cost_of_sales)" /></div>
                            </div>
                            <div class="space-y-4">
                                <div><x-input-label value="Operational Expenses" /><x-text-input type="number"
                                        name="operational_expenses" class="w-full border-red-100" :value="old('operational_expenses', $guarantor->operational_expenses)" />
                                </div>
                                <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-2xl">
                                    <p class="text-[10px] font-black uppercase text-amber-600 italic">Pre-Update Insight
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1 leading-tight font-medium">Re-submitting this
                                        form will automatically recalculate the **Payment Capacity** based on these
                                        revised figures.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. FOOTER SIGN-OFF -->
                <div
                    class="bg-slate-900 dark:bg-white p-8 rounded-[3rem] shadow-2xl flex flex-col md:flex-row justify-between items-center gap-6 group overflow-hidden relative">
                    <div class="text-white dark:text-slate-900 text-xs italic opacity-60">"Corrected assessment will
                        supersede any previously registered CF4 records for this specific file."</div>

                    <div class="flex items-center gap-4">
                        <x-primary-button
                            class="bg-indigo-600 dark:bg-indigo-600 dark:text-white px-12 py-5 font-black uppercase tracking-widest rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition transform">
                            Overwrite Registry File
                        </x-primary-button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
