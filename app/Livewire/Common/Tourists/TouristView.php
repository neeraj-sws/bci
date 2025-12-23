<?php

namespace App\Livewire\Common\Tourists;

use App\Helpers\SettingHelper;
use App\Models\Tourists;
use App\Models\Invoices;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class TouristView extends Component
{
    use WithPagination;
    public $view = 'livewire.common.tourists.tourist-view';

    public $clienId, $clientInfo, $totalSales, $totalPaid, $totalDue;
    public $route;

    public function mount($id)
    {
        $this->clienId = $id;
        $this->clientInfo = Tourists::findOrFail($id);
        $this->totalSales = Invoices::where('tourist_id', $id)->sum('amount');
        $this->totalPaid = Invoices::where('status', 2)->where('tourist_id', $id)->sum('amount');
        $this->totalDue = Invoices::where('status', '!=', 2)->where('tourist_id', $id)->sum('amount');
        
        $this->route = 'common';
    }

    public function render()
    {
        return view($this->view);
    }
}
