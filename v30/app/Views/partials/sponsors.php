<?php
$partneri = zavod()['partneri'];
$hasPartners = !empty($partneri['generalni']) || !empty($partneri['ceny']) || !empty($partneri['instituce']);
?>

<?php if ($hasPartners): ?>
<!-- Partneři nad patičkou -->
<section class="border-t border-slate-200 bg-snow">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-3 border-b border-slate-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-normal text-brand-700">Partneři závodu</p>
                <h2 class="mt-2 font-display text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Díky, že stojí za námi</h2>
            </div>
            <p class="max-w-sm text-sm leading-6 text-slate-500">Bez jejich podpory by se Vodácká 30 nemohla konat.</p>
        </div>

        <?php if (!empty($partneri['generalni'])): ?>
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <?php foreach ($partneri['generalni'] as $partner): ?>
                <a href="<?= e($partner['odkaz']) ?>" target="_blank" rel="noopener noreferrer"
                   class="group flex min-h-40 flex-col items-center justify-center gap-3 rounded-md border border-brand-200 bg-white p-6 transition-colors hover:border-brand-500 hover:bg-brand-50">
                    <?php if (has_img($partner['logo'])): ?>
                        <img src="<?= e(img_url($partner['logo'])) ?>"
                             alt="<?= e($partner['nazev']) ?>"
                             loading="lazy"
                             class="h-20 w-auto max-w-full object-contain transition-[opacity] group-hover:opacity-80">
                    <?php else: ?>
                        <span class="text-lg font-medium text-slate-900"><?= e($partner['nazev']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($partner['claim'])): ?>
                        <p class="text-center text-xs font-medium text-slate-500"><?= e($partner['claim']) ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php $ostatniPartneri = array_merge($partneri['ceny'] ?? [], $partneri['instituce'] ?? []); ?>
        <?php if (!empty($ostatniPartneri)): ?>
        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            <?php foreach ($ostatniPartneri as $partner): ?>
                <a href="<?= e($partner['odkaz']) ?>" target="_blank" rel="noopener noreferrer"
                   class="group flex min-h-24 items-center justify-center rounded-md border border-slate-200 bg-white p-4 transition-colors hover:border-brand-300 hover:bg-brand-50">
                    <?php if (has_img($partner['logo'])): ?>
                        <img src="<?= e(img_url($partner['logo'])) ?>"
                             alt="<?= e($partner['nazev']) ?>"
                             loading="lazy"
                             class="<?= in_array($partner['logo'], ['partner-horska-sluzba', 'partner-chko'], true) ? 'rounded-md bg-night p-2' : '' ?> h-12 w-auto max-w-full object-contain transition-[opacity] group-hover:opacity-80">
                    <?php else: ?>
                        <span class="text-center text-xs font-semibold text-slate-600"><?= e($partner['nazev']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
