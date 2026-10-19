<?php
$pageHeader = [
    'eyebrow'    => 'Běžecké lyžování · Orlické hory',
    'title'      => 'O závodu',
    'lead'       => 'Vodácká 30 je zimní běžecký závod po hřebenech Orlických hor, který pořádá TJ KVT Pardubice.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'O závodu']],
    'image'      => 'about',
];
require __DIR__ . '/partials/page_header.php';
?>

<!-- Příběh závodu -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-12 lg:gap-16">

            <div class="reveal lg:col-span-7" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Vodácké mistrovství ČR v běhu na lyžích</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    Závod na horách, pořádaný vodáky.
                </h2>
                <div class="mt-6 space-y-5 text-base leading-7 text-slate-600">
                    <p>
                        Název odkazuje na pořadatelský klub; hlavní závod se jede na běžkách. V roce 2026 vedla dospělá
                        trať na 30 km, dětské závody měly tratě 3 a 7 km.
                    </p>
                    <p>
                        Start a cíl ročníku 2026 byly u chaty Bedřichovka v Orlickém Záhoří. Tyto údaje popisují minulý
                        ročník a nepotvrzují podobu závodu 2027.
                    </p>
                </div>

                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="/propozice"
                       class="inline-flex items-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                        Propozice a trať
                    </a>
                    <a href="/vysledky"
                       class="inline-flex items-center rounded-md border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-900 transition-colors hover:border-slate-400 hover:bg-snow">
                        Výsledky 2026
                    </a>
                </div>
            </div>

            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <?php if (has_img('home-trail')): ?>
                    <img src="<?= e(img_url('home-trail')) ?>"
                         alt="Závodníci na trati Vodácké 30 v Orlických horách"
                         loading="lazy" decoding="async"
                         class="aspect-[4/3] w-full rounded-md object-cover">
                <?php else: ?>
                    <div class="relative isolate flex aspect-[4/3] w-full items-end overflow-hidden rounded-md bg-deep p-6">
                        <?php require __DIR__ . '/partials/contours.php'; ?>
                        <p class="text-sm text-white/60">Fotografie z trati bude doplněna pořadatelem.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<!-- Ročník 2026 v datech -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Ročník 2026</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Základní údaje
            </h2>

            <dl class="mt-10 grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2 lg:grid-cols-4">
                <?php foreach ($fakta as $fakt): ?>
                    <div class="border-t border-slate-300 pt-5">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($fakt['label']) ?></dt>
                        <dd class="mt-2 font-display text-lg font-bold leading-snug tracking-tight text-slate-900"><?= e($fakt['hodnota']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
</section>
