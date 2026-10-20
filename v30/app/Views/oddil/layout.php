<?php
/**
 * Layout zóny oddílu – vlastní chrome bez veřejné navigace.
 * Očekává: $oddilTitle, $oddilView a (nepovinně) $oddilAktivni.
 */
$prihlasen    = \App\Middleware\OddilAuth::check();
$oddilAktivni = $oddilAktivni ?? '';
$flashSuccess = flash_get('success');
$flashError   = flash_get('error');

$polozky = [
    'nastenka' => ['label' => 'Nástěnka',  'href' => '/oddil'],
    'soupiska' => ['label' => 'Soupiska',  'href' => '/oddil/zavodnici'],
    'prihlaska' => ['label' => 'Přihláška', 'href' => '/oddil/prihlaska'],
];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#06152A">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e(($oddilTitle ?? 'Zóna oddílu') . ' | Vodácká 30') ?></title>

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
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between gap-6 px-4 sm:px-6">
            <a href="<?= $prihlasen ? '/oddil' : '/' ?>" class="flex items-center gap-2.5">
                <span class="h-5 w-1.5 bg-brand-400" aria-hidden="true"></span>
                <span class="font-display text-base font-extrabold uppercase leading-none tracking-tight">Vodácká&nbsp;30</span>
                <span class="hidden text-xs font-medium uppercase tracking-[0.2em] text-white/40 sm:inline">Zóna oddílu</span>
            </a>

            <?php if ($prihlasen): ?>
                <div class="flex items-center gap-4">
                    <a href="/" class="hidden text-sm text-white/60 transition-colors hover:text-white sm:inline">Zobrazit web ↗</a>
                    <form method="post" action="/oddil/odhlaseni">
                        <?= csrf_field() ?>
                        <button type="submit"
                                class="rounded-md border border-white/25 px-3 py-1.5 text-sm font-semibold transition-colors hover:border-white/50 hover:bg-white/10">
                            Odhlásit
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($prihlasen): ?>
            <nav class="border-t border-white/10">
                <div class="mx-auto flex max-w-5xl gap-1 overflow-x-auto px-4 sm:px-6">
                    <?php foreach ($polozky as $klic => $polozka): ?>
                        <a href="<?= e($polozka['href']) ?>"
                           class="shrink-0 border-b-2 px-3 py-3 text-sm font-semibold transition-colors <?= $oddilAktivni === $klic
                               ? 'border-brand-400 text-white'
                               : 'border-transparent text-white/60 hover:border-white/30 hover:text-white' ?>">
                            <?= e($polozka['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </nav>
        <?php endif; ?>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
        <?php if ($flashSuccess): ?>
            <div class="mb-6 flex items-start gap-3 rounded-md border-l-2 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-600" aria-hidden="true"></span>
                <span><?= e($flashSuccess) ?></span>
            </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
            <div class="mb-6 flex items-start gap-3 rounded-md border-l-2 border-red-600 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-600" aria-hidden="true"></span>
                <span><?= e($flashError) ?></span>
            </div>
        <?php endif; ?>

        <?php require $oddilView; ?>
    </main>

</body>
</html>
