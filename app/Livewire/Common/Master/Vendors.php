<?php

namespace App\Livewire\Common\Master;

use App\Models\City;
use App\Models\Country;
use App\Models\ServiceLocations;
use App\Models\States;
use App\Models\Vehicles;
use App\Models\Vendors as Model;
use App\Models\VendorServiceArea;
use App\Models\VendorServiceLocations;
use App\Models\VendorsVehicles;
// use App\Models\VendorTypes;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};
use App\Models\IncomeExpenseCategory;
use App\Models\IncomeExpenseSubCategory;

#[Layout('components.layouts.common-app')]
class Vendors extends Component
{
    use WithPagination;

    public $itemId, $showModal = false, $vechileEdit = false, $vechileIndex;
    public $status = 1;
    public $name, $search = '', $contact, $secondary_contact, $city_id, $address;
    public $cities = [], $vehicles;
    public $isEditing = false;
    public $pageTitle = 'Vendors';

    public $model = Model::class;
    public $view = 'livewire.common.master.vendors';

    public $vehicle_id, $day_charge, $night_charge;
    public $vehiclesData = [];

    public $types, $type_id;

    public $states = [], $countrys = [];
    public $country = '101', $state;


    public $is_taxi = 0, $notes, $serviceAreas = [], $service_area_id;


    public $showLoModal = false;
    public $selectedServiceArea = [];
    public $baseLocations = [];

    public string $serviceAreaNames = '';

    public $type, $location, $vehicle;



    public $subcategorys = [], $sub_type_id;


    public function mount()
    {
        $this->countrys = Country::pluck('name', 'country_id')->toArray();
        $this->vehicles = Vehicles::where('status', 1)->pluck('name', 'vehicle_id');
        $this->types = IncomeExpenseCategory::where('type', 1)
            ->where('status', 1)->pluck('name', 'income_expense_category_id');

        $this->updatedCountry($this->country);


        $this->serviceAreas = ServiceLocations::where('soft_delete', 0)
            ->where('status', 1)
            ->orderBy('name')
            ->get();


        $this->baseLocations = ServiceLocations::where('soft_delete', 0)
            ->where('status', 1)
            ->pluck('name', 'service_location_id');

        $this->selectedServiceArea = [];
    }

