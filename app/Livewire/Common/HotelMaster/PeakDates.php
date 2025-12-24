<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};
use App\Models\PeackDate;
use App\Models\Hotel;

#[Layout('components.layouts.common-app')]
class PeakDates extends Component
{
    use WithPagination;

    public $itemId;
    public $title;
    public $hotel_id;
    public $start_date;
    public $end_date;
    public $is_new_year = 0;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Peak Dates';

    public $hotels = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_new_year' => 'nullable|boolean',
            'status' => 'required|in:0,1',
        ];
    }

    protected $validationAttributes = [
        'title' => 'Peak Date Title',
        'hotel_id' => 'Hotel',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'is_new_year' => 'New Year Peak',
    ];

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->orderBy('name')->get();
    }

    public function render()
    {
        $items = PeackDate::with('hotel')
            ->where('title', 'like', "%{$this->search}%")
            ->orderBy('start_date')
            ->paginate(10);

        return view('livewire.common.hotel-master.peak-dates', compact('items'));
    }

    public function store()
    {
        $this->validate();

        PeackDate::create($this->payload());

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = PeackDate::findOrFail($id);

        $this->itemId = $item->id;
        $this->title = $item->title;
        $this->hotel_id = $item->hotel_id;
        $this->start_date = $item->start_date;
        $this->end_date = $item->end_date;
        $this->is_new_year = $item->is_new_year;
        $this->status = $item->status;

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        PeackDate::findOrFail($this->itemId)->update($this->payload());

        $this->resetForm();
        $this->toast('Updated Successfully');
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
        PeackDate::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function toggleStatus($id)
    {
        $item = PeackDate::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    private function payload(): array
    {
        return [
            'title' => ucwords($this->title),
            'hotel_id' => $this->hotel_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_new_year' => $this->is_new_year ? 1 : 0,
            'status' => $this->status,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'title',
            'hotel_id',
            'start_date',
            'end_date',
            'is_new_year',
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
