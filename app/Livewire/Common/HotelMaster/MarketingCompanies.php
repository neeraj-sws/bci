<?php

namespace App\Livewire\Common\HotelMaster;

use App\Models\MarketingCompanies as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.hotel-app')]
class MarketingCompanies extends Component
{
    use WithPagination;

    public $itemId;
    public $status = 1;
    public $name, $contact_person, $email, $phone, $search = '';
    public $isEditing = false;
    public $pageTitle = 'Marketing Companies';

    public $model = Model::class;
    public $view = 'livewire.common.hotel-master.marketing-companies';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',marketing_company_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'contact_person' => 'nullable|string|max:255',
            'email' => $this->isEditing
                ? 'nullable|email|max:255|unique:' . $table . ',email,' . $this->itemId . ',marketing_company_id'
                : 'nullable|email|max:255|unique:' . $table . ',email',
            'phone' => 'nullable|string|max:15',
        ];
    }


    public function render()
    {
        $items = $this->model::where('name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

        return view($this->view, compact('items'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
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
        $this->name = $item->name;
        $this->contact_person = $item->contact_person;
        $this->email = $item->email;
        $this->phone = $item->phone;
        $this->status = $item->status;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
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
        $this->reset(['name', 'contact_person', 'email', 'phone', 'itemId', 'isEditing', 'status']);
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $markteing = $this->model::findOrFail($id);
        $markteing->status = !$markteing->status;
        $markteing->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }
}
