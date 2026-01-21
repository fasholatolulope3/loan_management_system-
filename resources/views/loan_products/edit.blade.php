<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Loan Product: ') }} {{ $loanProduct->name }}
            </h2>
            <a href="{{ route('loan-products.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Back to
                List</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <form method="POST" action="{{ route('loan-products.update', $loanProduct) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" name="name" class="block mt-1 w-full" type="text"
                                :value="old('name', $loanProduct->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Interest Rate -->
                        <div>
                            <x-input-label for="interest_rate" :value="__('Interest Rate (%)')" />
                            <x-text-input name="interest_rate" class="block mt-1 w-full" type="number" step="0.01"
                                :value="old('interest_rate', $loanProduct->interest_rate)" required />
                        </div>

                        <!-- Penalty Rate -->
                        <div>
                            <x-input-label for="penalty_rate" :value="__('Penalty Rate (%)')" />
                            <x-text-input name="penalty_rate" class="block mt-1 w-full" type="number" step="0.01"
                                :value="old('penalty_rate', $loanProduct->penalty_rate)" required />
                        </div>

                        <!-- Min Amount -->
                        <div>
                            <x-input-label for="min_amount" :value="__('Minimum Principal (₦)')" />
                            <x-text-input name="min_amount" class="block mt-1 w-full" type="number" :value="old('min_amount', $loanProduct->min_amount)"
                                required />
                        </div>

                        <!-- Max Amount -->
                        <div>
                            <x-input-label for="max_amount" :value="__('Maximum Principal (₦)')" />
                            <x-text-input name="max_amount" class="block mt-1 w-full" type="number" :value="old('max_amount', $loanProduct->max_amount)"
                                required />
                        </div>

                        <!-- Duration -->
                        <div>
                            <x-input-label for="duration_months" :value="__('Duration (Months)')" />
                            <x-text-input name="duration_months" class="block mt-1 w-full" type="number"
                                :value="old('duration_months', $loanProduct->duration_months)" required />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="active" {{ $loanProduct->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ $loanProduct->status == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-6">
                        <x-primary-button>
                            {{ __('Update Product Configuration') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
