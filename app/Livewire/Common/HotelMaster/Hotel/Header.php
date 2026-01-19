<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\Hotel;

class Header extends Component
{
    public $hotelId;
    public $hotel;
    public $route;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->hotel = Hotel::with(['hotelType', 'hotelCategory', 'hotelMealType'])->find($hotelId);
        $this->route = session('role') ?? 'common';
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.header');
    }
}
