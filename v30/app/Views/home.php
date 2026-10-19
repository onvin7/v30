<!-- Hero – fotka v pozadí -->
<section class="relative bg-slate-900">
    <img
        src="https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=dramatic%20winter%20landscape%2C%20Nordic%20cross-country%20skiers%20on%20a%20snowy%20forest%20trail%20at%20dawn%2C%20Orlick%C3%A9%20hory%20mountains%2C%20dark%20pine%20forest%2C%20cold%20blue%20morning%20light%2C%20cinematic%20wide%20shot%2C%20professional%20sports%20photography&image_size=landscape_16_9"
        alt="Závodníci na běžkách v zasněženém lese v Orlických horách"
        class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-slate-950/65"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
        <p class="text-xs sm:text-sm font-semibold uppercase tracking-[0.3em] text-sky-200 mb-5">
            Závod vodáků na běžkách
        </p>

        <h1 class="font-display text-5xl sm:text-7xl lg:text-8xl font-black uppercase tracking-tight text-white leading-none mb-6">
            <?= htmlspecialchars($hero['nazev']) ?>
        </h1>

        <p class="text-lg sm:text-xl text-slate-200 max-w-2xl mb-9">
            <?= htmlspecialchars($hero['podtitul']) ?>
        </p>

        <!-- Termín a místo -->
        <div class="flex flex-wrap items-center gap-x-8 gap-y-3 text-white mb-10">
            <span class="inline-flex items-center gap-2.5">
                <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                <span class="font-semibold"><?= htmlspecialchars($hero['datum']) ?></span>
            </span>
            <span class="inline-flex items-center gap-2.5">
                <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                </svg>
                <span class="font-semibold"><?= htmlspecialchars($hero['misto']) ?></span>
            </span>
        </div>

        <!-- CTA tlačítka -->
        <div class="flex flex-wrap gap-3">
            <a href="/prihlaska" class="inline-flex items-center rounded-md bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3.5 transition-colors">
                Registrovat do závodu
            </a>
            <a href="/startovni-listina" class="inline-flex items-center rounded-md bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-6 py-3.5 transition-colors">
                Startovní listina
            </a>
        </div>
    </div>
</section>

<!-- Trať a místo -->
<section class="bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 mb-4">Trať a místo</p>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-slate-900 mb-5">
                    <?= htmlspecialchars($trat['nazev']) ?>
                </h2>
                <p class="text-slate-600 mb-9">
                    <?= htmlspecialchars($trat['popis']) ?>
                </p>

                <dl class="grid grid-cols-2 gap-x-6 gap-y-6 mb-9">
                    <?php foreach ($trat['fakta'] as $fakt): ?>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-1"><?= htmlspecialchars($fakt['label']) ?></dt>
                            <dd class="font-display text-xl font-bold text-slate-900"><?= htmlspecialchars($fakt['hodnota']) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <a href="/propozice" class="inline-flex items-center gap-2 rounded-md border border-slate-200 hover:bg-slate-100 text-slate-900 font-semibold px-5 py-3 transition-colors">
                    Propozice a trať
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            <div>
                <img
                    src="https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=wooden%20mountain%20chalet%20in%20snowy%20winter%20forest%2C%20Orlick%C3%A9%20hory%20mountains%2C%20Czech%20countryside%2C%20snow-covered%20trail%20leading%20to%20the%20hut%2C%20cold%20clear%20winter%20day%2C%20professional%20photography&image_size=landscape_4_3"
                    alt="Chata Bedřichovka v Orlických horách"
                    class="w-full h-72 lg:h-[26rem] object-cover rounded-lg border border-slate-200">
            </div>

        </div>
    </div>
</section>
