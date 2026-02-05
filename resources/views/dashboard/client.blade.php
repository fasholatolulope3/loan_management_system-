<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-200 leading-tight">
            {{ __('My Financial Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome & Primary Actions -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Hello,
                        {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-500 dark:text-slate-400">Here is a breakdown of your current standing.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('loans.create') }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-indigo-200 dark:shadow-none text-sm">
                        New Application
                    </a>
                </div>
            </div>

            <!-- KEY METRICS: THE TWO-WAY LOGIC -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- 1. Disbursement Stat: Funds Received (Company -> Client) -->
                <div class="bg-indigo-700 text-white p-6 rounded-3xl shadow-xl relative overflow-hidden">
                    <div class="absolute right-[-10%] top-[-20%] w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                    <h3 class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-4">Total Funds Received
                    </h3>
                    <p class="text-3xl font-black">
                        ₦{{ number_format($stats['funds_received'], 2) }}
                    </p>
                    <p class="text-[10px] mt-2 opacity-80 uppercase font-bold tracking-tighter">Verified disbursements
                        into your account</p>
                </div>

                <!-- 2. Repayment Stat: Amount Paid (Client -> Company) -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                        Total Amount Repaid</h3>
                    <p class="text-3xl font-black text-emerald-600 dark:text-emerald-500">
                        ₦{{ number_format($stats['total_repaid'], 2) }}
                    </p>
                    <div class="flex items-center gap-1 mt-2 text-emerald-500">
                        <x-heroicon-s-check-badge class="w-4 h-4" />
                        <p class="text-[10px] font-black uppercase italic">Successfully Verified</p>
                    </div>
                </div>

                <!-- 3. Current Standing: Total Balance -->
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                        Outstanding Balance</h3>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">
                        ₦{{ number_format($stats['total_balance'], 2) }}
                    </p>
                    <p class="text-[10px] mt-2 text-slate-400 uppercase font-bold">Principal + Remaining Interest</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- ACTION CARD: NEXT REPAYMENT -->
                <div
                    class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6 uppercase tracking-tight">
                            Active Installment</h3>

                        @if ($stats['upcoming_payment'])
                            <div
                                class="flex justify-between items-center p-6 bg-slate-50 dark:bg-slate-950/50 rounded-2xl mb-6 border border-slate-100 dark:border-slate-800">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1 tracking-widest">
                                        Installment Due</p>
                                    <p class="text-3xl font-black text-indigo-600">
                                        ₦{{ number_format($stats['upcoming_payment']->total_due, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Due Date</p>
                                    <p class="text-sm font-black dark:text-white">
                                        {{ optional($stats['upcoming_payment']->due_date)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div
                                class="mb-8 flex items-start gap-4 p-4 bg-blue-50 dark:bg-indigo-900/20 border border-blue-100 dark:border-indigo-800 rounded-xl">
                                <x-heroicon-o-information-circle class="w-6 h-6 text-indigo-500 flex-shrink-0" />
                                <div>
                                    <p
                                        class="text-xs font-bold text-indigo-700 dark:text-indigo-400 uppercase tracking-widest">
                                        Repayment Instruction</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        Transfer exactly <span
                                            class="font-bold text-slate-900 dark:text-white">₦{{ number_format($stats['upcoming_payment']->total_due, 2) }}</span>
                                        to <br />
                                        <strong>GTBank: 0123456789</strong> (SmartLoan Global), then upload your receipt
                                        below.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('payments.create', ['schedule_id' => $stats['upcoming_payment']->id, 'loan_id' => $stats['upcoming_payment']->loan_id]) }}"
                                class="block w-full bg-slate-900 dark:bg-white dark:text-slate-900 text-white text-center py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-[1.02] transition shadow-2xl active:scale-95">
                                I Have Transferred Funds
                            </a>
                        @else
                            <div class="py-12 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 mb-4">
                                    <x-heroicon-o-check-circle class="w-10 h-10" />
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">No Pending Payments</h4>
                                <p class="text-sm text-gray-500 mt-1">Your account is in good standing! 🏆</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SECONDARY TOOLS -->
                <div class="space-y-6">
                    <!-- My Loans Redirect -->
                    <a href="{{ route('loans.index') }}"
                        class="group bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 flex justify-between items-center hover:border-indigo-500 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-indigo-50 dark:bg-slate-950 rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition">
                                <x-heroicon-o-document-text class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white">Full Loan History</h4>
                                <p class="text-xs text-slate-400 font-medium">Review your past and current loan
                                    agreements</p>
                            </div>
                        </div>
                        <x-heroicon-o-chevron-right
                            class="w-5 h-5 text-slate-300 group-hover:text-indigo-600 transition" />
                    </a>

                    <!-- Profile Support Info -->
                    <div class="bg-slate-900 text-white p-8 rounded-3xl shadow-lg relative overflow-hidden">
                        <div class="absolute left-0 bottom-0 opacity-10">
                            <x-heroicon-o-question-mark-circle class="w-40 h-40" />
                        </div>
                        <h4 class="text-sm font-black text-indigo-400 uppercase tracking-widest mb-2">Technical Support
                        </h4>
                        <p class="text-sm text-slate-300 mb-6 leading-relaxed">Having issues with your transfer
                            verification? Chat with our support team immediately.</p>
                        <a href="#contact"
                            class="inline-flex items-center text-xs font-black uppercase tracking-widest text-white border-b-2 border-indigo-500 pb-1 hover:text-indigo-400 transition">Get
                            Assistance &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
