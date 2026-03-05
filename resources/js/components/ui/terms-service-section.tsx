import React from "react";
import { 
  Info, 
  Briefcase, 
  CheckSquare, 
  ClipboardList, 
  DollarSign, 
  CreditCard, 
  TrendingUp, 
  Shield, 
  Copyright, 
  Gavel, 
  Edit3, 
  Globe,
  ArrowRight
} from "lucide-react";

const terms = [
  {
    id: 1,
    title: "Introduction",
    icon: Info,
    content: "These Terms of Service (“Terms”) govern the use of the services, products, and website of Power Dove Capital Investment Ltd (“the Company”). By accessing our services or engaging in any financial transaction with us, you agree to be bound by these Terms. If you do not agree with these Terms, you should not use our services.",
  },
  {
    id: 2,
    title: "Services Provided",
    icon: Briefcase,
    content: "Power Dove Capital Investment Ltd provides structured lending services, SME financing, and investment products subject to eligibility, due diligence, and internal approval processes. All financial products are governed by separate written agreements executed between the Company and the client.",
  },
  {
    id: 3,
    title: "Eligibility",
    icon: CheckSquare,
    content: "To access our services, you must: Be legally capable of entering into binding contracts; provide accurate and complete information; and comply with all applicable laws and regulations. The Company reserves the right to refuse service at its discretion where eligibility requirements are not met.",
  },
  {
    id: 4,
    title: "Client Obligations",
    icon: ClipboardList,
    content: "Clients agree to: Provide truthful and complete documentation; notify the Company of any material change in financial condition; adhere strictly to agreed repayment schedules; and use loan proceeds solely for lawful purposes. Failure to comply may result in suspension of services or legal recovery action.",
  },
  {
    id: 5,
    title: "Fees and Charges",
    icon: DollarSign,
    content: "All applicable interest rates, administrative fees, processing fees, and default charges are disclosed prior to contract execution. By signing a financial agreement, the client acknowledges and accepts the stated terms.",
  },
  {
    id: 6,
    title: "Loan Repayment and Default",
    icon: CreditCard,
    content: "Repayment obligations are contractually binding. In the event of default: Late payment charges may apply; recovery procedures may be initiated; and legal remedies may be pursued. The Company may report delinquent obligations to appropriate regulatory or credit reporting bodies.",
  },
  {
    id: 7,
    title: "Investment Terms",
    icon: TrendingUp,
    content: "Investment products are subject to documented agreements outlining tenure, return structure, and associated risks. Unless expressly stated in writing, returns are not guaranteed. Investors acknowledge and accept associated financial risks.",
  },
  {
    id: 8,
    title: "Limitation of Liability",
    icon: Shield,
    content: "The Company shall not be liable for losses resulting from inaccurate information provided by clients, delays caused by regulatory review or force majeure events, or indirect or consequential damages. Nothing in these Terms limits liability where such limitation is prohibited by law.",
  },
  {
    id: 9,
    title: "Intellectual Property",
    icon: Copyright,
    content: "All website content, logos, branding materials, and documentation remain the intellectual property of Power Dove Capital Investment Ltd and may not be reproduced without written consent.",
  },
  {
    id: 10,
    title: "Dispute Resolution",
    icon: Gavel,
    content: "Disputes shall first be addressed through internal resolution procedures. Where unresolved, disputes may be referred to mediation, arbitration, or competent courts in accordance with applicable laws.",
  },
  {
    id: 11,
    title: "Amendments",
    icon: Edit3,
    content: "The Company reserves the right to amend these Terms from time to time. Updated versions will be published through official channels.",
  },
  {
    id: 12,
    title: "Governing Law",
    icon: Globe,
    content: "These Terms shall be governed by and construed in accordance with the laws applicable within the Company’s jurisdiction of operation.",
  },
];

export default function TermsServiceSection() {
  return (
    <section id="terms" className="relative py-24 bg-zinc-950">
      <div className="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-indigo-500/20 to-transparent" />
      
      <div className="max-w-7xl mx-auto px-6 relative z-10">
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
          <div className="space-y-4 max-w-2xl">
            <h2 className="text-sm font-bold text-indigo-400 uppercase tracking-[0.2em] animate-fade-in">
              Agreement
            </h2>
            <h3 className="text-4xl md:text-5xl font-medium tracking-tight text-white animate-fade-in delay-100">
              Terms of <span className="text-zinc-500">Service</span>
            </h3>
            <p className="text-lg text-zinc-400 animate-fade-in delay-200">
              Please read these terms carefully before engaging with our financial products and services.
            </p>
          </div>
          <div className="animate-fade-in delay-300">
            <div className="p-4 rounded-2xl bg-white/5 border border-white/10 text-xs text-zinc-500 italic">
              Last Updated: March 2026
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
          {terms.map((term, index) => (
            <div
              key={term.id}
              className="group relative space-y-4 animate-fade-in"
              style={{ animationDelay: `${(index + 3) * 100}ms` }}
            >
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-indigo-500/10 group-hover:border-indigo-500/20 transition-all duration-500">
                  <term.icon className="w-5 h-5 text-zinc-400 group-hover:text-indigo-400 transition-colors" />
                </div>
                <h4 className="text-lg font-semibold text-white group-hover:text-indigo-300 transition-colors">
                  {term.id}. {term.title}
                </h4>
              </div>
              <p className="text-sm text-zinc-400 leading-relaxed pl-13 border-l border-white/5 group-hover:border-indigo-500/20 transition-colors">
                {term.content}
              </p>
            </div>
          ))}
        </div>

        <div className="mt-20 p-8 rounded-3xl border border-indigo-500/20 bg-indigo-500/5 backdrop-blur-xl animate-fade-in delay-1000">
          <div className="flex flex-col md:flex-row items-center justify-between gap-8">
            <div className="space-y-2">
              <h4 className="text-xl font-semibold text-white">Questions about these terms?</h4>
              <p className="text-zinc-400 text-sm">Contact our compliance department for professional guidance and inquiries.</p>
            </div>
            <button className="group px-8 py-4 rounded-full bg-white text-zinc-950 font-bold hover:bg-indigo-50 transition-all flex items-center gap-2">
              Compliance Inquiry
              <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}
