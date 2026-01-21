<?php

namespace App\Livewire\Common\Tourists;

use App\Helpers\SettingHelper;
use App\Models\Tourists as Model;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithFileUploads, WithPagination};

#[Layout('components.layouts.common-app')]
class TouristList extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $pageTitle = 'Tourists';
    public $sortBy = 'created_at';
    public $sortDirection = 'asc';
    public $search = '';
    public $route;
    public $itemId;

    // 👇 Add this
    public $perPage = 15;

    protected $paginationTheme = 'bootstrap';
    public $import_file, $showModel = false;

    public function mount($id = null)
    {
        $this->route = 'common';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = Model::where(function ($query) {
            $query->where('primary_contact', 'like', "%{$this->search}%")
                ->orWhereNull('primary_contact');
        })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.common.tourists.tourist-list', compact('items'));
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
            'action' => 'delete',
        ]);
    }

    #[On('delete')]
    public function delete()
    {
        $model = Model::find($this->itemId);
        $model->soft_primary_contact = $model->primary_contact;
        $model->primary_contact = null;
        $model->save();
        $model->delete();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }
    public function openImportModel()
    {
        $this->import_file = '';
        $this->showModel = !$this->showModel;
    }
    public function closeImportModal()
    {
        $this->import_file = '';
        $this->showModel = false;
    }
    public function importData()
    {
        $this->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        $result = SettingHelper::ImportHelper(
            $this->import_file,
            Model::class,
            [
                0 => 'company_name',
                1 => 'date',
                2 => 'primary_contact',
                3 => 'contact_email',
                4 => 'contact_phone',
                5 => 'address',
                6 => 'reference',
                7 => 'city_suburb',
                8 => 'state',
                9 => 'zip_code',
                10 => 'country_id',
                11 => 'base_currency_code',
                12 => 'birthday',
                13 => 'anniversary',
            ]
        );

        if (isset($result['error'])) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => '',
                'message' => $result['error']
            ]);
            return;
        }

        if (!empty($result['errors'])) {
            $this->dispatch('swal:toast', [
                'type' => 'warning',
                'title' => '',
                'message' => 'Imported ' . $result['inserted'] . ' rows with some errors.'
            ]);
        }


        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Successfully imported ' . $result['inserted'] . ' tourists!'
        ]);
        $this->import_file = '';
        $this->showModel = false;
    }
    public function exportExcel()
    {
        $query = Model::query();
        if ($this->search) {
            $query->where('primary_contact', 'like', "%{$this->search}%");
        }
        $records = $query->get();
        $headings = [
            'Company Name',
            'Date',
            'Contact Person',
            'Email',
            'Phone',
            'Address',
            'City',
            'State',
            'Zip Code',
            'Country',
            'Reference',
            'Base Currency',
            'Birthday',
            'Anniversary',
            'Tax ID',
        ];
        $data = $records->map(function ($item) {
            return [
                $item->company_name ?? 'NA',
                $item->date ?? 'NA',
                $item->primary_contact ?? 'NA',
                $item->contact_email ?? 'NA',
                $item->contact_phone ?? 'NA',
                $item->address ?? 'NA',
                $item->city?->name ?? 'NA',
                $item->stateRelation?->name ?? 'NA',
                $item->zip_code ?? 'NA',
                $item->country?->name ?? 'NA',
                $item->reference ?? 'NA',
                $item->base_currency_code ?? 'NA',
                $item->birthday ?? 'NA',
                $item->anniversary ?? 'NA',
                $item->other?->tax_id ?? 'NA',
            ];
        })->toArray();


        return SettingHelper::ExportHelper('tourists', $headings, $data);
    }

    public function shortby($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updating()
    {
        $this->resetPage();
    }
}
