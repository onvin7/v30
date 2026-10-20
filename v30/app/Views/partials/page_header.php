<?php
/**
 * Sdílené tmavé záhlaví podstránky.
 *
 * Vstup (nastavuje šablona před `require`):
 *   $pageHeader = [
 *       'eyebrow'    => 'Běžecké lyžování · Orlické hory',
 *       'title'      => 'O závodu',
 *       'lead'       => 'Krátký perex…',
 *       'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'O závodu']],
 *       'image'      => 'about',   // klíč z config/images.php, nepovinné
 *   ];
 *
 * Když pro daný klíč není fotka, vykreslí se grafický blok s motivem vrstevnic.
 */
$eyebrow     = (string) ($pageHeader['eyebrow'] ?? '');
$headerTitle = (string) ($pageHeader['title'] ?? '');
$lead        = (string) ($pageHeader['lead'] ?? '');
$breadcrumb  = $pageHeader['breadcrumb'] ?? null;
$imageUrl    = isset($pageHeader['image']) ? img_url((string) $pageHeader['image']) : '';
$imageSrcset = isset($pageHeader['image']) ? img_srcset((string) $pageHeader['image'], [800, 1200, 1600]) : '';
?>
<section class="relative isolate overflow-hidden bg-night text-white">
    <?php if ($imageUrl !== ''): ?>
        <img src="<?= e($imageUrl) ?>" alt="" aria-hidden="true" decoding="async"
             <?php if ($imageSrcset !== ''): ?>srcset="<?= e($imageSrcset) ?>" sizes="100vw"<?php endif; ?>
             class="absolute inset-0 -z-20 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-gradient-to-tr from-night via-night/85 to-deep/60" aria-hidden="true"></div>
    <?php else: ?>
        <?php require __DIR__ . '/contours.php'; ?>
    <?php endif; ?>

    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">

        <?php if (is_array($breadcrumb) && $breadcrumb !== []): ?>
            <?php $lastIndex = count($breadcrumb) - 1; ?>
            <nav aria-label="Drobečková navigace" class="mb-7">
                <ol class="flex flex-wrap items-center gap-2 text-xs font-medium uppercase tracking-[0.18em]">
                    <?php foreach ($breadcrumb as $index => $crumb): ?>
                        <li class="flex items-center gap-2">
                            <?php if ($index < $lastIndex && !empty($crumb['href'])): ?>
                                <a href="<?= e($crumb['href']) ?>" class="text-white/45 transition-colors hover:text-white"><?= e($crumb['label']) ?></a>
                            <?php elseif ($index === $lastIndex): ?>
                                <span aria-current="page" class="text-white/70"><?= e($crumb['label']) ?></span>
                            <?php else: ?>
                                <span class="text-white/45"><?= e($crumb['label']) ?></span>
                            <?php endif; ?>
                            <?php if ($index < $lastIndex): ?>
                                <span class="text-white/20" aria-hidden="true">/</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>

        <?php if ($eyebrow !== ''): ?>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ice sm:tracking-[0.3em]"><?= e($eyebrow) ?></p>
        <?php endif; ?>

        <h1 class="mt-4 max-w-4xl font-display text-4xl font-black uppercase leading-[0.95] tracking-tight sm:text-5xl">
            <?= e($headerTitle) ?>
        </h1>

        <?php if ($lead !== ''): ?>
            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg"><?= e($lead) ?></p>
        <?php endif; ?>
    </div>
</section>
