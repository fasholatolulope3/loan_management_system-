<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Onboard New Client</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('clients.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Account Details -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-indigo-600 border-b pb-2">Account Login Info</h3>
                            <div>
                                <x-input-label for="name" value="Full Name" />
                                <x-text-input name="name" class="w-full mt-1" :value="old('name')" required />
                            </div>
                            <div>
                                <x-input-label for="email" value="Email Address" />
                                <x-text-input type="email" name="email" class="w-full mt-1" :value="old('email')"
                                    required />
                            </div>
                            <div>
                                <x-input-label for="phone" value="Phone Number" />
                                <x-text-input name="phone" class="w-full mt-1" :value="old('phone')" required />
                            </div>
                        </div>

                        <!-- KYC Details -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-emerald-600 border-b pb-2">KYC / ID Verification</h3>
                            <div>
                                <x-input-label for="national_id" value="National ID (NIN)" />
                                <x-text-input name="national_id" class="w-full mt-1" :value="old('national_id')" required />
                            </div>
                            <div>
                                <x-input-label for="bvn" value="BVN" />
                                <x-text-input name="bvn" class="w-full mt-1" :value="old('bvn')" required />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="date_of_birth" value="Date of Birth" />
                                    <x-text-input type="date" name="date_of_birth" class="w-full mt-1"
                                        :value="old('date_of_birth')" required />
                                </div>
                                <div>
                                    <x-input-label for="income" value="Annual Income" />
                                    <x-text-input type="number" name="income" class="w-full mt-1" :value="old('income')"
                                        required />
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <x-input-label for="address" value="Residential Address" />
                            <textarea name="address" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" rows="3" required>{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <x-primary-button class="px-8 py-3">
                            Confirm and Onboard Client
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
