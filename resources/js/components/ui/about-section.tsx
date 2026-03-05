import React from "react";
import { 
  Target, 
  Eye, 
  Shield, 
  Zap, 
  Briefcase, 
  Handshake, 
  TrendingUp,
  Award,
  Users
} from "lucide-react";

const coreValues = [
  {
    title: "Integrity",
    description: "We uphold honesty and ethical standards in all dealings.",
    icon: Shield,
  },
  {
    title: "Transparency",
    description: "Clear terms, clear processes, and no hidden obligations.",
    icon: Eye,
  },
  {
    title: "Professionalism",
    description: "Structured systems and disciplined execution.",
    icon: Award,
  },
  {
    title: "Accountability",
    description: "Responsible stewardship of client and investor trust.",
    icon: Users,
  },
  {
    title: "Growth-Oriented Partnership",
    description: "We succeed when our clients succeed.",
    icon: Handshake,
  },
];

export default function AboutSection() {
  return (
    <section id="about" className="relative py-24 bg-zinc-950 overflow-hidden">
      {/* Subtle Background Glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full pointer-events-none -z-10">
        <div className="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-500/5 blur-[120px] rounded-full animate-pulse" />
      </div>

      <div className="max-w-7xl mx-auto px-6 relative z-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-24">
          <div className="space-y-8 animate-fade-in">
            <div className="space-y-4">
              <h2 className="text-sm font-bold text-indigo-400 uppercase tracking-[0.2em]">
                Who We Are
              </h2>
              <h3 className="text-4xl md:text-5xl font-medium tracking-tight text-white">
                Empowering <span className="text-zinc-500">Sustainable Growth</span>
              </h3>
            </div>
            
            <div className="space-y-6 text-lg text-zinc-400 leading-relaxed">
              <p>
                Power Dove Capital Investment Ltd is a professionally managed financial services company committed to delivering structured lending and investment solutions that promote sustainable growth for individuals and businesses.
              </p>
              <p>
                 Established with a strong focus on integrity, transparency, and financial discipline, we provide tailored financing options designed to meet the evolving needs of entrepreneurs, SMEs, and corporate clients.
              </p>
              <p className="text-sm text-zinc-500 italic">
                Through sound governance and compliance-driven operations, we continue to position ourselves as a reliable partner in financial empowerment and wealth creation.
              </p>
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 animate-fade-in delay-300">
            {/* Mission Card */}
            <div className="p-8 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm space-y-4 hover:bg-white/[0.08] transition-all duration-500">
              <div className="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20">
                <Target className="w-6 h-6 text-indigo-400" />
              </div>
              <h4 className="text-xl font-semibold text-white">Our Mission</h4>
              <p className="text-sm text-zinc-400 leading-relaxed">
                To provide accessible, transparent, and professionally structured financial solutions that empower businesses and individuals to achieve sustainable growth.
              </p>
            </div>

            {/* Vision Card */}
            <div className="p-8 rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm space-y-4 hover:bg-white/[0.08] transition-all duration-500 sm:mt-8">
              <div className="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center border border-violet-500/20">
                <TrendingUp className="w-6 h-6 text-violet-400" />
              </div>
              <h4 className="text-xl font-semibold text-white">Our Vision</h4>
              <p className="text-sm text-zinc-400 leading-relaxed">
                To be a trusted and reputable financial partner known for disciplined risk management, ethical practices, and long-term value creation.
              </p>
            </div>
          </div>
        </div>

        {/* Core Values Section */}
        <div className="space-y-12">
          <div className="text-center space-y-4">
            <h4 className="text-sm font-bold text-indigo-400 uppercase tracking-[0.2em] animate-fade-in delay-500">
              Our Principles
            </h4>
            <h5 className="text-3xl font-medium tracking-tight text-white animate-fade-in delay-600">
              Driven by <span className="text-zinc-500">Core Values</span>
            </h5>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            {coreValues.map((value, index) => (
              <div
                key={index}
                className="group relative p-6 rounded-2xl border border-white/5 bg-white/[0.02] text-center space-y-4 transition-all duration-500 hover:bg-white/[0.05] hover:border-white/10 animate-fade-in"
                style={{ animationDelay: `${(index + 7) * 100}ms` }}
              >
                <div className="mx-auto w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:bg-indigo-500/10 group-hover:border-indigo-500/20 transition-all duration-500">
                  <value.icon className="w-5 h-5 text-zinc-500 group-hover:text-indigo-400 transition-colors" />
                </div>
                <div className="space-y-2">
                  <h6 className="text-sm font-bold text-white group-hover:text-indigo-300 transition-colors">
                    {value.title}
                  </h6>
                  <p className="text-xs text-zinc-500 leading-relaxed">
                    {value.description}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
