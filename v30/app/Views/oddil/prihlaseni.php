<?php
/**
 * Přihlášení oddílu.
 */
$oldEmail = old_get('email');
$inputClass = 'mt-2 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-400/40';
$labelClass = 'block text-sm font-semibold text-slate-700';
?>
<div class="mx-auto max-w-sm">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
    <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Přihlášení</h1>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        Přihlaste se účtem oddílu a přihlaste svoje závodníky hromadně.
    </p>

    <form method="post" action="/oddil/prihlaseni" class="mt-8 space-y-5">
        <?= csrf_field() ?>

        <!-- Honeypot – skryté pole, které lidé nevyplní. -->
        <div class="hidden" aria-hidden="true">
            <label for="website">Web</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div>
            <label for="email" class="<?= $labelClass ?>">E-mail</label>
            <input type="email" id="email" name="email" value="<?= e($oldEmail) ?>"
                   autocomplete="username" inputmode="email" enterkeyhint="next" required autofocus
                   class="<?= $inputClass ?>">
        </div>

        <div>
            <label for="heslo" class="<?= $labelClass ?>">Heslo</label>
            <input type="password" id="heslo" name="heslo"
                   autocomplete="current-password" enterkeyhint="send" required
                   class="<?= $inputClass ?>">
        </div>

        <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-md bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-500">
            Přihlásit se
        </button>
    </form>

    <div class="mt-8 border-t border-slate-200 pt-6">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Ještě nemáte účet?</p>
        <a href="/oddil/registrace" class="mt-3 inline-block text-sm font-semibold text-brand-700 transition-colors hover:text-brand-500">
            Založit účet oddílu →
        </a>
        <p class="mt-3 text-xs leading-5 text-slate-500">
            Účet vzniká na základě žádosti a zpřístupníme ho po schválení pořadatelem.
        </p>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">
        <a href="/" class="transition-colors hover:text-slate-600">← Zpět na web</a>
    </p>
</div>
