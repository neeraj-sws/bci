<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};

use App\Models\HotelRate;
use App\Models\Hotel;
use App\Models\RoomCategory;
use App\Models\RateTypes;
use App\Models\Season;
use App\Models\MealType;
use App\Models\Occupancy;

#[Layout('components.layouts.common-app')]
class HotelRates extends Component
{
    use WithPagination;

    public $itemId;
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

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Hotel Rates';

    // Dropdowns
    public $hotels = [];
    public $roomCategories = [];
    public $rateTypes = [];
    public $seasons = [];
    public $mealPlans = [];
    public $occupancies = [];

    protected function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'room_category_id' => 'required|exists:room_categoris,room_categoris_id',
            'rate_type_id' => 'required|exists:rate_types,rate_type_id',
            'season_id' => 'required|exists:seasons,seasons_id',
            'meal_plan_id' => 'required|exists:meal_plans,meal_plans_id',
            'occupancy_id' => 'required|exists:occupances,occupancy_id',

            'weekday_rate' => 'required|numeric|min:0',
            'weekend_rate' => 'required|numeric|min:0',
            'child_rate' => 'nullable|numeric|min:0',
            'extra_bed_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
        ];
    }

    protected $validationAttributes = [
        'hotel_id' => 'Hotel',
        'room_category_id' => 'Room Category',
        'rate_type_id' => 'Rate Type',
        'season_id' => 'Season',
        'meal_plan_id' => 'Meal Plan',
        'occupancy_id' => 'Occupancy',
    ];

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->get();
        $this->roomCategories = RoomCategory::where('status', 1)->get();
        $this->rateTypes = RateTypes::where('status', 1)->get();
        $this->seasons = Season::where('status', 1)->get();
        $this->mealPlans = MealType::where('status', 1)->get();
        $this->occupancies = Occupancy::where('status', 1)->get();
    }

    public function render()
    {
        $items = HotelRate::with([
            'hotel',
            'roomCategory',
            'rateType',
            'season',
            'mealPlan',
            'occupancy'
        ])
            ->paginate(10);

        return view('livewire.common.hotel-master.hotel-rates', compact('items'));
    }

    public function store()
    {
        $this->validate();

        HotelRate::create($this->payload());

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = HotelRate::findOrFail($id);

        $this->itemId = $item->id;
        $this->fill($item->only(array_keys($this->payload())));
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        HotelRate::findOrFail($this->itemId)->update($this->payload());

        $this->resetForm();
        $this->toast('Updated Successfully');
    }

    #[On('delete')]
    public function delete()
    {
        HotelRate::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function confirmDelete($id)
    {
        $this->itemId = $id;

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, delete it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'delete'
        ]);
    }

    public function toggleStatus($id)
    {
        $item = HotelRate::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    private function payload(): array
    {
        return [
            'hotel_id' => $this->hotel_id,
            'room_category_id' => $this->room_category_id,
            'rate_type_id' => $this->rate_type_id,
            'season_id' => $this->season_id,
            'meal_plan_id' => $this->meal_plan_id,
            'occupancy_id' => $this->occupancy_id,
            'weekday_rate' => $this->weekday_rate,
            'weekend_rate' => $this->weekend_rate,
            'child_rate' => $this->child_rate,
            'extra_bed_rate' => $this->extra_bed_rate,
            'status' => $this->status,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'hotel_id',
            'room_category_id',
            'rate_type_id',
            'season_id',
            'meal_plan_id',
            'occupancy_id',
            'weekday_rate',
            'weekend_rate',
            'child_rate',
            'extra_bed_rate',
            'status',
            'isEditing',
        ]);
        $this->resetValidation();
    }

    private function toast($msg)
    {
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' ' . $msg
        ]);
    }
}
