<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\ChildPolicy;

class ChildPolicies extends Component
{
    public $hotelId;

    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
    }

    public function render()
    {
        $childPolicies = ChildPolicy::where('hotel_id', $this->hotelId)
            ->with('roomCategory')
            ->orderBy('free_child_age', 'asc')
            ->get();

        $groupedPolicies = $childPolicies->groupBy('free_child_age');

        return view('livewire.common.hotel-master.hotel.child-policies', [
            'childPolicies'   => $childPolicies,
            'groupedPolicies' => $groupedPolicies,
        ]);
    }
}
