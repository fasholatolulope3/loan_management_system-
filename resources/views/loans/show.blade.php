<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Loan Details: #{{ $loan->id }}</h2>
            @if ($loan->status === 'pending' && auth()->user()->role !== 'client')
                <div class="flex space-x-2">
                    <form action="{{ route('loans.approve', $loan) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">Approve Loan</button>
                    </form>
                    <form action="{{ route('loans.reject', $loan) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="bg-red-600 text-white px-4 py-2 rounded text-sm font-bold">Reject</button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Loan Summary Card -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <p class="text-xs text-gray-500 uppercase">Principal</p>
                    <p class="text-xl font-bold">₦{{ number_format($loan->amount, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <p class="text-xs text-gray-500 uppercase">Interest Rate</p>
                    <p class="text-xl font-bold">{{ $loan->interest_rate }}%</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <p class="text-xs text-gray-500 uppercase">Duration</p>
                    <p class="text-xl font-bold">{{ $loan->duration_months }} Months</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <p class="text-xs text-gray-500 uppercase">Total Repayment</p>
                    <p class="text-xl font-bold text-indigo-600">
                        ₦{{ number_format($loan->schedules->sum('total_due'), 2) }}</p>
                </div>
            </div>
            <!-- Client Verification Card -->
            <div class="bg-indigo-900 text-white rounded-2xl p-8 mb-8 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Verification Credit Memo</h3>
                    <span class="px-3 py-1 bg-indigo-700 rounded-full text-xs uppercase font-black">Strict
                        Verification</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Identity -->
                    <div>
                        <p class="text-indigo-300 text-xs uppercase font-bold mb-2">Identity Details</p>
                        <p class="text-sm"><strong>NIN:</strong> {{ $loan->client->national_id }}</p>
                        <p class="text-sm"><strong>BVN:</strong> {{ $loan->client->bvn }}</p>
                        <p class="text-sm">
                            <strong>DOB:</strong>
                            {{ $loan->client->date_of_birth ? $loan->client->date_of_birth->format('d M Y') : 'Not Provided' }}
                        </p>
                    </div>

                    <!-- Financials -->
                    <div>
                        <p class="text-indigo-300 text-xs uppercase font-bold mb-2">Financial Status</p>
                        <p class="text-sm"><strong>Reported Income:</strong>
                            ₦{{ number_format($loan->client->income, 2) }}</p>
                        <p class="text-sm"><strong>Address:</strong> {{ $loan->client->address }}</p>
                    </div>

                    <!-- Guarantor -->
                    <div>
                        <p class="text-indigo-300 text-xs uppercase font-bold mb-2">Primary Guarantor</p>
                        @php $g = $loan->client->guarantors->first(); @endphp
                        @if ($g)
                            <p class="text-sm font-bold">{{ $g->name }}</p>
                            <p class="text-sm">{{ $g->relationship }} ({{ $g->phone }})</p>
                        @else
                            <p class="text-sm text-red-400">Missing Guarantor!</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Repayment Schedule -->
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Repayment Schedule</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Installment</th>
                            <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            @if (auth()->user()->role !== 'client')
                                <th class="px-6 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($loan->schedules as $schedule)
                            <tr>
                                <td class="px-6 py-3 text-sm">{{ $schedule->due_date->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-sm font-bold">₦{{ number_format($schedule->total_due, 2) }}
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <span
                                        class="{{ $schedule->status === 'paid' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                @if (auth()->user()->role !== 'client')
                                    <td class="px-6 py-3 text-right">
                                        @if ($schedule->status !== 'paid' && $loan->status === 'active')
                                            <a href="{{ route('payments.create', ['loan_id' => $loan->id, 'schedule_id' => $schedule->id]) }}"
                                                class="text-indigo-600 hover:underline text-sm font-bold">Record
                                                Payment</a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
