<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Vodácká 30, běžecký lyžařský závod v Orlických horách.')">
    <meta name="theme-color" content="#f8fafc">
    <title>@yield('title', 'Vodácká 30 | Běžecký závod v Orlických horách')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-4 focus:py-3 focus:text-slate-900 focus:ring-2 focus:ring-orange-600">Přeskočit na obsah</a>

    <div class="flex min-h-screen flex-col">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-semibold text-slate-900" aria-label="Vodácká 30, úvodní stránka">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-md bg-orange-600 text-xs font-bold text-white" aria-hidden="true">V30</span>
                    <span>Vodácká 30</span>
                </a>

                <nav class="flex flex-wrap gap-1" aria-label="Hlavní navigace">
                    <a href="{{ route('about') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">O závodu</a>
                    <a href="{{ route('regulations') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Propozice</a>
                    <a href="{{ route('results') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Výsledky</a>
                    <a href="{{ route('registration') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Přihlášky</a>
                    <a href="{{ route('contact') }}" class="rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Kontakt</a>
                </nav>
            </div>
        </header>

        <main id="main" class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex w-full max-w-7xl flex-col gap-2 px-4 py-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <strong class="text-slate-900">TJ KVT Pardubice</strong>
                <span>Vodácká 30 · běžecký lyžařský závod v Orlických horách</span>
                <a href="{{ route('contact') }}" class="font-medium text-slate-700 underline decoration-slate-300 underline-offset-4 hover:text-orange-700">Kontakt</a>
            </div>
        </footer>
    </div>
</body>
</html>
