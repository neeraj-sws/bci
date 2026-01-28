<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use App\Models\Hotel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.hotel-app')]
class HotelDetail extends Component
{
    public $hotel;
    public $hotelId;
    public $pageTitle = 'Hotel Details';
    public $route;

    public function mount($id)
    {
        $this->hotelId = $id;
        $this->route = session('role') ?? 'common';

        // Load hotel - basic info only, child components handle their own data
        $this->hotel = Hotel::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.hotel-detail');
    }
}
