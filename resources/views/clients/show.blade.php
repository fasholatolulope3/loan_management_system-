<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Client Profile: {{ $client->user?->name ?? 'Deleted/Unknown User' }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('clients.edit', $client) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit Profile
                </a>
                <a href="{{ route('loans.create', ['client_id' => $client->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    Apply for Loan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Sidebar: Client Summary -->
                <div class="space-y-6">
                    <!-- Client Profile Card -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="text-center border-b pb-4 mb-4">
                            <!-- Null-safe Avatar -->
                            <div
                                class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold text-3xl mx-auto mb-2">
                                {{ substr($client->user?->name ?? '?', 0, 1) }}
                            </div>

                            <!-- Null-safe Name -->
                            <h3 class="text-lg font-bold">
                                {{ $client->user?->name ?? 'Orphaned Client (No User)' }}
                            </h3>

                            <span
                                class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold uppercase">
                                Active Client
                            </span>
                        </div>

                        <div class="space-y-3">
                            <!-- Null-safe Email -->
                            <p class="text-sm text-gray-600">
                                <strong>Email:</strong> {{ $client->user?->email ?? 'N/A' }}
                            </p>

                            <!-- FIX: Added Null-safe operator to Phone Number -->
                            <p class="text-sm text-gray-600">
                                <strong>Phone:</strong> {{ $client->user?->phone ?? 'Not Available' }}
                            </p>

                            <p class="text-sm text-gray-600">
                                <strong>ID:</strong> {{ $client->national_id }}
                            </p>

                            <p class="text-sm text-gray-600">
                                <strong>Annual Income:</strong> ₦{{ number_format($client->income, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Guarantors Section -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-users class="w-5 h-5 mr-2 text-gray-400" /> Guarantors
                        </h4>

                        @forelse($client->guarantors as $guarantor)
                            <div class="mb-3 text-sm border-b pb-2 last:border-0 last:pb-0">
                                <p class="font-medium text-gray-900">{{ $guarantor->name }}</p>
                                <p class="text-gray-500 text-xs">
                                    <span class="font-bold uppercase">{{ $guarantor->relationship }}</span> •
                                    {{ $guarantor->phone }}
                                </p>
                                <p class="text-gray-400 text-[10px] mt-1 italic">{{ $guarantor->address }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500 italic">No guarantors linked yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Main Content: Loan History -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-bold text-gray-900 mb-4">Loan History</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <th class="pb-3">Product</th>
                                        <th class="pb-3">Amount</th>
                                        <th class="pb-3">Date</th>
                                        <th class="pb-3">Status</th>
                                        <th class="pb-3"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($client->loans as $loan)
                                        <tr>
                                            <td class="py-4 text-sm text-gray-900">{{ $loan->product->name }}</td>
                                            <td class="py-4 text-sm font-bold">₦{{ number_format($loan->amount, 2) }}
                                            </td>
                                            <td class="py-4 text-sm text-gray-500">
                                                {{ $loan->created_at->format('d M Y') }}</td>
                                            <td class="py-4">
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full 
                                                    {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ strtoupper($loan->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-right">
                                                <a href="{{ route('loans.show', $loan) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 text-sm">Details</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-10 text-center text-gray-500 italic">This
                                                client has no loan history.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
