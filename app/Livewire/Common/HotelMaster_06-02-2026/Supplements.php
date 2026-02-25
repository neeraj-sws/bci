<?php

namespace App\Livewire\Common\HotelMaster;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\{Layout, On};

use App\Models\Supplement;
use App\Models\Hotel;
use App\Models\PeackDate;

#[Layout('components.layouts.common-app')]
class Supplements extends Component
{
    use WithPagination;

    public $itemId;
    public $hotel_id;
    public $peak_date_id;
    public $title;
    public $amount;
    public $mandatory = 0;
    public $status = 1;

    public $search = '';
    public $isEditing = false;
    public $pageTitle = 'Supplements';

    // Dropdown data
    public $hotels = [];
    public $peakDates = [];

    protected function rules()
    {
        return [
            'hotel_id' => 'required|exists:hotels,hotels_id',
            'peak_date_id' => 'nullable|exists:peak_dates,peak_dates_id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'mandatory' => 'nullable|boolean',
            'status' => 'required|in:0,1',
        ];
    }

    protected $validationAttributes = [
        'hotel_id' => 'Hotel',
        'peak_date_id' => 'Peak Date',
        'title' => 'Supplement Title',
        'amount' => 'Amount',
        'mandatory' => 'Mandatory',
    ];

    public function mount()
    {
        $this->hotels = Hotel::where('status', 1)->orderBy('name')->get();
        $this->peakDates = PeackDate::where('status', 1)->orderBy('start_date')->get();
    }

    public function render()
    {
        $items = Supplement::with(['hotel'])
            ->where('title', 'like', "%{$this->search}%")
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.common.hotel-master.supplements', compact('items'));
    }

    public function store()
    {
        $this->validate();

        Supplement::create($this->payload());

        $this->resetForm();
        $this->toast('Added Successfully');
    }

    public function edit($id)
    {
        $item = Supplement::findOrFail($id);

        $this->itemId = $item->id;
        $this->hotel_id = $item->hotel_id;
        $this->peak_date_id = $item->peak_date_id;
        $this->title = $item->title;
        $this->amount = $item->amount;
        $this->mandatory = $item->mandatory;
        $this->status = $item->status;

        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        Supplement::findOrFail($this->itemId)->update($this->payload());

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
        Supplement::destroy($this->itemId);
        $this->toast('Deleted Successfully');
    }

    public function toggleStatus($id)
    {
        $item = Supplement::findOrFail($id);
        $item->update(['status' => !$item->status]);
        $this->toast('Status Changed');
    }

    private function payload(): array
    {
        return [
            'hotel_id' => $this->hotel_id,
            'peak_date_id' => $this->peak_date_id,
            'title' => ucwords($this->title),
            'amount' => $this->amount,
            'mandatory' => $this->mandatory ? 1 : 0,
            'status' => $this->status,
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'itemId',
            'hotel_id',
            'peak_date_id',
            'title',
            'amount',
            'mandatory',
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
