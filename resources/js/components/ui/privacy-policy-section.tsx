import React, { useState } from "react";
import { 
  ShieldCheck, 
  Database, 
  Target, 
  Gavel, 
  Share2, 
  Lock, 
  Clock, 
  UserRound, 
  Globe, 
  RefreshCw,
  Eye,
  ArrowRight
} from "lucide-react";
import ContactSupportModal from "./contact-support-modal";

const policies = [
  {
    id: 1,
    title: "Introduction",
    icon: ShieldCheck,
    content: "Power Dove Capital Investment Ltd is committed to safeguarding the privacy and confidentiality of client information. This Privacy Policy explains how we collect, use, process, and protect personal data.",
  },
  {
    id: 2,
    title: "Information We Collect",
    icon: Database,
    content: "We collect full name, dob, ID details, residential/business addresses, contact info (phone, email), financial info (bank statements, income records), business registration docs, and transaction records directly from clients or lawful verification channels.",
  },
  {
    id: 3,
    title: "Purpose of Processing",
    icon: Target,
    content: "Personal data is processed for customer onboarding (KYC), credit assessment, loan/investment management, regulatory reporting, fraud prevention, risk management, and compliance with legal obligations.",
  },
  {
    id: 4,
    title: "Legal Basis",
    icon: Gavel,
    content: "We process personal data based on contractual necessity, legal and regulatory obligations, legitimate business interests, and client consent where explicitly required.",
  },
  {
    id: 5,
    title: "Data Sharing",
    icon: Share2,
    content: "Client data may be shared with regulatory authorities, credit bureaus, partner financial institutions, and professional advisers under confidentiality. We do not sell client data to third parties.",
  },
  {
    id: 6,
    title: "Data Security",
    icon: Lock,
    content: "We implement administrative, technical, and physical safeguards to protect data from unauthorized access or disclosure. Access is restricted to authorized personnel only.",
  },
  {
    id: 7,
    title: "Data Retention",
    icon: Clock,
    content: "Personal data is retained only for as long as necessary to fulfill contractual and regulatory requirements, ensuring compliance with governing archival standards.",
  },
  {
    id: 8,
    title: "Client Rights",
    icon: UserRound,
    content: "Clients may request access to their personal data, request correction of inaccurate info, or request clarification regarding data usage. Requests should be directed to our compliance unit.",
  },
  {
    id: 9,
    title: "Website Tracking",
    icon: Globe,
    content: "Our website may use basic cookies for functionality and performance monitoring. Users may adjust browser settings to manage cookie preferences as they see fit.",
  },
  {
    id: 10,
    title: "Policy Updates",
    icon: RefreshCw,
    content: "This policy may be updated periodically to reflect regulatory changes or operational practices. The latest version will always be published through our official channels.",
  },
];

export default function PrivacyPolicySection() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  return (
    <section id="privacy" className="relative py-24 bg-zinc-950">
      <div className="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent" />
      
      <div className="max-w-7xl mx-auto px-6 relative z-10">
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
          <div className="space-y-4 max-w-2xl">
            <h2 className="text-sm font-bold text-emerald-400 uppercase tracking-[0.2em] animate-fade-in">
              Data Governance
            </h2>
            <h3 className="text-4xl md:text-5xl font-medium tracking-tight text-white animate-fade-in delay-100">
              Privacy <span className="text-zinc-500">Policy</span>
            </h3>
            <p className="text-lg text-zinc-400 animate-fade-in delay-200">
              Transparency in how we handle your personal information is fundamental to our financial partnership.
            </p>
          </div>
          <div className="animate-fade-in delay-300">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 text-xs text-emerald-400">
              <Eye className="w-4 h-4" />
              Your data is secure with us
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
          {policies.map((policy, index) => (
            <div
              key={policy.id}
              className="group flex gap-6 animate-fade-in"
              style={{ animationDelay: `${(index + 3) * 100}ms` }}
            >
              <div className="flex-shrink-0">
                <div className="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-500">
                  <policy.icon className="w-6 h-6 text-zinc-500 group-hover:text-emerald-400 transition-colors" />
                </div>
              </div>
              <div className="space-y-3">
                <h4 className="text-xl font-semibold text-white group-hover:text-emerald-300 transition-colors">
                  {policy.id}. {policy.title}
                </h4>
                <p className="text-sm text-zinc-400 leading-relaxed font-light">
                  {policy.content}
                </p>
              </div>
            </div>
          ))}
        </div>

        <div className="mt-20 flex flex-col items-center">
          <div className="w-full max-w-4xl p-8 rounded-[2.5rem] bg-emerald-500/5 border border-emerald-500/10 backdrop-blur-xl relative overflow-hidden text-center animate-fade-in delay-1000">
            <div className="relative z-10 space-y-6">
              <h4 className="text-2xl font-semibold text-white">Privacy Inquiries</h4>
              <p className="text-zinc-400 max-w-xl mx-auto italic">
                "For privacy-related inquiries, please contact our designated data protection or compliance officer."
              </p>
              <button 
                onClick={() => setIsModalOpen(true)}
                className="px-10 py-4 rounded-full bg-emerald-500 hover:bg-emerald-400 text-white font-bold transition-all shadow-[0_0_20px_rgba(16,185,129,0.2)] flex items-center gap-2 mx-auto"
              >
                Contact Data Officer
                <ArrowRight className="w-4 h-4" />
              </button>
            </div>
            {/* Background Texture */}
            <div className="absolute top-0 right-0 p-10 opacity-5">
              <ShieldCheck className="w-48 h-48 text-emerald-400" />
            </div>
          </div>
        </div>

        <ContactSupportModal 
          isOpen={isModalOpen} 
          onClose={() => setIsModalOpen(false)} 
        />
      </div>
    </section>
  );
}
