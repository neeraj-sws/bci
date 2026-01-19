<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\RoomCategory;
use Carbon\Carbon;

class RoomCards extends Component
{
    public $hotelId;
    public $roomCategories;
    public $expandedRoom = null;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->loadRoomCategories();
    }

    public function loadRoomCategories()
    {
        $this->roomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with([
                'occupancies.occupancy',
                'childPolicies' => function ($query) {
                    $query->where('status', 1);
                },
                'peakDates' => function ($query) {
                    $query->where('status', 1);
                }
            ])
            ->get();
    }

    public function toggleRoom($roomId)
    {
        if ($this->expandedRoom === $roomId) {
            $this->expandedRoom = null;
        } else {
            $this->expandedRoom = $roomId;
        }
    }

    public function getUpcomingPeaks($room)
    {
        return $room->peakDates
            ->filter(fn($peak) => Carbon::parse($peak->end_date)->gte(now()))
            ->sortBy('start_date')
            ->take(2);
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.room-cards');
    }
}
