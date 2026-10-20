<?php
// Hero – fotografie z reálného ročníku přes tmavou plochu.
// Bez fotky (prázdný klíč `home-hero`) se vykreslí grafický motiv vrstevnic.
// Hero je největší prvek na stránce, proto dostává responzivní varianty a nejvyšší prioritu.
$heroSrcset = has_img('home-hero') ? img_srcset('home-hero', [960, 1440, 1920]) : '';
?>
<section class="relative isolate overflow-hidden bg-night text-white">
    <?php if (has_img('home-hero')): ?>
        <img src="<?= e(img_url('home-hero')) ?>" alt="" aria-hidden="true" decoding="async" fetchpriority="high"
             <?php if ($heroSrcset !== ''): ?>srcset="<?= e($heroSrcset) ?>" sizes="100vw"<?php endif; ?>
             class="hero-img absolute inset-0 -z-20 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-night via-night/80 to-night/40" aria-hidden="true"></div>
        <div class="absolute inset-x-0 bottom-0 -z-10 h-1/2 bg-gradient-to-t from-night to-transparent" aria-hidden="true"></div>
    <?php else: ?>
        <?php require __DIR__ . '/partials/contours.php'; ?>
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-deep/60 via-transparent to-night" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-28 lg:px-8 lg:py-36">
        <p class="hero-in text-xs font-semibold uppercase tracking-[0.3em] text-ice">
            Závod vodáků na běžkách
        </p>

        <h1 class="hero-in hero-in-2 mt-5 font-display text-4xl font-black uppercase leading-[0.9] tracking-tight sm:text-6xl lg:text-8xl">
            <?= e($hero['nazev']) ?>
        </h1>

        <p class="hero-in hero-in-3 mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
            <?= e($hero['podtitul']) ?>
        </p>

        <dl class="hero-in hero-in-3 mt-9 flex flex-wrap gap-x-12 gap-y-5 border-t border-white/10 pt-6 text-sm">
            <div>
                <dt class="text-xs font-medium uppercase tracking-[0.2em] text-white/40">Termín</dt>
                <dd class="mt-1 font-semibold tabular-nums"><?= e($hero['datum']) ?></dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-[0.2em] text-white/40">Místo</dt>
                <dd class="mt-1 font-semibold"><?= e($hero['misto']) ?></dd>
            </div>
        </dl>

        <div class="hero-in hero-in-4 mt-9 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
            <a href="/prihlaska"
               class="inline-flex items-center justify-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                Registrovat do závodu
            </a>
            <a href="/startovni-listina"
               class="inline-flex items-center justify-center rounded-md border border-white/25 px-6 py-3 text-sm font-semibold text-white transition-colors hover:border-white/50 hover:bg-white/10">
                Startovní listina
            </a>
        </div>
    </div>
</section>

<!-- Trať a místo -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-16">

            <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Trať a místo</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    <?= e($trat['nazev']) ?>
                </h2>
                <p class="mt-5 max-w-prose text-base leading-7 text-slate-600">
                    <?= e($trat['popis']) ?>
                </p>

                <dl class="mt-8 grid grid-cols-2 gap-x-8 gap-y-6 border-t border-slate-200 pt-6">
                    <?php foreach ($trat['fakta'] as $fakt): ?>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($fakt['label']) ?></dt>
                            <dd class="mt-1 font-display text-lg font-bold tabular-nums text-slate-900"><?= e($fakt['hodnota']) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <a href="/propozice"
                   class="mt-8 inline-flex items-center gap-2 border-b border-brand-600 pb-1 text-sm font-semibold text-brand-700 transition-colors hover:border-brand-400 hover:text-brand-500">
                    Propozice a trať
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
                <?php if (has_img('home-trail')): ?>
                    <?php $tratSrcset = img_srcset('home-trail', [600, 900, 1200]); ?>
                    <img src="<?= e(img_url('home-trail')) ?>"
                         alt="Závodníci na trati Vodácké 30 v Orlických horách"
                         loading="lazy" decoding="async"
                         <?php if ($tratSrcset !== ''): ?>srcset="<?= e($tratSrcset) ?>" sizes="(min-width: 1024px) 600px, 100vw"<?php endif; ?>
                         class="aspect-[4/3] w-full rounded-md object-cover lg:aspect-[3/2]">
                <?php else: ?>
                    <div class="relative isolate flex aspect-[4/3] w-full items-end overflow-hidden rounded-md bg-deep p-6 lg:aspect-[3/2]">
                        <?php require __DIR__ . '/partials/contours.php'; ?>
                        <p class="text-sm text-white/60">Fotografie z trati bude doplněna pořadatelem.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<!-- Ročník 2026 v číslech -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Ročník 2026 v číslech</p>
            <dl class="mt-8 grid grid-cols-2 gap-x-8 gap-y-8 lg:grid-cols-4">
                <?php foreach ($rocnik as $metrika): ?>
                    <div class="border-t border-slate-200 pt-5">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($metrika['label']) ?></dt>
                        <dd class="mt-2 font-display text-3xl font-black tabular-nums leading-none text-slate-900 sm:text-4xl">
                            <?= e($metrika['hodnota']) ?><?php if ($metrika['jednotka'] !== ''): ?> <span class="text-base font-bold text-slate-400"><?= e($metrika['jednotka']) ?></span><?php endif; ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
