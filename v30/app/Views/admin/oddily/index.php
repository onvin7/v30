<?php
/**
 * Seznam účtů oddílů v administraci.
 * Očekává: $oddily, $resetHeslo.
 */
$stavPopis = [
    'cekajici'  => ['label' => 'Čeká na schválení', 'class' => 'bg-amber-100 text-amber-800'],
    'schvaleny' => ['label' => 'Schváleno',         'class' => 'bg-emerald-100 text-emerald-800'],
    'zamitnuty' => ['label' => 'Zamítnuto',         'class' => 'bg-slate-100 text-slate-500'],
];

$cekajici = count(array_filter($oddily, static fn ($o) => ($o['stav'] ?? '') === 'cekajici'));

$tlacitko = 'rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition-colors hover:border-slate-400 hover:bg-slate-50';
?>
<div class="flex flex-wrap items-end justify-between gap-4">
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-brand-700">Administrace</p>
        <h1 class="mt-3 font-display text-2xl font-extrabold tracking-tight sm:text-3xl">Oddíly</h1>
        <p class="mt-2 text-sm text-slate-600">
            Účty oddílů zakládají vedoucí sami. Vy je schvalujete; do té doby se nikdo nepřihlásí.
            <?php if ($cekajici > 0): ?>
                <span class="font-semibold text-amber-700">Na schválení čeká <?= (int) $cekajici ?>.</span>
            <?php endif; ?>
        </p>
    </div>

    <form method="post" action="/admin/oddily/import">
        <?= csrf_field() ?>
        <button type="submit" class="<?= $tlacitko ?>"
                title="Naplní soupisky existujících účtů závodníky z 26. ročníku">
            Importovat soupisky 26. ročníku
        </button>
    </form>
</div>

<?php if (is_array($resetHeslo) && ! empty($resetHeslo['heslo'])): ?>
    <div class="mt-6 rounded-md border-l-2 border-brand-600 bg-brand-50 px-5 py-4">
        <p class="text-sm font-semibold text-brand-900">Nové heslo pro oddíl „<?= e($resetHeslo['oddil'] ?? '') ?>“</p>
        <p class="mt-2 font-mono text-lg font-bold tracking-wider text-brand-900"><?= e($resetHeslo['heslo']) ?></p>
        <p class="mt-2 text-xs text-brand-900/70">
            Zkopírujte ho teď – znovu se nezobrazí. Oddílu ho zatím musíte předat mimo web.
        </p>
    </div>
<?php endif; ?>

<?php if (empty($oddily)): ?>
    <div class="mt-8 rounded-md border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
        <p class="text-sm text-slate-500">Zatím není založený žádný účet oddílu.</p>
    </div>
<?php else: ?>
    <ul class="mt-8 space-y-px overflow-hidden rounded-md border border-slate-200 bg-slate-200">
        <?php foreach ($oddily as $oddil): ?>
            <?php
            $oid    = (int) ($oddil['id'] ?? 0);
            $stav   = (string) ($oddil['stav'] ?? '');
            $popis  = $stavPopis[$stav] ?? ['label' => $stav, 'class' => 'bg-slate-100 text-slate-500'];
            $token  = (string) ($oddil['token_zadosti'] ?? '');
            $soupiska = isset($oddil['zavodnici']) && is_array($oddil['zavodnici']) ? $oddil['zavodnici'] : [];
            ?>
            <li class="bg-white px-5 py-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="font-display text-base font-extrabold tracking-tight"><?= e($oddil['nazev'] ?? '') ?></h2>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold <?= e($popis['class']) ?>"><?= e($popis['label']) ?></span>
                        </div>
                        <p class="mt-1.5 text-sm text-slate-600">
                            <?= e($oddil['kontakt_jmeno'] ?? '') ?>
                            <?php if (! empty($oddil['telefon'])): ?>&middot; <?= e($oddil['telefon']) ?><?php endif; ?>
                        </p>
                        <p class="text-sm text-slate-500"><?= e($oddil['email'] ?? '') ?></p>
                        <p class="mt-1.5 text-xs text-slate-400">
                            <?= count($soupiska) ?> závodníků v soupisce &middot;
                            založeno <?= e($oddil['vytvoreno'] ?? '') ?>
                            <?php if (! empty($oddil['schvaleno'])): ?>&middot; schváleno <?= e($oddil['schvaleno']) ?><?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <?php if ($stav === 'cekajici'): ?>
                        <form method="post" action="/admin/oddily/<?= $oid ?>/schvalit">
                            <?= csrf_field() ?>
                            <button type="submit"
                                    class="rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-500">
                                Schválit
                            </button>
                        </form>
                        <form method="post" action="/admin/oddily/<?= $oid ?>/zamitnout">
                            <?= csrf_field() ?>
                            <button type="submit" class="<?= $tlacitko ?>">Zamítnout</button>
                        </form>
                    <?php elseif ($stav === 'schvaleny'): ?>
                        <form method="post" action="/admin/oddily/<?= $oid ?>/zamitnout">
                            <?= csrf_field() ?>
                            <button type="submit" class="<?= $tlacitko ?>">Zrušit přístup</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="/admin/oddily/<?= $oid ?>/schvalit">
                            <?= csrf_field() ?>
                            <button type="submit" class="<?= $tlacitko ?>">Přesto schválit</button>
                        </form>
                    <?php endif; ?>

                    <form method="post" action="/admin/oddily/<?= $oid ?>/reset-hesla">
                        <?= csrf_field() ?>
                        <button type="submit" class="<?= $tlacitko ?>">Nové heslo</button>
                    </form>

                    <form method="post" action="/admin/oddily/<?= $oid ?>/smazat"
                          onsubmit="return confirm('Smazat účet oddílu i s celou soupiskou? Akce je nevratná.');">
                        <?= csrf_field() ?>
                        <button type="submit"
                                class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 transition-colors hover:border-red-300 hover:bg-red-50">
                            Smazat
                        </button>
                    </form>
                </div>

                <?php if ($token !== ''): ?>
                    <p class="mt-3 text-xs text-slate-400">
                        Náhled oznámení:
                        <a href="/oddil/oznameni/stav-zadosti/<?= e($token) ?>" class="text-brand-700 underline decoration-brand-300 underline-offset-2 hover:text-brand-500">stav žádosti</a>
                        &middot;
                        <a href="/oddil/oznameni/ucet-schvalen/<?= e($token) ?>" class="text-brand-700 underline decoration-brand-300 underline-offset-2 hover:text-brand-500">schválení účtu</a>
                    </p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
