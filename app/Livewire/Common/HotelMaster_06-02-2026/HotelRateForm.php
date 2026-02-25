<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{
    HotelRate,
    Hotel,
    RoomCategory,
    RateTypes,
    Season,
    MealType,
    Occupancy
};

#[Layout('components.layouts.common-app')]
class HotelRateForm extends Component
{
    public $hotelRateId;
    public $isEditing = false;

    public $hotel_id;
    public $room_category_id;
    public $rate_type_id;
    public $season_id;
    public $meal_plan_id;
    public $occupancy_id;
    public $weekday_rate;
    public $weekend_rate;
    public $child_rate;
    public $extra_bed_rate;
    public $status = 1;

    public $hotels;
    public $roomCategories =[];
    public $rateTypes;
    public $seasons;
    public $mealPlans;
    public $occupancies;

    protected function rules()
    {
        return [
            'hotel_id' => 'required',
            'room_category_id' => 'required',
            'rate_type_id' => 'required',
            'season_id' => 'required',
            'meal_plan_id' => 'required',
            'occupancy_id' => 'required',
            'weekday_rate' => 'required|numeric',
            'weekend_rate' => 'required|numeric',
            'status'      => 'required',
        ];
    }

    public function mount($id = null)
    {
        $this->hotels = Hotel::where('status', 1)->get();
        $this->rateTypes = RateTypes::where('status', 1)->get();
        $this->seasons = Season::where('status', 1)->get();
        $this->mealPlans = MealType::where('status', 1)->get();
        $this->occupancies = Occupancy::where('status', 1)->get();

        if ($id) {
            $rate = HotelRate::findOrFail($id);
            $this->fill($rate->toArray());
            $this->hotelRateId = $id;
            $this->isEditing = true;
        }
    }

    public function save()
    {
        $this->validate();

        HotelRate::updateOrCreate(
            ['hotel_rates_id' => $this->hotelRateId],
            $this->only(array_keys($this->rules()))
        );

        return redirect()->route('common.hotel-rates');
    }

    public function updatedHotelId()
    {
        $this->roomCategories = RoomCategory::where('status', 1)->where('hotel_id',$this->hotel_id)->get();
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel-rate-form');
    }
}
