<?php

namespace App\Livewire\Common\Resorts;

use App\Models\Parks;
use App\Models\ResortCategorys;
use Livewire\Component;
use App\Models\Resorts as Model;
use Livewire\Attributes\{Layout, On};
use App\Models\Zones;
use Livewire\WithPagination;


#[Layout('components.layouts.common-app')]

class Resort extends Component
{
    use WithPagination;

    public $resortId;
    public $name;
    public $phone;
    public $park_id;
    public $location_gate;
    public $address;
    public $primary_contact;
    public $secondary_phone;
    public $zone_id;
    public $drive_link;
    public $isEditing = false;
    public $isFormOpen = false;
    public $search = '';

    // Room category properties
    public $categories = [];
    public $categoryId;
    public $category_name;
    public $regular_rate;
    public $high_season_rate;
    public $extra_child_rate;
    public $extra_adult_rate;
    public $isCategoryFormOpen = false;
    public $editingCategoryIndex = null;

    public $pageTitle = 'Resort';

    public $model = Model::class,$zones=[];

    public function mount()
    {
        $this->addEmptyCategory();
    }

    public function render()
    {
        $resorts = $this->model::with('categories')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('primary_contact', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        $parks = Parks::all()->pluck('name', 'id');

        return view('livewire.common.resorts.resort', compact('resorts', 'parks'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->isCategoryFormOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'resortId', 'name', 'phone', 'park_id', 'location_gate',
            'address', 'primary_contact', 'secondary_phone', 'zone_id', 'drive_link',
            'isEditing', 'categories', 'editingCategoryIndex'
        ]);

        $this->addEmptyCategory();
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'primary_contact' => 'required',
            'park_id' => 'required|exists:parks,park_id',
            'location_gate' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,zone_id',
            'drive_link' => 'required|url',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.regular_rate' => 'required|numeric|min:0',
            'categories.*.high_season_rate' => 'required|numeric|min:0',
            'categories.*.extra_child_rate' => 'required|numeric|min:0',
            'categories.*.extra_adult_rate' => 'required|numeric|min:0',
        ]);

        $resort = $this->model::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'primary_contact' => $this->primary_contact,
            'park_id' => $this->park_id,
            'location_gate' => $this->location_gate,
            'address' => $this->address,
            'secondary_phone' => $this->secondary_phone,
            'zone_id' => $this->zone_id,
            'drive_link' => $this->drive_link,
        ]);

        foreach ($this->categories as $category) {
            $resort->categories()->create($category);
        }

        $this->closeForm();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
    }

    public function edit($id)
    {
        $resort = $this->model::with('categories')->findOrFail($id);

        $this->resortId = $resort->id;
        $this->name = $resort->name;
        $this->phone = $resort->phone;
        $this->park_id = $resort->park_id;
        $this->location_gate = $resort->location_gate;
        $this->address = $resort->address;
        $this->primary_contact = $resort->primary_contact;
        $this->secondary_phone = $resort->secondary_phone;
        $this->zone_id = $resort->zone_id;
        $this->drive_link = $resort->drive_link;

        $this->categories = $resort->categories->toArray();

               $this->updatedParkId($this->park_id, true);

        $this->isEditing = true;
        $this->isFormOpen = true;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'primary_contact' => 'required',
            'park_id' => 'required|exists:parks,park_id',
            'location_gate' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'zone_id' => 'required|exists:zones,zone_id',
            'drive_link' => 'required|url',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.regular_rate' => 'required|numeric|min:0',
            'categories.*.high_season_rate' => 'required|numeric|min:0',
            'categories.*.extra_child_rate' => 'required|numeric|min:0',
            'categories.*.extra_adult_rate' => 'required|numeric|min:0',
        ]);

        $resort = $this->model::find($this->resortId);
        $resort->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'primary_contact' => $this->primary_contact,
            'park_id' => $this->park_id,
            'location_gate' => $this->location_gate,
            'address' => $this->address,
            'secondary_phone' => $this->secondary_phone,
            'zone_id' => $this->zone_id,
            'drive_link' => $this->drive_link,
        ]);

        $currentIds = collect($this->categories)->pluck('id')->filter();
        $resort->categories()->whereNotIn('id', $currentIds)->delete();

        foreach ($this->categories as $category) {
            if (isset($category['id'])) {
                ResortCategorys::find($category['id'])->update($category);
            } else {
                $resort->categories()->create($category);
            }
        }

        $this->closeForm();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);
    }

    public function delete($id)
    {
        $this->model::find($id)->delete();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    // Category methods
    public function addCategory()
    {
        $this->categories[] = [
            'name' => '',
            'regular_rate' => 0,
            'high_season_rate' => 0,
            'extra_child_rate' => 0,
            'extra_adult_rate' => 0
        ];
    }

    public function removeCategory($index)
    {
        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
    }

    public function addEmptyCategory()
    {
        $this->categories = [[
            'name' => '',
            'regular_rate' => 0,
            'high_season_rate' => 0,
            'extra_child_rate' => 0,
            'extra_adult_rate' => 0
        ]];
    }

        public function updatedParkId($id,$clear=false){
        if(!$clear){
                    $this->zone_id = '';
        }

        $this->zones = Zones::orderBy('zone_id', 'desc')
            ->where('park_id',$id)
            ->pluck('name', 'zone_id')
            ->toArray();

    }
}
