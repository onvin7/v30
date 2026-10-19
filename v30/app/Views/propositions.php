<?php
$pageHeader = [
    'eyebrow'    => 'Poslední zveřejněný ročník · 2026',
    'title'      => 'Propozice a trať',
    'lead'       => 'Podmínky 26. ročníku (17. ledna 2026) vycházejí ze zveřejněných propozic pořadatele.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Propozice a trať']],
    'image'      => 'header-propositions',
];
require __DIR__ . '/partials/page_header.php';
?>

<!-- Program a pořadatelé -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-16">

            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <div class="border-l-2 border-brand-600 bg-snow px-5 py-5 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Informace k ročníku 2026</p>
                    <h2 class="mt-3 font-display text-lg font-extrabold leading-snug tracking-tight sm:text-xl">
                        Údaje z posledního zveřejněného ročníku.
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Datum, kategorie, tratě, ceny a termíny přihlášek níže popisují ročník 2026. Na další ročník se
                        automaticky nevztahují a budou aktualizovány po potvrzení pořadatelem.
                    </p>
                </div>

                <p class="mt-12 text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Pořadatelé</p>
                <dl class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                    <?php foreach ($poradatele as $osoba): ?>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($osoba['role']) ?></dt>
                            <dd class="text-sm font-semibold text-slate-900"><?= e($osoba['jmeno']) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>

            <div class="reveal lg:col-span-7" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Časový program</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl">
                    Den závodu
                </h2>

                <ol class="mt-8">
                    <?php foreach ($program as $index => $bod): ?>
                        <li class="flex gap-6 py-3.5 <?= $index === 0 ? '' : 'border-t border-slate-200' ?>">
                            <span class="w-16 shrink-0 font-display text-base font-bold tabular-nums text-brand-700"><?= e($bod['cas']) ?></span>
                            <span class="text-sm text-slate-700 sm:text-base"><?= e($bod['polozka']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

        </div>
    </div>
</section>

<!-- Kategorie a tratě -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Rozpis</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Kategorie a tratě
            </h2>
            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600">
                Rozpis odpovídá poslednímu zveřejněnému ročníku 2026.
            </p>

            <div class="mt-10 overflow-x-auto">
                <table class="w-full min-w-[40rem] text-sm">
                    <caption class="sr-only">Kategorie, tratě, styl a startovné ročníku 2026</caption>
                    <thead>
                        <tr class="border-b border-slate-300 text-xs font-medium uppercase tracking-wider text-slate-500">
                            <th scope="col" class="py-3 pr-4 text-left">Kategorie</th>
                            <th scope="col" class="w-24 py-3 pr-4 text-right">Trať</th>
                            <th scope="col" class="w-28 py-3 pr-4 text-left">Styl</th>
                            <th scope="col" class="w-32 py-3 text-right">Startovné</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 border-b border-slate-200">
                        <?php foreach ($kategorie as $kat): ?>
                            <tr class="transition-colors hover:bg-white">
                                <td class="py-3.5 pr-4 font-semibold text-slate-900"><?= e($kat['nazev']) ?></td>
                                <td class="py-3.5 pr-4 text-right tabular-nums text-slate-500"><?= e($kat['trat']) ?></td>
                                <td class="py-3.5 pr-4 text-slate-500"><?= e($kat['styl']) ?></td>
                                <td class="py-3.5 text-right tabular-nums text-slate-900"><?= e($kat['cena']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Startovné a platba -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16">

            <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Startovné</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl">
                    Kolik zaplatíte
                </h2>
                <ul class="mt-8 list-disc space-y-3 pl-5 text-sm leading-6 text-slate-600 marker:text-brand-600">
                    <?php foreach ($startovne as $radek): ?>
                        <li><?= e($radek) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Přihlášení</p>
                <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-3xl">
                    Platba a přihláška
                </h2>
                <p class="mt-8 max-w-prose text-sm leading-6 text-slate-600">
                    Přihláška se podává elektronicky přes stránku Přihlášky. Platba probíhá bankovním převodem;
                    variabilní symbol obdržíte v rekapitulaci přihlášky. Dokud platbu pořadatel neověří, není
                    přihláška označena jako zaplacená.
                </p>
                <a href="/prihlaska"
                   class="mt-8 inline-flex items-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                    Přejít na přihlášku
                </a>
            </div>

        </div>
    </div>
</section>

<!-- Trať, bezpečnost, ubytování -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal grid grid-cols-1 gap-10 sm:grid-cols-3 sm:gap-8" x-intersect.once="$el.classList.add('is-visible')">

            <div class="border-t border-slate-300 pt-5">
                <h2 class="font-display text-lg font-extrabold tracking-tight">Trať</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Hlavní okruh má 30 km a vede po hřebenech Orlických hor. Start a cíl jsou u chaty Bedřichovka
                    v Orlickém Záhoří, kontrolní bod je na Pěticestí u Horské služby.
                </p>
            </div>

            <div class="border-t border-slate-300 pt-5">
                <h2 class="font-display text-lg font-extrabold tracking-tight">Bezpečnost a časový limit</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Závod se jede za běžných zimních podmínek a končí v 17:00. Závodníci startují na vlastní
                    nebezpečí a musí respektovat pokyny pořadatelů a časový limit trati.
                </p>
            </div>

            <div class="border-t border-slate-300 pt-5">
                <h2 class="font-display text-lg font-extrabold tracking-tight">Ubytování a kontakt</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Zázemí závodu je u chaty Bedřichovka v Orlickém Záhoří. Ubytování lze domluvit na
                    <a href="mailto:info@bedrichovka.cz" class="font-medium text-brand-700 underline decoration-brand-300 underline-offset-2 transition-colors hover:text-brand-500">info@bedrichovka.cz</a>
                    nebo +420 775 346 832.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Trasy a mapy -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Mapy</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Trasy a mapové podklady
            </h2>
            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-600">
                Mapové podklady jednotlivých tratí ročníku 2026.
            </p>
        </div>

        <div class="mt-12 space-y-14">
            <?php foreach ($trasy as $trasa): ?>
                <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">

                    <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2 border-t border-slate-300 pt-5">
                        <h3 class="font-display text-lg font-extrabold tracking-tight sm:text-xl"><?= e($trasa['nazev']) ?></h3>
                        <p class="text-sm tabular-nums text-slate-500"><?= e($trasa['trat']) ?> &middot; <?= e($trasa['styl']) ?></p>
                    </div>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600"><?= e($trasa['popis']) ?></p>

                    <?php if ($trasa['mapa'] !== ''): ?>
                        <div class="mt-6 overflow-hidden rounded-md border border-slate-200">
                            <iframe
                                src="<?= e($trasa['mapa']) ?>"
                                title="Mapa trasy <?= e($trasa['nazev']) ?>"
                                loading="lazy"
                                class="h-80 w-full border-0 sm:h-96"
                                allowfullscreen></iframe>
                        </div>
                        <a href="<?= e($trasa['mapa']) ?>" target="_blank" rel="noopener noreferrer"
                           class="mt-4 inline-flex items-center gap-2 border-b border-brand-600 pb-1 text-sm font-semibold text-brand-700 transition-colors hover:border-brand-400 hover:text-brand-500">
                            Otevřít mapu
                            <span aria-hidden="true">↗</span>
                        </a>
                    <?php else: ?>
                        <p class="mt-4 text-sm italic text-slate-500">Mapa této trati bude doplněna.</p>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Odkaz na oficiální propozice -->
<section class="relative isolate overflow-hidden bg-night text-white">
    <?php require __DIR__ . '/partials/contours.php'; ?>

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-ice">Archiv</p>
            <h2 class="mt-4 max-w-2xl font-display text-2xl font-extrabold uppercase leading-tight tracking-tight sm:text-4xl">
                Propozice 2026
            </h2>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">
                Podmínky posledního zveřejněného ročníku zůstávají dostupné na původním webu závodu.
            </p>
            <div class="mt-8">
                <a href="https://vodacka30.cz/index.php?menu=2" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center rounded-md border border-white/25 px-6 py-3 text-sm font-semibold text-white transition-colors hover:border-white/50 hover:bg-white/10">
                    Otevřít propozice 2026 ↗
                </a>
            </div>
        </div>
    </div>
</section>
