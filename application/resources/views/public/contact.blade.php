@extends('layouts.public')

@section('title', 'Kontakt | Vodácká 30')
@section('description', 'Kontakt na pořadatele závodu Vodácká 30.')

@section('content')
    <div class="space-y-8">
        <x-ui.page-heading eyebrow="Pořadatel" title="Kontakt" lead="Vodáckou 30 pořádá TJ KVT Pardubice." />

        <section class="grid gap-4 lg:grid-cols-2">
            <x-ui.card>
                <div class="space-y-4 p-6">
                    <div>
                        <p class="text-sm font-semibold text-slate-600">Organizační výbor</p>
                        <h2 class="mt-1 text-2xl font-bold text-slate-900">Potřebujete se na něco zeptat?</h2>
                    </div>
                    <p class="text-sm leading-6 text-slate-600">Aktuální kontakty na organizační výbor, ředitele závodu a hlavního rozhodčího zveřejňuje pořadatel na původním webu.</p>
                    <x-ui.button href="https://vodacka30.cz/index.php?menu=5" target="_blank" rel="noopener noreferrer">Kontakty pořadatele <span aria-hidden="true">↗</span></x-ui.button>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div id="fotografie" class="divide-y divide-slate-200 px-6">
                    <p class="py-4 text-sm font-semibold text-slate-600">Další informace</p>
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://vodacka30.cz/index.php?menu=4" target="_blank" rel="noopener noreferrer">
                        Fotogalerie závodu <span aria-hidden="true">↗</span>
                    </a>
                    <a class="flex items-center justify-between gap-4 py-4 text-sm font-semibold text-slate-900 hover:text-orange-700" href="https://kvt.pardubice.cz/" target="_blank" rel="noopener noreferrer">
                        TJ KVT Pardubice <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </x-ui.card>
        </section>
    </div>
@endsection
