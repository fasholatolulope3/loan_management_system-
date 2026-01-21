<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Officer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500 uppercase font-bold">Pending My Review</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['my_pending_approvals'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500 uppercase font-bold">Total Active Loans</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['active_loans'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-sm text-gray-500 uppercase font-bold">Payments Collected Today</p>
                    <p class="text-2xl font-bold text-green-600">₦{{ number_format($stats['today_payments'], 2) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <p>Ready to process new loan applications? <a href="{{ route('loans.index') }}"
                        class="text-blue-600 underline">View Loan Queue</a></p>
            </div>
        </div>
    </div>
</x-app-layout>
