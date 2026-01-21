<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LMS') }} - Smart Loan Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes blob {

            0%,
            100% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>

<body class="antialiased bg-white font-sans text-slate-900">

    <!-- Sticky Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="flex items-center justify-between py-4 px-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-indigo-200 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900 uppercase">Smart<span
                        class="text-indigo-600">Loan</span></span>
            </div>

            <div class="flex items-center gap-6">
                <a href="#features"
                    class="hidden md:block text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Features</a>
                <a href="#how-it-works"
                    class="hidden md:block text-sm font-medium text-slate-600 hover:text-indigo-600 transition">Process</a>

                @if (Route::has('login'))
                    <div class="h-6 w-px bg-slate-200 hidden md:block"></div>
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition">Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="hidden sm:flex bg-slate-900 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-slate-800 transition shadow-lg">
                                Get Started
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="relative px-6 pt-16 pb-24 max-w-7xl mx-auto overflow-hidden">
            <!-- Animated Blobs -->
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 mb-6">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">New: Instant Approvals
                            active</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black tracking-tight text-slate-900 leading-[1.05]">
                        Financial freedom, <br />
                        <span class="text-indigo-600 italic">simplified.</span>
                    </h1>
                    <p class="mt-8 text-xl text-slate-600 leading-relaxed max-w-lg">
                        Apply for personal and business loans in under 5 minutes. No paperwork, no hidden fees, just
                        smart finance.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-indigo-700 transition shadow-xl shadow-indigo-200 text-center">
                            Apply Now
                        </a>
                        <a href="#how-it-works"
                            class="bg-white border border-slate-200 text-slate-700 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition text-center">
                            Learn More
                        </a>
                    </div>
                </div>

                <!-- Calculator Card -->
                <div class="relative">
                    <div class="relative bg-slate-900 p-1 rounded-3xl shadow-3xl">
                        <div class="bg-white p-8 rounded-[1.4rem] space-y-8">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-slate-900">Loan Estimator</h3>
                                <div
                                    class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-black rounded-lg uppercase">
                                    Best Rates</div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between text-sm font-bold">
                                    <span class="text-slate-500">I want to borrow</span>
                                    <span class="text-indigo-600">₦750,000</span>
                                </div>
                                <input type="range"
                                    class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <div class="flex justify-between text-xs text-slate-400 font-medium">
                                    <span>₦50,000</span>
                                    <span>₦2,000,000</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-slate-50 rounded-2xl">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Monthly Pay
                                    </p>
                                    <p class="text-xl font-black text-slate-900">₦68,200</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Interest
                                        Rate</p>
                                    <p class="text-xl font-black text-slate-900">2.5%</p>
                                </div>
                            </div>

                            <a href="{{ route('register') }}"
                                class="block w-full text-center bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition">
                                Continue Application
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Why choose SmartLoan?</h2>
                    <p class="text-slate-600">We’ve removed the friction from borrowing. No bank queues, no physical
                        forms.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Instant Disbursement</h3>
                        <p class="text-slate-500 leading-relaxed">Once approved, funds are sent to your verified bank
                            account in seconds.</p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Safe & Encrypted</h3>
                        <p class="text-slate-500 leading-relaxed">Your data is protected with bank-grade 256-bit
                            encryption and security protocols.</p>
                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                        <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Flexible Repayment</h3>
                        <p class="text-slate-500 leading-relaxed">Choose a tenure that fits your cash flow, from 3
                            months up to 2 years.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="py-20 px-6">
            <div
                class="max-w-5xl mx-auto bg-indigo-600 rounded-[3rem] p-12 text-center text-white shadow-2xl shadow-indigo-200">
                <h2 class="text-4xl font-black mb-6">Ready to grow your finances?</h2>
                <p class="text-indigo-100 text-lg mb-10 max-w-xl mx-auto">Join 50,000+ Nigerians who trust SmartLoan
                    for their personal and business financial needs.</p>
                <a href="{{ route('register') }}"
                    class="inline-block bg-white text-indigo-600 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-indigo-50 transition">
                    Create Your Account
                </a>
            </div>
        </section>
    </main>

    <footer class="py-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold tracking-tight text-slate-900 uppercase">Smart<span
                        class="text-indigo-600">Loan</span></span>
            </div>
            <p class="text-slate-400 text-sm italic">Licensed by the Central Bank of Nigeria (CBN)</p>
            <p class="text-slate-500 text-sm">&copy; {{ date('Y') }} SmartLoan. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
