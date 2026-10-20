<?php
/**
 * Přihlášení do administrace.
 */
$oldUzivatel = old_get('uzivatel');
?>
<div class="mx-auto max-w-sm">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Administrace</p>
    <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Přihlášení</h1>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        Zadejte přístupové údaje pořadatele.
    </p>

    <form method="post" action="/admin/prihlaseni" class="mt-8 space-y-5">
        <?= csrf_field() ?>

        <!-- Honeypot – skryté pole, které lidé nevyplní. -->
        <div class="hidden" aria-hidden="true">
            <label for="website">Web</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div>
            <label for="uzivatel" class="block text-sm font-semibold text-slate-700">Jméno</label>
            <input type="text" id="uzivatel" name="uzivatel" value="<?= e($oldUzivatel) ?>"
                   autocomplete="username" required autofocus
                   class="mt-2 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40">
        </div>

        <div>
            <label for="heslo" class="block text-sm font-semibold text-slate-700">Heslo</label>
            <input type="password" id="heslo" name="heslo"
                   autocomplete="current-password" required
                   class="mt-2 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40">
        </div>

        <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-md bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
            Přihlásit se
        </button>
    </form>

    <p class="mt-6 text-center text-xs text-slate-400">
        <a href="/" class="transition-colors hover:text-slate-600">← Zpět na web</a>
    </p>
</div>
