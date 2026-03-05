import React from "react";
import MegaMenu from "./mega-menu";
import { NAV_ITEMS } from "../blocks/mega-menu-demo";
import { ArrowRight, Menu, X, ChevronRight } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

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
    const [isMenuOpen, setIsMenuOpen] = React.useState(false);

    React.useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 10);
        };
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    // Prevent scroll when menu is open
    React.useEffect(() => {
        if (isMenuOpen) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "unset";
        }
    }, [isMenuOpen]);

    return (
        <>
            <nav className={`fixed top-0 w-full z-[60] transition-all duration-300 ${isScrolled ? "py-3" : "py-5"}`}>
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

                    {/* Center Nav Links - Mega Menu (Desktop) */}
                    <div className="hidden md:flex items-center gap-1 bg-white/5 border border-white/10 rounded-full px-2 py-1 backdrop-blur-md">
                        <MegaMenu items={NAV_ITEMS} />
                    </div>

                    {/* Right Actions */}
                    <div className="flex items-center gap-3 md:gap-4 relative z-10">
                        {isLoggedIn ? (
                            <a href={dashboardUrl}
                                className="hidden sm:flex text-sm font-medium text-white px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300 items-center gap-2 group">
                                Dashboard
                                <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </a>
                        ) : (
                            <a href={loginUrl} className="hidden sm:block text-sm font-medium text-slate-300 hover:text-white transition-colors px-4 py-2">
                                Sign in
                            </a>
                        )}
                        
                        {/* Mobile Menu Button */}
                        <button 
                            onClick={() => setIsMenuOpen(!isMenuOpen)}
                            className="md:hidden p-2 text-slate-400 hover:text-white transition-colors relative z-[70]"
                            aria-label="Toggle Menu"
                        >
                            {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                        </button>
                    </div>
                </div>
            </nav>

            {/* Mobile Menu Overlay */}
            <AnimatePresence>
                {isMenuOpen && (
                    <motion.div
                        initial={{ opacity: 0, x: "100%" }}
                        animate={{ opacity: 1, x: 0 }}
                        exit={{ opacity: 0, x: "100%" }}
                        transition={{ type: "spring", damping: 25, stiffness: 200 }}
                        className="fixed inset-0 z-[55] bg-zinc-950 flex flex-col pt-24 px-6 md:hidden"
                    >
                        <div className="flex flex-col gap-6 overflow-y-auto pb-10">
                            {NAV_ITEMS.map((item) => (
                                <div key={item.id} className="flex flex-col gap-4 border-b border-white/5 pb-6">
                                    <div className="flex items-center justify-between group">
                                        {item.link && !item.subMenus ? (
                                            <a 
                                                href={item.link} 
                                                className="text-xl font-medium text-white hover:text-indigo-400 transition-colors"
                                                onClick={() => setIsMenuOpen(false)}
                                            >
                                                {item.label}
                                            </a>
                                        ) : (
                                            <span className="text-xl font-medium text-white">{item.label}</span>
                                        )}
                                        {item.subMenus && <ChevronRight className="w-5 h-5 text-zinc-500" />}
                                    </div>
                                    {item.subMenus && (
                                        <div className="grid grid-cols-1 gap-4 pl-4">
                                            {item.subMenus.map((sub) => (
                                                <div key={sub.title} className="flex flex-col gap-2">
                                                    <span className="text-xs font-bold text-zinc-500 uppercase tracking-widest">{sub.title}</span>
                                                    {sub.items.map((subItem) => (
                                                        <a 
                                                            key={subItem.label} 
                                                            href={subItem.link || "#"} 
                                                            className="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition-colors"
                                                            onClick={() => setIsMenuOpen(false)}
                                                        >
                                                            <div className="p-2 rounded-lg bg-white/5 border border-white/10">
                                                                <subItem.icon className="w-4 h-4 text-white" />
                                                            </div>
                                                            <div className="flex flex-col">
                                                                <span className="text-sm font-medium text-white">{subItem.label}</span>
                                                                <span className="text-xs text-zinc-500">{subItem.description}</span>
                                                            </div>
                                                        </a>
                                                    ))}
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            ))}
                            
                            <div className="mt-4 flex flex-col gap-4">
                                {isLoggedIn ? (
                                    <a href={dashboardUrl} className="flex items-center justify-center gap-2 w-full py-4 rounded-2xl bg-white text-zinc-950 font-bold hover:bg-zinc-200 transition-colors">
                                        Dashboard
                                        <ArrowRight className="w-4 h-4" />
                                    </a>
                                ) : (
                                    <a href={loginUrl} className="flex items-center justify-center w-full py-4 rounded-2xl bg-white text-zinc-950 font-bold hover:bg-zinc-200 transition-colors">
                                        Sign In
                                    </a>
                                )}
                            </div>
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </>
    );
}

