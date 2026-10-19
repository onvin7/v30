<!-- Startovní listina -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Ročník 2026</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Startovní listina</h1>
        <p class="text-lg text-slate-600 max-w-3xl">
            Závodníci 26. ročníku (17. ledna 2026) podle kategorií. Zveřejňujeme pouze schválené veřejné údaje.
        </p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <?php foreach ($startovniListina as $skupina): ?>
            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                <div class="px-6 sm:px-8 py-4 border-b border-slate-200">
                    <h2 class="font-display text-lg font-extrabold text-slate-900"><?= e($skupina['kategorie']) ?></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <caption class="sr-only">Startovní listina – <?= e($skupina['kategorie']) ?></caption>
                        <thead>
                            <tr class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th scope="col" class="text-right w-24 px-4 sm:px-8 py-3">Start. číslo</th>
                                <th scope="col" class="text-left px-4 py-3">Závodník</th>
                                <th scope="col" class="text-left px-4 sm:px-8 py-3">Oddíl / tým</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skupina['zavodnici'] as $zavodnik): ?>
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                                    <td class="px-4 sm:px-8 py-3 text-right tabular-nums font-semibold text-slate-900"><?= e($zavodnik['cislo']) ?></td>
                                    <td class="px-4 py-3 text-slate-900"><?= e($zavodnik['jmeno']) ?></td>
                                    <td class="px-4 sm:px-8 py-3 text-slate-600"><?= e($zavodnik['oddil']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Ochrana osobních údajů -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-orange-600 rounded-lg p-6 sm:p-8">
            <p class="text-sm font-semibold text-slate-600">Ochrana osobních údajů</p>
            <p class="text-sm leading-6 text-slate-600 mt-2 max-w-3xl">
                Ve veřejné startovní listině se nezobrazují e-mail, telefon, datum narození, poznámka ani stav platby.
                Závodníci jsou uvedeni pouze se souhlasem a v rozsahu schváleném pořadatelem.
            </p>
        </div>

    </div>
</section>
