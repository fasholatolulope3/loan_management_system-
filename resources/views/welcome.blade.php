<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        if (this.darkMode) document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
    }
}" x-init="if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
    darkMode = true;
} else {
    document.documentElement.classList.remove('dark');
    darkMode = false;
}"
    :class="{ 'dark': darkMode }" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS') }} - Financial Excellence</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .gradient-text {
            background: linear-gradient(to right, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body
    class="antialiased bg-white dark:bg-slate-950 font-sans text-slate-900 dark:text-slate-100 transition-colors duration-300">

    <!-- Navbar -->
    <nav
        class="sticky top-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-900">
        <div class="flex items-center justify-between py-4 px-6 max-w-7xl mx-auto">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-indigo-500/20 shadow-lg group-hover:scale-110 transition">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-xl font-black tracking-tight uppercase">Get<span
                        class="text-indigo-600">Loan</span></span>
            </a>

            <div class="flex items-center gap-2 md:gap-8">
                <div class="hidden md:flex items-center gap-6">
                    <a href="#features" class="text-sm font-medium hover:text-indigo-600 transition">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium hover:text-indigo-600 transition">How it
                        Works</a>
                </div>

                <!-- Theme Toggle -->
                <button @click="toggleTheme()"
                    class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>

                @if (Route::has('login'))
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-sm font-bold bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">Dashboard
                                &rarr;</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold hover:text-indigo-600 transition">Log
                                in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:scale-105 transition shadow-xl">Get
                                    Started</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero & Functional Calculator -->
    <header class="relative px-6 py-16 md:py-24 max-w-7xl mx-auto overflow-hidden">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h1 class="text-6xl md:text-8xl font-black tracking-tighter leading-[0.9] mb-8">
                    Smart funds for <span class="gradient-text">Smart people.</span>
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 leading-relaxed mb-10 max-w-lg">
                    Get approved in minutes with our AI-driven processing. Flexible tenures, no hidden fees, just pure
                    financial empowerment.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-indigo-700 transition shadow-2xl shadow-indigo-500/40">Apply
                        for a Loan</a>
                    <a href="#features"
                        class="bg-slate-100 dark:bg-slate-900 border border-transparent dark:border-slate-800 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-slate-200 transition">Learn
                        More</a>
                </div>
            </div>

            <!-- Functional Estimator -->
            <div x-data="{ amount: 250000, tenure: 12, rate: 0.025 }">
                <div class="bg-slate-100 dark:bg-slate-900 p-2 rounded-[3rem]">
                    <div class="bg-white dark:bg-slate-800 p-8 md:p-10 rounded-[2.5rem] shadow-2xl space-y-8">
                        <h3 class="text-xl font-black">Loan Estimator</h3>

                        <!-- Amount Slider -->
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm font-bold">
                                <span class="text-slate-400">Loan Amount</span>
                                <span class="text-indigo-600 dark:text-indigo-400 text-xl"
                                    x-text="'₦' + parseInt(amount).toLocaleString()"></span>
                            </div>
                            <input type="range" min="10000" max="2000000" step="10000" x-model="amount"
                                class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        </div>

                        <!-- Tenure Grid -->
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="t in [3, 6, 12]">
                                <button @click="tenure = t"
                                    :class="tenure === t ? 'bg-indigo-600 text-white' :
                                        'bg-slate-50 dark:bg-slate-700 text-slate-500'"
                                    class="py-2 rounded-xl text-sm font-bold transition" x-text="t + ' Mo'"></button>
                            </template>
                        </div>

                        <div class="pt-6 border-t dark:border-slate-700">
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-xs font-black uppercase text-slate-400 mb-1">Monthly Repayment</p>
                                    <p class="text-4xl font-black text-slate-900 dark:text-white"
                                        x-text="'₦' + Math.round((amount / tenure) + (amount * rate)).toLocaleString()">
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black uppercase text-slate-400 mb-1">Interest Rate</p>
                                    <p class="text-lg font-bold text-emerald-500"
                                        x-text="(rate * 100).toFixed(1) + '%'"></p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('register') }}"
                            class="block w-full bg-slate-900 dark:bg-white dark:text-slate-900 text-white text-center py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-[1.02] transition">Apply
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Trust Logotypes -->
    <div
        class="max-w-7xl mx-auto px-6 py-12 flex flex-wrap justify-center gap-10 opacity-30 grayscale hover:grayscale-0 transition-all">
        <div class="font-bold text-2xl">CBN REGULATED</div>
        <div class="font-bold text-2xl">NDIC INSURED</div>
        <div class="font-bold text-2xl">PCI-DSS COMPLIANT</div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-slate-50 dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div
                        class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-3">Flash Approval</h4>
                    <p class="text-slate-500 dark:text-slate-400">Our machine learning algorithms process applications
                        in under 60 seconds. Say goodbye to bank queues.</p>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div
                        class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-3">Ironclad Security</h4>
                    <p class="text-slate-500 dark:text-slate-400">Your financial data is encrypted using military-grade
                        AES-256 bits protocols. We never share your data.</p>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black mb-3">Transparent Tenure</h4>
                    <p class="text-slate-500 dark:text-slate-400">Manage your repayments easily. Pay daily, weekly, or
                        monthly. No penalties for early repayment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-5xl md:text-7xl font-black tracking-tight mb-12">Take the next step. <br /><span
                class="text-indigo-600">Apply today.</span></h2>
        <a href="{{ route('register') }}"
            class="inline-block bg-slate-900 dark:bg-white dark:text-slate-900 text-white px-16 py-6 rounded-full font-black text-2xl hover:scale-110 shadow-2xl transition duration-500">Create
            Free Account</a>
        <p class="mt-8 text-slate-400 font-medium italic">Join 200,000+ satisfied clients across Africa.</p>
    </section>

    <!-- Simple Footer -->
    <footer class="py-12 border-t dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 opacity-60">
            <span class="text-xs font-bold uppercase tracking-widest">© {{ date('Y') }} GETLOAN INC.</span>
            <div class="flex gap-6 text-xs font-bold uppercase">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </footer>

</body>

</html>
