@extends('layouts.public')

@section('title', 'O závodu | Vodácká 30')
@section('description', 'Informace o běžeckém lyžařském závodu Vodácká 30 v Orlických horách.')

@section('content')
    <div class="space-y-8">
        <x-ui.page-heading eyebrow="Běžecké lyžování · Orlické hory" title="O závodu" lead="Vodácká 30 je zimní běžecký závod po hřebenech Orlických hor, který pořádá TJ KVT Pardubice." />

        <section class="grid gap-4 lg:grid-cols-2" aria-labelledby="race-facts-heading">
            <x-ui.card>
                <div class="space-y-4 p-6">
                    <p class="text-sm font-semibold text-slate-600">Vodácké mistrovství ČR v běhu na lyžích</p>
                    <h2 id="race-facts-heading" class="text-2xl font-bold leading-tight text-slate-900">Závod na horách, pořádaný vodáky.</h2>
                    <p class="text-sm leading-6 text-slate-600">Název odkazuje na pořadatelský klub; hlavní závod se jede na běžkách. V roce 2026 vedla dospělá trať na 30 km, dětské závody měly tratě 3 a 7 km.</p>
                    <p class="text-sm leading-6 text-slate-600">Start a cíl ročníku 2026 byly u chaty Bedřichovka v Orlickém Záhoří. Tyto údaje popisují minulý ročník a nepotvrzují podobu závodu 2027.</p>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="p-6">
                    <p class="text-sm font-semibold text-slate-600">Ročník 2026</p>
                    <dl class="mt-4 divide-y divide-slate-200">
                        <div class="py-3"><dt class="text-xs font-medium text-slate-500">Pořadatel</dt><dd class="mt-1 text-sm font-semibold text-slate-900">TJ KVT Pardubice</dd></div>
                        <div class="py-3"><dt class="text-xs font-medium text-slate-500">Místo startu a cíle</dt><dd class="mt-1 text-sm font-semibold text-slate-900">Bedřichovka, Orlické Záhoří</dd></div>
                        <div class="py-3"><dt class="text-xs font-medium text-slate-500">Dětské tratě</dt><dd class="mt-1 text-sm font-semibold text-slate-900">3 km klasicky · 7 km volně</dd></div>
                        <div class="py-3"><dt class="text-xs font-medium text-slate-500">Kontrolní bod</dt><dd class="mt-1 text-sm font-semibold text-slate-900">Pěticestí</dd></div>
                    </dl>
                </div>
            </x-ui.card>
        </section>

        <div class="flex flex-wrap gap-3">
            <x-ui.button href="{{ route('regulations') }}">Propozice</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('results') }}">Výsledky 2026</x-ui.button>
        </div>
    </div>
@endsection
