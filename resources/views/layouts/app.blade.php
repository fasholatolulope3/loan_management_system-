<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PDEI') }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/images/logo.jpeg') }}">
    <script>
        // Check for saved theme or system preference
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-300">

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        <!-- 1. SIDEBAR (Left Fixed Area) -->
        <x-sidebar />

        <!-- 2. MAIN WRAPPER (Right Content Area) -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- 3. TOP NAVIGATION (Search, Profile, Logout) -->
            @include('layouts.navigation')

            <!-- 4. PAGE HEADER (Title & Action Buttons) -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- 5. DYNAMIC PAGE CONTENT (Scrollable) -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages for Success/Error -->
                    @if (session('success'))
                        <div class="max-w-7xl mx-auto mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm"
                            role="alert">
                            <p class="font-bold">Success</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="max-w-7xl mx-auto mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm"
                            role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Content Slot -->
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

</body>

</html>