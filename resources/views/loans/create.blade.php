<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Loan Application') }}</h2>
    </x-slot>

    <div class="py-12" x-data="{
        selectedProduct: '',
        products: {{ $products->toJson() }},
        get currentProduct() {
            return this.products.find(p => p.id == this.selectedProduct) || null
        }
    }">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <!-- 1. Global Error Display (Critical for debugging why it won't submit) -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-8 rounded-lg shadow border-t-4 border-indigo-600">
                <form method="POST" action="{{ route('loans.store') }}">
                    @csrf

                    <!-- Client ID Logic -->
                    @if (auth()->user()->role !== 'client')
                        <div class="mb-6">
                            <x-input-label for="client_id" :value="__('Select Client')" />
                            <select name="client_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                                <option value="">-- Choose Client --</option>
                                @foreach (\App\Models\Client::with('user')->get() as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->user->name }} ({{ $client->national_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="client_id" value="{{ auth()->user()->client->id }}">
                    @endif

                    <!-- Loan Product Selection -->
                    <div class="mb-6">
                        <x-input-label for="loan_product_id" :value="__('Loan Product')" />
                        <select name="loan_product_id" x-model="selectedProduct"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
                            <option value="">-- Select a Loan Package --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Product Details Display -->
                    <template x-if="currentProduct">
                        <div class="mb-6 grid grid-cols-2 gap-4 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                            <div>
                                <p class="text-xs text-indigo-600 uppercase font-bold">Interest Rate</p>
                                <p class="text-lg font-black text-slate-900"
                                    x-text="currentProduct.interest_rate + '%'"></p>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-600 uppercase font-bold">Repayment Period</p>
                                <p class="text-lg font-black text-slate-900"
                                    x-text="currentProduct.duration_months + ' Months'"></p>
                            </div>
                            <div class="col-span-2 pt-2 border-t border-indigo-200">
                                <p class="text-xs text-gray-500 italic">
                                    Allowed Range: ₦<span
                                        x-text="Number(currentProduct.min_amount).toLocaleString()"></span>
                                    - ₦<span x-text="Number(currentProduct.max_amount).toLocaleString()"></span>
                                </p>
                            </div>
                        </div>
                    </template>

                    <!-- Amount Input -->
                    <div class="mb-6">
                        <x-input-label for="amount" :value="__('Principal Amount (₦)')" />
                        <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount"
                            :value="old('amount')" placeholder="e.g. 50000" required />
                        <p class="mt-1 text-xs text-gray-500">Enter the amount you wish to borrow.</p>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('loans.index') }}"
                            class="mr-4 text-sm text-gray-600 hover:underline">Cancel</a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            Submit Loan Application
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
