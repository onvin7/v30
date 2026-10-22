<?php
$pageHeader = [
    'eyebrow'    => 'Oficiální archiv',
    'title'      => 'Výsledky a archiv',
    'lead'       => 'Výsledky 26. ročníku (17. ledna 2026) podle kategorií.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Výsledky a archiv']],
    'image'      => 'header-results',
];
require __DIR__ . '/partials/page_header.php';
?>

<!-- Filtr kategorií -->
<section class="border-b border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav aria-label="Filtr kategorií" class="-mx-4 -mb-px flex gap-7 overflow-x-auto px-4 no-scrollbar sm:mx-0 sm:px-0">
            <a href="/vysledky"
               class="whitespace-nowrap border-b-2 py-4 text-sm font-medium transition-colors <?= $vybranaKategorie === '' ? 'border-brand-600 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-900' ?>"
               <?= $vybranaKategorie === '' ? 'aria-current="true"' : '' ?>>Vše</a>
            <?php foreach ($vsechnyKategorie as $skupina): ?>
                <?php $isActive = $vybranaKategorie === $skupina['klic']; ?>
                <a href="/vysledky?kategorie=<?= e($skupina['klic']) ?>"
                   class="whitespace-nowrap border-b-2 py-4 text-sm font-medium transition-colors <?= $isActive ? 'border-brand-600 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-900' ?>"
                   <?= $isActive ? 'aria-current="true"' : '' ?>><?= e($skupina['nazev']) ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>

