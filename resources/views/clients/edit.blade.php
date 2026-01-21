<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Onboard New Client</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-md">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Column 1: Client Personal Details -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
                        <h3 class="text-lg font-bold text-indigo-600 border-b pb-2">Step 1: Personal & Account</h3>

                        <div>
                            <x-input-label value="Full Name" />
                            <x-text-input name="name" class="w-full mt-1" :value="old('name')" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Email Address" />
                                <x-text-input type="email" name="email" class="w-full mt-1" :value="old('email')"
                                    required />
                            </div>
                            <div>
                                <x-input-label value="Phone Number" />
                                <x-text-input name="phone" class="w-full mt-1" :value="old('phone')" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="National ID (NIN)" />
                                <x-text-input name="national_id" class="w-full mt-1" :value="old('national_id')" required />
                            </div>
                            <div>
                                <x-input-label value="BVN" />
                                <x-text-input name="bvn" class="w-full mt-1" :value="old('bvn')" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Date of Birth" />
                                <x-text-input type="date" name="date_of_birth" class="w-full mt-1" :value="old('date_of_birth')"
                                    required />
                            </div>
                            <div>
                                <x-input-label value="Annual Income" />
                                <x-text-input type="number" name="income" class="w-full mt-1" :value="old('income')"
                                    required />
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Home Address" />
                            <textarea name="address" class="w-full mt-1 border-gray-300 rounded-md" rows="2" required>{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- Column 2: Guarantor Details -->
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4 border-t-4 border-emerald-500">
                        <h3 class="text-lg font-bold text-emerald-600 border-b pb-2">Step 2: Primary Guarantor</h3>

                        <div>
                            <x-input-label value="Guarantor Full Name" />
                            <x-text-input name="g_name" class="w-full mt-1" :value="old('g_name')" required />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Guarantor Phone" />
                                <x-text-input name="g_phone" class="w-full mt-1" :value="old('g_phone')" required />
                            </div>
                            <div>
                                <x-input-label value="Relationship" />
                                <x-text-input name="g_relationship" class="w-full mt-1" :value="old('g_relationship')"
                                    placeholder="e.g. Sibling, Friend" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Guarantor Address" />
                            <textarea name="g_address" class="w-full mt-1 border-gray-300 rounded-md" rows="3" required>{{ old('g_address') }}</textarea>
                        </div>

                        <div class="pt-6">
                            <x-primary-button
                                class="w-full justify-center py-4 bg-indigo-600 text-white font-bold rounded-xl shadow-lg">
                                Complete Onboarding
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
