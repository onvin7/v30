<?php
/**
 * Žádost o účet oddílu.
 */
$errors     = flash_get('errors', []);
$inputClass = 'mt-2 block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm';
$labelClass = 'block text-sm font-medium leading-6 text-slate-900';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>
<div class="mx-auto max-w-2xl">
    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Zóna oddílu</p>
    <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Založení účtu oddílu</h1>
    <p class="mt-3 text-sm leading-6 text-slate-600">
        Účet slouží vedoucím oddílů a týmů. Po založení si do něj vložíte soupisku závodníků
        a příští ročník je přihlásíte jedním krokem. Účet zveřejníme až po schválení pořadatelem.
    </p>

    <form method="post" action="/oddil/registrace"
          class="mt-8 rounded-md border border-slate-200 bg-white p-6 sm:p-8"
          novalidate>
        <?= csrf_field() ?>

        <!-- Honeypot proti spamu (skrytý) -->
        <div class="hidden" aria-hidden="true">
            <label for="website">Web</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="space-y-6">

            <div>
                <label for="nazev" class="<?= $labelClass ?>">Název oddílu / týmu</label>
                <input type="text" id="nazev" name="nazev" value="<?= e(old_get('nazev')) ?>" required maxlength="100"
                       autocomplete="organization" enterkeyhint="next" class="<?= $inputClass ?>"
                       placeholder="Např. TJ KVT Pardubice">
                <?php if (isset($errors['nazev'])): ?>
                    <p class="<?= $errorClass ?>"><?= e($errors['nazev']) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="kontakt_jmeno" class="<?= $labelClass ?>">Kontaktní osoba</label>
                    <input type="text" id="kontakt_jmeno" name="kontakt_jmeno" value="<?= e(old_get('kontakt_jmeno')) ?>" required maxlength="100"
                           autocomplete="name" enterkeyhint="next" class="<?= $inputClass ?>">
                    <?php if (isset($errors['kontakt_jmeno'])): ?>
                        <p class="<?= $errorClass ?>"><?= e($errors['kontakt_jmeno']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="telefon" class="<?= $labelClass ?>">
                        Telefon <span class="font-normal text-slate-400">(nepovinné)</span>
                    </label>
                    <input type="tel" id="telefon" name="telefon" value="<?= e(old_get('telefon')) ?>" maxlength="30"
                           autocomplete="tel" inputmode="tel" enterkeyhint="next" class="<?= $inputClass ?>">
                    <?php if (isset($errors['telefon'])): ?>
                        <p class="<?= $errorClass ?>"><?= e($errors['telefon']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label for="email" class="<?= $labelClass ?>">E-mail</label>
                <input type="email" id="email" name="email" value="<?= e(old_get('email')) ?>" required
                       autocomplete="email" inputmode="email" enterkeyhint="next"
                       class="<?= $inputClass ?>" placeholder="vedouci@oddil.cz">
                <p class="mt-1.5 text-xs text-slate-500">Na tento e-mail se přihlašujete – je to zároveň přihlašovací jméno.</p>
                <?php if (isset($errors['email'])): ?>
                    <p class="<?= $errorClass ?>"><?= e($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="heslo" class="<?= $labelClass ?>">Heslo</label>
                    <input type="password" id="heslo" name="heslo" required minlength="8"
                           autocomplete="new-password" enterkeyhint="next" class="<?= $inputClass ?>">
                    <p class="mt-1.5 text-xs text-slate-500">Alespoň 8 znaků.</p>
                    <?php if (isset($errors['heslo'])): ?>
                        <p class="<?= $errorClass ?>"><?= e($errors['heslo']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="heslo2" class="<?= $labelClass ?>">Heslo znovu</label>
                    <input type="password" id="heslo2" name="heslo2" required minlength="8"
                           autocomplete="new-password" enterkeyhint="send" class="<?= $inputClass ?>">
                </div>
            </div>

            <div>
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="souhlas" value="1" <?= old_get('souhlas') === '1' ? 'checked' : '' ?> required
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                    <span class="text-sm leading-6 text-slate-600">
                        Souhlasím se zpracováním uvedených údajů pro účely závodu Vodácká 30.
                    </span>
                </label>
                <?php if (isset($errors['souhlas'])): ?>
                    <p class="<?= $errorClass ?>"><?= e($errors['souhlas']) ?></p>
                <?php endif; ?>
            </div>

        </div>

        <div class="mt-8 border-t border-slate-200 pt-6">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-brand-500 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-400">
                Odeslat žádost
            </button>
        </div>
    </form>

    <p class="mt-6 text-sm text-slate-500">
        Účet už máte?
        <a href="/oddil/prihlaseni" class="font-semibold text-brand-700 underline decoration-brand-300 underline-offset-2 transition-colors hover:text-brand-500">Přihlaste se</a>.
    </p>
</div>
