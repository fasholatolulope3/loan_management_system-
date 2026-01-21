<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Define New Loan Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- DEBUGGER: Show all errors if any -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <!-- IMPORTANT: Check action and method -->
                <form method="POST" action="{{ route('loan-products.store') }}">
                    @csrf <!-- CSRF Token is REQUIRED -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="Product Name" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full" type="text"
                                :value="old('name')" required />
                        </div>

                        <div>
                            <x-input-label for="interest_rate" value="Interest Rate (%)" />
                            <x-text-input name="interest_rate" class="block mt-1 w-full" type="number" step="0.01"
                                :value="old('interest_rate')" required />
                        </div>

                        <div>
                            <x-input-label for="penalty_rate" value="Penalty Rate (%)" />
                            <x-text-input name="penalty_rate" class="block mt-1 w-full" type="number" step="0.01"
                                :value="old('penalty_rate')" required />
                        </div>

                        <div>
                            <x-input-label for="min_amount" value="Min Principal" />
                            <x-text-input name="min_amount" class="block mt-1 w-full" type="number" :value="old('min_amount')"
                                required />
                        </div>

                        <div>
                            <x-input-label for="max_amount" value="Max Principal" />
                            <x-text-input name="max_amount" class="block mt-1 w-full" type="number" :value="old('max_amount')"
                                required />
                        </div>

                        <div>
                            <x-input-label for="duration_months" value="Duration (Months)" />
                            <x-text-input name="duration_months" class="block mt-1 w-full" type="number"
                                :value="old('duration_months')" required />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button>
                            {{ __('Save Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
