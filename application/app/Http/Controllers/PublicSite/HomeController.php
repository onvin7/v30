<?php

namespace App\Http\Controllers\PublicSite;

use App\Enums\RaceEditionStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $event = Event::query()
            ->with('raceEditions.categories')
            ->where('slug', 'vodacka-30')
            ->first();

        $editions = $event?->raceEditions ?? new Collection;
        $currentEdition = $editions->firstWhere('is_current', true);
        $archiveEdition = $editions
            ->where('status', RaceEditionStatus::Archived)
            ->sortByDesc('year')
            ->first();
        $archiveCategories = $archiveEdition?->categories ?? new Collection;
        $distances = $archiveCategories
            ->pluck('distance_km')
            ->filter()
            ->map(fn (string|float $distance): float => (float) $distance)
            ->unique()
            ->sort()
            ->values();
        $mainDistance = $distances->last();
        $mainDistanceLabel = $mainDistance === null
            ? null
            : rtrim(rtrim(number_format($mainDistance, 2, '.', ''), '0'), '.');
        $childrenDistances = $distances
            ->filter(fn (float $distance): bool => $mainDistance !== null && $distance < $mainDistance)
            ->map(fn (float $distance): string => rtrim(rtrim(number_format($distance, 2, '.', ''), '0'), '.'))
            ->implode(' / ');

        return view('public.home.index', [
            'event' => $event,
            'currentEdition' => $currentEdition,
            'archiveEdition' => $archiveEdition,
            'mainDistanceLabel' => $mainDistanceLabel,
            'childrenDistances' => $childrenDistances,
        ]);
    }
}
