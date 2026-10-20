<?php
/**
 * Oznámení místo e-mailu – zatím se jen vypíše na stránku.
 * Očekává: $text (prostý text zprávy), $title.
 *
 * Až se napojí odesílání e-mailů, pošle se přesně tento text na adresu oddílu
 * a tato stránka zůstane jen jako náhled / záložní odkaz.
 */
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#06152A">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($title ?? 'Oznámení | Vodácká 30') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#EFF7FD', 100: '#D9EDF9', 200: '#B6DCF4', 300: '#85C4EC',
                            400: '#4DA6E0', 500: '#2689CC', 600: '#1565A1', 700: '#0F5082',
                        },
                        night: '#06152A',
                        deep: '#0B2A4A',
                        snow: '#F5F9FC',
                        ice: '#7DD3FC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Archivo', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-snow font-sans text-slate-900 antialiased">

    <header class="bg-night text-white">
        <div class="mx-auto flex h-16 max-w-3xl items-center gap-2.5 px-4 sm:px-6">
            <span class="h-5 w-1.5 bg-brand-400" aria-hidden="true"></span>
            <span class="font-display text-base font-extrabold uppercase leading-none tracking-tight">Vodácká&nbsp;30</span>
            <span class="hidden text-xs font-medium uppercase tracking-[0.2em] text-white/40 sm:inline">Oznámení</span>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6 sm:py-14">

        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zpráva pro oddíl</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Oznámení</h1>
        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600">
            Toto je text, který oddílu odešleme e-mailem. Zatím se zobrazuje přímo tady.
        </p>

        <div class="mt-8 overflow-hidden rounded-md border border-slate-200 bg-white">
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 bg-snow px-5 py-3">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Vodácká 30</p>
                <p class="text-xs text-slate-400">automatická zpráva</p>
            </div>
            <pre class="whitespace-pre-wrap break-words px-5 py-6 font-sans text-sm leading-7 text-slate-800"><?= e($text) ?></pre>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="/oddil/prihlaseni"
               class="inline-flex items-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                Přejít na přihlášení oddílu
            </a>
            <a href="/"
               class="inline-flex items-center rounded-md border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-900 transition-colors hover:border-slate-400 hover:bg-white">
                Zpět na web
            </a>
        </div>

    </main>

</body>
</html>