</section>

<!-- Den závodu -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-16">

            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Den závodu</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                    Od registrace k vyhlášení
                </h2>
                <p class="mt-5 max-w-prose text-base leading-7 text-slate-600">
                    Klíčové body programu 26. ročníku. Kompletní propozice včetně kategorií, tratí a startovného
                    najdete na samostatné stránce.
                </p>
                <a href="/propozice"
                   class="mt-8 inline-flex items-center gap-2 border-b border-brand-600 pb-1 text-sm font-semibold text-brand-700 transition-colors hover:border-brand-400 hover:text-brand-500">
                    Propozice a trať
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <ol class="reveal lg:col-span-7" x-intersect.once="$el.classList.add('is-visible')">
                <?php foreach ($program as $index => $bod): ?>
                    <li class="flex gap-4 py-5 sm:gap-6 <?= $index === 0 ? 'pb-5' : 'border-t border-slate-200' ?>">
                        <span class="w-16 shrink-0 font-display text-lg font-bold tabular-nums text-brand-700"><?= e($bod['cas']) ?></span>
                        <span class="min-w-0 pt-0.5 text-base text-slate-700"><?= e($bod['polozka']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>

        </div>
    </div>
</section>

<?php if (! empty($novinky)): ?>
<?php
// České názvy měsíců (2. pád) pro editorial formát data – „17. ledna 2026".
$mesice = [1 => 'ledna', 'února', 'března', 'dubna', 'května', 'června', 'července', 'srpna', 'září', 'října', 'listopadu', 'prosince'];
?>
<!-- Novinky -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">

        <div class="reveal max-w-2xl" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Novinky</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Ze zákulisí závodu
            </h2>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-x-16 gap-y-10 lg:grid-cols-3">
            <?php foreach ($novinky as $prispevek): ?>
                <?php
                $datum  = (string) ($prispevek['datum'] ?? '');
                $stamp  = $datum !== '' ? strtotime($datum) : false;
                $zobraz = $stamp
                    ? (int) date('j', $stamp) . '. ' . $mesice[(int) date('n', $stamp)] . ' ' . date('Y', $stamp)
                    : $datum;
                $perex  = \App\Helpers\Text::truncate((string) ($prispevek['perex'] ?? ''), 160);
                ?>
                <article class="reveal border-t border-slate-300 pt-6" x-intersect.once="$el.classList.add('is-visible')">
                    <time datetime="<?= e($datum) ?>" class="font-display text-xs font-bold uppercase tracking-[0.2em] text-brand-700">
                        <?= e($zobraz) ?>
                    </time>
                    <h3 class="mt-3 font-display text-lg font-bold leading-snug tracking-tight text-slate-900">
                        <?= e((string) ($prispevek['nadpis'] ?? '')) ?>
                    </h3>
                    <?php if ($perex !== ''): ?>
                        <p class="mt-3 text-sm leading-6 text-slate-600"><?= e($perex) ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>

<!-- Poslední výsledky -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">

            <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Výsledky 2026</p>
                    <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                        Nejrychlejší muži PRO
                    </h2>
                </div>
                <a href="/vysledky"
                   class="inline-flex items-center gap-2 border-b border-brand-600 pb-1 text-sm font-semibold text-brand-700 transition-colors hover:border-brand-400 hover:text-brand-500">
                    Všechny výsledky
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <ol class="mt-10 divide-y divide-slate-200 border-y border-slate-200">
                <?php foreach ($podium as $radek): ?>
                    <li class="flex items-center gap-4 py-5 sm:gap-6">
                        <span class="w-8 shrink-0 font-display text-xl font-black tabular-nums <?= $radek['poradi'] === 1 ? 'text-brand-600' : 'text-slate-300' ?>">
                            <?= e($radek['poradi']) ?>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-semibold text-slate-900"><?= e($radek['jmeno']) ?></span>
                            <span class="block text-sm text-slate-500"><?= e($radek['oddil']) ?></span>
                        </span>
                        <span class="shrink-0 font-display text-lg font-bold tabular-nums text-slate-900"><?= e($radek['cas']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>

            <p class="mt-4 text-sm text-slate-500">Kategorie <?= e($podiumKategorie) ?> &middot; 26. ročník, 17. ledna 2026</p>
        </div>
    </div>
</section>

<!-- Závěrečné CTA -->
<section class="relative isolate overflow-hidden bg-night text-white">
    <?php require __DIR__ . '/partials/contours.php'; ?>

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-ice">Přihlášky</p>
            <h2 class="mt-4 max-w-2xl font-display text-2xl font-extrabold uppercase leading-tight tracking-tight sm:text-4xl">
                Přihlaste se na další ročník
            </h2>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">
                Registrace dalšího ročníku zatím není pořadatelem potvrzená. Přihlášku můžete odeslat už teď –
                s dotazy na termín a podmínky se obraťte přímo na pořadatele.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <a href="/prihlaska"
                   class="inline-flex items-center justify-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                    Podat přihlášku
                </a>
                <a href="/kontakt"
                   class="inline-flex items-center justify-center rounded-md border border-white/25 px-6 py-3 text-sm font-semibold text-white transition-colors hover:border-white/50 hover:bg-white/10">
                    Kontakt na pořadatele
                </a>
            </div>
        </div>
    </div>
</section>
