<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 leading-tight uppercase tracking-tight">
            🛡️ {{ __('System Security & Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search & Filtering -->
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 mb-8">
                <form method="GET" action="{{ route('audit-logs.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label value="Action Description" />
                        <x-text-input name="description" value="{{ request('description') }}" class="w-full mt-1"
                            placeholder="Filter by action (e.g. approved)..." />
                    </div>
                    <div>
                        <x-input-label value="Authorized User" />
                        <select name="user_id"
                            class="w-full mt-1 border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 rounded-md">
                            <option value="">All Staff Members</option>
                            @foreach (\App\Models\User::whereIn('role', ['admin', 'officer'])->get() as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <x-primary-button class="w-full justify-center py-2.5">Apply Trace</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- LOGS TABLE -->
            <div
                class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                        <thead class="bg-slate-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest">
                                    Timestamp (GMT)</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest">
                                    Operator</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest">Action
                                    Code</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest">Trace
                                    Detail</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest">IP
                                    Metadata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40 transition">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-xs font-medium text-slate-500 dark:text-slate-400">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-black text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                                {{ substr($log->user?->name ?? 'SYS', 0, 1) }}
                                            </div>
                                            <span
                                                class="text-sm font-bold dark:text-white">{{ $log->user?->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-black rounded uppercase border border-indigo-100 dark:border-indigo-900">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-slate-700 text-[10px] font-bold font-mono text-gray-600 dark:text-slate-300">
                                            {{ $log->ip_address }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <x-heroicon-o-shield-exclamation class="w-12 h-12 text-slate-300 mb-2" />
                                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">No
                                                traces found in current log file.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
