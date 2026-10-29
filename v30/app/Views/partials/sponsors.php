<?php
$partneri = zavod()['partneri'];
$hasPartners = !empty($partneri['generalni']) || !empty($partneri['ceny']) || !empty($partneri['instituce']);
?>

<?php if ($hasPartners): ?>
<!-- Partneři nad patičkou -->
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">

        <?php if (!empty($partneri['generalni'])): ?>
        <!-- Generální / hlavní partneři -->
        <div class="mb-10">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 mb-6">Hlavní partneři</p>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($partneri['generalni'] as $partner): ?>
                    <a href="<?= e($partner['odkaz']) ?>" target="_blank" rel="noopener noreferrer"
                       class="group flex flex-col items-center gap-3 rounded-md border border-slate-200 bg-snow p-6 transition-colors hover:border-brand-300 hover:bg-white">
                        <?php if (has_img($partner['logo'])): ?>
                            <img src="<?= e(img_url($partner['logo'])) ?>"
                                 alt="<?= e($partner['nazev']) ?>"
                                 loading="lazy"
                                 class="h-16 w-auto object-contain transition-transform group-hover:scale-105">
                        <?php else: ?>
                            <span class="text-lg font-bold text-slate-900"><?= e($partner['nazev']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($partner['claim'])): ?>
                            <p class="text-center text-xs text-slate-500"><?= e($partner['claim']) ?></p>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($partneri['ceny'])): ?>
        <!-- Dodavatelé cen -->
        <div class="mb-10">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 mb-6">Dodavatelé cen</p>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                <?php foreach ($partneri['ceny'] as $partner): ?>
                    <a href="<?= e($partner['odkaz']) ?>" target="_blank" rel="noopener noreferrer"
                       class="group flex items-center justify-center rounded-md border border-slate-200 bg-snow p-4 transition-colors hover:border-brand-300 hover:bg-white">
                        <?php if (has_img($partner['logo'])): ?>
                            <img src="<?= e(img_url($partner['logo'])) ?>"
                                 alt="<?= e($partner['nazev']) ?>"
                                 loading="lazy"
                                 class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                        <?php else: ?>
                            <span class="text-xs font-semibold text-slate-600"><?= e($partner['nazev']) ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($partneri['instituce'])): ?>
        <!-- Instituce -->
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 mb-6">Instituce</p>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                <?php foreach ($partneri['instituce'] as $partner): ?>
                    <a href="<?= e($partner['odkaz']) ?>" target="_blank" rel="noopener noreferrer"
                       class="group flex items-center justify-center rounded-md border border-slate-200 bg-snow p-3 transition-colors hover:border-brand-300 hover:bg-white">
                        <?php if (has_img($partner['logo'])): ?>
                            <img src="<?= e(img_url($partner['logo'])) ?>"
                                 alt="<?= e($partner['nazev']) ?>"
                                 loading="lazy"
                                 class="h-10 w-auto object-contain transition-transform group-hover:scale-105">
                        <?php else: ?>
                            <span class="text-xs font-semibold text-slate-600"><?= e($partner['nazev']) ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>
