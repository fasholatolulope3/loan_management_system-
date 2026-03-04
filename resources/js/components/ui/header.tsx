import React from "react";
import MegaMenu from "./mega-menu";
import { NAV_ITEMS } from "../blocks/mega-menu-demo";
import { ArrowRight } from "lucide-react";

interface HeaderProps {
    logoUrl: string;
    appName: string;
    isLoggedIn: boolean;
    dashboardUrl: string;
    loginUrl: string;
}

export default function Header({ 
    logoUrl, 
    appName, 
    isLoggedIn, 
    dashboardUrl, 
    loginUrl 
}: HeaderProps) {
    const [isScrolled, setIsScrolled] = React.useState(false);

    React.useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 10);
        };
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    return (
        <nav className={`fixed top-0 w-full z-50 transition-all duration-300 ${isScrolled ? "py-3" : "py-5"}`}>
            <div className="absolute inset-0 bg-zinc-950/60 backdrop-blur-xl border-b border-white/5 shadow-[0_4px_30px_rgba(0,0,0,0.1)]"></div>
            <div className="relative flex items-center justify-between px-6 max-w-7xl mx-auto">
                
                {/* Logo area */}
                <a href="/" className="flex items-center gap-3 group relative z-10 mr-8">
                    <div className="relative flex items-center justify-center h-10 w-auto rounded-xl overflow-hidden shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40 transition-all duration-300 border border-white/10">
                        <img src={logoUrl} alt={appName} className="h-10 w-auto object-contain bg-zinc-900" />
                    </div>
                    <span className="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400 tracking-tight group-hover:to-white transition-all">
                        {appName}
                    </span>
                </a>

                {/* Center Nav Links - Mega Menu */}
                <div className="hidden md:flex items-center gap-1 bg-white/5 border border-white/10 rounded-full px-2 py-1 backdrop-blur-md">
                    <MegaMenu items={NAV_ITEMS} />
                </div>

                {/* Right Actions */}
                <div className="flex items-center gap-3 md:gap-4 relative z-10">
                    {isLoggedIn ? (
                        <a href={dashboardUrl}
                            className="text-sm font-medium text-white px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300 flex items-center gap-2 group">
                            Dashboard
                            <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </a>
                    ) : (
                        <a href={loginUrl} class="hidden sm:block text-sm font-medium text-slate-300 hover:text-white transition-colors px-4 py-2">
                            Sign in
                        </a>
                    )}
                    
                    {/* Mobile Menu Button - Placeholder for mobile menu logic if needed */}
                    <button className="md:hidden p-2 text-slate-400 hover:text-white">
                        <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
    );
}
