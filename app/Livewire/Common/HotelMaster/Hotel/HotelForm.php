<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use App\Models\Chain;
use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Hotel;
use App\Models\HotelTypes;
use App\Models\HotelCategories;
use App\Models\HotelMealPlan;
use App\Models\MarketingCompany;
use App\Models\MealType;
use App\Models\Parks;
use App\Models\RateTypes;
use App\Models\States;

#[Layout('components.layouts.hotel-app')]
class HotelForm extends Component
{
    public $hotelId;
    public $isEditing = false;

    // DB fields
    public $name;
    public $hotel_type_id;
    public $hotel_category_id;
    public $parent_chain_id;
    public $marketing_company_id;
    public $park_id;
    public $location;
    public $status = 1;

    // Dropdown data
    public $hotel_types = [];
    public $hotel_categories = [];
    public $parks = [];
    public $rateTypes, $rate_type, $mealTypes, $meal_type = [];
    public $chainHotels = [];
    public $marketedHotels = [], $countrys = [], $states = [], $citys = [];
    public $showModel = false;
    public $modalType = null;
    public $modalTitle;
    public $newTitle,$country_id,$state,$city;


    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'hotel_type_id' => 'required|exists:hotel_types,hotel_type_id',
            'hotel_category_id' => 'required|exists:hotel_categories,hotel_category_id',
            'parent_chain_id' => 'nullable|integer',
            'marketing_company_id' => 'nullable|integer',
            'park_id' => 'required|exists:parks,park_id',
            'location' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'rate_type' => 'nullable|exists:rate_types,rate_type_id',
            'meal_type' => 'required|array|min:1',
            'meal_type.*' => 'required|exists:meal_plans,meal_plans_id',
            'country_id' => 'required|exists:country,country_id',
            'state' => 'required|exists:states,state_id',
            'city' => 'required|exists:city,city_id',
        ];
    }

    protected $validationAttributes = [
        'hotel_type_id' => 'Hotel Type',
        'hotel_category_id' => 'Hotel Category',
        'parent_chain_id' => 'Parent Chain',
        'marketing_company_id' => 'Marketing Company',
        'park_id' => 'Park',
        'meal_type' => 'Meal Type',
    ];

    public function mount($id = null)
    {
        $this->hotel_types = HotelTypes::where('status', 1)->get();
        $this->hotel_categories = HotelCategories::where('status', 1)->get();
        $this->parks = Parks::where('status', 1)->get();
        $this->rateTypes = RateTypes::where('status', 1)->get();
        $this->mealTypes = MealType::where('status', 1)->get();
        $this->countrys = Country::orderByRaw("CASE WHEN name = 'India' THEN 0 ELSE 1 END")
            ->orderBy('name')->pluck('name', 'country_id')->toArray();

        if ($id) {
            $hotel = Hotel::findOrFail($id);

            $this->hotelId = $id;
            $this->isEditing = true;

            $this->name = $hotel->name;
            $this->hotel_type_id = $hotel->hotel_type_id;
            $this->hotel_category_id = $hotel->hotel_category_id;
            $this->parent_chain_id = $hotel->parent_chain_id;
            $this->marketing_company_id = $hotel->marketing_company_id;
            $this->park_id = $hotel->park_id;
            $this->country_id = $hotel->country_id;
            $this->state = $hotel->state_id;
            $this->city = $hotel->city_id;
            $this->updatedCountryId($this->country_id);
            $this->updatedState($this->state);
            $this->status = $hotel->status;
            $this->rate_type = $hotel->rate_type_id;
            $this->meal_type = HotelMealPlan::where('hotel_id', $hotel->id)
                ->pluck('meal_plan_id')
                ->toArray();

            $this->loadHotelsByType($this->hotel_type_id);
        }
    }

    public function store()
    {
        $this->validate();

        $hotel = Hotel::create($this->payload());

        foreach ($this->meal_type as $mealTypeId) {
            HotelMealPlan::insert([
                'hotel_id' => $hotel->id,
                'meal_plan_id' => $mealTypeId,
            ]);
        }

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Hotel added successfully'
        ]);

        return redirect()->route('common.hotel-list');
    }

    public function update()
    {
        $this->validate();

        Hotel::findOrFail($this->hotelId)->update($this->payload());
        HotelMealPlan::where('hotel_id', $this->hotelId)->delete();
        foreach ($this->meal_type as $mealTypeId) {
            HotelMealPlan::insert([
                'hotel_id' => $this->hotelId,
                'meal_plan_id' => $mealTypeId,
            ]);
        }


        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Hotel updated successfully'
        ]);

        return redirect()->route('common.hotel-list');
    }

    public function updatedHotelTypeId($value)
    {
        $this->loadHotelsByType($value);
    }

    private function loadHotelsByType($hotelTypeId)
    {
        if ($hotelTypeId == 1) {
            $this->marketedHotels = MarketingCompany::where('status', 1)->get();
            $this->chainHotels = [];
        }

        if ($hotelTypeId == 2) {
            $this->chainHotels = Chain::where('status', 1)->get();
            $this->marketedHotels = [];
        }
    }


    private function payload(): array
    {
        return [
            'name' => ucwords($this->name),
            'hotel_type_id' => $this->hotel_type_id,
            'hotel_category_id' => $this->hotel_category_id,
            'parent_chain_id' => $this->parent_chain_id,
            'marketing_company_id' => $this->marketing_company_id,
            'park_id' => $this->park_id,
            'country_id' => $this->country_id,
            'state_id' => $this->state,
            'city_id' => $this->city,
            'status' => $this->status,
            'rate_type_id' => $this->rate_type,
        ];
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetErrorBag();

        if ($this->hotel_type_id == 2) {
            $this->modalType = 'chain';
            $this->modalTitle = 'Add Parent Chain';
        }

        if ($this->hotel_type_id == 1) {
            $this->modalType = 'marketing';
            $this->modalTitle = 'Add Marketing Company';
        }

        $this->newTitle = null;
        $this->showModel = true;
    }

    public function saveModalData()
    {
        $this->validate([
            'newTitle' => 'required|string|max:255',
        ]);

        if ($this->modalType === 'chain') {

            Chain::create([
                'title' => ucwords($this->newTitle),
                'status' => 1,
            ]);

            $this->chainHotels = Chain::where('status', 1)->get();
        }

        if ($this->modalType === 'marketing') {

            MarketingCompany::create([
                'title' => ucwords($this->newTitle),
                'status' => 1,
            ]);

            $this->marketedHotels = MarketingCompany::where('status', 1)->get();
        }

        $this->reset(['newTitle', 'showModel', 'modalType']);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Saved successfully'
        ]);
    }

    public function updatedCountryId($id)
    {
        $this->states = States::where('country_id', $id)->pluck('name', 'state_id')->toArray();
    }

    public function updatedState($id)
    {
        $this->citys = City::where('state_id', $id)->pluck('name', 'city_id')->toArray();
    }


    public function render()
    {
        return view('livewire.common.hotel-master.hotel.hotel-form');
    }
}
