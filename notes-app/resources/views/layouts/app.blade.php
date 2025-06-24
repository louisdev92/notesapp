<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'NotesApp')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">
<div class="min-h-screen flex flex-col">

    {{-- Navigation --}}
    @include('layouts.navigation')

    {{-- Page Heading --}}
    @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Page Content --}}
    <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 animate-fade-in">
        @yield('content')
    </main>

    {{-- Footer (optionnel) --}}
    <footer class="text-center py-6 text-sm text-gray-500 dark:text-gray-400">
        © {{ date('Y') }} NotesApp. Tous droits réservés.
    </footer>
</div>
</body>
</html>
