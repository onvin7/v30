@extends('layouts.public')

@section('title', 'Přihlášky | Vodácká 30')
@section('description', 'Stav přihlášek na další ročník závodu Vodácká 30.')

@section('content')
    <div class="space-y-8">
        <x-ui.page-heading eyebrow="Další ročník · 2027" title="Přihlášky" lead="Registrace zatím není otevřená." />

        <x-ui.card class="border-l-4 border-l-orange-600">
            <div class="space-y-4 p-6">
                <div>
                    <p class="text-sm font-semibold text-slate-600">Stav registrace</p>
                    <h2 id="registration-status-heading" class="mt-1 text-2xl font-bold text-slate-900">Přihlášky připravujeme.</h2>
                </div>
                <p class="max-w-3xl text-sm leading-6 text-slate-600">Formulář otevřeme po potvrzení data, kategorií, cen a pravidel ročníku 2027. Lodní kategorie ze starého formuláře se do běžkařské Vodácké 30 nepřebírají.</p>
                <x-ui.button variant="secondary" href="{{ route('regulations') }}">Aktuální stav propozic</x-ui.button>
            </div>
        </x-ui.card>
    </div>
@endsection
