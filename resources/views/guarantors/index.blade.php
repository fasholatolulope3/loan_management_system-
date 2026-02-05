<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
                📋 {{ __('Guarantor Assessment Registry') }}
            </h2>
            <a href="{{ route('clients.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-black shadow-lg transition uppercase tracking-widest">
                New Onboarding
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search / Search Requirement #9 logic -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 mb-8">
                <form method="GET" action="{{ route('guarantors.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <x-text-input name="search" value="{{ request('search') }}" class="w-full"
                            placeholder="Search by name, phone or relation..." />
                    </div>
                    <x-primary-button class="px-8">
                        {{ __('Filter Registry') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- REGISTRY TABLE -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black uppercase text-slate-500 tracking-widest">
                                    Guarantor Profile</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black uppercase text-slate-500 tracking-widest">
                                    Linked Client</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black uppercase text-slate-500 tracking-widest">
                                    Category</th>
                                <th
                                    class="px-6 py-4 text-left text-[10px] font-black uppercase text-slate-500 tracking-widest text-emerald-600">
                                    Net Income</th>
                                <th
                                    class="px-6 py-4 text-right text-[10px] font-black uppercase text-slate-500 tracking-widest">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($guarantors as $guarantor)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-slate-950 flex items-center justify-center text-indigo-600 font-black text-xs border border-indigo-100 dark:border-indigo-900">
                                                {{ substr($guarantor->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ $guarantor->name }}</p>
                                                <p class="text-[11px] text-slate-400 font-medium italic">
                                                    {{ $guarantor->relationship }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                                        {{ $guarantor->client?->user?->name ?? 'Orphaned' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 rounded text-[10px] font-black uppercase border
                                        {{ $guarantor->type === 'Business Owner' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-blue-50 text-blue-600 border-blue-200' }}">
                                            {{ $guarantor->type }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap font-black text-sm text-slate-900 dark:text-white">
                                        ₦{{ number_format($guarantor->net_monthly_income, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('guarantors.show', $guarantor) }}"
                                                class="p-2 hover:bg-white dark:hover:bg-slate-700 rounded-lg transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group">
                                                <x-heroicon-o-eye
                                                    class="w-4 h-4 text-slate-400 group-hover:text-indigo-600" />
                                            </a>
                                            <a href="{{ route('guarantors.edit', $guarantor) }}"
                                                class="p-2 hover:bg-white dark:hover:bg-slate-700 rounded-lg transition border border-transparent hover:border-slate-200 dark:hover:border-slate-600 group">
                                                <x-heroicon-o-pencil-square
                                                    class="w-4 h-4 text-slate-400 group-hover:text-amber-600" />
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <x-heroicon-o-user-group
                                                class="w-12 h-12 text-slate-200 dark:text-slate-700 mb-2" />
                                            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                                                Registry is currently empty</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-4 bg-slate-50 dark:bg-slate-900">
                    {{ $guarantors->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
