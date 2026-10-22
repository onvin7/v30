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

// SEO metadata pro <head>. Stránky mohou doplnit $description a $robots.
$seoIndex       = $robots ?? true;
$seoTitle       = \App\Helpers\Seo::title($title ?? null);
$seoDescription = \App\Helpers\Seo::description($description ?? null);
$seoCanonical   = \App\Helpers\Seo::canonical($currentPath);
$seoOpenGraph   = \App\Helpers\Seo::openGraph([
    'title'       => $seoTitle,
    'description' => $seoDescription,
    'url'         => $seoCanonical,
]);
$seoTwitter     = \App\Helpers\Seo::twitterCard([
    'title'       => $seoTitle,
    'description' => $seoDescription,
]);
$seoStructured  = $seoIndex ? \App\Helpers\Seo::structuredData() : [];
?>
<!DOCTYPE html>
<html lang="cs" class="scroll-pt-20 xl:scroll-pt-0">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#06152A">
    <title><?= e($seoTitle) ?></title>

    <!-- SEO / meta -->
    <meta name="description" content="<?= e($seoDescription) ?>">
    <meta name="robots" content="<?= e(\App\Helpers\Seo::robots($seoIndex)) ?>">
    <link rel="canonical" href="<?= e($seoCanonical) ?>">

    <!-- Open Graph -->
    <?php foreach ($seoOpenGraph as $vlastnost => $hodnota): ?>
        <meta property="<?= e($vlastnost) ?>" content="<?= e($hodnota) ?>">
    <?php endforeach; ?>

    <!-- Twitter Card -->
    <?php foreach ($seoTwitter as $nazev => $hodnota): ?>
        <meta name="<?= e($nazev) ?>" content="<?= e($hodnota) ?>">
    <?php endforeach; ?>

    <!-- Strukturovaná data -->
    <?php foreach ($seoStructured as $schema): ?>
        <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endforeach; ?>

    <!-- Tailwind CSS (CDN – bez buildování přes Node.js) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#EFF7FD',
                            100: '#D9EDF9',
                            200: '#B6DCF4',
                            300: '#85C4EC',
                            400: '#4DA6E0',
                            500: '#2689CC',
                            600: '#1565A1',
                            700: '#0F5082',
                            800: '#0C3F66',
                            900: '#0A3352',
                            950: '#071F34',
                        },
                        night: '#06152A',
                        deep:  '#0B2A4A',
                        snow:  '#F5F9FC',
                        ice:   '#7DD3FC',
                    },
                    fontFamily: {
                        sans:    ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
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

    <!-- Fotky (Zonerama) -->
    <link rel="preconnect" href="https://eu.zonerama.com" crossorigin>

    <!-- Alpine.js – plugin Intersect musí být načtený před jádrem Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.14.1/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <script>
        // Scroll-reveal je vázaný na třídu .js – bez JavaScriptu zůstane obsah vždy viditelný.
        document.documentElement.classList.add('js');
        window.addEventListener('load', function () {
            setTimeout(function () {
                document.querySelectorAll('.reveal:not(.is-visible)').forEach(function (el) {
                    el.classList.add('is-visible');
                });
            }, 1200);
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }

        ::selection { background-color: #1565A1; color: #ffffff; }

        :focus-visible {
            outline: 2px solid #4DA6E0;
            outline-offset: 2px;
        }

        /* Scroll-reveal sekcí (Alpine Intersect přidá .is-visible) */
        .js .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .7s cubic-bezier(.2, .7, .3, 1), transform .7s cubic-bezier(.2, .7, .3, 1);
        }
        .js .reveal.is-visible {
            opacity: 1;
            transform: none;
        }

        /* Vstupní sekvence hero */
        .hero-in { animation: hero-in .9s cubic-bezier(.2, .7, .3, 1) both; }
        .hero-in-2 { animation-delay: .12s; }
        .hero-in-3 { animation-delay: .24s; }
        .hero-in-4 { animation-delay: .36s; }
        @keyframes hero-in {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: none; }
        }

        /* Pomalý zoom fotografie v hero */
        .hero-img { animation: hero-zoom 1.6s cubic-bezier(.2, .7, .3, 1) both; }
        @keyframes hero-zoom {
            from { opacity: 0; transform: scale(1.06); }
            to   { opacity: 1; transform: none; }
        }

        /* Hlavička: na širokých obrazovkách je statická nahoře a odscrolluje se.
           Breakpoint musí odpovídat `xl:` v hlavičce a kontrole v JS níže. */
        @media (min-width: 1280px) {
            .site-header {
                position: relative !important;
                transform: none !important;
            }
        }

        /* Vodorovné lišty (filtr kategorií) – scroll funguje, ale bez viditelného pruhu */
        .no-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Dlouhé nezlomitelné řetězce (e-maily, názvy oddílů) nesmí roztáhnout stránku */
        dd, dt, p, li, h1, h2, h3 {
            overflow-wrap: break-word;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
            .hero-in, .hero-img { animation: none !important; }
            .site-header { transform: none !important; transition: none !important; }
        }
    </style>
</head>
<!-- `min-height: 100dvh` řeší adresní lištu na mobilu; `min-h-screen` je záloha pro starší prohlížeče. -->
<body class="bg-snow text-slate-900 font-sans antialiased min-h-screen flex flex-col" style="min-height: 100dvh;">

    <a href="#obsah"
       class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-brand-700">
        Přeskočit na obsah
    </a>

    <!-- Hlavička: na mobilu sticky (při scrollu dolů se schová, nahoru vrátí), na desktopu statická -->
    <header
        x-data="{ open: false, hidden: false, lastY: 0 }"
        @scroll.window="
            const y = window.scrollY;
            hidden = (window.innerWidth < 1280) && y > lastY && y > 140;
            if (hidden) { open = false; }
            lastY = y;
        "
        :class="{ '-translate-y-full': hidden }"
        class="site-header sticky top-0 z-50 bg-night text-white transition-transform duration-300 ease-out">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-6">

                <!-- Logo -->
                <a href="/" class="group flex items-center gap-2.5">
                    <span class="h-5 w-1.5 bg-brand-400 transition-colors group-hover:bg-ice" aria-hidden="true"></span>
                    <span class="font-display text-lg font-extrabold uppercase leading-none tracking-tight">Vodácká&nbsp;30</span>
                </a>

                <!-- Navigace (desktop) -->
                <nav class="hidden items-center gap-1 text-sm font-medium xl:flex" aria-label="Hlavní navigace">
                    <?php foreach ($navItems as $item): ?>
                        <?php $isActive = $currentPath === $item['href']; ?>
                        <a href="<?= e($item['href']) ?>"
                           class="group relative px-3 py-2 transition-colors <?= $isActive ? 'text-white' : 'text-white/70 hover:text-white' ?>"
                           <?= $isActive ? 'aria-current="page"' : '' ?>>
                            <?= e($item['label']) ?>
                            <span aria-hidden="true"
                                  class="absolute inset-x-3 bottom-0 h-px origin-left bg-ice transition-transform duration-300 <?= $isActive ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' ?>"></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <!-- CTA (desktop) -->
                <div class="hidden xl:block">
                    <a href="/prihlaska"
                       class="inline-flex items-center rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                        Přihlásit se
                    </a>
                </div>

                <!-- Tlačítko mobilního menu -->
                <button type="button" @click="open = !open"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md text-white/80 transition-colors hover:bg-white/10 hover:text-white xl:hidden"
                        :aria-expanded="open ? 'true' : 'false'" aria-controls="mobilni-menu"
                        aria-label="Menu">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigace (mobil) -->
        <div id="mobilni-menu" x-show="open" x-cloak @click.outside="open = false"
             class="border-t border-white/10 bg-night xl:hidden">
            <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-4 text-sm font-medium sm:px-6" aria-label="Mobilní navigace">
                <?php foreach ($navItems as $item): ?>
                    <?php $isActive = $currentPath === $item['href']; ?>
                    <a href="<?= e($item['href']) ?>"
                       class="flex min-h-[44px] items-center justify-between rounded-md px-3 py-2.5 transition-colors <?= $isActive ? 'bg-white/10 text-white' : 'text-white/75 hover:bg-white/5 hover:text-white' ?>"
                       <?= $isActive ? 'aria-current="page"' : '' ?>>
                        <?= e($item['label']) ?>
                        <?php if ($isActive): ?>
                            <span class="h-1.5 w-1.5 rounded-full bg-ice" aria-hidden="true"></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
                <a href="/prihlaska"
                   class="mt-2 inline-flex min-h-[44px] items-center justify-center rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white transition-colors hover:bg-brand-400">
                    Přihlásit se
                </a>
            </nav>
        </div>
    </header>

    <!-- Obsah jednotlivých stránek -->
    <main id="obsah" tabindex="-1" class="flex-1 focus:outline-none" x-data="{}">
        <?php if ($flashSuccess): ?>
            <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                <div class="flex items-start gap-3 rounded-md border-l-2 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-600" aria-hidden="true"></span>
                    <span><?= e($flashSuccess) ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
            <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                <div class="flex items-start gap-3 rounded-md border-l-2 border-red-600 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-600" aria-hidden="true"></span>
                    <span><?= e($flashError) ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php require $view; ?>
    </main>

    <!-- Patička -->
    <footer class="bg-night text-white/60">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <a href="/" class="flex items-center gap-2.5">
                        <span class="h-5 w-1.5 bg-brand-400" aria-hidden="true"></span>
                        <span class="font-display text-lg font-extrabold uppercase leading-none tracking-tight text-white">Vodácká&nbsp;30</span>
                    </a>
                    <p class="mt-4 max-w-xs text-sm leading-6">
                        Běžkařský závod v Orlických horách. Pořádá TJ KVT Pardubice.
                    </p>
                </div>

                <div>
                    <p class="font-display text-xs font-bold uppercase tracking-[0.2em] text-white/40">Navigace</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="/zavod" class="transition-colors hover:text-white">O závodu</a></li>
                        <li><a href="/galerie" class="transition-colors hover:text-white">Galerie</a></li>
                        <li><a href="/kontakt" class="transition-colors hover:text-white">Kontakt</a></li>
                    </ul>
                </div>

                <div>
                    <p class="font-display text-xs font-bold uppercase tracking-[0.2em] text-white/40">Závod</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="/propozice" class="transition-colors hover:text-white">Propozice a trať</a></li>
                        <li><a href="/prihlaska" class="transition-colors hover:text-white">Přihlášky</a></li>
                        <li><a href="/startovni-listina" class="transition-colors hover:text-white">Startovní listina</a></li>
                        <li><a href="/vysledky" class="transition-colors hover:text-white">Výsledky a archiv</a></li>
                    </ul>
                </div>

                <div>
                    <p class="font-display text-xs font-bold uppercase tracking-[0.2em] text-white/40">Pořadatel</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li>
                            <a href="https://kvt.pardubice.cz/" target="_blank" rel="noopener noreferrer" class="transition-colors hover:text-white">
                                TJ KVT Pardubice ↗
                            </a>
                        </li>
                        <li>Chata Bedřichovka, Orlické Záhoří</li>
                        <li>
                            <a href="mailto:info@bedrichovka.cz" class="transition-colors hover:text-white">info@bedrichovka.cz</a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="mt-12 flex flex-col gap-2 border-t border-white/10 pt-6 text-xs text-white/35 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; <?= date('Y') ?> Vodácká 30 &middot; Ondřej Vincenc</p>
                <p>Bedřichovka, Orlické hory</p>
            </div>
        </div>
    </footer>

</body>
</html>
