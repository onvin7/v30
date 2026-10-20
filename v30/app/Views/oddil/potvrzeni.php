<?php
/**
 * Potvrzení odeslané přihlášky.
 * Očekává: $prihlaska, $platba, $oddil.
 */
?>
<div class="mx-auto max-w-3xl">
    <div class="flex items-start gap-4">
        <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700" aria-hidden="true">
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 0 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/></svg>
        </span>
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
            <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Přihláška byla odeslána</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                Děkujeme. Za oddíl <span class="font-semibold text-slate-900"><?= e($oddil['nazev'] ?? '') ?></span>
                jsme přijali přihlášku. Stejné údaje vám odešleme i e-mailem.
            </p>
        </div>
    </div>

    <div class="mt-8">
        <?php require __DIR__ . '/partials/rozpis.php'; ?>
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="/oddil"
           class="inline-flex items-center rounded-md bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
            Zpět na nástěnku
        </a>
        <a href="/oddil/prihlaska"
           class="inline-flex items-center rounded-md border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-900 transition-colors hover:border-slate-400 hover:bg-white">
            Přihlásit další závodníky
        </a>
    </div>
</div>