<!-- Výsledkové listiny -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">

        <?php if (empty($kategorie)): ?>
            <p class="border-l-2 border-brand-600 bg-snow px-5 py-4 text-sm text-slate-600">
                Pro tuto kategorii zatím výsledky nemáme.
            </p>
        <?php endif; ?>

        <div class="space-y-12 sm:space-y-16">
            <?php foreach ($kategorie as $skupina): ?>
                <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">

                    <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2">
                        <h2 class="font-display text-xl font-extrabold tracking-tight sm:text-2xl"><?= e($skupina['nazev']) ?></h2>
                        <p class="text-sm text-slate-500">26. ročník &middot; 17. ledna 2026</p>
                    </div>

                    <?php
                    // Barvu pořadí potřebují oba výpisy níže – mobilní seznam i tabulka.
                    $radky = array_map(static function (array $radek): array {
                        $jePrvni  = $radek['poradi'] === 1;
                        $jePodium = is_int($radek['poradi']) && $radek['poradi'] <= 3;

                        $radek['barvaPoradi'] = $jePrvni ? 'text-brand-600' : ($jePodium ? 'text-slate-900' : 'text-slate-400');

                        return $radek;
                    }, $skupina['vysledky']);
                    ?>

                    <!-- Mobil: záznamový seznam – tabulka potřebuje 42rem, do telefonu se nevejde -->
                    <ul class="mt-6 divide-y divide-slate-200 border-y border-slate-200 md:hidden">
                        <?php foreach ($radky as $radek): ?>
                            <li class="py-4">
                                <div class="flex items-baseline justify-between gap-4">
                                    <div class="flex min-w-0 items-baseline gap-3">
                                        <span class="w-6 shrink-0 text-right font-display text-base font-black tabular-nums <?= $radek['barvaPoradi'] ?>"><?= e($radek['poradi']) ?></span>
                                        <p class="min-w-0 font-semibold text-slate-900"><?= e($radek['jmeno']) ?></p>
                                    </div>
                                    <span class="shrink-0 font-display text-base font-bold tabular-nums text-slate-900"><?= e($radek['cas']) ?></span>
                                </div>
                                <p class="mt-1 pl-9 text-sm text-slate-500">
                                    <?= $radek['oddil'] !== '' ? e($radek['oddil']) : '—' ?>
                                    <?php if ($radek['cislo'] !== ''): ?> &middot; start <?= e($radek['cislo']) ?><?php endif; ?>
                                    <?php if ($radek['ztrata'] !== '—'): ?> &middot; ztráta <?= e($radek['ztrata']) ?><?php endif; ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Tablet a výš: tabulka se od md: vždy vejde (42rem ≤ 720 px) -->
                    <div class="mt-6 hidden overflow-x-auto md:block">
                        <table class="w-full min-w-[42rem] text-sm">
                            <caption class="sr-only">Výsledky – <?= e($skupina['nazev']) ?>, ročník 2026</caption>
                            <thead>
                                <tr class="border-b border-slate-300 text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <th scope="col" class="w-14 py-3 pr-4 text-right">Poř.</th>
                                    <th scope="col" class="w-16 py-3 pr-4 text-right">Start</th>
                                    <th scope="col" class="py-3 pr-4 text-left">Závodník</th>
                                    <th scope="col" class="py-3 pr-4 text-left">Oddíl / tým</th>
                                    <th scope="col" class="py-3 pr-4 text-right">Čas</th>
                                    <th scope="col" class="py-3 text-right">Ztráta</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 border-b border-slate-200">
                                <?php foreach ($radky as $radek): ?>
                                    <tr class="transition-colors hover:bg-snow">
                                        <td class="py-3.5 pr-4 text-right">
                                            <span class="font-display text-base font-black tabular-nums <?= $radek['barvaPoradi'] ?>"><?= e($radek['poradi']) ?></span>
                                        </td>
                                        <td class="py-3.5 pr-4 text-right tabular-nums text-slate-500"><?= $radek['cislo'] !== '' ? e($radek['cislo']) : '—' ?></td>
                                        <td class="py-3.5 pr-4 font-semibold text-slate-900"><?= e($radek['jmeno']) ?></td>
                                        <td class="py-3.5 pr-4 text-slate-500"><?= $radek['oddil'] !== '' ? e($radek['oddil']) : '—' ?></td>
                                        <td class="py-3.5 pr-4 text-right font-medium tabular-nums text-slate-900"><?= e($radek['cas']) ?></td>
                                        <td class="py-3.5 text-right tabular-nums text-slate-500"><?= e($radek['ztrata']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Oficiální podklady -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal max-w-3xl" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Podklady</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Výsledky 2026
            </h2>
            <p class="mt-5 text-base leading-7 text-slate-600">
                Vycházíme z oficiální listiny pořadatele. Originální dokumenty najdete na původním webu závodu.
            </p>

            <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200">
                <a href="https://vodacka30.cz/index.php?menu=3" target="_blank" rel="noopener noreferrer"
                   class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                    Zobrazit výsledkovou listinu
                    <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">↗</span>
                </a>
                <a href="https://vodacka30.cz/vysledky/V30_2026_vysledkova_listina.pdf" target="_blank" rel="noopener noreferrer"
                   class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                    Stáhnout oficiální PDF
                    <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">↗</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Archiv ročníků -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="reveal" x-intersect.once="$el.classList.add('is-visible')">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Archiv</p>
            <h2 class="mt-4 font-display text-2xl font-extrabold leading-tight tracking-tight sm:text-4xl">
                Ročníky 2015–2026
            </h2>
            <p class="mt-5 max-w-3xl text-base leading-7 text-slate-600">
                Starší ročníky postupně doplňujeme.
            </p>

            <ul class="mt-10 grid grid-cols-2 gap-x-8 gap-y-8 sm:grid-cols-3 lg:grid-cols-4">
                <?php foreach ($archiv as $rocnik): ?>
                    <li class="border-t border-slate-200 pt-4">
                        <?php if ($rocnik['overeno']): ?>
                            <a href="/vysledky" class="group block">
                                <span class="font-display text-2xl font-black tabular-nums text-slate-900 transition-colors group-hover:text-brand-600"><?= e($rocnik['rok']) ?></span>
                                <span class="mt-1 block text-xs text-slate-500"><?= e($rocnik['rocnik']) ?>. ročník &middot; <?= e($rocnik['datum']) ?></span>
                            </a>
                        <?php else: ?>
                            <span class="block" title="Zatím neověřeno">
                                <span class="font-display text-2xl font-black tabular-nums text-slate-300"><?= e($rocnik['rok']) ?></span>
                                <span class="mt-1 block text-xs text-slate-400"><?= e($rocnik['rocnik']) ?>. ročník &middot; <?= e($rocnik['datum']) ?></span>
                                <span class="sr-only"> – neověřeno</span>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
