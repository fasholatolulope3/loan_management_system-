import React from "react";
import { motion, AnimatePresence } from "framer-motion";
import { X, FileText, ShieldCheck, CheckCircle2, Download } from "lucide-react";

interface SampleAgreementModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function SampleAgreementModal({ isOpen, onClose }: SampleAgreementModalProps) {
  // Prevent body scroll when modal is open
  React.useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "unset";
    }
    return () => {
      document.body.style.overflow = "unset";
    };
  }, [isOpen]);

  return (
    <AnimatePresence>
      {isOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 md:p-10">
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
            className="absolute inset-0 bg-zinc-950/80 backdrop-blur-md"
          />

          {/* Modal Content */}
          <motion.div
            initial={{ opacity: 0, scale: 0.95, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.95, y: 20 }}
            className="relative w-full max-w-4xl max-h-[90vh] bg-zinc-900 border border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col"
          >
            {/* Header */}
            <div className="p-6 md:p-8 border-b border-white/5 flex items-center justify-between bg-zinc-900/50 backdrop-blur-xl">
              <div className="flex items-center gap-4">
                <div className="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20">
                  <FileText className="w-6 h-6 text-indigo-400" />
                </div>
                <div>
                  <h3 className="text-xl font-bold text-white tracking-tight">Sample Financial Agreement</h3>
                  <p className="text-sm text-zinc-500 font-medium">Power Dove Capital Investment Ltd</p>
                </div>
              </div>
              <div className="flex items-center gap-3">
                <button className="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-zinc-400 hover:text-white transition-colors">
                  <Download className="w-4 h-4" />
                  <span className="text-sm">PDF</span>
                </button>
                <button
                  onClick={onClose}
                  className="p-3 rounded-xl bg-white/5 border border-white/10 text-zinc-400 hover:text-white hover:bg-white/10 transition-all"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>
            </div>

            {/* Content Body */}
            <div className="flex-1 overflow-y-auto p-6 md:p-10 space-y-10 custom-scrollbar">
              {/* Introduction */}
              <div className="space-y-6">
                <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">
                  Confidential Draft
                </div>
                <h4 className="text-2xl font-bold text-white">1. PARTIES AND PREAMBLE</h4>
                <p className="text-zinc-400 leading-relaxed italic border-l-2 border-indigo-500/30 pl-6">
                  "This Agreement is made between Power Dove Capital Investment Ltd (hereinafter referred to as 'the Company') and the undersigned Client (hereinafter referred to as 'the Borrower/Investor'). This document serves as a standard template for the implementation of structured lending and investment products."
                </p>
              </div>

              {/* Terms Grid */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="p-6 rounded-2xl bg-white/[0.02] border border-white/5 space-y-4">
                  <div className="flex items-center gap-3 text-white font-semibold">
                    <ShieldCheck className="w-5 h-5 text-indigo-400" />
                    General Obligations
                  </div>
                  <ul className="space-y-3">
                     {[
                       "Accurate information disclosure",
                       "Compliance with AML requirements",
                       "Adherence to repayment cycles",
                       "Notification of financial changes"
                     ].map((text, i) => (
                       <li key={i} className="flex gap-3 text-xs text-zinc-500">
                         <CheckCircle2 className="w-4 h-4 text-zinc-700 shrink-0" />
                         {text}
                       </li>
                     ))}
                  </ul>
                </div>
                <div className="p-6 rounded-2xl bg-white/[0.02] border border-white/5 space-y-4">
                  <div className="flex items-center gap-3 text-white font-semibold">
                    <TrendingUp className="w-5 h-5 text-indigo-400" />
                    Financial Provisions
                  </div>
                  <ul className="space-y-3">
                     {[
                       "Risk-based interest calculations",
                       "Structured management fees",
                       "Late payment charge framework",
                       "Investment tenure definitions"
                     ].map((text, i) => (
                       <li key={i} className="flex gap-3 text-xs text-zinc-500">
                         <CheckCircle2 className="w-4 h-4 text-zinc-700 shrink-0" />
                         {text}
                       </li>
                     ))}
                  </ul>
                </div>
              </div>

              {/* Legal Text Section */}
              <div className="space-y-6">
                <h4 className="text-xl font-bold text-white">2. GOVERNANCE AND REMEDIES</h4>
                <div className="space-y-4 text-sm text-zinc-400 leading-relaxed font-light">
                  <p>
                    All facilities approved under this agreement are subject to robust internal credit assessment and appraisal. Power Dove Capital reserves the right to exercise legal remedies in events of default, including reporting to relevant credit bureaus.
                  </p>
                  <p>
                    Investment returns are projected based on structured deployment and are governed by the formal return schedule attached to specific product agreements.
                  </p>
                </div>
              </div>

              {/* Signature Section */}
              <div className="pt-10 border-t border-white/5 grid grid-cols-1 sm:grid-cols-2 gap-12">
                <div className="space-y-8">
                  <div className="space-y-2">
                    <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Company Seal</p>
                    <div className="h-16 w-32 border border-dashed border-white/10 rounded-lg flex items-center justify-center opacity-50 grayscale text-[8px] text-zinc-800">
                       POWER DOVE CAPITAL SEAL
                    </div>
                  </div>
                  <div className="space-y-1">
                    <p className="text-sm font-bold text-white">Power Dove Capital Ltd.</p>
                    <p className="text-xs text-zinc-500">Authorized Signatory</p>
                  </div>
                </div>
                <div className="space-y-8">
                  <div className="space-y-2">
                    <p className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Client Acknowledgement</p>
                    <div className="h-16 border-b border-white/10 flex items-end pb-2 italic text-zinc-600 font-serif">
                       Digital Signature Placeholder
                    </div>
                  </div>
                  <div className="space-y-1">
                    <p className="text-sm font-bold text-white">Sample Representative</p>
                    <p className="text-xs text-zinc-500">Verification Pending</p>
                  </div>
                </div>
 signatures           </div>
            </div>

            {/* Footer Actions */}
            <div className="p-6 md:p-8 border-t border-white/5 bg-zinc-950/50 backdrop-blur-xl flex flex-col sm:flex-row gap-4 items-center justify-between">
              <p className="text-xs text-zinc-500">
                This document is a sample and does not constitute a binding offer.
              </p>
              <div className="flex gap-4 w-full sm:w-auto">
                <button
                  onClick={onClose}
                  className="flex-1 sm:flex-none px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white font-bold hover:bg-white/10 transition-all"
                >
                  Close
                </button>
                <button className="flex-1 sm:flex-none px-8 py-3 rounded-full bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)]">
                  Execute Agreement
                </button>
              </div>
            </div>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
}
