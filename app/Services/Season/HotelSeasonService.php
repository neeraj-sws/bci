<?php

namespace App\Services\Season;

use App\Models\Season;
use App\Services\Season\SeasonResolverService;
use Carbon\Carbon;

class HotelSeasonService
{
    public function __construct(
        private readonly SeasonResolverService $seasonResolver
    ) {}

    /**
     * Get the current active season based on today's date
     *
     * @return Season|null
     */
    public function getCurrentSeason(): ?Season
    {
        $today = Carbon::today();

        return Season::where('status', 1)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->first();
    }

    /**
     * Get all active seasons ordered by start date
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActiveSeasons()
    {
        return Season::where('status', 1)
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Resolve season ID - returns provided season or current season
     *
     * @param mixed $seasonId
     * @return string|null
     */
    public function resolveSeasonId($seasonId = null): ?string
    {
        if ($seasonId) {
            return $seasonId;
        }

        $currentSeason = $this->getCurrentSeason();
        return $currentSeason?->seasons_id;
    }

    /**
     * Get season by ID
     *
     * @param mixed $seasonId
     * @return Season|null
     */
    public function getSeasonById($seasonId): ?Season
    {
        if (!$seasonId) {
            return null;
        }

        return Season::where('seasons_id', $seasonId)
            ->where('status', 1)
            ->first();
    }

    /**
     * Get default season (current season or first active season)
     *
     * @return Season|null
     */
    public function getDefaultSeason(): ?Season
    {
        $seasons = $this->getAllActiveSeasons();
        return $this->seasonResolver->resolve($seasons);
    }
}
