<!-- Galerie -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Fotografie ze závodu</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Galerie</h1>
        <p class="text-lg text-slate-600 max-w-3xl">
            Fotografie jsou členěné podle ročníků. Kompletní alba odkazujeme do externí galerie pořadatele.
        </p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">

        <!-- Alba podle ročníků -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <?php foreach ($alba as $album): ?>
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden flex flex-col">
                    <img
                        src="<?= e($album['nahled']) ?>"
                        alt="<?= e($album['nazev']) ?>"
                        loading="lazy"
                        class="w-full h-48 object-cover border-b border-slate-200">
                    <div class="p-6 sm:p-8 flex flex-col flex-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Ročník <?= e($album['rok']) ?></p>
                        <h2 class="font-display text-xl font-extrabold text-slate-900 mt-1 mb-2"><?= e($album['nazev']) ?></h2>
                        <p class="text-sm leading-6 text-slate-600 mb-4 flex-1"><?= e($album['popis']) ?></p>
                        <div class="border-t border-slate-200 pt-4">
                            <p class="text-xs text-slate-500 mb-3">Fotograf: <?= e($album['fotograf']) ?></p>
                            <a href="<?= e($album['odkaz']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-md bg-white border border-slate-300 hover:bg-slate-50 text-slate-900 text-sm font-semibold px-4 py-2.5 transition-colors">
                                Otevřít album ↗
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Fotografie z ročníku 2026 -->
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Ročník 2026</p>
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 mb-6">Z průběhu závodu</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <?php foreach ($fotky as $foto): ?>
                    <img
                        src="<?= e($foto['src']) ?>"
                        alt="<?= e($foto['alt']) ?>"
                        loading="lazy"
                        class="w-full aspect-square object-cover rounded-lg border border-slate-200">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white border border-slate-200 border-l-4 border-l-orange-600 rounded-lg p-6 sm:p-8">
            <p class="text-sm font-semibold text-slate-600">Archiv fotografií</p>
            <p class="text-sm leading-6 text-slate-600 mt-2 max-w-3xl">
                Starší alba doplníme po ověření práv k fotografiím a pravidel pro snímky dětí. Fotografie se do nového
                webu nahrávají až po schválení pořadatelem.
            </p>
        </div>

    </div>
</section>
