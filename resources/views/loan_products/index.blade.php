<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Loan Products') }}
            </h2>
            <!-- Secondary button in header -->
            @if ($products->count() > 0)
                <a href="{{ route('loan-products.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    + Add Product
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
{{-- 
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($products as $product)
                            <div class="border rounded-xl p-6 bg-gray-50 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-bold text-indigo-900">{{ $product->name }}</h3>
                                    <span
                                        class="px-2 py-1 text-xs font-bold rounded {{ $product->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ strtoupper($product->status) }}
                                    </span>
                                </div>

                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Interest Rate:</span>
                                        <span class="font-bold">{{ $product->interest_rate }}%</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Duration:</span>
                                        <span class="font-bold">{{ $product->duration_months }} Mo.</span>
                                    </div>
                                </div>

                                <a href="{{ route('loan-products.edit', $product) }}"
                                    class="block text-center py-2 bg-white border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-50">Edit
                                    Configuration</a>
                            </div>
                        @empty
                            <!-- PROMINENT EMPTY STATE BUTTON -->
                            <div class="col-span-full text-center py-20">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                                    <x-heroicon-o-briefcase class="w-8 h-8" />
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No Loan Products Found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first loan offering.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('loan-products.create') }}"
                                        class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 shadow-lg">
                                        <x-heroicon-o-plus class="w-5 h-5 mr-2" /> Create First Loan Product
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
