<?php
$pageHeader = [
    'eyebrow'    => 'Fotografie ze závodu',
    'title'      => 'Galerie',
    'lead'       => 'Tři poslední ročníky závodu ve fotografiích pořadatele. Kompletní alba jsou v externí galerii.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Galerie']],
    'image'      => 'header-gallery',
];
require __DIR__ . '/partials/page_header.php';
?>

<!-- Alba podle ročníků -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Alba</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Ročníky v obrazech
            </h2>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($alba as $album): ?>
                <article class="reveal group" x-intersect.once="$el.classList.add('is-visible')">
                    <a href="<?= e($album['odkaz']) ?>" target="_blank" rel="noopener noreferrer" class="block">

                        <div class="overflow-hidden rounded-md bg-deep">
                            <?php if ($album['nahled'] !== ''): ?>
                                <img src="<?= e($album['nahled']) ?>"
                                     alt="Fotografie z ročníku <?= e($album['rok']) ?>"
                                     loading="lazy" decoding="async"
                                     class="aspect-[3/2] w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                            <?php else: ?>
                                <div class="relative isolate flex aspect-[3/2] w-full items-end p-5">
                                    <?php require __DIR__ . '/partials/contours.php'; ?>
                                    <p class="text-sm text-white/50">Album bude doplněno.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h3 class="mt-4 font-display text-lg font-extrabold tracking-tight transition-colors group-hover:text-brand-600 sm:text-xl">
                            <?= e($album['nazev']) ?>
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            <?= e($album['datum']) ?>
                            <span class="text-slate-300" aria-hidden="true">/</span>
                            <span class="tabular-nums"><?= e($album['pocet']) ?></span> fotek
                            <span class="text-slate-300" aria-hidden="true">/</span>
                            <?= e($album['fotograf']) ?>
                        </p>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Fotografie z ročníku 2026 -->
<?php if ($fotky !== []): ?>
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">

            <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Ročník 2026</p>
                    <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                        Z průběhu závodu
                    </h2>
                </div>
                <a href="https://eu.zonerama.com/milvin/Album/14562205" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 border-b border-brand-600 pb-1 text-sm font-semibold text-brand-700 transition-colors hover:border-brand-400 hover:text-brand-500">
                    Celé album
                    <span aria-hidden="true">↗</span>
                </a>
            </div>

            <div class="mt-10 grid auto-rows-[9rem] grid-cols-2 gap-3 sm:auto-rows-[11rem] sm:grid-cols-3 lg:auto-rows-[13rem] lg:grid-cols-4">
                <?php foreach ($fotky as $index => $foto): ?>
                    <figure class="<?= $index === 0 ? 'col-span-2 row-span-2' : '' ?> overflow-hidden rounded-md bg-deep">
                        <img src="<?= e(img_url($foto['klic'])) ?>"
                             alt="<?= e($foto['alt']) ?>"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover">
                    </figure>
                <?php endforeach; ?>
            </div>

            <p class="mt-6 text-sm text-slate-500">
                Starší ročníky (2023 a starší) doplníme po ověření práv k fotografiím.
            </p>
        </div>
    </div>
</section>
<?php endif; ?>
