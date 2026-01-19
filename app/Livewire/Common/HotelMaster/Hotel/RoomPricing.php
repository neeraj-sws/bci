<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\RoomCategory;

class RoomPricing extends Component
{
    public $hotelId;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.room-pricing');
    }
}
