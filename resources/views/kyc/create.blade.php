<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- CRITICAL: ERROR DISPLAY -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-md">
                    <p class="font-bold">Submission Failed:</p>
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ensure route matches your web.php -->
            <form method="POST" action="{{ route('kyc.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Identity Section -->
                    <div class="bg-white p-8 rounded-xl shadow">
                        <h3 class="font-bold text-indigo-600 mb-4 uppercase">1. Personal Identity</h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="National ID (NIN)" />
                                <x-text-input name="national_id" class="w-full" :value="old('national_id')" required />
                            </div>
                            <div>
                                <x-input-label value="BVN" />
                                <x-text-input name="bvn" class="w-full" :value="old('bvn')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Date of Birth" />
                                    <x-text-input type="date" name="date_of_birth" :value="old('date_of_birth')" class="w-full"
                                        required />
                                </div>
                                <div>
                                    <x-input-label value="Annual Income" />
                                    <x-text-input type="number" name="income" :value="old('income')" class="w-full"
                                        required />
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Residential Address" />
                                <textarea name="address" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Guarantor Section -->
                    <div class="bg-white p-8 rounded-xl shadow">
                        <h3 class="font-bold text-emerald-600 mb-4 uppercase">2. Emergency Guarantor</h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label value="Guarantor Name" />
                                <x-text-input name="g_name" class="w-full" :value="old('g_name')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Phone" />
                                    <x-text-input name="g_phone" class="w-full" :value="old('g_phone')" required />
                                </div>
                                <div>
                                    <x-input-label value="Relationship" />
                                    <x-text-input name="g_relationship" class="w-full" :value="old('g_relationship')" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Guarantor Address" />
                                <textarea name="g_address" class="w-full border-gray-300 rounded-md shadow-sm">{{ old('g_address') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8">
                            <x-primary-button class="w-full justify-center py-3">
                                Complete Onboarding
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
