<?php
/**
 * Seznam příspěvků.
 * Očekává: $prispevky (pole příspěvků).
 */
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Novinky</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Příspěvky</h1>
        <p class="mt-2 text-sm text-slate-600">Příspěvky se zobrazují v sekci „Novinky" na úvodní stránce.</p>
    </div>
    <a href="/admin/prispevky/novy"
       class="inline-flex items-center rounded-md bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
        Nový příspěvek
    </a>
</div>

<?php if (empty($prispevky)): ?>
    <div class="mt-8 rounded-md border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-sm text-slate-500">Zatím tu nejsou žádné příspěvky.</p>
        <a href="/admin/prispevky/novy" class="mt-4 inline-block text-sm font-semibold text-brand-700 hover:text-brand-500">
            Vytvořit první příspěvek →
        </a>
    </div>
<?php else: ?>
    <ul class="mt-8 divide-y divide-slate-200 overflow-hidden rounded-md border border-slate-200 bg-white">
        <?php foreach ($prispevky as $p): ?>
            <li class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="truncate font-semibold text-slate-900"><?= e($p['nadpis'] ?? '') ?></span>
                        <?php if ((int) ($p['zverejneno'] ?? 0) === 1): ?>
                            <span class="shrink-0 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Zveřejněno</span>
                        <?php else: ?>
                            <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">Skryto</span>
                        <?php endif; ?>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">
                        <?= e($p['datum'] ?? '') ?>
                        <?php if (! empty($p['upraveno'])): ?> &middot; upraveno <?= e($p['upraveno']) ?><?php endif; ?>
                    </p>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <a href="/admin/prispevky/<?= (int) ($p['id'] ?? 0) ?>/upravit"
                       class="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition-colors hover:border-slate-400 hover:bg-slate-50">
                        Upravit
                    </a>
                    <form method="post" action="/admin/prispevky/<?= (int) ($p['id'] ?? 0) ?>/smazat"
                          onsubmit="return confirm('Opravdu smazat tento příspěvek?');">
                        <?= csrf_field() ?>
                        <button type="submit"
                                class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 transition-colors hover:border-red-300 hover:bg-red-50">
                            Smazat
                        </button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
