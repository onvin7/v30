<?php
$pageHeader = [
    'eyebrow'    => 'Ročník 2026',
    'title'      => 'Startovní listina',
    'lead'       => 'Závodníci 26. ročníku (17. ledna 2026) podle kategorií. Zveřejňujeme pouze schválené veřejné údaje.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Startovní listina']],
    'image'      => 'header-startlist',
];
require __DIR__ . '/partials/page_header.php';

$celkemZavodniku = 0;
foreach ($startovniListina as $skupina) {
    $celkemZavodniku += count($skupina['zavodnici']);
}
?>

<!-- Rychlé kotvy na kategorie -->
<section class="border-b border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav aria-label="Kategorie" class="-mb-px flex gap-7 overflow-x-auto">
            <?php foreach ($startovniListina as $index => $skupina): ?>
                <a href="#kategorie-<?= $index + 1 ?>"
                   class="whitespace-nowrap border-b-2 border-transparent py-4 text-sm font-medium text-slate-500 transition-colors hover:border-slate-300 hover:text-slate-900">
                    <?= e($skupina['kategorie']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>

<!-- Startovní listina -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">

        <p class="text-sm text-slate-500">
            Celkem <span class="font-semibold tabular-nums text-slate-900"><?= $celkemZavodniku ?></span> závodníků v
            <span class="font-semibold tabular-nums text-slate-900"><?= count($startovniListina) ?></span> kategoriích.
        </p>

        <div class="mt-12 space-y-14">
            <?php foreach ($startovniListina as $index => $skupina): ?>
                <div id="kategorie-<?= $index + 1 ?>" class="reveal scroll-mt-24" x-intersect.once="$el.classList.add('is-visible')">

                    <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2">
                        <h2 class="font-display text-xl font-extrabold tracking-tight sm:text-2xl"><?= e($skupina['kategorie']) ?></h2>
                        <p class="text-sm tabular-nums text-slate-500"><?= count($skupina['zavodnici']) ?> závodníků</p>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="w-full min-w-[34rem] text-sm">
                            <caption class="sr-only">Startovní listina – <?= e($skupina['kategorie']) ?></caption>
                            <thead>
                                <tr class="border-b border-slate-300 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <th scope="col" class="w-24 py-3 pr-4 text-right">Start. číslo</th>
                                    <th scope="col" class="py-3 pr-4 text-left">Závodník</th>
                                    <th scope="col" class="py-3 text-left">Oddíl / tým</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 border-b border-slate-200">
                                <?php foreach ($skupina['zavodnici'] as $zavodnik): ?>
                                    <tr class="transition-colors hover:bg-snow">
                                        <td class="py-3.5 pr-4 text-right">
                                            <span class="font-display text-base font-black tabular-nums text-brand-600"><?= $zavodnik['cislo'] !== '' ? e($zavodnik['cislo']) : '—' ?></span>
                                        </td>
                                        <td class="py-3.5 pr-4 font-semibold text-slate-900"><?= e($zavodnik['jmeno']) ?></td>
                                        <td class="py-3.5 text-slate-500"><?= $zavodnik['oddil'] !== '' ? e($zavodnik['oddil']) : '—' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Ochrana osobních údajů -->
        <div class="reveal mt-16 border-l-2 border-brand-600 bg-snow px-5 py-5 sm:px-6" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Ochrana osobních údajů</p>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                Ve veřejné startovní listině se nezobrazují e-mail, telefon, datum narození, poznámka ani stav platby.
                Závodníci jsou uvedeni pouze se souhlasem a v rozsahu schváleném pořadatelem.
            </p>
        </div>

    </div>
</section>
