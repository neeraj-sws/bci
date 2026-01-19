<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\Hotel;
use App\Models\Occupancy;

class Settings extends Component
{
    public $hotelId;
    public $hotel;
    public $occupancies;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->hotel = Hotel::with(['hotelType', 'hotelCategory', 'hotelMealType'])->find($this->hotelId);
        
        // Load occupancy types
        $this->occupancies = Occupancy::where('status', 1)->get();
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.settings');
    }
}
