@extends('layouts.public')

@section('title', 'Propozice | Vodácká 30')
@section('description', 'Propozice a podmínky závodu Vodácká 30.')

@section('content')
    <div class="space-y-8">
        <x-ui.page-heading eyebrow="Aktuální ročník · 2027" title="Propozice" lead="Oficiální podmínky dalšího ročníku zatím pořadatel nepotvrdil." />

        <x-ui.card class="border-l-4 border-l-orange-600">
            <div class="space-y-3 p-6">
                <p class="text-sm font-semibold text-slate-600">Informace k ročníku 2027</p>
                <h2 id="regulations-status-heading" class="text-2xl font-bold text-slate-900">Připravujeme aktuální znění.</h2>
                <p class="max-w-3xl text-sm leading-6 text-slate-600">Datum, kategorie, tratě, ceny, kapacity a termíny přihlášek zveřejníme po potvrzení pořadatelem. Údaje z předchozího ročníku se na rok 2027 automaticky nevztahují.</p>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="space-y-4 p-6">
                <div>
                    <p class="text-sm font-semibold text-slate-600">Archiv</p>
                    <h2 id="previous-regulations-heading" class="mt-1 text-xl font-bold text-slate-900">Propozice 2026</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Podmínky posledního zveřejněného ročníku zůstávají dostupné na původním webu.</p>
                </div>
                <x-ui.button variant="secondary" href="https://vodacka30.cz/index.php?menu=2" target="_blank" rel="noopener noreferrer">Otevřít propozice 2026 <span aria-hidden="true">↗</span></x-ui.button>
            </div>
        </x-ui.card>
    </div>
@endsection
