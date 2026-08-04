<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Prediksi IPK')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts
    @stack('styles')
</head>

<body class="min-h-screen overflow-x-hidden bg-[#f6f8fc] font-sans text-slate-800 antialiased">
    @yield('sidebar')

    <button type="button" data-sidebar-overlay class="fixed inset-0 z-40 hidden bg-slate-950/40 lg:hidden"
        aria-label="Tutup sidebar"></button>

    <div class="min-h-screen transition-all duration-300 lg:pl-24 xl:pl-28">
        @include('partials.header')

        <main class="min-h-[calc(100vh-65px)] px-4 py-5 sm:px-6 lg:px-7">
            <div class="mx-auto w-full max-w-[1600px]">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>

        @include('partials.footer')
    </div>

    @stack('scripts')
</body>

</html>
