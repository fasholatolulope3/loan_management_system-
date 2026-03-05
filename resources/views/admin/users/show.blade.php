<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter">
                👤 Authority Profile: {{ $user->name }}
            </h2>
            <a href="{{ route('users.index') }}"
                class="bg-slate-100 dark:bg-slate-900 text-slate-500 hover:text-slate-900 dark:hover:text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border border-slate-200 dark:border-slate-800 transition">
                &larr; Back to Registry
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column: Primary Stats & Identity -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profile Card -->
                    <div
                        class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                        <div
                            class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-4xl font-black mb-4 border-4 border-white dark:border-slate-800 shadow-xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-1 uppercase tracking-tighter">
                            {{ $user->name }}</h3>
                        <p
                            class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest mb-6 italic">
                            {{ strtoupper($user->role) }} // {{ strtoupper($user->status ?? 'ACTIVE') }}
                        </p>

                        <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-3 text-left">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400">
                                    <x-heroicon-s-envelope class="w-5 h-5" />
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Email
                                        Address</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 truncate">
                                        {{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-left">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-400">
                                    <x-heroicon-s-phone class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Official
                                        Line</p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $user->phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Center Attribution -->
                    <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl text-white">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-2">Assigned
                            Jurisdiction</p>
                        <h4 class="text-xl font-black uppercase mb-4">
                            {{ $user->collationCenter?->name ?? 'SYSTEM MASTER AUTHORITY' }}</h4>
                        <div
                            class="inline-block px-3 py-1 bg-white/10 rounded-lg text-[10px] font-black uppercase tracking-widest">
                            CODE: {{ $user->collationCenter?->center_code ?? 'ROOT' }}
                        </div>
                    </div>
                </div>

                <!-- Right Column: Audit Trail & Timeline -->
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                        <div
                            class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/10">
                            <h4 class="font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest text-xs">
                                System Forensic Trail</h4>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent 50
                                Activities</span>
                        </div>

                        <div class="p-8">
                            <div class="space-y-8">
                                @forelse($user->auditLogs as $log)
                                    <div
                                        class="relative pl-8 pb-8 border-l border-slate-100 dark:border-slate-700 last:pb-0">
                                        <div
                                            class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-indigo-500 ring-4 ring-indigo-500/20 shadow-lg">
                                        </div>
                                        <div class="flex justify-between items-start mb-2">
                                            <p
                                                class="text-xs font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">
                                                {{ str_replace('_', ' ', $log->action) }}
                                            </p>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                                {{ $log->created_at->diffForHumans() }} // {{ $log->ip_address }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 leading-relaxed">
                                            {{ $log->description }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1">
                                            {{ $log->created_at->format('M d, Y @ H:i:s') }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="py-12 text-center">
                                        <x-heroicon-o-finger-print class="w-12 h-12 text-slate-200 mx-auto mb-4" />
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">No digital
                                            footprint recorded</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Meta Data -->
                    <div class="grid grid-cols-2 gap-6">
                        <div
                            class="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Enrolled On
                            </p>
                            <p class="font-bold text-slate-700 dark:text-slate-300">
                                {{ $user->created_at->format('M d, Y @ H:i') }}</p>
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-900 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Last Update
                            </p>
                            <p class="font-bold text-slate-700 dark:text-slate-300">
                                {{ $user->updated_at->format('M d, Y @ H:i') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>