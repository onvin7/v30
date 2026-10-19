<!-- Propozice a trať -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Poslední zveřejněný ročník &middot; 2026</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Propozice a trať</h1>
        <p class="text-lg text-slate-600 max-w-3xl">Podmínky 26. ročníku (17. ledna 2026) vycházejí ze zveřejněných propozic pořadatele.</p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <!-- Upozornění na platnost údajů -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-orange-600 rounded-lg p-6 sm:p-8">
            <p class="text-sm font-semibold text-slate-600">Informace k ročníku 2026</p>
            <h2 class="font-display text-2xl font-extrabold text-slate-900 mt-1 mb-3">Údaje z posledního zveřejněného ročníku.</h2>
            <p class="text-sm leading-6 text-slate-600 max-w-3xl">
                Datum, kategorie, tratě, ceny a termíny přihlášek níže popisují ročník 2026. Na další ročník se
                automaticky nevztahují a budou aktualizovány po potvrzení pořadatelem.
            </p>
        </div>

        <!-- Pořadatelé -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
            <h2 class="font-display text-xl font-extrabold text-slate-900 mb-4">Pořadatelé</h2>
            <dl class="divide-y divide-slate-200">
                <?php foreach ($poradatele as $osoba): ?>
                    <div class="py-3 flex items-baseline justify-between gap-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($osoba['role']) ?></dt>
                        <dd class="text-sm font-semibold text-slate-900"><?= e($osoba['jmeno']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>

        <!-- Program -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
            <h2 class="font-display text-xl font-extrabold text-slate-900 mb-4">Časový program</h2>
            <dl class="divide-y divide-slate-200">
                <?php foreach ($program as $bod): ?>
                    <div class="py-3 flex items-baseline gap-6">
                        <dt class="w-28 shrink-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($bod['cas']) ?></dt>
                        <dd class="text-sm text-slate-600"><?= e($bod['polozka']) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>

        <!-- Kategorie -->
        <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8 pb-4">
                <h2 class="font-display text-xl font-extrabold text-slate-900">Kategorie a tratě</h2>
                <p class="text-sm text-slate-600 mt-2">Rozpis odpovídá poslednímu zveřejněnému ročníku 2026.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <caption class="sr-only">Kategorie, tratě, styl a startovné ročníku 2026</caption>
                    <thead>
                        <tr class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 border-y border-slate-200">
                            <th scope="col" class="text-left px-4 sm:px-8 py-3">Kategorie</th>
                            <th scope="col" class="text-right px-4 py-3">Trať</th>
                            <th scope="col" class="text-left px-4 py-3">Styl</th>
                            <th scope="col" class="text-right px-4 sm:px-8 py-3">Startovné</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kategorie as $kat): ?>
                            <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                <td class="px-4 sm:px-8 py-3 text-slate-900"><?= e($kat['nazev']) ?></td>
                                <td class="px-4 py-3 text-right tabular-nums text-slate-600"><?= e($kat['trat']) ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= e($kat['styl']) ?></td>
                                <td class="px-4 sm:px-8 py-3 text-right tabular-nums text-slate-600"><?= e($kat['cena']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Startovné a platba -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <h2 class="font-display text-xl font-extrabold text-slate-900 mb-4">Startovné</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-slate-600">
                    <?php foreach ($startovne as $radek): ?>
                        <li><?= e($radek) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <h2 class="font-display text-xl font-extrabold text-slate-900 mb-4">Platba a přihlášení</h2>
                <p class="text-sm leading-6 text-slate-600 mb-3">
                    Přihláška se podává elektronicky přes stránku Přihlášky. Platba probíhá bankovním převodem;
                    variabilní symbol obdržíte v rekapitulaci přihlášky. Dokud platbu pořadatel neověří, není
                    přihláška označena jako zaplacená.
                </p>
                <a href="/prihlaska" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-3 transition-colors">Přejít na přihlášku</a>
            </div>
        </div>

        <!-- Trať, bezpečnost, ubytování -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <h2 class="font-display text-lg font-extrabold text-slate-900 mb-3">Trať</h2>
                <p class="text-sm leading-6 text-slate-600">
                    Hlavní okruh má 30 km a vede po hřebenech Orlických hor. Start a cíl jsou u chaty Bedřichovka
                    v Orlickém Záhoří, kontrolní bod je na Pěticestí u Horské služby.
                </p>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <h2 class="font-display text-lg font-extrabold text-slate-900 mb-3">Bezpečnost a časový limit</h2>
                <p class="text-sm leading-6 text-slate-600">
                    Závod se jede za běžných zimních podmínek a končí v 17:00. Závodníci startují na vlastní
                    nebezpečí a musí respektovat pokyny pořadatelů a časový limit trati.
                </p>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <h2 class="font-display text-lg font-extrabold text-slate-900 mb-3">Ubytování a kontakt</h2>
                <p class="text-sm leading-6 text-slate-600">
                    Zázemí závodu je u chaty Bedřichovka v Orlickém Záhoří. Ubytování lze domluvit na
                    info@bedrichovka.cz nebo +420 775 346 832.
                </p>
            </div>
        </div>

        <!-- Trasy a mapy -->
        <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8 pb-4">
                <h2 class="font-display text-xl font-extrabold text-slate-900">Trasy a mapy</h2>
                <p class="text-sm text-slate-600 mt-2">Mapové podklady jednotlivých tratí ročníku 2026.</p>
            </div>
            <div class="divide-y divide-slate-200">
                <?php foreach ($trasy as $trasa): ?>
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1 mb-3">
                            <h3 class="font-display text-lg font-bold text-slate-900"><?= e($trasa['nazev']) ?></h3>
                            <span class="text-sm font-semibold tabular-nums text-slate-600"><?= e($trasa['trat']) ?> &middot; <?= e($trasa['styl']) ?></span>
                        </div>
                        <p class="text-sm leading-6 text-slate-600 mb-4"><?= e($trasa['popis']) ?></p>
                        <?php if ($trasa['mapa'] !== ''): ?>
                            <div class="rounded-lg overflow-hidden border border-slate-200">
                                <iframe
                                    src="<?= e($trasa['mapa']) ?>"
                                    title="Mapa trasy <?= e($trasa['nazev']) ?>"
                                    loading="lazy"
                                    class="w-full h-80 sm:h-96 border-0"
                                    allowfullscreen></iframe>
                            </div>
                            <a href="<?= e($trasa['mapa']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-4 py-2.5 transition-colors mt-4">Otevřít mapu ↗</a>
                        <?php else: ?>
                            <p class="text-sm italic text-slate-500">Mapa této trati bude doplněna.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Oficiální dokument -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
            <p class="text-sm font-semibold text-slate-600">Archiv</p>
            <h2 class="font-display text-xl font-extrabold text-slate-900 mt-1 mb-2">Propozice 2026</h2>
            <p class="text-sm leading-6 text-slate-600 mb-4">Podmínky posledního zveřejněného ročníku zůstávají dostupné na původním webu.</p>
            <a href="https://vodacka30.cz/index.php?menu=2" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-5 py-3 transition-colors">Otevřít propozice 2026 ↗</a>
        </div>

    </div>
</section>
