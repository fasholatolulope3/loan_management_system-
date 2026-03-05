import React from "react";
import { 
  Scale, 
  ShieldCheck, 
  Clock, 
  FileText, 
  AlertTriangle, 
  Lock, 
  UserCheck, 
  Search, 
  Gavel, 
  RefreshCcw,
  BookOpen
} from "lucide-react";

const policies = [
  {
    id: 1,
    title: "Legal Status",
    icon: Gavel,
    content: "Power Dove Capital Investment Ltd is a duly registered company operating in accordance with applicable corporate and financial regulations. All services are provided subject to governing laws and regulatory guidelines within our jurisdiction of operation.",
  },
  {
    id: 2,
    title: "Terms of Use",
    icon: FileText,
    content: "By engaging with our services, website, or financial products, clients agree to: Provide accurate and complete information during onboarding; comply with all contractual obligations; use our services for lawful purposes only; and abide by repayment terms. We reserve the right to decline applications or terminate agreements where terms are violated.",
  },
  {
    id: 3,
    title: "Loan Policy",
    icon: Scale,
    content: "All loan facilities are subject to credit appraisal and internal approval. Interest rates and fees are risk-based and fully disclosed prior to contract execution. Repayment schedules are contractually binding, and default charges apply strictly in accordance with signed agreements.",
  },
  {
    id: 4,
    title: "Investment Policy",
    icon: BookOpen,
    content: "All investment products are structured under formal agreements outlining tenure, expected returns, and associated risks. Returns are not guaranteed unless explicitly stated in writing. Investors are advised to review all documentation carefully before commitment.",
  },
  {
    id: 5,
    title: "Risk Disclosure",
    icon: AlertTriangle,
    content: "Financial services inherently involve risk. Clients acknowledge that market conditions and credit risks may impact returns or repayment performance. Past performance does not guarantee future results, and investment decisions should be made after careful evaluation.",
  },
  {
    id: 6,
    title: "Privacy & Data Protection",
    icon: Lock,
    content: "We are committed to protecting client data. Personal information is collected solely for legitimate business purposes and handled with strict confidentiality. Information is not disclosed to third parties except where required by law or contractual necessity.",
  },
  {
    id: 7,
    title: "AML & Compliance",
    icon: UserCheck,
    content: "All clients are subject to Know Your Customer (KYC) procedures. Transactions may be monitored in accordance with AML regulations. We maintain zero tolerance for fraud, financial misconduct, or illicit transactions.",
  },
  {
    id: 8,
    title: "Limitation of Liability",
    icon: ShieldCheck,
    content: "Power Dove Capital shall not be liable for losses arising from inaccurate information provided by clients, delays caused by regulatory reviews, force majeure events, or investment risks clearly disclosed within executed agreements.",
  },
  {
    id: 9,
    title: "Dispute Resolution",
    icon: Search,
    content: "Any disputes shall first be addressed through internal resolution mechanisms. Where unresolved, matters may be referred to mediation, arbitration, or competent courts in accordance with governing laws.",
  },
  {
    id: 10,
    title: "Policy Amendments",
    icon: RefreshCcw,
    content: "Power Dove Capital reserves the right to update or amend its policies in line with regulatory changes and operational requirements. Updated policies will be communicated through official channels.",
  },
];

export default function LegalPoliciesSection() {
  return (
    <section id="legal" className="relative py-24 bg-zinc-950">
      <div className="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/5 to-transparent" />
      
      <div className="max-w-7xl mx-auto px-6 relative z-10">
        <div className="text-center mb-16 space-y-4">
          <h2 className="text-sm font-bold text-indigo-400 uppercase tracking-[0.2em] animate-fade-in">
            Governance
          </h2>
          <h3 className="text-4xl md:text-5xl font-medium tracking-tight text-white animate-fade-in delay-100">
            Legal & <span className="text-zinc-500">Policies</span>
          </h3>
          <p className="max-w-3xl mx-auto text-lg text-zinc-400 animate-fade-in delay-200">
            Our operations are guided by financial best practices and regulatory standards to ensure a secure and ethical financial partnership.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {policies.map((policy, index) => (
            <div
              key={policy.id}
              className="group relative p-8 rounded-3xl border border-white/5 bg-white/[0.02] transition-all duration-500 hover:bg-white/[0.04] hover:border-white/10 animate-fade-in"
              style={{ animationDelay: `${(index + 3) * 100}ms` }}
            >
              <div className="flex gap-6">
                <div className="flex-shrink-0 w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 group-hover:bg-indigo-500/20 group-hover:scale-110 transition-all duration-500">
                  <policy.icon className="w-6 h-6 text-indigo-400 group-hover:text-white transition-colors duration-500" />
                </div>
                <div className="space-y-3">
                  <div className="flex items-center gap-3">
                    <span className="text-xs font-mono text-zinc-600">0{policy.id === 10 ? '10' : policy.id}</span>
                    <h4 className="text-xl font-semibold text-white group-hover:text-indigo-300 transition-colors">
                      {policy.title}
                    </h4>
                  </div>
                  <p className="text-zinc-400 leading-relaxed text-sm">
                    {policy.content}
                  </p>
                </div>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-16 text-center">
          <p className="text-zinc-500 text-sm italic">
            For further clarification regarding our Legal & Policies framework, please contact our compliance unit through our official communication channels.
          </p>
        </div>
      </div>
    </section>
  );
}
