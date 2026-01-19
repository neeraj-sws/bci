<?php

namespace App\Livewire\Common\TripMaster;

use App\Models\Trip as Model;
use Carbon\Carbon;
use Livewire\Attributes\{Layout};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Archive extends Component
{
    use WithPagination;
    public $search = '';

    public $pageTitle = 'Customer Trips Archive';

    public $model = Model::class;
    public $view = 'livewire.common.trip-master.archive';

    public function render()
    {
        $items = $this->model::where('name', 'like', "%{$this->search}%")
        ->whereDate('end_date', '<', Carbon::today()) 
        ->orderBy('updated_at', 'desc')
        ->latest()->paginate(10);
        return view($this->view, compact('items'));
    }
}
