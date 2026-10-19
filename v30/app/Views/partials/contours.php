<?php
/**
 * Grafický motiv vrstevnic – používá se jako podklad tmavých ploch, když
 * pro dané místo není k dispozici reálná fotografie.
 *
 * Vykresluje se absolutně přes celou plochu rodičovského prvku (musí být
 * `relative` a `isolate`).
 */
?>
<svg class="pointer-events-none absolute inset-0 -z-10 h-full w-full opacity-[0.22]"
     viewBox="0 0 1200 420" preserveAspectRatio="none" fill="none" aria-hidden="true">
    <g stroke="#4DA6E0" stroke-width="1.25">
        <path d="M-40 352 C 180 276 360 404 620 324 C 850 252 1010 362 1240 288"/>
        <path d="M-40 306 C 190 232 370 358 630 282 C 860 214 1020 322 1240 250"/>
        <path d="M-40 260 C 200 190 380 312 640 240 C 870 176 1030 282 1240 212"/>
        <path d="M-40 214 C 210 148 390 266 650 198 C 880 138 1040 242 1240 174"/>
        <path d="M-40 168 C 220 106 400 220 660 156 C 890 100 1050 202 1240 136"/>
        <path d="M-40 122 C 230 64 410 174 670 114 C 900 62 1060 162 1240 98"/>
        <path d="M-40 76 C 240 22 420 128 680 72 C 910 24 1070 122 1240 60"/>
    </g>
</svg>
