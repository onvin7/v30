@props(['eyebrow', 'title', 'lead'])

<header class="border-b border-slate-200 pb-8 sm:pb-10">
    <p class="text-sm font-semibold text-slate-600">{{ $eyebrow }}</p>
    <h1 class="mt-3 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">{{ $title }}</h1>
    <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">{{ $lead }}</p>
</header>