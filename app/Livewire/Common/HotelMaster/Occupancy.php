<?php

namespace App\Livewire\Common\HotelMaster;

use App\Models\Occupancy as Model;
use App\Models\PeakDateRoomCategoryOccupances;
use App\Models\RoomCategoryOccupances;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Occupancy extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $title, $search = '';
    public $isEditing = false;
    public $pageTitle = 'ocupancy';

    public $model = Model::class;
    public $view = 'livewire.common.hotel-master.occupancy';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'title' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',title,' . $this->itemId . ',occupancy_id'
                : 'required|string|max:255|unique:' . $table . ',title',
        ];
    }


    public function render()
    {
        $items = $this->model::where('title', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

        return view($this->view, compact('items'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'title' => $this->title,
            'status' => $this->status,
        ]);

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
        $this->title = $item->title;
        $this->status = $item->status;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'title' => $this->title,
            'status' => $this->status
        ]);

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

        $occupancy = $this->model::find($id);

        if ($occupancy) {
            $roomOccupancyCount = RoomCategoryOccupances::where('occupancy_id', $id)->count();
            $peakOccupancyCount = PeakDateRoomCategoryOccupances::where('occupancy_id', $id)->count();

            if ($roomOccupancyCount > 0 || $peakOccupancyCount > 0) {
                $this->dispatch('swal:toast', [
                    'type' => 'error',
                    'title' => 'Cannot Delete',
                    'message' => 'This occupancy is being used in ' . ($roomOccupancyCount + $peakOccupancyCount) . ' room/peak date configuration(s). Please remove those first.'
                ]);
                return;
            }
        }

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
        $this->reset(['title', 'itemId', 'isEditing', 'status']);
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
