<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\PeackDate;
use App\Models\ChildPolicy;

class KpiCards extends Component
{
    public $hotelId;
    public $totalRoomCategories = 0;
    public $totalPeakDates = 0;
    public $totalChildPolicies = 0;
    public $startingPrice = 0;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->loadKpiData();
    }

    public function loadKpiData()
    {
        // Total Room Categories
        $this->totalRoomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->count();

        // Total Peak Dates
        $this->totalPeakDates = PeackDate::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->count();

        // Total Child Policies
        $this->totalChildPolicies = ChildPolicy::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->count();

        // Starting Price (Lowest base price from room categories)
        $lowestPrice = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->whereHas('occupancies')
            ->with('occupancies')
            ->get()
            ->flatMap(function($room) {
                return $room->occupancies->pluck('rate');
            })
            ->min();

        $this->startingPrice = $lowestPrice ?? 0;
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.kpi-cards');
    }
}
