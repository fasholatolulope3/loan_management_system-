<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PDEI') }} - Financial Excellence</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/welcome-root.tsx'])
    
    <style>
        /* Custom animations for premium feel */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="antialiased bg-zinc-950 font-sans text-slate-300 transition-colors duration-300 selection:bg-indigo-500/30 selection:text-indigo-200">

    <!-- Dynamic Premium Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px] animate-pulse"></div>
        <div class="absolute top-[60%] -right-[10%] w-[40%] h-[40%] rounded-full bg-violet-600/10 blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Premium Glassmorphism Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300" id="navbar">
        <div class="absolute inset-0 bg-zinc-950/60 backdrop-blur-xl border-b border-white/5 shadow-[0_4px_30px_rgba(0,0,0,0.1)]"></div>
        <div class="relative flex items-center justify-between py-5 px-6 max-w-7xl mx-auto">
            
            <!-- Logo area -->
            <a href="/" class="flex items-center gap-3 group relative z-10 mr-8">
                <div class="relative flex items-center justify-center h-10 w-auto rounded-xl overflow-hidden shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40 transition-all duration-300 border border-white/10">
                    <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ config('app.name', 'PDEI') }}" class="h-10 w-auto object-contain bg-zinc-900">
                </div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400 tracking-tight group-hover:to-white transition-all">
                    PDEI
                </span>
            </a>

            <!-- Center Nav Links -->
            <div class="hidden md:flex items-center gap-1 bg-white/5 border border-white/10 rounded-full px-2 py-1 backdrop-blur-md">
                <div class="relative group px-4 py-2">
                    <button class="flex items-center gap-1 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                        Product
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Dropdown -->
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-4 w-48 opacity-0 translate-y-2 pointer-events-none group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-300">
                        <div class="bg-zinc-900/90 backdrop-blur-xl border border-white/10 rounded-2xl p-2 shadow-2xl">
                            <a href="#features" class="block px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition-colors">Features</a>
                            <a href="#integrations" class="block px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition-colors">Integrations</a>
                            <a href="#changelog" class="block px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition-colors">Changelog</a>
                        </div>
                    </div>
                </div>
                <a href="#pricing" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors rounded-full hover:bg-white/5">Pricing</a>
                <a href="#company" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors rounded-full hover:bg-white/5">Company</a>
                <a href="#resources" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors rounded-full hover:bg-white/5">Resources</a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3 md:gap-4 relative z-10">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-medium text-white px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300 flex items-center gap-2 group">
                            Dashboard
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-medium text-slate-300 hover:text-white transition-colors px-4 py-2">
                            Sign in
                        </a>
                    @endauth
                @endif
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden p-2 text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- React Root (Hero Section mounted here) -->
    <main id="welcome-root" class="pt-24 min-h-[80vh]">
        <!-- React component mounts here -->
    </main>

    <!-- Premium Structured Footer -->
    <footer class="relative mt-24 border-t border-white/10 bg-zinc-950 pt-20 pb-12 overflow-hidden">
        <!-- Decorative Glow -->
        <div class="absolute top-0 inset-x-0 h-px w-full bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[w-3/4] max-w-2xl max-h-32 bg-indigo-500/10 blur-[100px] rounded-full"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 lg:gap-8 mb-16">
                <!-- Brand Column -->
                <div class="col-span-2 lg:col-span-1">
                    <a href="/" class="flex items-center gap-3 group inline-block mb-6">
                        <img src="{{ asset('assets/images/logo.jpeg') }}" alt="{{ config('app.name') }}" class="h-8 w-auto rounded border border-white/10">
                        <span class="text-xl font-bold text-white tracking-tight">PDEI</span>
                    </a>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-xs mb-8">
                        Elevating financial excellence with advanced loan management solutions tailored for modern institutions.
                    </p>
                    
                    <!-- Social Links -->
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group">
                            <span class="sr-only">YouTube</span>
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 1: Product -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Features<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#pricing" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Pricing<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#integrations" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Integrations<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#changelog" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Changelog <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">New</span></a></li>
                    </ul>
                </div>

                <!-- Column 2: Company -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#about" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">About Us<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#testimonials" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Testimonials<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#brand" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Brand Assets<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#contact" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Contact<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                    </ul>
                </div>

                <!-- Column 3: Resources -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#blog" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Blog<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#faqs" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">FAQs<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#help" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Help Center<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                    </ul>
                </div>

                <!-- Column 4: Legal -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#privacy" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Privacy Policy<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                        <li><a href="#terms" class="text-sm text-slate-400 hover:text-white transition-colors relative inline-block group">Terms of Service<span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-500 group-hover:w-full transition-all duration-300"></span></a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500 leading-5">
                    &copy; {{ date('Y') }} {{ config('app.name', 'PDEI') }}. All rights reserved.
                </p>
                <div class="flex gap-6 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        All systems operational
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Navbar scroll effect script -->
    <script>
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('py-3');
                navbar.classList.remove('py-5');
            } else {
                navbar.classList.add('py-5');
                navbar.classList.remove('py-3');
            }
        });
    </script>
</body>

</html>