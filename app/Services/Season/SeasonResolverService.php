<?php

namespace App\Services\Season;

use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SeasonResolverService
{
    /**
     * Resolve best season:
     * 1. Current running season
     * 2. Nearest future season
     * 3. Nearest past season
     */
    public function resolve(?Collection $seasons = null): ?Season
    {
        $today = Carbon::today();

        $seasons = $seasons ?? Season::where('status', 1)
            ->orderBy('start_date')
            ->get();

        if ($seasons->isEmpty()) {
            return null;
        }

        $current = $seasons->first(
            fn($season) =>
            $today->between(
                Carbon::parse($season->start_date),
                Carbon::parse($season->end_date)
            )
        );

        if ($current) {
            return $current;
        }

        $future = $seasons
            ->filter(fn($season) => Carbon::parse($season->start_date)->gt($today))
            ->sortBy('start_date')
            ->first();

        if ($future) {
            return $future;
        }

        return $seasons
            ->filter(fn($season) => Carbon::parse($season->end_date)->lt($today))
            ->sortByDesc('end_date')
            ->first();
    }
}
