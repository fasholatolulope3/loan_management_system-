import React from "react";
import {
  CheckCircle2,
  AlertCircle,
  FileText,
  Scale,
  ShieldCheck,
  CreditCard,
  Building2,
  BarChart3,
  CornerDownRight,
} from "lucide-react";

const pricingCategories = [
  {
    title: "Loan Facilities",
    icon: CreditCard,
    items: [
      { label: "Loan Amount", value: "Subject to credit assessment" },
      { label: "Interest Rate", value: "Risk-based, tenure-dependent" },
      { label: "Tenure", value: "Short to medium-term" },
      { label: "Management Fee", value: "One-time administrative charge" },
      { label: "Default Charges", value: "Applied only on overdue obligations" },
    ],
  },
  {
    title: "SME & Business Finance",
    icon: Building2,
    items: [
      { label: "Structure", value: "Tailored to business cash flow" },
      { label: "Repayment", value: "Documented schedule" },
      { label: "Terms", value: "Clearly defined contractual terms" },
    ],
  },
  {
    title: "Investment Products",
    icon: BarChart3,
    items: [
      { label: "Plans", value: "Structured with defined tenure" },
      { label: "Returns", value: "Communicated transparently" },
      { label: "Governance", value: "Formal investment agreement" },
    ],
  },
];

const coreValues = [
  "Full disclosure of terms before contract execution",
  "Written agreements for all transactions",
  "Professional credit evaluation process",
  "Ethical and responsible financial practices",
];

export default function PricingSection() {
  return (
    <section id="pricing" className="relative py-24 bg-zinc-950 overflow-hidden">
      {/* Decorative background glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full pointer-events-none -z-10">
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[600px] bg-indigo-500/5 blur-[120px] rounded-full" />
      </div>

      <div className="max-w-7xl mx-auto px-6 relative z-10">
        <div className="text-center mb-20 space-y-4">
          <h2 className="text-sm font-bold text-indigo-400 uppercase tracking-[0.2em] animate-fade-in">
            Pricing & Terms
          </h2>
          <h3 className="text-4xl md:text-5xl font-medium tracking-tight text-white animate-fade-in delay-100">
            A Framework of <span className="text-zinc-500">Transparency</span>
          </h3>
          <p className="max-w-2xl mx-auto text-lg text-zinc-400 animate-fade-in delay-200">
            Our pricing structure is risk-based and aligned with responsible lending standards to ensure clarity and confidence.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
          {pricingCategories.map((category, idx) => (
            <div
              key={idx}
              className="group relative flex flex-col p-8 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-md transition-all duration-500 hover:border-white/20 animate-fade-in"
              style={{ animationDelay: `${(idx + 3) * 100}ms` }}
            >
              <div className="flex items-center gap-4 mb-8">
                <div className="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 group-hover:scale-110 transition-transform duration-500">
                  <category.icon className="w-6 h-6 text-indigo-400" />
                </div>
                <h4 className="text-xl font-semibold text-white">{category.title}</h4>
              </div>

              <div className="space-y-6 flex-grow">
                {category.items.map((item, i) => (
                  <div key={i} className="space-y-1.5">
                    <div className="flex items-center gap-2 text-xs font-bold text-zinc-500 uppercase tracking-widest">
                      <CornerDownRight className="w-3 h-3 text-indigo-500/50" />
                      {item.label}
                    </div>
                    <div className="text-sm text-zinc-300 font-medium pl-5 border-l border-white/5">
                      {item.value}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>

        {/* Global Terms Alert */}
        <div className="animate-fade-in delay-700">
          <div className="relative overflow-hidden p-8 md:p-12 rounded-[2rem] border border-indigo-500/20 bg-indigo-500/5 backdrop-blur-xl">
            <div className="absolute top-0 right-0 p-8 opacity-10">
              <Scale className="w-32 h-32 text-indigo-400" />
            </div>

            <div className="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
              <div>
                <h4 className="text-2xl font-semibold text-white mb-6 flex items-center gap-3">
                  <ShieldCheck className="w-6 h-6 text-indigo-400" />
                  Responsible Financial Practices
                </h4>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  {coreValues.map((value, i) => (
                    <div key={i} className="flex items-start gap-3">
                      <CheckCircle2 className="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" />
                      <span className="text-sm text-zinc-300 leading-snug">{value}</span>
                    </div>
                  ))}
                </div>
              </div>

              <div className="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-4">
                <div className="flex gap-3 text-zinc-400">
                  <AlertCircle className="w-5 h-5 text-indigo-400 shrink-0" />
                  <p className="text-xs leading-relaxed italic">
                    All facilities and returns are subject to due diligence, internal approval, and prevailing regulatory guidelines. Pricing is determined based on risk profile and tenure.
                  </p>
                </div>
                <button className="w-full py-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold flex items-center justify-center gap-2 transition-all">
                  <FileText className="w-4 h-4" />
                  View Sample Agreement
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
