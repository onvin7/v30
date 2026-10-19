<!-- Potvrzení přihlášky -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Rekapitulace přihlášky</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Přihláška odeslána</h1>
        <p class="text-lg text-slate-600">
            Děkujeme. Níže je rekapitulace přihlášky a platební instrukce. Zkontrolujte prosím, že údaje odpovídají skutečnosti.
        </p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <!-- Rekapitulace -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
            <div class="flex items-baseline justify-between gap-4 border-b border-slate-200 pb-4">
                <h2 class="font-display text-xl font-extrabold text-slate-900">Rekapitulace</h2>
                <span class="text-sm font-semibold tabular-nums text-slate-600"><?= e($prihlaseni['id']) ?></span>
            </div>
            <dl class="divide-y divide-slate-200">
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Závodník</dt>
                    <dd class="text-sm font-semibold text-slate-900"><?= e($prihlaseni['jmeno'] . ' ' . $prihlaseni['prijmeni']) ?></dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Oddíl / tým</dt>
                    <dd class="text-sm font-semibold text-slate-900"><?= e($prihlaseni['oddil']) ?></dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Kategorie</dt>
                    <dd class="text-sm font-semibold text-slate-900"><?= e($prihlaseni['kategorie']) ?> (<?= e($prihlaseni['trat']) ?>)</dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Ročník narození</dt>
                    <dd class="text-sm font-semibold text-slate-900"><?= e($prihlaseni['rocnik_narozeni']) ?></dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">E-mail</dt>
                    <dd class="text-sm font-semibold text-slate-900"><?= e($prihlaseni['email']) ?></dd>
                </div>
                <?php if ($prihlaseni['poznamka'] !== ''): ?>
                    <div class="py-3 flex items-baseline justify-between gap-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Poznámka</dt>
                        <dd class="text-sm text-slate-600 text-right max-w-md"><?= e($prihlaseni['poznamka']) ?></dd>
                    </div>
                <?php endif; ?>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Odesláno</dt>
                    <dd class="text-sm text-slate-600"><?= e($prihlaseni['vytvoreno']) ?></dd>
                </div>
            </dl>
        </div>

        <!-- Platební instrukce -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-orange-600 rounded-lg p-6 sm:p-8">
            <h2 class="font-display text-xl font-extrabold text-slate-900 mb-1">Platební instrukce</h2>
            <p class="text-sm leading-6 text-slate-600 mb-4">
                Startovné uhraďte bankovním převodem. Platba se považuje za přijatou až po jejím ověření pořadatelem.
            </p>
            <dl class="divide-y divide-slate-200">
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Číslo účtu</dt>
                    <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['ucet']) ?></dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Částka</dt>
                    <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['castka']) ?></dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Variabilní symbol</dt>
                    <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['vs']) ?></dd>
                </div>
                <div class="py-3 flex items-baseline justify-between gap-4">
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Poznámka pro příjemce</dt>
                    <dd class="text-sm font-semibold text-slate-900"><?= e($platba['poznamka']) ?></dd>
                </div>
            </dl>
        </div>

        <!-- Další kroky -->
        <div class="flex flex-wrap gap-3">
            <a href="/" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-3 transition-colors">Zpět na úvod</a>
            <a href="/propozice" class="inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-5 py-3 transition-colors">Propozice a trať</a>
        </div>

        <p class="text-sm text-slate-600">
            Potvrzení o přijetí přihlášky e-mailem bude součástí produkční verze webu.
        </p>

    </div>
</section>
