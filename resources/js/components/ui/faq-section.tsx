import React, { useState } from "react";
import { Plus, Minus, HelpCircle, MessageCircle } from "lucide-react";

const faqs = [
  {
    question: "What services does Power Dove Capital Investment Ltd provide?",
    answer: "Power Dove Capital Investment Ltd offers structured lending facilities, SME and working capital finance, and structured investment products designed to support sustainable financial growth for individuals and businesses.",
  },
  {
    question: "Who qualifies to apply for a loan facility?",
    answer: "Loan facilities are available to eligible individuals, entrepreneurs, and duly registered businesses that meet our internal credit assessment and documentation requirements.",
  },
  {
    question: "How are interest rates determined?",
    answer: "Interest rates are risk-based and are determined after a comprehensive credit appraisal. Factors considered include the loan amount, tenure, repayment capacity, financial history, and overall risk profile.",
  },
  {
    question: "What documentation is required for loan applications?",
    answer: "Applicants are generally required to provide valid identification, proof of address, bank statements, and relevant business registration documents (for corporate applicants). Additional documentation may be requested depending on the facility type.",
  },
  {
    question: "What is the typical processing timeline?",
    answer: "Processing timelines vary depending on the completeness of documentation and the outcome of due diligence reviews. Applications that meet all requirements are processed within a reasonable and clearly communicated timeframe.",
  },
  {
    question: "Are all fees and charges disclosed upfront?",
    answer: "Yes. All applicable fees, charges, and terms are clearly disclosed prior to contract execution. We operate a transparent pricing policy with no undisclosed charges.",
  },
  {
    question: "What happens in the event of default?",
    answer: "In the event of late or missed payments, default charges may apply in accordance with the signed agreement. We encourage early communication to explore possible restructuring options where appropriate.",
  },
  {
    question: "How do your investment products operate?",
    answer: "Our investment products are structured with defined tenures and clearly documented terms. Returns, risk considerations, and obligations are outlined in a formal investment agreement prior to onboarding.",
  },
  {
    question: "How is client information protected?",
    answer: "We maintain strict confidentiality standards and implement appropriate data protection measures to safeguard client information in line with applicable regulations.",
  },
  {
    question: "How can I initiate an application or inquiry?",
    answer: "Prospective clients may contact us through our official communication channels or visit our office for preliminary discussions and onboarding guidance.",
  },
];

const FaqItem = ({ question, answer, isOpen, onClick, index }: { 
  question: string; 
  answer: string; 
  isOpen: boolean; 
  onClick: () => void;
  index: number;
}) => {
  return (
    <div 
      className={`group border-b border-white/10 transition-all duration-500 ${isOpen ? 'bg-white/[0.02]' : ''} animate-fade-in`}
      style={{ animationDelay: `${(index + 2) * 100}ms` }}
    >
      <button
        onClick={onClick}
        className="flex w-full items-center justify-between py-6 text-left focus:outline-none"
      >
        <span className={`text-lg font-medium transition-colors duration-300 ${isOpen ? 'text-indigo-400' : 'text-zinc-200 group-hover:text-white'}`}>
          {question}
        </span>
        <div className={`ml-4 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/10 transition-all duration-300 ${isOpen ? 'rotate-180 bg-indigo-500/20 border-indigo-500/30' : 'group-hover:border-white/20'}`}>
          {isOpen ? (
            <Minus className="h-4 w-4 text-indigo-400" />
          ) : (
            <Plus className="h-4 w-4 text-zinc-400 group-hover:text-white" />
          )}
        </div>
      </button>
      <div
        className={`overflow-hidden transition-all duration-500 ease-in-out ${
          isOpen ? "max-h-96 pb-6 opacity-100" : "max-h-0 opacity-0"
        }`}
      >
        <p className="text-zinc-400 leading-relaxed max-w-4xl">
          {answer}
        </p>
      </div>
    </div>
  );
};

export default function FaqSection() {
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  return (
    <section id="faqs" className="relative py-24 bg-zinc-950">
      {/* Subtle top divider with glow */}
      <div className="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent" />
      
      <div className="max-w-4xl mx-auto px-6 relative z-10">
        <div className="text-center mb-16 space-y-4">
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-indigo-500/20 bg-indigo-500/5 text-xs font-bold text-indigo-400 uppercase tracking-widest animate-fade-in">
            <HelpCircle className="w-3 h-3" />
            Support Center
          </div>
          <h2 className="text-4xl md:text-5xl font-medium tracking-tight text-white animate-fade-in delay-100">
            Frequently Asked <span className="text-zinc-500">Questions</span>
          </h2>
          <p className="text-lg text-zinc-400 animate-fade-in delay-200">
            Find quick answers to common inquiries about our services, processes, and standards.
          </p>
        </div>

        <div className="space-y-2">
          {faqs.map((faq, index) => (
            <FaqItem
              key={index}
              index={index}
              question={faq.question}
              answer={faq.answer}
              isOpen={openIndex === index}
              onClick={() => setOpenIndex(openIndex === index ? null : index)}
            />
          ))}
        </div>

        {/* Contact CTA */}
        <div className="mt-16 p-8 rounded-3xl border border-white/5 bg-white/[0.02] flex flex-col md:flex-row items-center justify-between gap-6 animate-fade-in delay-700">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20">
              <MessageCircle className="w-6 h-6 text-indigo-400" />
            </div>
            <div>
              <h4 className="text-lg font-semibold text-white">Still have questions?</h4>
              <p className="text-sm text-zinc-400">Our customer service team is here to assist you professionally.</p>
            </div>
          </div>
          <button className="px-8 py-3 rounded-full bg-white text-zinc-950 font-bold hover:bg-zinc-200 transition-colors">
            Contact Support
          </button>
        </div>
      </div>
    </section>
  );
}
