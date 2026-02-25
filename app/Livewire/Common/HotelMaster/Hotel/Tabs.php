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
        $this->activeTab = session('hotel_active_tab_' . $this->hotelId, 'overview');
    }

    public function setActiveTab($tab)
    {
        session(['hotel_active_tab_' . $this->hotelId => $tab]);
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.tabs');
    }
}
