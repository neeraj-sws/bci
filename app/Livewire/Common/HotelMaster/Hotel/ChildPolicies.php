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
            ->with(['roomCategory', 'peakDate'])
            ->orderBy('free_child_age', 'asc')
            ->get();

        // Group by room category first, then by peak date, then by age
        $groupedByRoom = $childPolicies->groupBy('room_category_id');
        
        // Separate regular and peak date policies
        $regularPolicies = $childPolicies->where('peak_date_id', null);
        $peakPolicies = $childPolicies->where('peak_date_id', '!=', null);

        return view('livewire.common.hotel-master.hotel.child-policies', [
            'childPolicies'   => $childPolicies,
            'groupedByRoom' => $groupedByRoom,
            'regularPolicies' => $regularPolicies->groupBy('room_category_id'),
            'peakPolicies' => $peakPolicies->groupBy('peak_date_id'),
        ]);
    }
}
