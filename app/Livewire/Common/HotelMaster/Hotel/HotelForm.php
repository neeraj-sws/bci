<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Hotel;
use App\Models\HotelTypes;
use App\Models\HotelCategories;

#[Layout('components.layouts.common-app')]
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
    public $location;
    public $status = 1;

    // Dropdown data
    public $hotel_types = [];
    public $hotel_categories = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'hotel_type_id' => 'required|exists:hotel_types,hotel_type_id',
            'hotel_category_id' => 'required|exists:hotel_categories,hotel_category_id',
            'parent_chain_id' => 'nullable|integer',
            'marketing_company_id' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'status' => 'required|boolean',
        ];
    }

    protected $validationAttributes = [
        'hotel_type_id' => 'Hotel Type',
        'hotel_category_id' => 'Hotel Category',
        'parent_chain_id' => 'Parent Chain',
        'marketing_company_id' => 'Marketing Company',
    ];

    public function mount($id = null)
    {
        $this->hotel_types = HotelTypes::where('status', 1)->get();
        $this->hotel_categories = HotelCategories::where('status', 1)->get();

        if ($id) {
            $hotel = Hotel::findOrFail($id);

            $this->hotelId = $id;
            $this->isEditing = true;

            $this->name = $hotel->name;
            $this->hotel_type_id = $hotel->hotel_type_id;
            $this->hotel_category_id = $hotel->hotel_category_id;
            $this->parent_chain_id = $hotel->parent_chain_id;
            $this->marketing_company_id = $hotel->marketing_company_id;
            $this->location = $hotel->location;
            $this->status = $hotel->status;
        }
    }

    public function store()
    {
        $this->validate();

        Hotel::create($this->payload());

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Hotel added successfully'
        ]);

        return redirect()->route('common.hotels');
    }

    public function update()
    {
        $this->validate();

        Hotel::findOrFail($this->hotelId)->update($this->payload());

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Hotel updated successfully'
        ]);

        return redirect()->route('common.hotels');
    }

    private function payload(): array
    {
        return [
            'name' => ucwords($this->name),
            'hotel_type_id' => $this->hotel_type_id,
            'hotel_category_id' => $this->hotel_category_id,
            'parent_chain_id' => $this->parent_chain_id,
            'marketing_company_id' => $this->marketing_company_id,
            'location' => $this->location,
            'status' => $this->status,
        ];
    }

    public function render()
    {
        return view('livewire.common.hotel-master.hotel.hotel-form');
    }
}
