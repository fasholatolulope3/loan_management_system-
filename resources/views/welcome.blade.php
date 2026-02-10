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
</head>

<body class="antialiased bg-zinc-950 font-sans text-white transition-colors duration-300">

    <!-- Navbar (Simplified, matches PDEI branding) -->
    <nav class="fixed top-0 w-full z-50 bg-zinc-950/80 backdrop-blur-lg border-b border-white/5">
        <div class="flex items-center justify-between py-4 px-6 max-w-7xl mx-auto">
            <a href="/" class="flex items-center group">
                <div
                    class="w-12 h-12 flex items-center justify-center bg-white/5 rounded-xl border border-white/10 group-hover:border-indigo-500/50 transition-colors shadow-lg">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-2">
                        <path d="M50 20 L80 80 L20 80 Z" fill="none" stroke="#ffffff" stroke-width="8"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M75 25 L85 15 M80 25 L80 10 M85 30 L95 20" stroke="#e63946" stroke-width="6"
                            stroke-linecap="round" />
                    </svg>
                </div>
            </a>

            <div class="flex items-center gap-6">
                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-sm font-bold bg-white text-zinc-950 px-6 py-2 rounded-full hover:bg-zinc-200 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-zinc-400 hover:text-white transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-white text-zinc-950 px-6 py-2 rounded-full text-sm font-bold hover:bg-zinc-200 transition">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- React Root -->
    <main id="welcome-root" class="pt-20">
        <!-- React component mounts here -->
    </main>

    <!-- Simple Footer -->
    <footer class="py-12 bg-zinc-950 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 opacity-40">
            <span class="text-xs font-bold uppercase tracking-widest">© {{ date('Y') }} {{ config('app.name') }}</span>
            <div class="flex gap-6 text-xs font-bold uppercase">
                <a href="#" class="hover:text-white transition">Privacy</a>
                <a href="#" class="hover:text-white transition">Terms</a>
                <a href="#" class="hover:text-white transition">Contact</a>
            </div>
        </div>
    </footer>

</body>

</html>