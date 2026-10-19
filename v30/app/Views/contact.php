<!-- Kontakt -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Pořadatel</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Kontakt</h1>
        <p class="text-lg text-slate-600 max-w-3xl">Vodáckou 30 pořádá TJ KVT Pardubice.</p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <p class="text-sm font-semibold text-slate-600">Organizační výbor</p>
                <h2 class="font-display text-2xl font-extrabold text-slate-900 mt-1 mb-4">Potřebujete se na něco zeptat?</h2>
                <dl class="divide-y divide-slate-200">
                    <?php foreach ($kontakty as $kontakt): ?>
                        <div class="py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500"><?= e($kontakt['role']) ?></dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-900">
                                <?= e($kontakt['jmeno']) ?>
                                <?php if (!empty($kontakt['telefon'])): ?>
                                    <span class="font-normal text-slate-600">&middot; <?= e($kontakt['telefon']) ?></span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
                <p class="text-sm leading-6 text-slate-600 mt-4">
                    Aktuální kontakty na organizační výbor, ředitele závodu a hlavního rozhodčího zveřejňuje pořadatel
                    na původním webu.
                </p>
                <a href="https://vodacka30.cz/index.php?menu=5" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-5 py-3 transition-colors mt-4">Kontakty pořadatele ↗</a>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                <p class="px-6 sm:px-8 py-4 text-sm font-semibold text-slate-600 border-b border-slate-200">Další informace</p>
                <div class="divide-y divide-slate-200 px-6 sm:px-8">
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="/galerie">
                        Fotogalerie závodu <span aria-hidden="true">→</span>
                    </a>
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://kvt.pardubice.cz/" target="_blank" rel="noopener noreferrer">
                        TJ KVT Pardubice ↗
                    </a>
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="/propozice">
                        Propozice a trať <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
