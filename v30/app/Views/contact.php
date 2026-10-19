<?php
$pageHeader = [
    'eyebrow'    => 'Pořadatel',
    'title'      => 'Kontakt',
    'lead'       => 'Vodáckou 30 pořádá TJ KVT Pardubice. Aktuální kontakty na organizační výbor zveřejňuje pořadatel.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Kontakt']],
    'image'      => 'header-contact',
];
require __DIR__ . '/partials/page_header.php';
?>

<!-- Kontakty -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-16">

            <div class="reveal lg:col-span-7" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Organizační výbor</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    Potřebujete se na něco zeptat?
                </h2>

                <dl class="mt-10 divide-y divide-slate-200 border-y border-slate-200">
                    <?php foreach ($kontakty as $kontakt): ?>
                        <div class="py-5">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($kontakt['role']) ?></dt>
                            <dd class="mt-1.5 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                <span class="font-display text-lg font-bold tracking-tight text-slate-900"><?= e($kontakt['jmeno']) ?></span>
                                <?php if (!empty($kontakt['telefon'])): ?>
                                    <a href="tel:<?= e('+420' . preg_replace('/\s+/', '', $kontakt['telefon'])) ?>"
                                       class="text-sm font-medium tabular-nums text-brand-700 transition-colors hover:text-brand-500">
                                        <?= e($kontakt['telefon']) ?>
                                    </a>
                                <?php endif; ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <p class="mt-6 max-w-prose text-sm leading-6 text-slate-600">
                    Aktuální kontakty na organizační výbor, ředitele závodu a hlavního rozhodčího zveřejňuje pořadatel
                    na původním webu.
                </p>

                <a href="https://vodacka30.cz/index.php?menu=5" target="_blank" rel="noopener noreferrer"
                   class="mt-8 inline-flex items-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                    Kontakty pořadatele ↗
                </a>
            </div>

            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Další informace</p>
                <div class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                    <a href="/galerie" class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                        Fotogalerie závodu
                        <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">→</span>
                    </a>
                    <a href="https://kvt.pardubice.cz/" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                        TJ KVT Pardubice
                        <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">↗</span>
                    </a>
                    <a href="/propozice" class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                        Propozice a trať
                        <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">→</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Zázemí závodu -->
<section class="relative isolate overflow-hidden bg-night text-white">
    <?php require __DIR__ . '/partials/contours.php'; ?>

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-ice">Zázemí závodu</p>
            <h2 class="mt-4 max-w-2xl font-display text-2xl font-extrabold uppercase leading-tight tracking-tight sm:text-4xl">
                Chata Bedřichovka
            </h2>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">
                Start, cíl i vyhlášení jsou u chaty Bedřichovka v Orlickém Záhoří. Ubytování a stravování lze domluvit
                přímo s chatou.
            </p>
            <dl class="mt-9 flex flex-wrap gap-x-12 gap-y-5 border-t border-white/10 pt-6 text-sm">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.2em] text-white/40">Místo</dt>
                    <dd class="mt-1 font-semibold">Bedřichovka, Orlické Záhoří</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.2em] text-white/40">E-mail</dt>
                    <dd class="mt-1 font-semibold"><a href="mailto:info@bedrichovka.cz" class="transition-colors hover:text-ice">info@bedrichovka.cz</a></dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-[0.2em] text-white/40">Telefon</dt>
                    <dd class="mt-1 font-semibold tabular-nums"><a href="tel:+420775346832" class="transition-colors hover:text-ice">+420 775 346 832</a></dd>
                </div>
            </dl>
        </div>
    </div>
</section>
