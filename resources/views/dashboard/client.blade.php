<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Loan Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Loan Summary -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Total Outstanding Balance</h3>
                    <p class="text-4xl font-bold text-indigo-700">₦{{ number_format($stats['total_balance'], 2) }}</p>
                    <a href="{{ route('loans.create') }}"
                        class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">Apply for New Loan</a>
                </div>

                <!-- Next Repayment -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4">Upcoming Repayment</h3>
                    @if ($stats['upcoming_payment'] && $stats['upcoming_payment']->schedules->first())
                        @php $next = $stats['upcoming_payment']->schedules->first(); @endphp
                        <p class="text-gray-600 italic">Due Date: {{ $next->due_date->format('d M Y') }}</p>
                        <p class="text-2xl font-bold text-red-500">₦{{ number_format($next->total_due, 2) }}</p>
                    @else
                        <p class="text-gray-500">No upcoming payments due.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
