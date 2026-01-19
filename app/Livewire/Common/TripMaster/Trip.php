<?php

namespace App\Livewire\Common\TripMaster;

use App\Models\Quotations;
use App\Models\Trip as Model;
use App\Models\TripItem;
use Carbon\Carbon;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Trip extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $name, $search = '', $end_date, $start_date;
    public $isEditing = false;
    public $pageTitle = 'Customer Trips';

    public $model = Model::class;
    public $view = 'livewire.common.trip-master.trip';

    public $qutotions = [];
    public $selectedQuotations = [];

    public function mount()
    {
        $this->qutotions = Quotations::with(['tour', 'tourist'])
            ->select('quotation_id', 'quotation_no', 'tour_id', 'tourist_id')
            ->whereIn('status', [1, 2, 6])
            ->whereHas('prinvoice', function ($query) {
                $query->where('status', 2); // paid status
            })
            ->orderBy('updated_at', 'desc')
            ->get()
            ->mapWithKeys(function ($quotation) {
                return [
                    $quotation->quotation_id => $quotation->quotation_no . ' | '
                        . ($quotation?->tourist?->primary_contact ?? '') . ' | '
                        . ($quotation?->tour?->name ?? '')
                ];
            })
            ->toArray();
    }
    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',trip_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'start_date' => 'required',
            'end_date' => 'required',
            'selectedQuotations' => 'required|array|min:1',
            'selectedQuotations.*' => 'exists:quotations,quotation_id',
        ];
    }


    public function render()
    {
        $items = $this->model::where('name', 'like', "%{$this->search}%")
        ->whereDate('end_date', '>=', Carbon::today()) 
        ->orderBy('updated_at', 'desc')
        ->latest()->paginate(10);
        return view($this->view, compact('items'));
    }



    public function store()
    {

        $this->validate($this->rules());

        $trip = $this->model::create([
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);
        foreach ($this->selectedQuotations as $item) {
            TripItem::create([
                'trip_id' => $trip->id,
                'quotation_id' => $item
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
        $this->end_date = $item->end_date;
        $this->start_date = $item->start_date;
        $this->status = $item->status;
        $this->selectedQuotations = $item->items()
            ->pluck('quotation_id')
            ->toArray();


        $this->isEditing = true;

        $this->dispatch('initializeIconPicker');
    }

    public function update()
    {
        $this->validate($this->rules());

        $trip = $this->model::findOrFail($this->itemId);

        $trip->update([
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);

        TripItem::where('trip_id', $trip->id)->delete();
        foreach ($this->selectedQuotations as $item) {
            TripItem::create([
                'trip_id' => $trip->id,
                'quotation_id' => $item
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
        $this->model::destroy($this->itemId);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'itemId', 'isEditing', 'status', 'selectedQuotations', 'end_date', 'start_date']);
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $habitat = $this->model::findOrFail($id);
        $habitat->status = !$habitat->status;
        $habitat->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }
}
