@extends('layouts.public')

@section('title', 'Výsledky | Vodácká 30')
@section('description', 'Oficiální výsledky a archiv závodu Vodácká 30.')

@section('content')
    <div class="space-y-8">
        <x-ui.page-heading eyebrow="Oficiální archiv" title="Výsledky" lead="Výsledky jsou zatím dostupné na původním webu pořadatele. Nový archiv připravujeme po ověření historických podkladů." />

        <x-ui.card>
            <div class="space-y-4 p-6">
                <div>
                    <p class="text-sm font-semibold text-slate-600">Poslední dostupný ročník</p>
                    <h2 id="latest-results-heading" class="mt-1 text-xl font-bold text-slate-900">Výsledky 2026</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Oficiální výsledková listina je k dispozici v HTML i jako PDF na původním webu.</p>
                </div>
                <div class="divide-y divide-slate-200 border-y border-slate-200">
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://vodacka30.cz/index.php?menu=3" target="_blank" rel="noopener noreferrer">
                        Zobrazit výsledkovou listinu <span aria-hidden="true">↗</span>
                    </a>
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://vodacka30.cz/vysledky/V30_2026_vysledkova_listina.pdf" target="_blank" rel="noopener noreferrer">
                        Stáhnout oficiální PDF <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="border-l-4 border-l-orange-600">
            <div class="p-6">
                <p class="text-sm font-semibold text-slate-600">Historický archiv</p>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Starší ročníky budeme přidávat po kontrole zdrojových listin a potvrzení pořadatelem. Neověřené výsledky zde nevydáváme za oficiální.</p>
            </div>
        </x-ui.card>
    </div>
@endsection
