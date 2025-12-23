<?php

namespace App\Livewire\Common\Items;

use App\Models\Items as Model;
use App\Models\Taxes;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component,WithPagination};

#[Layout('components.layouts.common-app')]
class Item extends Component
{
    use WithPagination;

    public $itemId;
    public $status=1;
    public $name,$rate,$taxes,$tax_id,$type,$description,$search = '';
    public $sku,$unit;
    public $tab = 'active';

    public $isEditing = false;
    public $pageTitle = 'Items';

    public $model = Model::class;
    public $view = 'livewire.common.items.item';



    public function rules()
    {
        $table = (new $this->model)->getTable();

        return [
            'name' => $this->isEditing
                ? 'required|string|max:255|unique:' . $table . ',name,' . $this->itemId . ',item_id'
                : 'required|string|max:255|unique:' . $table . ',name',
            'rate'=>'required|numeric',
            'tax_id'=>'required|numeric',
            'type'=>'required',
            'description'=>'required',
        ];
    }

    public function render()
    {

        $query = $this->model::query()
        ->where('name', 'like', "%{$this->search}%");
 $inactiveCount = $this->model::where('status', '0')->count();
    $activeCount = $this->model::where('status', '1')->count();
        if ($this->tab === 'active') {
            $query->where('status', '1');
        } else {
            $query->where('status', '0');
        }

        $items = $query->orderBy('updated_at', 'desc')->paginate(10);

        $this->taxes = Taxes::all()->pluck('tax_name', 'id');

       return view($this->view, compact('items','inactiveCount', 'activeCount'));
    }



    public function store()
    {
        $this->validate($this->rules());

        $this->model::create([
            'name' => $this->name,
            'sku' => $this->sku,
            'rate' => $this->rate,
            'unit' => $this->unit,
            'tax_id' => $this->tax_id,
            'type' => $this->type,
            'status' => $this->status,
            'description' => $this->description,
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
        $this->sku = $item->sku;
        $this->rate = $item->rate;
        $this->unit = $item->unit;
        $this->tax_id = $item->tax_id;
        $this->type = $item->type;
        $this->description = $item->description;
        $this->status = $item->status;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate($this->rules());

        $this->model::findOrFail($this->itemId)->update([
            'name' => $this->name,
            'sku' => $this->sku,
            'rate' => $this->rate,
            'unit' => $this->unit,
            'tax_id' => $this->tax_id,
            'type' => $this->type,
            'description' => $this->description,
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
        $this->reset(['itemId', 'isEditing' ,'name',
        'sku',
        'rate',
        'unit',
        'tax_id',
        'type',
        'status',
        'description']);
        $this->resetValidation();
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
