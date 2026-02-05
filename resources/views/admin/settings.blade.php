<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2
                    class="font-black text-xl text-gray-800 dark:text-slate-200 uppercase tracking-tighter leading-tight">
                    ⚙️ {{ __('System Infrastructure Settings') }}
                </h2>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-1 italic">
                    Enterprise Protocol v2.4.0
                </p>
            </div>
            <div class="flex items-center gap-3 no-print">
                <button type="button" onclick="window.print()"
                    class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-500 rounded-lg hover:text-indigo-600 transition">
                    <x-heroicon-o-printer class="w-5 h-5" />
                </button>
                <x-primary-button
                    class="bg-indigo-600 dark:bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest px-8">
                    Push Config Global
                </x-primary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- SECTION 1: CORPORATE IDENTITY & INFRASTRUCTURE -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <h3 class="text-sm font-black uppercase text-indigo-600 dark:text-indigo-400 tracking-widest">Branch
                        Foundation</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed italic">Identify your
                        primary operations center and legal identity for the loan dossiers.</p>
                </div>

                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-100 dark:border-slate-700 p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label value="Institutional Name"
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                                <x-text-input class="w-full mt-1 bg-slate-50 dark:bg-slate-900 border-none font-bold"
                                    value="SmartLoan Global Inc." disabled />
                            </div>
                            <div>
                                <x-input-label value="Operation Currency"
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                                <div class="relative">
                                    <x-text-input
                                        class="w-full mt-1 bg-slate-50 dark:bg-slate-900 border-none font-bold"
                                        value="Nigerian Naira (₦)" disabled />
                                    <span
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-indigo-500 uppercase">NGN-Zone</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-input-label value="Registry Headquarters"
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400" />
                            <x-text-input class="w-full mt-1 bg-slate-50 dark:bg-slate-900 border-none"
                                value="Financial Tower, Victoria Island, Lagos" disabled />
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: GLOBAL FINANCIAL PROTOCOL (The Requirements Hub) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                <div class="lg:col-span-1">
                    <h3 class="text-sm font-black uppercase text-amber-600 dark:text-amber-500 tracking-widest">
                        Financial Guardrails</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed italic">Set mandatory
                        constraints for Arrears, Fines, and Approval thresholds.</p>
                </div>

                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Policy Rule: 0.005 Penalty -->
                        <div
                            class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <span class="p-2 bg-red-50 dark:bg-red-900/20 rounded-xl text-red-600">
                                    <x-heroicon-o-scale class="w-5 h-5" />
                                </span>
                                <span
                                    class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[9px] font-black uppercase italic">System
                                    Logic Active</span>
                            </div>
                            <h4 class="font-black text-xs uppercase tracking-tight text-slate-900 dark:text-white">Late
                                Arrears Penalty Rate</h4>
                            <p class="text-xs text-slate-500 mt-1 mb-4 italic leading-tight">Requirement #1 enforced.
                            </p>
                            <p class="text-3xl font-black italic tracking-tighter text-indigo-600">0.005 <span
                                    class="text-xs font-bold text-slate-400 tracking-widest">PER DAY</span></p>
                        </div>

                        <!-- Policy Rule: Multi-Branch Access -->
                        <div
                            class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <span class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-600">
                                    <x-heroicon-o-lock-closed class="w-5 h-5" />
                                </span>
                                <span
                                    class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[9px] font-black uppercase italic">Enforced
                                    Policy</span>
                            </div>
                            <h4 class="font-black text-xs uppercase tracking-tight text-slate-900 dark:text-white">
                                Center Data Partitioning</h4>
                            <p class="text-xs text-slate-500 mt-1 mb-4 italic leading-tight">Requirement #5 enforced.
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                                <span
                                    class="text-[10px] font-black uppercase text-slate-900 dark:text-white tracking-widest">Credentials
                                    Restricted</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- SECTION 3: SYSTEM INTEGRITY & LOGGING -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                <div class="lg:col-span-1">
                    <h3 class="text-sm font-black uppercase text-slate-400 tracking-widest">Oversight & Compliance</h3>
                </div>

                <div class="lg:col-span-2">
                    <div
                        class="bg-slate-900 dark:bg-slate-950 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-700">
                        </div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="text-lg font-black text-white italic tracking-tighter">Audit Trajectory
                                    System</h4>
                                <a href="{{ route('audit-logs.index') }}"
                                    class="text-[10px] font-black uppercase text-indigo-400 underline decoration-indigo-400/50 underline-offset-4 hover:text-white transition">Access
                                    Registry &rarr;</a>
                            </div>
                            <div class="grid grid-cols-3 gap-6 text-center border-t border-white/10 pt-6">
                                <div>
                                    <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Log Level</p>
                                    <p class="text-xs text-indigo-400 font-black">HIGH RESOLUTION</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-500 uppercase mb-1">Staff Fingerprint</p>
                                    <p class="text-xs text-white font-black">MANDATORY</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-500 uppercase mb-1">IP Tracking</p>
                                    <p class="text-xs text-white font-black">ENABLED</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requirement #9 Policy Footer -->
            <div class="flex justify-center opacity-30 group hover:opacity-100 transition-opacity">
                <span
                    class="text-[8px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.4em] italic border border-slate-200 dark:border-slate-800 px-6 py-2 rounded-full">
                    Validated Internal Control Registry • 0.005 Daily Factor Algorithm V2.5
                </span>
            </div>
        </div>
    </div>

    <!-- Print Specific Typography -->
    <style>
        @media print {
            body {
                background: white !important;
            }

            .shadow-xl,
            .shadow-2xl,
            .shadow-sm {
                box-shadow: none !important;
            }

            .bg-white,
            .bg-slate-900 {
                border: 1px solid #eee !important;
                color: black !important;
            }
        }
    </style>
</x-app-layout>
