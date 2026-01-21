<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Financial Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded shadow border-t-4 border-indigo-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Portfolio</p>
                    <p class="text-2xl font-bold">₦{{ number_format($reportData['active_principal'], 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded shadow border-t-4 border-green-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Collections To Date</p>
                    <p class="text-2xl font-bold">₦{{ number_format($reportData['total_collected'], 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded shadow border-t-4 border-purple-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Repayment Rate</p>
                    <p class="text-2xl font-bold">{{ number_format($reportData['repayment_rate'], 1) }}%</p>
                </div>
                <div class="bg-white p-6 rounded shadow border-t-4 border-gray-500">
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Records</p>
                    <p class="text-2xl font-bold">{{ $reportData['total_loans'] }} Loans</p>
                </div>
            </div>

            <!-- Visualization Placeholder -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold mb-4">Portfolio Performance</h3>
                <div
                    class="h-64 bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300 rounded text-gray-400 italic">
                    [Chart.js Integration Point: Monthly Disbursement vs Collections]
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
