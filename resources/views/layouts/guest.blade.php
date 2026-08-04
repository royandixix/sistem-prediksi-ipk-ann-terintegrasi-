<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Prediksi IPK')</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    @fonts
</head>
<body class="font-sans antialiased">
    @yield('content')

    @stack('scripts')
</body>
</html>