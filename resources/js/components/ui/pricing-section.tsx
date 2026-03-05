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
  Info
} from "lucide-react";
import SampleAgreementModal from "./sample-agreement-modal";

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
  const [isModalOpen, setIsModalOpen] = React.useState(false);

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

        {/* Global Terms Alert - Refined based on Mockup */}
        <div className="animate-fade-in delay-700">
          <div className="relative overflow-hidden p-8 md:p-10 rounded-3xl border border-white/5 bg-[#0a0a0b] shadow-2xl">
            <div className="relative z-10 flex flex-col gap-8">
              <div className="flex gap-4 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                <div className="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 shrink-0">
                  <Info className="w-5 h-5 text-indigo-400" />
                </div>
                <p className="text-sm text-zinc-400 leading-relaxed italic pr-4">
                  All facilities and returns are subject to due diligence, internal approval, and prevailing regulatory guidelines. Pricing is determined based on risk profile and tenure.
                </p>
              </div>
              
              <button 
                onClick={() => setIsModalOpen(true)}
                className="w-full group relative overflow-hidden py-4 md:py-5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold flex items-center justify-center gap-3 transition-all ring-1 ring-white/20 hover:ring-white/40 shadow-[0_0_30px_rgba(79,70,229,0.3)]"
              >
                <div className="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover:animate-shimmer" />
                <FileText className="w-5 h-5 transition-transform group-hover:scale-110" />
                <span>View Sample Agreement</span>
              </button>
            </div>
          </div>
        </div>

        {/* Modal Integration */}
        <SampleAgreementModal 
          isOpen={isModalOpen} 
          onClose={() => setIsModalOpen(false)} 
        />
      </div>
    </section>
  );
}
