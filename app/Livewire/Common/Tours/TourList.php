<?php

namespace App\Livewire\Common\Tours;

use App\Helpers\SettingHelper;
use App\Models\Clients;
use App\Models\Tours as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class TourList extends Component
{
    use WithPagination;

    public $itemId;
    public $search = '';
    public $tab = 'all';
    public $pageTitle = 'Tour Master';

    public $model = Model::class;
    public $view = 'livewire.common.tours.tour-list';

    public $route;

    public function mount($id = null)
    {
        $this->route = 'common';
    }

    public function render()
    {
        $query = $this->model::query()
            ->where('name', 'like', "%{$this->search}%");

        $inactiveCount = $this->model::where('status', '0')->count();
        $activeCount = $this->model::where('status', '1')->count();
        $allCount = $this->model::count();

        if ($this->tab === 'active') {
            $query->where('status', '1');
        } else if ($this->tab === 'inactive') {
            $query->where('status', '0');
        }
        $items = $query->orderBy('updated_at', 'desc')->paginate(10);
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
        $model = Model::find($this->itemId);
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
}
