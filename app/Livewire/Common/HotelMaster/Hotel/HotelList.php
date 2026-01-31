<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use App\Helpers\SettingHelper;
use App\Models\Clients;
use App\Models\Hotel as Model;
use App\Models\Country;
use App\Models\States;
use App\Models\City;
use App\Models\Parks;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.hotel-app')]
class HotelList extends Component
{
    use WithPagination;

    public $itemId;
    public $search = '';
    public $tab = 'all';
    public $pageTitle = 'Hotel List';
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';
    
    // Filters
    public $filterPark = '';
    public $filterCountry = '';
    public $filterState = '';
    public $filterCity = '';
    
    // Filter Data
    public $parks = [];
    public $countries = [];
    public $states = [];
    public $cities = [];

    public $model = Model::class;
    public $view = 'livewire.common.hotel-master.hotel.hotel-list';

    public $route;

    public function mount($id = null)
    {
        $this->route = 'common';
        // Load only essential data on mount
        $this->parks = Parks::where('status', 1)->orderBy('name')->pluck('name', 'park_id');
        $this->countries = Country::orderByRaw("CASE WHEN name = 'India' THEN 0 ELSE 1 END")->orderBy('name')->pluck('name', 'country_id');
    }
    
    public function loadStates()
    {
        if ($this->filterCountry) {
            $this->states = States::where('country_id', $this->filterCountry)->orderBy('name')->pluck('name', 'state_id');
        } else {
            $this->states = [];
        }
    }
    
    public function loadCities()
    {
        if ($this->filterState) {
            $this->cities = City::where('state_id', $this->filterState)->orderBy('name')->pluck('name', 'city_id');
        } else {
            $this->cities = [];
        }
    }
    
    public function updatedFilterCountry()
    {
        $this->filterState = '';
        $this->filterCity = '';
        $this->loadStates();
        $this->loadCities();
        $this->resetPage();
    }
    
    public function updatedFilterState()
    {
        $this->filterCity = '';
        $this->loadCities();
        $this->resetPage();
    }
    
    public function clearFilters()
    {
        $this->filterPark = '';
        $this->filterCountry = '';
        $this->filterState = '';
        $this->filterCity = '';
        $this->search = '';
        $this->states = [];
        $this->cities = [];
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->model::query()
            ->leftJoin('hotel_types', 'hotels.hotel_type_id', '=', 'hotel_types.hotel_type_id')
            ->leftJoin('hotel_categories', 'hotels.hotel_category_id', '=', 'hotel_categories.hotel_category_id')
            ->leftJoin('rate_types', 'hotels.rate_type_id', '=', 'rate_types.rate_type_id')
            ->leftJoin('parks', 'hotels.park_id', '=', 'parks.park_id')
            ->leftJoin('country', 'hotels.country_id', '=', 'country.country_id')
            ->leftJoin('states', 'hotels.state_id', '=', 'states.state_id')
            ->leftJoin('city', 'hotels.city_id', '=', 'city.city_id')
            ->with(['hotelType', 'hotelCategory', 'hotelRateType', 'hotelMealType.mealType', 'park', 'country', 'state', 'city'])
            ->where('hotels.name', 'like', "%{$this->search}%");
        
        // Apply filters
        if ($this->filterPark) {
            $query->where('hotels.park_id', $this->filterPark);
        }
        if ($this->filterCountry) {
            $query->where('hotels.country_id', $this->filterCountry);
        }
        if ($this->filterState) {
            $query->where('hotels.state_id', $this->filterState);
        }
        if ($this->filterCity) {
            $query->where('hotels.city_id', $this->filterCity);
        }

        $sortable = [
            'name'            => 'hotels.name',
            'status'          => 'hotels.status',
            'created_at'      => 'hotels.created_at',
            'updated_at'      => 'hotels.updated_at',
            'hotel_type'      => 'hotel_types.title',
            'hotel_category'  => 'hotel_categories.title',
            'rate_type'       => 'rate_types.title',
            'park'            => 'parks.name',
            'country'         => 'country.name',
            'state'           => 'states.name',
            'city'            => 'city.name',
        ];

        $sortField = $sortable[$this->sortBy] ?? 'hotels.updated_at';

        if ($this->tab === 'active') {
            $query->where('hotels.status', 1);
        } elseif ($this->tab === 'inactive') {
            $query->where('hotels.status', 0);
        }

        $items = $query
            ->select('hotels.*')
            ->orderBy($sortField, $this->sortDirection)
            ->paginate(10);

        $inactiveCount = $this->model::where('status', 0)->count();
        $activeCount   = $this->model::where('status', 1)->count();
        $allCount      = $this->model::count();

        return view($this->view, compact('items', 'inactiveCount', 'activeCount', 'allCount'));
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
        $this->model::destroy($this->itemId);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }
    public function toggleStatus($id)
    {
        $habitat = $this->model::findOrFail($id);
        $habitat->status = !$habitat->status;
        $habitat->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function shortby($field)
    {

        $allowed = ['name', 'status', 'updated_at', 'created_at', 'hotel_type', 'hotel_category', 'rate_type', 'park', 'country', 'state', 'city'];
        if (!in_array($field, $allowed)) return;
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updating()
    {
        $this->resetPage();
    }
}
