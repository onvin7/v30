@extends('layouts.public')

@section('title', ($event?->name ?? 'Vodácká 30').' | Běžecký závod v Orlických horách')
@section('description', $event?->summary ?? 'Vodácká 30, běžecký lyžařský závod po hřebenech Orlických hor.')

@section('content')
    <div class="space-y-6 sm:space-y-8">
        <section class="grid gap-4 lg:grid-cols-3" aria-labelledby="page-title">
            <x-ui.card class="border-t-4 border-t-orange-600 lg:col-span-2">
                <div class="flex h-full flex-col justify-between gap-8 p-6 sm:p-8">
                    <div>
                        <p class="text-sm font-semibold text-slate-600">Běžecké lyžování · Orlické hory</p>
                        <h1 id="page-title" class="mt-3 text-4xl font-bold leading-tight text-slate-900 sm:text-5xl">{{ $event?->name ?? 'Vodácká 30' }}</h1>
                        <p class="mt-2 text-lg font-semibold text-slate-700">Po hřebenech Orlických hor</p>
                        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">{{ $event?->summary ?? 'Zimní běžecký závod pořádaný vodáky. Závodní tratě a kategorie budou uvedeny po potvrzení pořadatelem.' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <x-ui.button href="{{ route('registration') }}">Přihlášky {{ $currentEdition?->year ?? '' }}</x-ui.button>
                        <x-ui.button variant="secondary" href="{{ route('about') }}">O závodu</x-ui.button>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="border-t-4 border-t-orange-600">
                <div class="flex h-full flex-col justify-between gap-6 p-6 sm:p-6">
                    <div>
                        <p class="text-sm font-semibold text-slate-600">
                            {{ $currentEdition?->event_date ? ($currentEdition->date_confirmed ? 'Termín potvrzený pořadatelem' : 'Původní web uvádí') : 'Termín ročníku' }}
                        </p>
                        <p class="mt-4 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">
                            {{ $currentEdition?->event_date?->locale('cs')->translatedFormat('j. F') ?? 'Termín se připravuje' }}
                            @if ($currentEdition?->event_date)
                                <span class="block">{{ $currentEdition->year }}</span>
                            @endif
                        </p>
                    </div>
                    <p class="border-t border-slate-200 pt-4 text-sm leading-6 text-slate-600">
                        {{ $currentEdition?->date_confirmed ? 'Termín potvrzen pořadatelem.' : 'Termín čeká na potvrzení pořadatelem.' }}
                    </p>
                </div>
            </x-ui.card>
        </section>

        <section class="grid gap-4 sm:grid-cols-3" aria-label="Základní údaje o závodu">
            <x-ui.card>
                <div class="flex items-center gap-4 p-4">
                    <span class="text-2xl font-bold tabular-nums text-orange-600">{{ $mainDistanceLabel ?? '—' }}</span>
                    <span class="text-sm leading-5 text-slate-600">km hlavní trati</span>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="flex items-center gap-4 p-4">
                    <span class="text-2xl font-bold tabular-nums text-orange-600">{{ $childrenDistances !== '' ? $childrenDistances : '—' }}</span>
                    <span class="text-sm leading-5 text-slate-600">km dětských tratí {{ $archiveEdition?->year }}</span>
                </div>
            </x-ui.card>
            <x-ui.card>
                <div class="flex items-center gap-4 p-4">
                    <span class="text-2xl font-bold text-orange-600">OH</span>
                    <span class="text-sm leading-5 text-slate-600">Orlické hory<br>{{ $archiveEdition?->venue ?? 'místo bude upřesněno' }}</span>
                </div>
            </x-ui.card>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <x-ui.card>
                <div class="space-y-4 p-6 sm:p-6">
                    <p class="text-sm font-semibold text-slate-600">Vodácké mistrovství ČR v běhu na lyžích</p>
                    <h2 class="text-2xl font-bold leading-tight text-slate-900">Zima, hřebeny a poctivá třicítka.</h2>
                    <p class="text-sm leading-6 text-slate-600">Vodácká 30 vede po hřebenech Orlických hor. Aktuální propozice a kategorie dalšího ročníku pořadatel zveřejní po potvrzení.</p>
                    <a class="inline-flex items-center gap-2 text-sm font-semibold text-slate-900 underline decoration-slate-300 underline-offset-4 hover:text-orange-700" href="{{ route('about') }}">Více o závodu <span aria-hidden="true">→</span></a>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="space-y-4 p-6 sm:p-6">
                    <p class="text-sm font-semibold text-slate-600">Oficiální archiv</p>
                    <h2 class="text-2xl font-bold leading-tight text-slate-900">Závodní výsledky</h2>
                    <a class="flex items-center justify-between gap-4 border-t border-slate-200 pt-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="{{ route('results') }}">
                        Výsledky {{ $archiveEdition?->year }} <span aria-hidden="true">→</span>
                    </a>
                    <a class="flex items-center justify-between gap-4 border-t border-slate-200 pt-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="{{ route('contact') }}#fotografie">
                        Fotogalerie <span aria-hidden="true">→</span>
                    </a>
                </div>
            </x-ui.card>
        </section>

        <x-ui.card class="border-l-4 border-l-orange-600">
            <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-600">Ročník {{ $currentEdition?->year }}</p>
                    <h2 class="mt-1 text-xl font-bold text-slate-900">Přihlášky připravujeme.</h2>
                </div>
                <x-ui.button variant="secondary" href="{{ route('registration') }}">Stav registrace</x-ui.button>
            </div>
        </x-ui.card>
    </div>
@endsection
