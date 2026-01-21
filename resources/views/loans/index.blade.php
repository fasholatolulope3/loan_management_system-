<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ auth()->user()->role === 'client' ? __('My Loans') : __('Loan Management') }}
            </h2>
            @if (auth()->user()->role === 'client')
                <a href="{{ route('loans.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold">Apply for Loan</a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">Loan ID</th>
                            @if (auth()->user()->role !== 'client')
                                <th class="pb-3">Client</th>
                            @endif
                            <th class="pb-3">Product</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Date</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($loans as $loan)
                            <tr>
                                <td class="py-4 text-sm text-gray-600">#{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                @if (auth()->user()->role !== 'client')
                                    <td class="py-4 text-sm font-medium text-gray-900">
                                        {{ $loan->client?->user?->name ?? 'Unknown User' }}
                                    </td>
                                    </td>
                                @endif
                                <td class="py-4 text-sm text-gray-600">{{ $loan->product->name }}</td>
                                <td class="py-4 text-sm font-bold">₦{{ number_format($loan->amount, 2) }}</td>
                                <td class="py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-semibold
                                        {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $loan->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $loan->status === 'defaulted' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ strtoupper($loan->status) }}
                                    </span>
                                </td>
                                <td class="py-4 text-sm text-gray-500">{{ $loan->created_at->format('d M Y') }}</td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('loans.show', $loan) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-500 italic">No loans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $loans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
