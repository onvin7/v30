<?php
$pageHeader = [
    'eyebrow'    => 'Rekapitulace přihlášky',
    'title'      => 'Přihláška odeslána',
    'lead'       => 'Přihlášku jsme přijali. Zkontrolujte prosím údaje a podle instrukcí níže uhraďte startovné.',
    'breadcrumb' => [
        ['label' => 'Úvod', 'href' => '/'],
        ['label' => 'Přihlášky', 'href' => '/prihlaska'],
        ['label' => 'Potvrzení'],
    ],
];
require __DIR__ . '/partials/page_header.php';
?>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-16">

            <!-- Rekapitulace -->
            <div class="reveal lg:col-span-7" x-intersect.once="$el.classList.add('is-visible')">
                <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Rekapitulace</p>
                    <p class="font-display text-sm font-bold tabular-nums tracking-tight text-slate-500"><?= e($prihlaseni['id']) ?></p>
                </div>

                <dl class="mt-8 divide-y divide-slate-200 border-y border-slate-200">
                    <div class="flex items-baseline justify-between gap-4 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Závodník</dt>
                        <dd class="min-w-0 text-sm font-semibold text-slate-900"><?= e($prihlaseni['jmeno'] . ' ' . $prihlaseni['prijmeni']) ?></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Oddíl / tým</dt>
                        <dd class="min-w-0 text-sm font-semibold text-slate-900"><?= e($prihlaseni['oddil']) ?></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Kategorie</dt>
                        <dd class="min-w-0 text-sm font-semibold text-slate-900"><?= e($prihlaseni['kategorie']) ?> (<?= e($prihlaseni['trat']) ?>)</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Ročník narození</dt>
                        <dd class="min-w-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($prihlaseni['rocnik_narozeni']) ?></dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-4 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">E-mail</dt>
                        <dd class="min-w-0 text-sm font-semibold text-slate-900"><?= e($prihlaseni['email']) ?></dd>
                    </div>
                    <?php if ($prihlaseni['poznamka'] !== ''): ?>
                        <div class="flex items-baseline justify-between gap-4 py-4">
                            <dt class="shrink-0 text-xs font-medium uppercase tracking-wide text-slate-500">Poznámka</dt>
                            <dd class="min-w-0 max-w-md text-right text-sm text-slate-600"><?= e($prihlaseni['poznamka']) ?></dd>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-baseline justify-between gap-4 py-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Odesláno</dt>
                        <dd class="text-sm tabular-nums text-slate-600"><?= e($prihlaseni['vytvoreno']) ?></dd>
                    </div>
                </dl>

                <p class="mt-6 text-sm text-slate-500">
                    Kdyby něco nesedělo,
                    <a href="/kontakt" class="font-semibold text-brand-700 underline decoration-brand-300 underline-offset-2 transition-colors hover:text-brand-500">napište pořadateli</a>.
                </p>
            </div>

            <!-- Platební instrukce -->
            <div class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <div class="border-l-2 border-brand-600 bg-snow px-5 py-6 sm:px-6 sm:py-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Platební instrukce</p>

                    <?php if ($platba['ucet'] === ''): ?>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Číslo účtu pořadatel zatím nezveřejnil, proto platbu teď neposílejte. Ozvěte se
                            pořadateli, domluvte s ním způsob úhrady a peníze pošlete až po té.
                        </p>
                        <a href="/kontakt"
                           class="mt-5 inline-flex items-center justify-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                            Kontakt na pořadatele
                        </a>
                    <?php else: ?>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Startovné uhraďte převodem. Za přijatou platbu se počítá až po ověření pořadatelem.
                        </p>
                    <?php endif; ?>

                    <dl class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                        <?php if ($platba['ucet'] !== ''): ?>
                            <div class="flex items-baseline justify-between gap-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Číslo účtu</dt>
                                <dd class="min-w-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['ucet']) ?></dd>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Částka</dt>
                            <dd class="min-w-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['castka']) ?></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Variabilní symbol</dt>
                            <dd class="min-w-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['vs']) ?></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="min-w-0 text-xs font-medium uppercase tracking-wide text-slate-500">Včasná platba do</dt>
                            <dd class="min-w-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['uzavierka']) ?></dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500">Poznámka pro příjemce</dt>
                            <dd class="min-w-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($platba['poznamka']) ?></dd>
                        </div>
                    </dl>

                    <?php if ($platba['na_miste'] !== ''): ?>
                        <p class="mt-4 text-sm leading-6 text-slate-500">
                            Na místě startu se platí <?= e($platba['na_miste']) ?>, takže včasná platba se vyplatí.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mt-8 border-t border-slate-200 pt-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Co bude dál</p>
                    <ol class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <li class="flex gap-3">
                            <span class="font-display font-bold tabular-nums text-brand-700">1</span>
                            <span>Uhraďte startovné<?= $platba['ucet'] === '' ? ' – způsob platby vám sdělí pořadatel' : ' podle instrukcí výše' ?>.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-display font-bold tabular-nums text-brand-700">2</span>
                            <span>Pořadatel platbu ověří. Do té doby platí přihláška jako nepřijatá.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="font-display font-bold tabular-nums text-brand-700">3</span>
                            <span>Před závodem zkontrolujte startovní listinu a propozice.</span>
                        </li>
                    </ol>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="/"
                       class="inline-flex items-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                        Zpět na úvod
                    </a>
                    <a href="/propozice"
                       class="inline-flex items-center rounded-md border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-900 transition-colors hover:border-slate-400 hover:bg-snow">
                        Propozice a trať
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
