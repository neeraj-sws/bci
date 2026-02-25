<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;

class Tabs extends Component
{
    public $hotelId;
    public $activeTab = 'overview';

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.tabs');
    }
}
