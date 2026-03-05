import React from "react";
import {
  ShieldCheck,
  Zap,
  LayoutDashboard,
  Coins,
  TrendingUp,
  Briefcase,
  Users,
  LineChart,
  ClipboardCheck,
  Rocket,
} from "lucide-react";

const features = [
  {
    title: "Structured Lending Solutions",
    description: "Tailored short-term and medium-term loan facilities designed to meet individual and business cash flow needs.",
    icon: ShieldCheck,
  },
  {
    title: "SME & Business Financing",
    description: "Working capital support, business expansion funding, and asset financing for small and medium enterprises.",
    icon: Briefcase,
  },
  {
    title: "Competitive & Transparent Interest Structure",
    description: "Clear loan terms with no hidden charges, ensuring full transparency and client confidence.",
    icon: Coins,
  },
  {
    title: "Flexible Repayment Plans",
    description: "Customizable repayment schedules aligned with clients’ income cycles and operational structure.",
    icon: CalendarCheck,
  },
  {
    title: "Fast Processing & Disbursement",
    description: "Efficient credit appraisal system ensuring timely loan approval and fund release.",
    icon: Zap,
  },
  {
    title: "Investment Opportunities",
    description: "Secure and structured investment options designed to generate stable returns.",
    icon: TrendingUp,
  },
  {
    title: "Professional Risk Assessment",
    description: "Robust credit evaluation and risk management framework to protect both investors and borrowers.",
    icon: LineChart,
  },
  {
    title: "Client-Centered Approach",
    description: "Dedicated relationship management and personalized financial advisory support.",
    icon: Users,
  },
  {
    title: "Compliance & Ethical Standards",
    description: "Operations guided by financial best practices and regulatory standards.",
    icon: ClipboardCheck,
  },
  {
    title: "Growth-Oriented Financial Partnership",
    description: "Long-term partnership model focused on client sustainability and business expansion.",
    icon: Rocket,
  },
];

// Re-using the same icons or mapping to descriptive ones
import { CalendarCheck } from "lucide-react";

export default function FeaturesSection() {
  return (
    <section id="features" className="relative py-24 bg-zinc-950 overflow-hidden">
      {/* Background Decorative Elements */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none overflow-hidden -z-10">
        <div className="absolute top-1/4 -left-1/4 w-1/2 h-1/2 bg-indigo-500/5 blur-[120px] rounded-full animate-pulse" />
        <div className="absolute bottom-1/4 -right-1/4 w-1/2 h-1/2 bg-violet-500/5 blur-[120px] rounded-full animate-pulse" style={{ animationDelay: '3s' }} />
      </div>

      <div className="max-w-7xl mx-auto px-6 relative z-10">
        <div className="text-center mb-20 space-y-4">
          <h2 className="text-sm font-bold text-indigo-400 uppercase tracking-[0.2em] animate-fade-in">
            Our Expertise
          </h2>
          <h3 className="text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight text-white animate-fade-in delay-100">
            Core Features of <span className="text-zinc-500">Power Dove Capital</span>
          </h3>
          <p className="max-w-2xl mx-auto text-lg text-zinc-400 animate-fade-in delay-200">
            Innovative financial solutions designed to empower businesses and individuals through structured growth and transparent partnership.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {features.map((feature, index) => (
            <div
              key={index}
              className={`group relative p-8 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm transition-all duration-500 hover:bg-white/[0.08] hover:border-white/20 hover:-translate-y-1 animate-fade-in`}
              style={{ animationDelay: `${(index + 3) * 100}ms` }}
            >
              <div className="mb-6 relative">
                <div className="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 group-hover:bg-indigo-500/20 group-hover:scale-110 transition-all duration-500">
                  <feature.icon className="w-6 h-6 text-indigo-400 group-hover:text-white transition-colors duration-500" />
                </div>
                {/* Subtle Glow */}
                <div className="absolute inset-0 bg-indigo-500/20 blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
              </div>
              
              <h4 className="text-xl font-semibold text-white mb-3 group-hover:text-indigo-300 transition-colors">
                {feature.title}
              </h4>
              <p className="text-zinc-400 leading-relaxed text-sm">
                {feature.description}
              </p>
              
              {/* Corner Accent */}
              <div className="absolute top-4 right-4 w-8 h-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                <div className="absolute top-0 right-0 w-2 h-2 border-t border-r border-indigo-500/50" />
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
