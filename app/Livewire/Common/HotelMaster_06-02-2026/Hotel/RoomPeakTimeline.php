<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\RoomCategory;

class RoomPeakTimeline extends Component
{
    public $hotelId;
    public $roomCategories;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->loadRoomCategories();
    }

    public function loadRoomCategories()
    {
        $this->roomCategories = RoomCategory::where('hotel_id', $this->hotelId)
            ->where('status', 1)
            ->with(['peakDates' => function($query) {
                $query->where('status', 1)->orderBy('start_date', 'asc');
            }])
            ->get();
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.room-peak-timeline');
    }
}
