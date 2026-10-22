<?php
$pageHeader = [
    'eyebrow'    => 'Další ročník · ' . zavod()['rocnik']['rok'],
    'title'      => 'Přihlášky',
    'lead'       => 'Vyplňte přihlášku do běžkařské Vodácké 30.',
    'breadcrumb' => [['label' => 'Úvod', 'href' => '/'], ['label' => 'Přihlášky']],
    'image'      => 'header-registration',
];
require __DIR__ . '/partials/page_header.php';

$inputClass = 'mt-2 block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-brand-600 sm:text-sm';
$labelClass = 'block text-sm font-medium leading-6 text-slate-900';
$errorClass = 'mt-1.5 text-sm text-red-600';
?>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-16">

            <!-- Formulář -->
            <div class="reveal lg:col-span-7" x-intersect.once="$el.classList.add('is-visible')">
                <form method="post" action="/prihlaska"
                      class="rounded-md border border-slate-200 bg-white p-6 sm:p-8"
                      novalidate>
                    <?= csrf_field() ?>

                    <!-- Honeypot proti spamu (skrytý) -->
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Web</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="space-y-6">

                        <!-- Kategorie -->
                        <div>
                            <label for="kategorie" class="<?= $labelClass ?>">Kategorie</label>
                            <select id="kategorie" name="kategorie" required class="<?= $inputClass ?>">
                                <option value="">Vyberte kategorii</option>
                                <?php foreach ($kategorie as $kod => $kat): ?>
                                    <option value="<?= e($kod) ?>" <?= old_get('kategorie') === $kod ? 'selected' : '' ?>>
                                        <?= e($kat['nazev']) ?> (<?= e($kat['trat']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['kategorie'])): ?>
                                <p class="<?= $errorClass ?>"><?= e($errors['kategorie']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Oddíl -->
                        <div>
                            <label for="oddil" class="<?= $labelClass ?>">Oddíl / tým</label>
                            <input type="text" id="oddil" name="oddil" value="<?= e(old_get('oddil')) ?>" required maxlength="100"
                                   autocomplete="organization" class="<?= $inputClass ?>" placeholder="Např. TJ KVT Pardubice">
                            <?php if (isset($errors['oddil'])): ?>
                                <p class="<?= $errorClass ?>"><?= e($errors['oddil']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Jméno a příjmení -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="jmeno" class="<?= $labelClass ?>">Jméno</label>
                                <input type="text" id="jmeno" name="jmeno" value="<?= e(old_get('jmeno')) ?>" required maxlength="60"
                                       autocomplete="given-name" enterkeyhint="next" class="<?= $inputClass ?>">
                                <?php if (isset($errors['jmeno'])): ?>
                                    <p class="<?= $errorClass ?>"><?= e($errors['jmeno']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="prijmeni" class="<?= $labelClass ?>">Příjmení</label>
                                <input type="text" id="prijmeni" name="prijmeni" value="<?= e(old_get('prijmeni')) ?>" required maxlength="60"
                                       autocomplete="family-name" enterkeyhint="next" class="<?= $inputClass ?>">
                                <?php if (isset($errors['prijmeni'])): ?>
                                    <p class="<?= $errorClass ?>"><?= e($errors['prijmeni']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Ročník narození a e-mail -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="rocnik_narozeni" class="<?= $labelClass ?>">Ročník narození</label>
                                <input type="text" id="rocnik_narozeni" name="rocnik_narozeni" value="<?= e(old_get('rocnik_narozeni')) ?>" required inputmode="numeric" autocomplete="bday-year" enterkeyhint="next" maxlength="4"
                                       class="<?= $inputClass ?>" placeholder="Např. 1985">
                                <?php if (isset($errors['rocnik_narozeni'])): ?>
                                    <p class="<?= $errorClass ?>"><?= e($errors['rocnik_narozeni']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="email" class="<?= $labelClass ?>">E-mail</label>
                                <input type="email" id="email" name="email" value="<?= e(old_get('email')) ?>" required
                                       autocomplete="email" inputmode="email" enterkeyhint="next"
                                       class="<?= $inputClass ?>" placeholder="jmeno@email.cz">
                                <?php if (isset($errors['email'])): ?>
                                    <p class="<?= $errorClass ?>"><?= e($errors['email']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Poznámka -->
                        <div>
                            <label for="poznamka" class="<?= $labelClass ?>">
                                Poznámka <span class="font-normal text-slate-400">(nepovinné)</span>
                            </label>
                            <textarea id="poznamka" name="poznamka" rows="3" maxlength="500"
                                      class="<?= $inputClass ?>"><?= e(old_get('poznamka')) ?></textarea>
                        </div>

                        <!-- Souhlas -->
                        <div>
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="souhlas" value="1" <?= old_get('souhlas') === '1' ? 'checked' : '' ?> required
                                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600">
                                <span class="text-sm leading-6 text-slate-600">
                                    Souhlasím se zpracováním uvedených osobních údajů pro účely závodu Vodácká 30.
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
                            Odeslat přihlášku
                        </button>
                    </div>
                </form>
            </div>

            <!-- Doplňující informace -->
            <aside class="reveal lg:col-span-5" x-intersect.once="$el.classList.add('is-visible')">
                <div class="lg:sticky lg:top-24 space-y-10">

                    <div class="border-l-2 border-brand-600 bg-snow px-5 py-5 sm:px-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-700">Přihlašujete víc závodníků?</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Založte si účet oddílu, uložte soupisku a přihlaste celý oddíl jednou přihláškou
                            s jedním variabilním symbolem. Účet zpřístupníme po schválení pořadatelem.
                        </p>
                        <a href="/oddil/registrace" class="mt-4 inline-flex items-center text-sm font-semibold text-brand-700 transition-colors hover:text-brand-500">
                            Založit účet oddílu →
                        </a>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Startovné</p>
                        <dl class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                            <div class="flex items-baseline justify-between gap-4 py-3.5">
                                <dt class="text-sm text-slate-600">Dětské kategorie</dt>
                                <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($startovne['detske']) ?> Kč</dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-4 py-3.5">
                                <dt class="min-w-0 text-sm text-slate-600">Dospělí (včasná platba do <?= e($startovne['uzavierka']) ?>)</dt>
                                <dd class="shrink-0 text-sm font-semibold tabular-nums text-slate-900"><?= e($startovne['vcasna']) ?> Kč</dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-4 py-3.5">
                                <dt class="text-sm text-slate-600">Platba na místě</dt>
                                <dd class="text-sm font-semibold tabular-nums text-slate-900"><?= e($startovne['na_miste']) ?> Kč</dd>
                            </div>
                        </dl>
                        <p class="mt-4 text-sm text-slate-500">
                            Podmínky závodu najdete v
                            <a href="/propozice" class="font-semibold text-brand-700 underline decoration-brand-300 underline-offset-2 transition-colors hover:text-brand-500">propozicích</a>.
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Nejste si jistí?</p>
                        <div class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                            <a href="/propozice" class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                                Propozice a trať
                                <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">→</span>
                            </a>
                            <a href="/kontakt" class="group flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 transition-colors hover:text-brand-600">
                                Kontakt na pořadatele
                                <span aria-hidden="true" class="text-slate-400 transition-colors group-hover:text-brand-600">→</span>
                            </a>
                        </div>
                    </div>

                </div>
            </aside>

        </div>
    </div>
</section>
