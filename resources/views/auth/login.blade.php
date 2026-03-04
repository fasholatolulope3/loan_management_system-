<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Executive Login - {{ config('app.name', 'PDEI') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/login-root.tsx'])
</head>

<body class="antialiased font-sans transition-colors duration-300">
    <!-- Inject Laravel Session Data strictly into React dataset -->
    <div id="login-root" data-csrf="{{ csrf_token() }}" data-errors="{{ json_encode($errors->getMessages()) }}"
        data-status="{{ session('status') }}" data-old-email="{{ old('email') }}"
        data-route-login="{{ route('login') }}"
        data-route-password-request="{{ Route::has('password.request') ? route('password.request') : '' }}"></div>
</body>

</html>