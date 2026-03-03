<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\Hotel;
use App\Services\Season\HotelSeasonService;

class Header extends Component
{
    private const SEASON_SESSION_KEY_PREFIX = 'hotel_selected_season_';
    public $hotelId;
    public $hotel;
    public $route;
    public $seasons;
    public $selectedSeason = '';

    public function mount($hotelId, HotelSeasonService $seasonService)
    {
        $this->hotelId = $hotelId;
        $this->hotel = Hotel::with(['hotelType', 'hotelCategory', 'hotelMealType'])->find($hotelId);
        $this->route = session('role') ?? 'common';

        $this->seasons = $seasonService->getAllActiveSeasons();

        $defaultSeason = $seasonService->getDefaultSeason();
        $this->selectedSeason = session(
            self::SEASON_SESSION_KEY_PREFIX . $this->hotelId,
            $defaultSeason?->seasons_id ?? ''
        );

        if ($this->selectedSeason) {
            session([self::SEASON_SESSION_KEY_PREFIX . $this->hotelId => $this->selectedSeason]);
            $this->dispatch('seasonChanged', seasonId: $this->selectedSeason);
        }
    }

    public function updatedSelectedSeason($value)
    {
         session([self::SEASON_SESSION_KEY_PREFIX . $this->hotelId => $value]);
        $this->dispatch('seasonChanged', seasonId: $value);
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.header');
    }
}
