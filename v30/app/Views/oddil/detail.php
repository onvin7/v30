<?php
/**
 * Detail dřívější přihlášky oddílu.
 * Očekává: $prihlaska, $platba, $oddil.
 */
$zobrazStav = true;
?>
<div class="mx-auto max-w-3xl">
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Detail přihlášky</h1>
        <p class="mt-2 text-sm text-slate-600">
            Přehled přihlášky za oddíl <span class="font-semibold text-slate-900"><?= e($oddil['nazev'] ?? '') ?></span>.
        </p>
    </div>

    <div class="mt-8">
        <?php require __DIR__ . '/partials/rozpis.php'; ?>
    </div>

    <div class="mt-8">
        <a href="/oddil" class="inline-flex items-center text-sm font-semibold text-brand-700 transition-colors hover:text-brand-500">
            ← Zpět na nástěnku
        </a>
    </div>
</div>
