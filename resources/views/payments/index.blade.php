<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction History (Collections)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Collected By
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 rounded text-[10px] font-black uppercase
                {{ $payment->type === 'disbursement' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $payment->type === 'disbursement' ? 'Outgoing' : 'Incoming' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if (auth()->user()->role === 'client')
                                        <span
                                            class="font-bold">{{ $payment->type === 'disbursement' ? 'Received Funds' : 'Repayment Sent' }}</span>
                                    @else
                                        <span
                                            class="font-bold">{{ $payment->type === 'disbursement' ? 'Paid to Client' : 'Received from Client' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-black">
                                    {{ $payment->type === 'disbursement' ? '-' : '+' }}
                                    ₦{{ number_format($payment->amount_paid, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $payments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
