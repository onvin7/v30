<?php
// Aktuální cesta pro zvýraznění aktivního odkazu v navigaci.
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($currentPath !== '/') {
    $currentPath = rtrim($currentPath, '/');
}

// Jednorázové zprávy (flash) z formulářů.
$flashSuccess = flash_get('success');
$flashError   = flash_get('error');

// Položky hlavní navigace sdílené desktopem i mobilem.
$navItems = [
    ['href' => '/zavod',             'label' => 'O závodu'],
    ['href' => '/propozice',         'label' => 'Propozice a trať'],
    ['href' => '/prihlaska',         'label' => 'Přihlášky'],
    ['href' => '/startovni-listina', 'label' => 'Startovní listina'],
    ['href' => '/vysledky',          'label' => 'Výsledky a archiv'],
    ['href' => '/galerie',           'label' => 'Galerie'],
    ['href' => '/kontakt',           'label' => 'Kontakt'],
];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Vodácká 30') ?></title>
    <!-- Tailwind CSS (CDN – bez buildování přes Node.js) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Archivo', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <!-- Fonty -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js (odlehčená interaktivita – mobilní menu) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white text-slate-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Hlavička -->
    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2.5">
                    <span class="w-1.5 h-6 bg-orange-600" aria-hidden="true"></span>
                    <span class="font-display text-lg font-extrabold uppercase tracking-tight text-slate-900">Vodácká&nbsp;30</span>
                </a>

                <!-- Navigace (desktop) -->
                <nav class="hidden xl:flex items-center gap-0.5 text-sm font-medium text-slate-600">
                    <?php foreach ($navItems as $item): ?>
                        <?php $isActive = $currentPath === $item['href']; ?>
                        <a href="<?= e($item['href']) ?>"
                           class="px-3 py-2 rounded-md hover:text-slate-900 hover:bg-slate-50 <?= $isActive ? 'text-slate-900 bg-slate-100' : '' ?>"
                           <?= $isActive ? 'aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
                    <?php endforeach; ?>
                </nav>

                <!-- CTA (desktop) -->
                <div class="hidden xl:block">
                    <a href="/prihlaska" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-4 py-2 transition-colors">Přihlásit se</a>
                </div>

                <!-- Tlačítko mobilního menu -->
                <button type="button" @click="open = !open" class="xl:hidden inline-flex items-center justify-center w-10 h-10 rounded-md text-slate-700 hover:bg-slate-100" aria-label="Otevřít menu">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigace (mobil) -->
        <div x-show="open" x-cloak @click.outside="open = false" class="xl:hidden border-t border-slate-200 bg-white">
            <nav class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex flex-col gap-1 text-sm font-medium text-slate-700">
                <?php foreach ($navItems as $item): ?>
                    <?php $isActive = $currentPath === $item['href']; ?>
                    <a href="<?= e($item['href']) ?>"
                       class="px-3 py-2.5 rounded-md hover:bg-slate-50 <?= $isActive ? 'text-slate-900 bg-slate-100' : '' ?>"
                       <?= $isActive ? 'aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
                <?php endforeach; ?>
                <a href="/prihlaska" class="mt-2 inline-flex justify-center rounded-md bg-orange-600 hover:bg-orange-700 text-white font-semibold px-4 py-2.5 transition-colors">Přihlásit se</a>
            </nav>
        </div>
    </header>

    <!-- Obsah jednotlivých stránek -->
    <main class="flex-1">
        <?php if ($flashSuccess): ?>
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">
                    <?= e($flashSuccess) ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                    <?= e($flashError) ?>
                </div>
            </div>
        <?php endif; ?>
        <?php require $view; ?>
    </main>

    <!-- Patička -->
    <footer class="bg-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 text-sm text-slate-400 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="font-display text-base font-extrabold uppercase tracking-tight text-white">Vodácká&nbsp;30</p>
            <nav class="flex flex-wrap gap-x-6 gap-y-2">
                <a href="/propozice" class="hover:text-white">Propozice a trať</a>
                <a href="/prihlaska" class="hover:text-white">Přihlášky</a>
                <a href="/startovni-listina" class="hover:text-white">Startovní listina</a>
                <a href="/vysledky" class="hover:text-white">Výsledky</a>
                <a href="/galerie" class="hover:text-white">Galerie</a>
                <a href="/kontakt" class="hover:text-white">Kontakt</a>
            </nav>
            <p class="text-slate-500">&copy; <?= date('Y') ?> &middot; TJ KVT Pardubice &middot; Bedřichovka, Orlické hory</p>
        </div>
    </footer>

</body>
</html>
