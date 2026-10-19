<!-- O závodu -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Běžecké lyžování &middot; Orlické hory</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">O závodu</h1>
        <p class="text-lg text-slate-600 max-w-3xl">
            Vodácká 30 je zimní běžecký závod po hřebenech Orlických hor, který pořádá TJ KVT Pardubice.
        </p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <p class="text-sm font-semibold text-slate-600 mb-2">Vodácké mistrovství ČR v běhu na lyžích</p>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4">Závod na horách, pořádaný vodáky.</h2>
                <p class="text-sm leading-6 text-slate-600 mb-4">
                    Název odkazuje na pořadatelský klub; hlavní závod se jede na běžkách. V roce 2026 vedla dospělá trať
                    na 30 km, dětské závody měly tratě 3 a 7 km.
                </p>
                <p class="text-sm leading-6 text-slate-600">
                    Start a cíl ročníku 2026 byly u chaty Bedřichovka v Orlickém Záhoří. Tyto údaje popisují minulý
                    ročník a nepotvrzují podobu závodu 2027.
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <p class="text-sm font-semibold text-slate-600">Ročník 2026</p>
                <dl class="mt-4 divide-y divide-slate-200">
                    <?php foreach ($fakta as $fakt): ?>
                        <div class="py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($fakt['label']) ?></dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900"><?= e($fakt['hodnota']) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>

        </div>

        <div class="flex flex-wrap gap-3 mt-8">
            <a href="/propozice" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-3 transition-colors">Propozice</a>
            <a href="/vysledky" class="inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-5 py-3 transition-colors">Výsledky 2026</a>
        </div>
    </div>
</section>
