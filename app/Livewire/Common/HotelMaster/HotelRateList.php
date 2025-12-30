<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\HotelRate;

#[Layout('components.layouts.common-app')]
class HotelRateList extends Component
{
    use WithPagination;

    public $search = '';
    public $pageTitle = 'Hotel Rates';

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
            ->whereHas('hotel', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.common.hotel-master.hotel-rate-list', compact('items'));
    }

    public function toggleStatus($id)
    {
        $item = HotelRate::findOrFail($id);
        $item->update(['status' => !$item->status]);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Status Changed'
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'action' => 'delete',
            'id' => $id
        ]);
    }

    #[On('delete')]
    public function delete($id)
    {
        HotelRate::destroy($id);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Hotel Rate Deleted'
        ]);
    }
}
