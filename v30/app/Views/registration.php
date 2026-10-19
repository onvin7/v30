<!-- Přihlášky -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Další ročník &middot; 2027</p>
        <h1 class="font-display text-4xl sm:text-5xl font-extrabold uppercase tracking-tight text-slate-900 mb-5">Přihlášky</h1>
        <p class="text-lg text-slate-600 max-w-3xl">
            Vyplňte přihlášku do běžkařské Vodácké 30. Lodní kategorie ze staršího formuláře se do tohoto závodu nepřebírají.
        </p>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <!-- Upozornění na ukázkový režim -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-orange-600 rounded-lg p-6">
            <p class="text-sm font-semibold text-slate-600">Zkušební režim</p>
            <p class="text-sm leading-6 text-slate-600 mt-1">
                Registrace ročníku 2027 zatím není potvrzená pořadatelem. Odeslaná přihláška se v této fázi ukládá pouze
                do relace prohlížeče a neslouží jako závazná registrace.
            </p>
        </div>

        <!-- Formulář -->
        <form method="post" action="/prihlaska" class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8 space-y-6" novalidate>
            <?= csrf_field() ?>

            <!-- Honeypot proti spamu (skrytý) -->
            <div class="hidden" aria-hidden="true">
                <label for="website">Web</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <!-- Kategorie -->
            <div>
                <label for="kategorie" class="block text-sm font-medium leading-6 text-slate-900">Kategorie</label>
                <select id="kategorie" name="kategorie" required
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                    <option value="">Vyberte kategorii</option>
                    <?php foreach ($kategorie as $kod => $kat): ?>
                        <option value="<?= e($kod) ?>" <?= old_get('kategorie') === $kod ? 'selected' : '' ?>>
                            <?= e($kat['nazev']) ?> (<?= e($kat['trat']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['kategorie'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= e($errors['kategorie']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Oddíl -->
            <div>
                <label for="oddil" class="block text-sm font-medium leading-6 text-slate-900">Oddíl / tým</label>
                <input type="text" id="oddil" name="oddil" value="<?= e(old_get('oddil')) ?>" required maxlength="100"
                       class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"
                       placeholder="Např. TJ KVT Pardubice">
                <?php if (isset($errors['oddil'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= e($errors['oddil']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Jméno a příjmení -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="jmeno" class="block text-sm font-medium leading-6 text-slate-900">Jméno</label>
                    <input type="text" id="jmeno" name="jmeno" value="<?= e(old_get('jmeno')) ?>" required maxlength="60"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                    <?php if (isset($errors['jmeno'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= e($errors['jmeno']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="prijmeni" class="block text-sm font-medium leading-6 text-slate-900">Příjmení</label>
                    <input type="text" id="prijmeni" name="prijmeni" value="<?= e(old_get('prijmeni')) ?>" required maxlength="60"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6">
                    <?php if (isset($errors['prijmeni'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= e($errors['prijmeni']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ročník narození a e-mail -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="rocnik_narozeni" class="block text-sm font-medium leading-6 text-slate-900">Ročník narození</label>
                    <input type="text" id="rocnik_narozeni" name="rocnik_narozeni" value="<?= e(old_get('rocnik_narozeni')) ?>" required inputmode="numeric" maxlength="4"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"
                           placeholder="Např. 1985">
                    <?php if (isset($errors['rocnik_narozeni'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= e($errors['rocnik_narozeni']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-slate-900">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= e(old_get('email')) ?>" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"
                           placeholder="jmeno@email.cz">
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= e($errors['email']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Poznámka -->
            <div>
                <label for="poznamka" class="block text-sm font-medium leading-6 text-slate-900">Poznámka <span class="text-slate-400 font-normal">(nepovinné)</span></label>
                <textarea id="poznamka" name="poznamka" rows="3" maxlength="500"
                          class="mt-2 block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-orange-600 sm:text-sm sm:leading-6"><?= e(old_get('poznamka')) ?></textarea>
            </div>

            <!-- Souhlas -->
            <div>
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="souhlas" value="1" <?= old_get('souhlas') === '1' ? 'checked' : '' ?> required
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 text-orange-600 focus:ring-orange-600">
                    <span class="text-sm leading-6 text-slate-600">
                        Souhlasím se zpracováním uvedených osobních údajů pro účely závodu Vodácká 30.
                    </span>
                </label>
                <?php if (isset($errors['souhlas'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= e($errors['souhlas']) ?></p>
                <?php endif; ?>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <button type="submit" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold px-6 py-3 transition-colors">
                    Odeslat přihlášku
                </button>
            </div>
        </form>

        <p class="text-sm text-slate-600">
            Aktuální stav podmínek závodu najdete v <a href="/propozice" class="font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4 hover:text-orange-700">propozicích</a>.
        </p>

    </div>
</section>
