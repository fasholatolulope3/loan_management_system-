<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
                👥 {{ __('Verified Client Registry') }}
            </h2>
            <a href="{{ route('clients.create') }}"
                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/20 transition transform active:scale-95">
                <x-heroicon-o-user-plus class="w-4 h-4 mr-2 stroke-[3px]" /> Add New Member
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Requirement #9: Enhanced Search & Center Filtering -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] shadow-sm border border-slate-100 dark:border-slate-700 mb-8">
                <form method="GET" action="{{ route('clients.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <x-text-input type="text" name="search" placeholder="Search by name, ID, or business..."
                            value="{{ request('search') }}"
                            class="w-full dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl" />
                    </div>
                    <div>
                        <select name="center"
                            class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400 rounded-xl shadow-sm text-sm font-bold">
                            <option value="">All Branches</option>
                            @foreach (\App\Models\CollationCenter::all() as $center)
                                <option value="{{ $center->id }}"
                                    {{ request('center') == $center->id ? 'selected' : '' }}>{{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 px-4 py-2 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition">Filter</button>
                </form>
            </div>

            <!-- TABLE: HIGH DENSITY CLIENT DATA -->
            <div
                class="bg-white dark:bg-slate-800 overflow-hidden shadow-2xl rounded-[2.5rem] border border-slate-100 dark:border-slate-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                            <tr>
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Member Profile</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Business Analysis</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-600">
                                    ID / Verification</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-emerald-600">
                                    Avg Revenue</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Center</th>
                                <th
                                    class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($clients as $client)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition group">
                                    <!-- Profile Column -->
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-11 w-11 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-black text-sm border border-indigo-100 dark:border-indigo-800 group-hover:scale-110 transition">
                                                {{ substr($client->user?->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-black text-slate-900 dark:text-white leading-none">
                                                    {{ $client->user?->name ?? 'Orphaned Account' }}
                                                </div>
                                                <div
                                                    class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-tighter">
                                                    {{ $client->user?->email ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Business Info -->
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                            {{ $client->business_name ?? 'Personal File' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-medium italic mt-0.5">
                                            {{ $client->employment_status }}
                                        </div>
                                    </td>

                                    <!-- Identification -->
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div
                                            class="inline-flex items-center px-2 py-1 rounded bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 font-mono text-[11px] font-bold">
                                            NIN: {{ $client->national_id }}
                                        </div>
                                        <div class="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-widest">
                                            BVN: {{ $client->bvn ?? 'NOT SET' }}
                                        </div>
                                    </td>

                                    <!-- Income / Revenue -->
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-black text-emerald-600 dark:text-emerald-400 italic">
                                            ₦{{ number_format($client->income, 2) }}
                                        </div>
                                    </td>

                                    <!-- Branch (Requirement #5) -->
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 text-[10px] font-black uppercase rounded-lg border bg-slate-50 dark:bg-slate-900 text-slate-500 border-slate-200 dark:border-slate-700">
                                            {{ $client->user?->collationCenter?->center_code ?? 'MAIN' }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-8 py-5 whitespace-nowrap text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('clients.show', $client) }}"
                                                class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 hover:bg-indigo-600 hover:text-white transition duration-200">
                                                <x-heroicon-s-eye class="w-4 h-4" />
                                            </a>
                                            <a href="{{ route('clients.edit', $client) }}"
                                                class="p-2 rounded-lg bg-slate-50 dark:bg-slate-900/50 text-slate-500 hover:bg-slate-600 hover:text-white transition duration-200">
                                                <x-heroicon-s-pencil-square class="w-4 h-4" />
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <x-heroicon-o-users
                                                class="w-12 h-12 text-slate-200 dark:text-slate-700 mb-2" />
                                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">No
                                                member files exist in this center.</p>
                                            <a href="{{ route('clients.create') }}"
                                                class="text-indigo-600 text-[10px] font-black uppercase border-b-2 border-indigo-100 mt-2">Start
                                                Onboarding Process</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination Section -->
                @if ($clients->hasPages())
                    <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-t dark:border-slate-700">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
