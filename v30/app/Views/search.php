<?php
$pageHeader = [
    'eyebrow'    => 'Veřejný archiv',
    'title'      => 'Hledat závodníka',
    'lead'       => 'Najděte jméno nebo oddíl v ověřených veřejných výsledcích.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Hledat závodníka']],
];
require __DIR__ . '/partials/page_header.php';
?>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <form method="get" action="/zavodnici" class="rounded-md border border-slate-200 bg-white p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 sm:items-end">
                <div class="sm:col-span-2">
                    <label for="q" class="block text-sm font-medium leading-6 text-slate-900">Jméno nebo oddíl</label>
                    <input type="search" id="q" name="q" value="<?= e($query) ?>" minlength="2" class="mt-2 block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm" placeholder="Např. Novák nebo TJ KVT">
                </div>
                <div>
                    <label for="rok" class="block text-sm font-medium leading-6 text-slate-900">Ročník</label>
                    <select id="rok" name="rok" class="mt-2 block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm">
                        <option value="">Všechny ročníky</option>
                        <?php foreach ($years as $availableYear): ?>
                            <option value="<?= e($availableYear) ?>" <?= $year === (string) $availableYear ? 'selected' : '' ?>><?= e($availableYear) ?> · ověřeno</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-6 inline-flex min-h-[44px] items-center justify-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">Hledat</button>
        </form>

        <?php if ($error !== ''): ?>
            <p class="mt-8 border-l-2 border-red-600 bg-red-50 px-5 py-4 text-sm text-red-900" role="alert"><?= e($error) ?></p>
        <?php elseif ($query !== '' || $year !== ''): ?>
            <p class="mt-8 text-sm text-slate-500">Nalezeno <span class="font-semibold tabular-nums text-slate-900"><?= count($rows) ?></span> záznamů.</p>
            <?php if ($rows === []): ?>
                <p class="mt-4 border-l-2 border-brand-600 bg-snow px-5 py-4 text-sm text-slate-600">Nic jsme nenašli. Zkuste celé jméno, část oddílu nebo jiný ročník.</p>
            <?php else: ?>
                <div class="mt-8 overflow-x-auto">
                    <table class="w-full min-w-[42rem] text-sm">
                        <caption class="sr-only">Výsledky hledání závodníků</caption>
                        <thead>
                            <tr class="border-b border-slate-300 text-xs font-medium uppercase tracking-wider text-slate-500">
                                <th class="py-3 pr-4 text-left">Ročník</th>
                                <th class="py-3 pr-4 text-left">Zdroj</th>
                                <th class="py-3 pr-4 text-left">Závodník</th>
                                <th class="py-3 pr-4 text-left">Oddíl / tým</th>
                                <th class="py-3 pr-4 text-left">Kategorie</th>
                                <th class="py-3 pr-4 text-right">Start</th>
                                <th class="py-3 text-right">Výsledek</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 border-b border-slate-200">
                            <?php foreach ($rows as $radek): ?>
                                <tr class="transition-colors hover:bg-snow">
                                    <td class="py-3.5 pr-4 tabular-nums text-slate-500"><?= e($radek['rok']) ?></td>
                                    <td class="py-3.5 pr-4 text-slate-500">Výsledky</td>
                                    <td class="py-3.5 pr-4 font-semibold text-slate-900"><?= e($radek['jmeno']) ?></td>
                                    <td class="py-3.5 pr-4 text-slate-500"><?= $radek['oddil'] !== '' ? e($radek['oddil']) : '—' ?></td>
                                    <td class="py-3.5 pr-4 text-slate-500"><?= e($radek['kategorie']) ?></td>
                                    <td class="py-3.5 pr-4 text-right tabular-nums text-slate-500"><?= $radek['cislo'] !== '' ? e($radek['cislo']) : '—' ?></td>
                                    <td class="py-3.5 text-right tabular-nums text-slate-500">
                                        <?= $radek['poradi'] !== '' ? e($radek['poradi']) . '. ' . e($radek['cas']) : '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="mt-12 border-l-2 border-brand-600 bg-snow px-5 py-5 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Co zveřejňujeme</p>
            <p class="mt-3 text-sm leading-6 text-slate-600">Jen ročník závodu, jméno, oddíl, kategorie, startovní číslo a u ověřených výsledků pořadí s časem. Kontaktní údaje, ročník narození, poznámky, platby ani interní ID se nezobrazují.</p>
        </div>
    </div>
</section>
