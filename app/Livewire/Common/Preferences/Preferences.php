<?php

namespace App\Livewire\Common\Preferences;

use Livewire\Attributes\{Layout, On};
use Livewire\Component;

use App\Models\Leads;
use App\Models\LeadActivity;
use App\Models\LeadAdditions;
use App\Models\LeadFollowups;
use App\Models\QuotationItems;
use App\Models\Quotations;
use App\Models\InvEstActivity;
use App\Models\Tourists;
use App\Models\TouristOtherDetails;

#[Layout('components.layouts.common-app')]
class Preferences extends Component
{
    public $tab=1;
    public $emtyId;
    public function rules()
    {
        return [
            'paper_size' => 'required',
            'number_format' => 'required',
            'pdf_attachment' => 'required',
            'notify' => 'required',
            'notify2' => 'required',
            'notify3' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.common.preferences.preferences');
    }
    
       
    
    public function confirmDelete($id)
    {
        $this->emtyId = $id;

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
    public function emty(){
        if($this->emtyId ==1){
            Leads::truncate();
            LeadActivity::truncate();
            LeadAdditions::truncate();
            LeadFollowups::truncate();
            Tourists::truncate();
            TouristOtherDetails::truncate();
        }else{
            QuotationItems::truncate();
            Quotations::truncate();
            InvEstActivity::truncate();
        }
        
    $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Truncated',
            'message' => 'DONE'
        ]);
    }
}
