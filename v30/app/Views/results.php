<!-- Výsledky a archiv -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Oficiální archiv</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Výsledky a archiv</h1>
        <p class="text-lg text-slate-600 max-w-3xl">
            Oficiální výsledky 26. ročníku (17. ledna 2026) podle kategorií. Starší ročníky přidáváme po ověření
            zdrojových listin.
        </p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <!-- Filtr kategorií -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-medium uppercase tracking-wide text-slate-500 mr-1">Kategorie</span>
            <a href="/vysledky" class="inline-flex items-center rounded-md border px-3 py-1.5 text-sm font-medium transition-colors <?= $vybranaKategorie === '' ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50' ?>">Vše</a>
            <?php foreach ($vsechnyKategorie as $skupina): ?>
                <a href="/vysledky?kategorie=<?= e($skupina['klic']) ?>"
                   class="inline-flex items-center rounded-md border px-3 py-1.5 text-sm font-medium transition-colors <?= $vybranaKategorie === $skupina['klic'] ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50' ?>"><?= e($skupina['nazev']) ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($kategorie)): ?>
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <p class="text-sm text-slate-600">Pro zvolenou kategorii nejsou zveřejněné výsledky.</p>
            </div>
        <?php endif; ?>

        <!-- Výsledkové tabulky -->
        <?php foreach ($kategorie as $skupina): ?>
            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                <div class="px-6 sm:px-8 py-4 border-b border-slate-200">
                    <h2 class="font-display text-lg font-extrabold text-slate-900"><?= e($skupina['nazev']) ?></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <caption class="sr-only">Výsledky – <?= e($skupina['nazev']) ?>, ročník 2026</caption>
                        <thead>
                            <tr class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th scope="col" class="text-right w-16 px-4 sm:px-8 py-3">Poř.</th>
                                <th scope="col" class="text-right w-24 px-4 py-3">Start</th>
                                <th scope="col" class="text-left px-4 py-3">Závodník</th>
                                <th scope="col" class="text-left px-4 py-3">Oddíl / tým</th>
                                <th scope="col" class="text-right px-4 py-3">Čas</th>
                                <th scope="col" class="text-right px-4 sm:px-8 py-3">Ztráta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skupina['vysledky'] as $radek): ?>
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                    <td class="px-4 sm:px-8 py-3 text-right tabular-nums font-semibold text-slate-900"><?= e($radek['poradi']) ?></td>
                                    <td class="px-4 py-3 text-right tabular-nums text-slate-600"><?= e($radek['cislo']) ?></td>
                                    <td class="px-4 py-3 text-slate-900"><?= e($radek['jmeno']) ?></td>
                                    <td class="px-4 py-3 text-slate-600"><?= e($radek['oddil']) ?></td>
                                    <td class="px-4 py-3 text-right tabular-nums text-slate-900"><?= e($radek['cas']) ?></td>
                                    <td class="px-4 sm:px-8 py-3 text-right tabular-nums text-slate-600"><?= e($radek['ztrata']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Oficiální podklady -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
            <p class="text-sm font-semibold text-slate-600">Poslední dostupný ročník</p>
            <h2 class="font-display text-xl font-extrabold text-slate-900 mt-1 mb-4">Výsledky 2026</h2>
            <div class="divide-y divide-slate-200 border-y border-slate-200">
                <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://vodacka30.cz/index.php?menu=3" target="_blank" rel="noopener noreferrer">
                    Zobrazit výsledkovou listinu ↗
                </a>
                <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://vodacka30.cz/vysledky/V30_2026_vysledkova_listina.pdf" target="_blank" rel="noopener noreferrer">
                    Stáhnout oficiální PDF ↗
                </a>
            </div>
        </div>

        <!-- Archiv ročníků -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
            <h2 class="font-display text-xl font-extrabold text-slate-900 mb-2">Archiv ročníků</h2>
            <p class="text-sm leading-6 text-slate-600 mb-4 max-w-3xl">
                Starší ročníky budeme přidávat po kontrole zdrojových listin a potvrzení pořadatelem.
                Neověřené výsledky zde nevydáváme za oficiální.
            </p>
            <ul class="flex flex-wrap gap-2">
                <?php foreach ($archiv as $rocnik): ?>
                    <li>
                        <?php if ($rocnik['overeno']): ?>
                            <a href="/vysledky" class="inline-flex flex-col rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm font-medium text-slate-900 hover:bg-slate-50 transition-colors">
                                <?= e($rocnik['rok']) ?>
                                <span class="text-xs font-normal text-slate-500"><?= e($rocnik['rocnik']) ?>. ročník &middot; <?= e($rocnik['datum']) ?></span>
                            </a>
                        <?php else: ?>
                            <span class="inline-flex flex-col rounded-md border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm text-slate-400" title="Zatím neověřeno">
                                <?= e($rocnik['rok']) ?>
                                <span class="text-xs font-normal"><?= e($rocnik['rocnik']) ?>. ročník &middot; <?= e($rocnik['datum']) ?></span>
                                <span class="sr-only"> – neověřeno</span>
                            </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</section>
