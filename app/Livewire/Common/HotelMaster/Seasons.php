<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\Season;
use App\Models\Hotel;
use App\Models\PeackDate;
use App\Models\RoomCategoryOccupances;

#[Layout('components.layouts.hotel-app')]
class Seasons extends Component
{
    use WithPagination;

    public $itemId;
    public $name;
    public $start_date;
    public $end_date;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Season';


    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
        ];
    }

    protected $validationAttributes = [
        'name' => 'Season Name',
        'hotel_id' => 'Hotel',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
    ];


    public function render()
    {
        $items = Season::where('name', 'like', "%{$this->search}%")
            ->orderBy('start_date')
            ->paginate(10);

        return view('livewire.common.hotel-master.seasons', compact('items'));
    }

    private function seasonOverlaps(): bool
    {
        return Season::where(function ($q) {
            $q->whereDate('start_date', '<=', $this->end_date)
                ->whereDate('end_date', '>=', $this->start_date);
        })
            ->when($this->itemId, fn($q) => $q->where('id', '!=', $this->itemId))
            ->exists();
    }


    public function store()
    {
        $this->validate();
        if ($this->seasonOverlaps()) {
            $this->addError(
                'start_date',
                'This season overlaps with an existing season. Overlapping months or dates are not allowed.'
            );
            return;
        }
        Season::create($this->payload());

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = Season::findOrFail($id);

        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->start_date = $item->start_date;
        $this->end_date = $item->end_date;
        $this->status = $item->status;

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();
        if ($this->seasonOverlaps()) {
            $this->addError(
                'start_date',
                'This season overlaps with an existing season. Overlapping months or dates are not allowed.'
            );
            return;
        }
        Season::findOrFail($this->itemId)->update($this->payload());

        $this->resetForm();
        $this->toast('Updated Successfully');
    }

    public function confirmDelete($id)
    {
        // Check if season is being used in peak_dates table
        $seasonUsageCount = PeackDate::where('season_id', $id)->count();

        if ($seasonUsageCount > 0) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => 'Cannot delete this season. It is being used in ' . $seasonUsageCount . ' peak date(s).'
            ]);
            return;
        }

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
        // Double-check before deletion
        $seasonUsageCount = PeackDate::where('season_id', $this->itemId)->count();

        if ($seasonUsageCount > 0) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'message' => 'Cannot delete this season. It is being used in ' . $seasonUsageCount . ' peak date(s).'
            ]);
            return;
        }

        Season::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function toggleStatus($id)
    {
        $item = Season::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    private function payload(): array
    {
        return [
            'name' => ucwords($this->name),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'name',
            'start_date',
            'end_date',
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
