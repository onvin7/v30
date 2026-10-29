<?php
$pageHeader = [
    'eyebrow'    => 'Ročník 2026',
    'title'      => 'Startovní listina',
    'lead'       => 'Závodníci 26. ročníku (17. ledna 2026) podle kategorií.',
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
        <nav aria-label="Kategorie" class="-mx-4 -mb-px flex gap-7 overflow-x-auto px-4 no-scrollbar scroll-fade sm:mx-0 sm:px-0">
            <?php foreach ($startovniListina as $index => $skupina): ?>
                <a href="#kategorie-<?= $index + 1 ?>"
                   class="whitespace-nowrap border-b-2 border-transparent py-4 text-sm font-medium text-slate-500 transition-colors hover:border-slate-300 hover:text-slate-900">
                    <?= e($skupina['kategorie']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>

<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
        <div class="border-l-2 border-slate-400 bg-snow px-5 py-4 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">Archivní startovní listina</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Tato listina patří k <?= e(zavod()['archiv']['cislo']) ?>. ročníku
                (<?= e(zavod()['archiv']['datum']) ?>). Startovní listina pro <?= e(zavod()['rocnik']['rok']) ?>
                zatím nebyla zveřejněna.
            </p>
        </div>
    </div>
</section>

<!-- Startovní listina -->
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">

        <p class="text-sm text-slate-500">
            Celkem <span class="font-semibold tabular-nums text-slate-900"><?= $celkemZavodniku ?></span> závodníků v
            <span class="font-semibold tabular-nums text-slate-900"><?= count($startovniListina) ?></span> kategoriích.
        </p>

        <!-- Filtr -->
        <div class="mt-8 space-y-4">
            <div>
                <label for="filter-text" class="sr-only">Hledat jméno nebo klub</label>
                <input type="text" id="filter-text" placeholder="Hledat jméno nebo klub"
                       class="block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" class="filter-chip active inline-flex items-center rounded-full border border-brand-600 bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700 transition-colors hover:bg-brand-100" data-filter="all">Vše</button>
                <button type="button" class="filter-chip inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50" data-filter="30km-muzi">Muži 30 km</button>
                <button type="button" class="filter-chip inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50" data-filter="30km-zeny">Ženy 30 km</button>
                <button type="button" class="filter-chip inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50" data-filter="deti">Děti</button>
                <button type="button" class="filter-chip inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50" data-filter="turistika">Turistická</button>
            </div>
        </div>

        <div class="mt-12 space-y-10 sm:space-y-14">
            <?php foreach ($startovniListina as $index => $skupina): ?>
                <div id="kategorie-<?= $index + 1 ?>" class="reveal" x-intersect.once="$el.classList.add('is-visible')">

                    <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2">
                        <h2 class="font-display text-xl font-extrabold tracking-tight sm:text-2xl"><?= e($skupina['kategorie']) ?></h2>
                        <p class="text-sm tabular-nums text-slate-500"><?= count($skupina['zavodnici']) ?> závodníků</p>
                    </div>

                    <!-- Mobil: záznamový seznam – tabulka potřebuje 34rem, do telefonu se nevejde -->
                    <ul class="mt-6 divide-y divide-slate-200 border-y border-slate-200 md:hidden">
                        <?php foreach ($skupina['zavodnici'] as $zavodnik): ?>
                            <li class="flex items-baseline gap-3 py-3.5"
                                data-jmeno="<?= e($zavodnik['jmeno']) ?>"
                                data-oddil="<?= e($zavodnik['oddil']) ?>"
                                data-kategorie="<?= e($skupina['kategorie']) ?>">
                                <span class="w-8 shrink-0 text-right font-mono text-base font-black tabular-nums text-brand-600"><?= $zavodnik['cislo'] !== '' ? e($zavodnik['cislo']) : '—' ?></span>
                                <span class="min-w-0">
                                    <span class="block font-semibold text-slate-900"><?= e($zavodnik['jmeno']) ?></span>
                                    <span class="mt-0.5 block text-sm text-slate-500"><?= $zavodnik['oddil'] !== '' ? e($zavodnik['oddil']) : '—' ?></span>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Tablet a výš: tabulka se od md: vždy vejde (34rem ≤ 720 px) -->
                    <div class="mt-6 hidden overflow-x-auto md:block">
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
                                    <tr class="transition-colors hover:bg-snow start-list-row"
                                        data-jmeno="<?= e($zavodnik['jmeno']) ?>"
                                        data-oddil="<?= e($zavodnik['oddil']) ?>"
                                        data-kategorie="<?= e($skupina['kategorie']) ?>">
                                        <td class="py-3.5 pr-4 text-right">
                                            <span class="font-mono text-base font-black tabular-nums text-brand-600"><?= $zavodnik['cislo'] !== '' ? e($zavodnik['cislo']) : '—' ?></span>
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
                Zveřejňujeme jen jméno, oddíl a startovní číslo. E-mail, telefon, datum narození ani stav platby
                veřejně neuvádíme.
            </p>
        </div>

    </div>
</section>

<script>
(function() {
    var filterText = document.getElementById('filter-text');
    var filterChips = document.querySelectorAll('.filter-chip');
    var rows = document.querySelectorAll('li.start-list-row, tr.start-list-row');

    // Stav filtru
    var currentCategory = 'all';
    var currentSearch = '';

    // Filtrování podle kategorie
    function filterByCategory(category) {
        currentCategory = category;
        applyFilter();
    }

    // Filtrování podle textu
    function filterByText(text) {
        currentSearch = text.toLowerCase();
        applyFilter();
    }

    // Aplikace filtru
    function applyFilter() {
        var visibleCount = 0;

        rows.forEach(function(row) {
            var jmeno = (row.dataset.jmeno || '').toLowerCase();
            var oddil = (row.dataset.oddil || '').toLowerCase();
            var kategorie = (row.dataset.kategorie || '').toLowerCase();

            // Kategorie
            var categoryMatch = currentCategory === 'all';
            if (!categoryMatch) {
                if (currentCategory === '30km-muzi' && kategorie.includes('muži') && !kategorie.includes('radost')) {
                    categoryMatch = true;
                } else if (currentCategory === '30km-zeny' && kategorie.includes('ženy') && !kategorie.includes('radost')) {
                    categoryMatch = true;
                } else if (currentCategory === 'deti' && (kategorie.includes('mládež') || kategorie.includes('junioři'))) {
                    categoryMatch = true;
                } else if (currentCategory === 'turistika' && kategorie.includes('radost')) {
                    categoryMatch = true;
                }
            }

            // Text
            var textMatch = currentSearch === '' || jmeno.includes(currentSearch) || oddil.includes(currentSearch);

            // Zobrazení
            if (categoryMatch && textMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Zpráva o prázdném výsledku
        var emptyMessage = document.getElementById('empty-message');
        if (visibleCount === 0 && !emptyMessage) {
            var section = document.querySelector('section.bg-white');
            var message = document.createElement('p');
            message.id = 'empty-message';
            message.className = 'mt-4 text-sm text-slate-500';
            message.textContent = 'Žádný závodník neodpovídá filtru.';
            section.appendChild(message);
        } else if (visibleCount > 0 && emptyMessage) {
            emptyMessage.remove();
        }
    }

    // Event listener na textové pole
    if (filterText) {
        filterText.addEventListener('input', function(e) {
            filterByText(e.target.value);
        });
    }

    // Event listener na chipy
    filterChips.forEach(function(chip) {
        chip.addEventListener('click', function() {
            var filter = this.dataset.filter;

            // Aktualizace aktivního chipu
            filterChips.forEach(function(c) {
                c.classList.remove('active', 'border-brand-600', 'bg-brand-50', 'text-brand-700');
                c.classList.add('border-slate-300', 'bg-white', 'text-slate-700');
            });
            this.classList.remove('border-slate-300', 'bg-white', 'text-slate-700');
            this.classList.add('active', 'border-brand-600', 'bg-brand-50', 'text-brand-700');

            filterByCategory(filter);
        });
    });
})();
</script>
