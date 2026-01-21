<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Process Payment: Loan #{{ $loan->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow">
                <div class="mb-6 p-4 bg-blue-50 rounded text-blue-800">
                    <p><strong>Client:</strong> {{ $loan->client->user->name }}</p>
                    <p><strong>Installment Due:</strong> ₦{{ number_format($schedule->total_due, 2) }}</p>
                    <p><strong>Due Date:</strong> {{ $schedule->due_date->format('d M Y') }}</p>
                </div>

                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf
                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                    <div class="mb-4">
                        <x-input-label for="amount_paid" :value="__('Amount Collected (₦)')" />
                        <x-text-input id="amount_paid" class="block mt-1 w-full" type="number" step="0.01"
                            name="amount_paid" value="{{ $schedule->total_due }}" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="method" :value="__('Payment Method')" />
                        <select name="method" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="cash">Cash</option>
                            <option value="transfer">Bank Transfer</option>
                            <option value="card">POS / Card</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="reference" :value="__('Transaction Reference / Receipt #')" />
                        <x-text-input id="reference" class="block mt-1 w-full" type="text" name="reference"
                            value="{{ 'PAY-' . time() }}" required />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>Confirm Collection</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
