<?php

namespace App\Livewire\Common\Companies;

use App\Models\Companies as Model;
use App\Models\UploadImages;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class CompaniesList extends Component
{
    use WithPagination;

    public $pageTitle = 'Company Setting ';
    public $search = '';

    public $route;
    public $itemId;

    public function mount($id = null)
    {
        $this->route = 'common';
    }

    public function render()
    {
        $items = Model::where(function ($query) {
            $query->where('company_name', 'like', "%{$this->search}%")
                ->orWhereNull('company_name');
        })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);


        return view('livewire.common.companies.companies-list', compact('items'));
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
        $comp = Model::findOrFail($this->itemId);
        if ($comp->company_file_id) {
            $oldImage = UploadImages::find($comp->company_file_id);
            if ($oldImage) {
                $filePath = public_path('assets/images/' . $oldImage->file);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                $oldImage->delete();
            }
        }
        $comp->delete();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }
        public function toggleStatus($id)
    {
        $habitat = Model::findOrFail($id);
        Model::where('is_primary', 1)->update(['is_primary' => 0]);
        $habitat->is_primary = 1;
        $habitat->save();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Status Changed Successfully'
        ]);
    }
}
