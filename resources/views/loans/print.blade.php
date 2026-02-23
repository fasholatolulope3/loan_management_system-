<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOAN-DOSSIER-{{ $loan->id }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .print-page {
                padding: 40px;
                page-break-after: always;
            }

            .print-border {
                border: 1px solid #000 !important;
            }

            .print-bg {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            padding: 40px;
        }

        .assessment-table th,
        .assessment-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 12px;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-sm print:shadow-none print:max-w-full print-page">

        <!-- HEADER: CENTER CREDENTIALS -->
        <header class="flex justify-between items-start border-b-4 border-slate-900 pb-6 mb-8">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter text-slate-900">Credit Proposal File</h1>
                <p class="text-sm font-bold text-indigo-600 uppercase">Center: {{ $loan->collationCenter?->name }}
                    ({{ $loan->collationCenter?->center_code }})</p>
                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1 italic">Authorized Authority
                    Verification V.25</p>
            </div>
            <div class="text-right">
                <div class="bg-indigo-600 text-white px-4 py-2 rounded mb-2">
                    <p class="text-[10px] uppercase font-black tracking-widest">Principal Loan Amount</p>
                    <p class="text-xl font-bold">₦{{ number_format($loan->amount, 2) }}</p>
                </div>
                <div class="bg-slate-900 text-white px-4 py-2 rounded mb-2">
                    <p class="text-[10px] uppercase font-black tracking-widest">Loan Ref #</p>
                    <p class="text-xl font-bold italic">{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <p class="text-xs text-slate-500 font-bold uppercase">Date Created:
                    {{ $loan->created_at->format('d/m/Y') }}
                </p>
            </div>
        </header>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- I. INFORMATION ABOUT APPLICANT (PDF CF2) -->
            <section class="space-y-4">
                <h3 class="bg-slate-900 text-white px-3 py-1 text-xs font-black uppercase tracking-widest">I.
                    Information about Applicant</h3>
                <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Surname / First Name</span>
                        <span class="font-bold">{{ $loan->client?->user?->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">National ID / NIN</span>
                        <span class="font-mono">{{ $loan->client?->national_id }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Date of Birth / Residence
                            Since</span>
                        {{ optional($loan->client?->date_of_birth)->format('d/m/Y') ?? 'N/A' }} /
                        {{ $loan->residence_since ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Dependents / Home Type</span>
                        {{ $loan->dependent_count ?? 0 }} Persons / {{ strtoupper($loan->home_ownership ?? 'N/A') }}
                    </div>
                </div>
                @if(strtolower($loan->home_ownership) === 'renting')
                    <div class="mt-2 p-2 bg-slate-50 rounded border border-slate-100 text-[10px]">
                        <span class="text-slate-500 uppercase font-black mr-2">RENTAL NOTICE:</span>
                        <span class="font-bold">₦{{ number_format($loan->next_rent_amount, 2) }}</span> due on <span
                            class="font-bold text-indigo-600">{{ optional($loan->next_rent_date)->format('d M, Y') ?? 'N/A' }}</span>
                    </div>
                @endif
                <div class="mt-3">
                    <span class="text-slate-500 uppercase text-[10px] font-bold block">Full Residential Address</span>
                    <span class="text-xs italic">{{ $loan->client?->address }}</span>
                </div>
            </section>

            <!-- II. INFORMATION ABOUT BUSINESS (PDF CF2) -->
            <section class="space-y-4">
                <h3 class="bg-slate-900 text-white px-3 py-1 text-xs font-black uppercase tracking-widest">II. Business
                    Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                    <div class="col-span-2">
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Business Activity</span>
                        <span
                            class="font-bold uppercase tracking-tight text-indigo-700 text-lg">{{ $loan->business_name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Location / Tenure</span>
                        {{ $loan->business_location }} (Since: {{ optional($loan->business_start_date)->format('Y') }})
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Premises Ownership</span>
                        {{ strtoupper($loan->business_premise_type ?? 'N/A') }}
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Workforce 규모</span>
                        {{ $loan->employee_count ?? 0 }} Employees
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[10px] font-bold block">Points of Sale /
                            Ownership</span>
                        {{ $loan->point_of_sale_count ?? 0 }} POS /
                        {{ $loan->has_co_owners ? 'CO-OWNED' : 'SINGLE OWNER' }}
                    </div>
                </div>
            </section>
        </div>

        <!-- FINANCIAL TABLES: REQUIREMENT #7 -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <!-- III. CASH FLOW ANALYSIS -->
            <table class="w-full assessment-table">
                <thead class="bg-gray-100 uppercase text-[10px] font-black">
                    <tr>
                        <th colspan="2" class="text-center py-2 bg-slate-800 text-white border-none">III. Monthly
                            Cash Flow (₦)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Monthly Sales Revenue</td>
                        <td class="font-bold text-right">{{ number_format($loan->monthly_sales, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Monthly Cost of Sales</td>
                        <td class="font-bold text-red-500 text-right">({{ number_format($loan->cost_of_sales, 2) }})
                        </td>
                    </tr>
                    <tr class="bg-indigo-50 font-black">
                        <td>GROSS PROFIT</td>
                        <td class="text-right">{{ number_format($loan->gross_profit, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Operational Expenses</td>
                        <td class="text-red-500 text-right">-{{ number_format($loan->operational_expenses, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Other Net Income</td>
                        <td class="text-green-600 text-right">+{{ number_format($loan->other_net_income ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Family Expenses</td>
                        <td class="text-red-600 text-right">-{{ number_format($loan->family_expenses, 2) }}</td>
                    </tr>
                    <tr class="bg-indigo-600 text-white font-black text-sm">
                        <td>PAYMENT CAPACITY</td>
                        <td class="text-right">₦{{ number_format($loan->payment_capacity, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- IV. BALANCE SHEET -->
            <table class="w-full assessment-table">
                <thead class="bg-gray-100 uppercase text-[10px] font-black">
                    <tr>
                        <th colspan="2" class="text-center py-2 bg-slate-800 text-white border-none">IV. Balance
                            Sheet (₦)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Current Assets (Cash/INV)</td>
                        <td class="font-bold text-right">{{ number_format($loan->current_assets, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Fixed Assets (Tools/Prop)</td>
                        <td class="font-bold text-right">{{ number_format($loan->fixed_assets, 2) }}</td>
                    </tr>
                    <tr class="bg-indigo-50 font-black">
                        <td>TOTAL ASSETS</td>
                        <td class="text-right text-indigo-700">
                            {{ number_format($loan->current_assets + $loan->fixed_assets, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>TOTAL LIABILITIES</td>
                        <td class="font-bold text-red-600 text-right">
                            ({{ number_format($loan->total_liabilities, 2) }})</td>
                    </tr>
                    <tr class="bg-slate-900 text-white font-black text-sm">
                        <td>NET EQUITY</td>
                        <td class="text-right">₦{{ number_format($loan->equity_value, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SECTION V: COLLATERAL EVALUATION (Form CF5) -->
        <section class="mb-8">
            <h3 class="bg-amber-600 text-white px-3 py-1 text-xs font-black uppercase tracking-widest mb-2">V.
                Collateral Evaluation & Appraisal (Form CF5)</h3>
            <table class="w-full assessment-table">
                <thead class="bg-slate-50 text-[10px] font-black uppercase">
                    <tr>
                        <th>S/N</th>
                        <th>Asset Type</th>
                        <th>Item Description</th>
                        <th>Purchase Price</th>
                        <th>Market Value</th>
                        <th class="bg-amber-100">Liquidation Val</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loan->collaterals as $index => $c)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="uppercase">{{ $c->type }}</td>
                            <td>{{ $c->description }}</td>
                            <td class="text-right text-slate-400 italic">
                                {{ number_format($c->purchase_price ?? 0, 2) }}
                            </td>
                            <td class="font-bold text-right">{{ number_format($c->market_value, 2) }}</td>
                            <td class="font-black text-right text-amber-700">
                                ₦{{ number_format($c->liquidation_value, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-slate-50 font-black">
                        <td colspan="5" class="text-right text-[10px]">TOTAL COLLATERAL APPRAISAL:</td>
                        <td class="text-right text-amber-800 text-sm italic">
                            ₦{{ number_format($loan->collaterals->sum('liquidation_value'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- SECTION VI: GUARANTOR RECAP (Form CF4) -->
        @if ($loan->guarantor)
            <section class="mb-12">
                <h3 class="bg-emerald-700 text-white px-3 py-1 text-xs font-black uppercase tracking-widest mb-2">VI.
                    Primary Guarantor Credit Quality (Form CF4)</h3>
                <div class="border border-slate-200 p-4 grid grid-cols-3 gap-6 rounded">
                    <div>
                        <span class="text-slate-500 uppercase text-[9px] font-black block">Identification</span>
                        <p class="text-xs font-black">{{ $loan->guarantor->name }}</p>
                        <p class="text-[10px] text-slate-400 italic leading-none">{{ $loan->guarantor->relationship }}
                            • {{ $loan->guarantor->phone }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[9px] font-black block">Status / Income</span>
                        <p class="text-xs font-black uppercase italic">{{ $loan->guarantor->type }}</p>
                        <p class="text-xs font-black text-emerald-600 tracking-tighter">
                            ₦{{ number_format($loan->guarantor->net_monthly_income, 2) }}/mo</p>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase text-[9px] font-black block">Officer Recommendation</span>
                        <p class="text-[10px] italic leading-snug">"Client qualifies under the
                            {{ $loan->product?->name }} loan product rules."
                        </p>
                    </div>
                </div>
            </section>
        @endif

        <!-- SECTION VII: AMORTIZATION LEDGER -->
        <section class="mb-12">
            <h3 class="bg-indigo-900 text-white px-3 py-1 text-xs font-black uppercase tracking-widest mb-2">VII. Future
                Repayment Schedule (Amortization)</h3>
            <table class="w-full assessment-table">
                <thead class="bg-slate-50 text-[10px] font-black uppercase">
                    <tr>
                        <th class="text-center">Inst.</th>
                        <th>Due Date</th>
                        <th class="text-right">Principal</th>
                        <th class="text-right">Interest</th>
                        <th class="text-right">Total Installment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loan->schedules as $index => $s)
                        <tr>
                            <td class="text-center font-bold text-slate-500">{{ $index + 1 }} of
                                {{ $loan->schedules->count() }}
                            </td>
                            <td class="font-bold">{{ $s->due_date->format('d M, Y') }}</td>
                            <td class="text-right font-medium text-slate-600 italic">
                                ₦{{ number_format($s->principal_amount, 2) }}</td>
                            <td class="text-right font-medium text-slate-600 italic">
                                ₦{{ number_format($s->interest_amount, 2) }}</td>
                            <td class="text-right font-black text-slate-900">₦{{ number_format($s->total_due, 2) }}</td>
                            <td class="text-center">
                                <span
                                    class="uppercase text-[9px] font-black {{ $s->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    [{{ $s->status }}]
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-indigo-50 font-black">
                        <td colspan="4" class="text-right text-[10px] py-3">GRAND TOTAL REPAYMENT:</td>
                        <td class="text-right text-indigo-900 text-sm italic">
                            ₦{{ number_format($loan->schedules->sum('total_due'), 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- FINAL SIGN OFF: GOVERNANCE -->
        <footer class="pt-8 border-t-2 border-dashed border-slate-300 grid grid-cols-2 gap-20">
            <div class="text-center">
                <div class="border-b border-black mb-1 h-12"></div>
                <p class="text-[9px] font-black uppercase">Loan Officer (Site Investigator)</p>
                <p class="text-sm font-bold mt-1 tracking-tighter underline underline-offset-4">
                    {{ auth()->user()->name }}
                </p>
            </div>
            <div class="text-center">
                <div
                    class="border-b border-black mb-1 h-12 italic text-slate-200 flex items-center justify-center font-bold">
                    APPROVER STAMP</div>
                <p class="text-[9px] font-black uppercase">Authorized Credit Committee Signature</p>
                <p class="text-xs font-medium text-slate-400 uppercase mt-1 italic tracking-[0.2em]">Verified Approval
                    Authority</p>
            </div>
        </footer>

        <!-- NAVIGATION ACTIONS (Will not be printed) -->
        <div class="no-print mt-10 flex justify-center gap-4">
            <button onclick="window.print()"
                class="bg-indigo-600 text-white px-8 py-3 rounded-full font-black text-sm uppercase tracking-widest shadow-xl hover:bg-indigo-700 transition transform hover:scale-105">
                🖨️ Execute Print Dossier
            </button>
            <a href="{{ route('loans.show', $loan->id) }}"
                class="bg-slate-200 text-slate-600 px-8 py-3 rounded-full font-black text-sm uppercase tracking-widest transition">
                Return to Digital File
            </a>
        </div>
    </div>
</body>

</html>