    public function rules()
    {
        $table = (new $this->model)->getTable();
        $isTaxi  = $this->is_taxi ? 'required' : 'nullable';

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',vendor_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'contact' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city_id' => 'required',
            'type_id' => 'required',
            'sub_type_id' => 'required',
            'service_area_id' => $isTaxi,
        ];
    }

    public function render()
    {

        $items = $this->model::query()

            // Search by vendor name
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
            )

            // Filter by vendor type
            ->when(
                $this->type,
                fn($q) =>
                $q->where('type_id', $this->type)
            )

            ->when(
                $this->vehicle,
                fn($q) =>
                $q->whereHas(
                    'vehicles',
                    fn($qr) =>
                    $qr->where('vehicle_id', $this->vehicle)
                )
            )

            ->when(
                $this->location,
                fn($q) =>
                $q->whereHas(
                    'serviceLocations',
                    fn($qr) =>
                    $qr->where('vendor_service_area_id', $this->location)
                )
            )
            // ->where('soft_delete', 0)
            ->with(['vehicles', 'type'])
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view($this->view, compact('items'));
    }

    public function store()
    {
        $this->validate($this->rules());

        $vendor = $this->model::create([
            'name' => ucwords($this->name),
            'contact' => $this->contact,
            'secondary_contact' => $this->secondary_contact,
            'state_id' => $this->state,
            'country_id' => $this->country,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'status' => $this->status,
            'type_id' => $this->type_id,
            'sub_type_id' => $this->sub_type_id,
            'notes' => $this->notes,
            'base_location_id' => $this->service_area_id,
        ]);

        foreach ($this->vehiclesData as $vehicle) {
            VendorsVehicles::create([
                'vendor_id' => $vendor->id,
                'vehicle_id' => $vehicle['vehicle_id'],
                'day_charge' => $vehicle['day_charge'],
                // 'night_charge' => $vehicle['night_charge'],
            ]);
        }

        // Save pivot table entries
        foreach ($this->selectedServiceArea as $areaId) {
            VendorServiceLocations::create([
                'vendor_id' => $vendor->id,
                'vendor_service_area_id' => $areaId
            ]);
        }

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
    }

    public function edit($id)
    {
        $this->resetForm();

        $item = $this->model::findOrFail($id);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->contact = $item->contact;
        $this->secondary_contact = $item->secondary_contact;
        $this->country = $item->country_id;
        $this->state = $item->state_id;
        $this->city_id = $item->city_id;
        $this->address = $item->address;
        $this->type_id = $item->type_id;
        $this->sub_type_id = $item->sub_type_id;
        $this->status = $item->status;
        $this->notes = $item->notes;
        $this->service_area_id = $item->base_location_id;

        $this->updatedCountry($item->country_id);
        $this->updatedState($item->state_id);
        $this->updatedTypeId($item->type_id, true);
        $this->updatedSubTypeId($this->sub_type_id);


        $this->vehiclesData = VendorsVehicles::where('vendor_id', $item->id)
            ->get(['vehicle_id', 'day_charge', 'night_charge'])
            ->toArray();


        $this->selectedServiceArea = VendorServiceLocations::where('vendor_id', $id)
            ->pluck('vendor_service_area_id')
            ->toArray();

        $serviceAreas = ServiceLocations::where('soft_delete', 0)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $this->serviceAreas = $serviceAreas->sortBy(function ($area) {
            return in_array($area->service_location_id, $this->selectedServiceArea) ? 0 : 1;
        })->values();

        $this->updatedSelectedServiceArea();


        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $vendor = $this->model::findOrFail($this->itemId);

        $vendor->update([
            'name' => ucwords($this->name),
            'contact' => $this->contact,
            'secondary_contact' => $this->secondary_contact,
            'state_id' => $this->state,
            'country_id' => $this->country,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'status' => $this->status,
            'type_id' => $this->type_id,
            'sub_type_id' => $this->sub_type_id,
            'notes' => $this->notes,
            'base_location_id' => $this->service_area_id,
        ]);

        VendorsVehicles::where('vendor_id', $vendor->id)->delete();

        foreach ($this->vehiclesData as $vehicle) {
            VendorsVehicles::create([
                'vendor_id' => $vendor->id,
                'vehicle_id' => $vehicle['vehicle_id'],
                'day_charge' => $vehicle['day_charge'],
                // 'night_charge' => $vehicle['night_charge'],
            ]);
        }

        // Delete old selections
        VendorServiceLocations::where('vendor_id', $this->itemId)->delete();

        // Insert updated selections
        foreach ($this->selectedServiceArea as $areaId) {
            VendorServiceLocations::create([
                'vendor_id' => $this->itemId,
                'vendor_service_area_id' => $areaId
            ]);
        }

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);
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

    #[On('delete')]
    public function delete()
    {
        $model =  $this->model::where('vendor_id', $this->itemId)->first();
        $model->soft_name = $model->name;
        $model->name = null;
        $model->save();
        $model->delete();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'itemId',
            'isEditing',
            'status',
            'contact',
            'secondary_contact',
            'city_id',
            'country',
            'state',
            'address',
            'vehiclesData',
            'showModal',
            'type_id',
            'sub_type_id',
            'notes',
            'service_area_id',
            'selectedServiceArea',
            'serviceAreaNames',
            'is_taxi'
        ]);
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $vendor = $this->model::findOrFail($id);
        $vendor->status = !$vendor->status;
        $vendor->save();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Status Changed Successfully'
        ]);
    }


    public function addVehicle()
    {
        $this->validate([
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'day_charge' => 'required|numeric|min:0',
            // 'night_charge' => 'required|numeric|min:0',
        ]);
        if (collect($this->vehiclesData)->contains('vehicle_id', $this->vehicle_id)) {
            $this->addError('vehicle_id', 'This vehicle is already added.');
            return;
        }

        $this->vehiclesData[] = [
            'vehicle_id' => $this->vehicle_id,
            'day_charge' => $this->day_charge,
            // 'night_charge' => $this->night_charge,
        ];

        $this->resetVehicleForm();
    }

    public function editVehicle($index)
    {
        $vehicle = $this->vehiclesData[$index];

        $this->vehicle_id = $vehicle['vehicle_id'];
        $this->day_charge = $vehicle['day_charge'];
        $this->night_charge = $vehicle['night_charge'];
        $this->vechileIndex = $index;
        $this->vechileEdit = true;
        $this->showModal = true;
    }

    public function editVechileStore()
    {
        if (isset($this->vehiclesData[$this->vechileIndex])) {
            $this->vehiclesData[$this->vechileIndex] = [
                'vehicle_id' => $this->vehicle_id,
                'day_charge' => $this->day_charge,
                'night_charge' => $this->night_charge,
            ];
        }
        $this->resetVehicleForm();
    }

    public function removeVehicle($index)
    {
        unset($this->vehiclesData[$index]);
        $this->vehiclesData = array_values($this->vehiclesData);
    }

    public function resetVehicleForm()
    {
        $this->reset(['vehicle_id', 'day_charge', 'night_charge', 'showModal', 'vechileEdit']);
        $this->resetValidation();
    }

    public function showModel()
    {
        $this->showModal = true;
    }

    public function updatedCountry($id)
    {
        $this->states = States::where('country_id', $id)->pluck('name', 'state_id')->toArray();
    }

    public function updatedState($id)
    {
        $this->cities = City::where('state_id', $id)->pluck('name', 'city_id')->toArray();
    }
    public function updatedTypeId($id, $isclear)
    {
        if (!$isclear) {
            $this->sub_type_id = null;
        }
        $this->subcategorys = IncomeExpenseSubCategory::where('category_id', $id)
            ->where('type', 1)
            ->pluck('name', 'income_expense_sub_category_id');
    }

    public function updatedSubTypeId($id)
    {
        $this->is_taxi = IncomeExpenseSubCategory::where('income_expense_sub_category_id', $id)
            ->value('is_taxi');
    }

    public function openServiceAreaModal()
    {
        $this->showLoModal = true;
    }
    public function toggleArea($id)
    {
        if (in_array($id, $this->selectedServiceArea)) {
            $this->selectedServiceArea = array_diff($this->selectedServiceArea, [$id]);
        } else {
            $this->selectedServiceArea[] = $id;
        }

        $this->updatedSelectedServiceArea();
    }
    public function updatedSelectedServiceArea()
    {
        $this->serviceAreaNames = ServiceLocations::whereIn('service_location_id', $this->selectedServiceArea)
            ->pluck('name')        // get only names
            ->implode(', ');       // convert to comma-separated string
    }

    public function clearFilters()
    {
        $this->reset(['type', 'vehicle', 'location', 'search']);
    }
}
