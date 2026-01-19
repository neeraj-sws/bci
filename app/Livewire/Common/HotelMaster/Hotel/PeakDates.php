<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use App\Models\PeackDate;
use App\Models\RoomCategory;

class PeakDates extends Component
{
    public $hotelId;
    public $peakDates;
    public $expandedPeak = null;
    public $highestSurcharge = 0;
    public $highestSurchargePeak = null;
    public function mount($hotelId)
    {
        $this->hotelId = $hotelId;
        $this->loadPeakDates();
        $this->calculateHighestSurcharge();
    }
    public function loadPeakDates()
    {
        $this->peakDates = PeackDate::where('hotel_id', $this->hotelId)->with(['roomCategory', 'occupancies.occupancy', 'childPolicies' => function ($query) {
            $query->where('status', 1);
        }])->orderBy('start_date', 'asc')->get();
    }
    public function calculateHighestSurcharge()
    {
        $maxSurcharge = 0;
        $peakWithMax = null;
        foreach ($this->peakDates as $peak) {
            $extraAmount = $peak->extra_amount ?? 0;
            if ($extraAmount > $maxSurcharge) {
                $maxSurcharge = $extraAmount;
                $peakWithMax = $peak;
            }
        }
        $this->highestSurcharge = $maxSurcharge;
        $this->highestSurchargePeak = $peakWithMax;
    }
    public function togglePeak($peakId)
    {
        if ($this->expandedPeak === $peakId) {
            $this->expandedPeak = null;
        } else {
            $this->expandedPeak = $peakId;
        }
    }
    public function getRoomCategoriesForPeak($peakId)
    {
        return RoomCategory::where('hotel_id', $this->hotelId)->where('status', 1)->whereHas('peakDates', function ($query) use ($peakId) {
            $query->where('peak_dates_id', $peakId);
        })->with(['occupancies.occupancy'])->get();
    }
    public function render()
    {
        return view('livewire.common.hotel-master.hotel.peak-dates');
    }
}
