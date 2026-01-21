<?php

namespace App\Livewire\Common\HotelMaster\Hotel;

use App\Helpers\SettingHelper;
use App\Models\Clients;
use App\Models\Hotel as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class HotelList extends Component
{
    use WithPagination;

    public $itemId;
    public $search = '';
    public $tab = 'all';
    public $pageTitle = 'Hotel List';
    public $sortBy = 'updated_at';
    public $sortDirection = 'desc';

    public $model = Model::class;
    public $view = 'livewire.common.hotel-master.hotel.hotel-list';

    public $route;

    public function mount($id = null)
    {
        $this->route = 'common';
    }

    public function render()
    {
        $query = $this->model::query()
            ->leftJoin('hotel_types', 'hotels.hotel_type_id', '=', 'hotel_types.hotel_type_id')
            ->leftJoin('hotel_categories', 'hotels.hotel_category_id', '=', 'hotel_categories.hotel_category_id')
            ->leftJoin('rate_types', 'hotels.rate_type_id', '=', 'rate_types.rate_type_id')
            ->with(['hotelType', 'hotelCategory', 'hotelRateType', 'hotelMealType.mealType'])
            ->where('hotels.name', 'like', "%{$this->search}%");

        $sortable = [
            'name'            => 'hotels.name',
            'status'          => 'hotels.status',
            'created_at'      => 'hotels.created_at',
            'updated_at'      => 'hotels.updated_at',
            'hotel_type'      => 'hotel_types.title',
            'hotel_category'  => 'hotel_categories.title',
            'rate_type'       => 'rate_types.title',
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

        $allowed = ['name', 'status', 'updated_at', 'created_at', 'hotel_type', 'hotel_category', 'rate_type'];
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
