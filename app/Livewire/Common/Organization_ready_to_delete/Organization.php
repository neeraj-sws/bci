<?php

namespace App\Livewire\Common\Organization;

use App\Models\Currency;
use App\Models\FiscalYear;
use App\Models\OrganizationSetting;
use App\Models\UploadImages;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\{Hash, Auth};

#[Layout('components.layouts.common-app')]
class Organization extends Component
{
    use WithFileUploads;

    public $organization;
    public $logo;

    public $organization_name, $city, $state, $zip_code, $country;
    public $street_address, $time_zone, $company_tax_id, $phone, $fax_number;
    public $website, $fiscal_year, $currency, $language;
    public $ficalYears, $currencys,$tab=1,$isAdmin=false;

    public function mount()
    {
        $this->organization = OrganizationSetting::first();
        if(Auth::guard('web')->user()->hasRole('admin')){
            $this->isAdmin = true;
        }
        if ($this->organization) {
            $this->fill($this->organization->toArray());
        }
    }

    public function rules()
    {
        return [
            'organization_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:225',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'required|string|max:225',
            'street_address' => 'required|string|max:255',
            'time_zone' => 'nullable|string|max:255',
            'company_tax_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'fax_number' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'fiscal_year' => 'nullable|integer',
            'currency' => 'nullable|integer',
            'language' => 'nullable|string|max:200',
            'logo' => $this->organization && $this->organization->file_id ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ];
    }

    public function render()
    {
        $this->ficalYears = FiscalYear::all()->pluck('name', 'id');
        $this->currencys = Currency::all()->pluck('currency', 'id');
        return view('livewire.common.organization.organization');
    }

    public function save()
    {
        $this->validate($this->rules());

        try {
            $imageId = $this->organization->file_id ?? null;

            if ($this->logo) {
                $fileName = 'logo.' . $this->logo->getClientOriginalExtension();
                $path = 'assets/images';
                $this->logo->storeAs($path, $fileName, 'public_root');
                $image = UploadImages::updateOrCreate(
                    ['id' => $imageId],
                    ['file' => $fileName]
                );
                $imageId = $image->id;
            }



            $data = [
                'organization_name' => $this->organization_name,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'country' => $this->country,
                'street_address' => $this->street_address,
                'time_zone' => $this->time_zone,
                'company_tax_id' => $this->company_tax_id,
                'phone' => $this->phone,
                'fax_number' => $this->fax_number,
                'website' => $this->website,
                'fiscal_year' => $this->fiscal_year,
                'currency' => $this->currency,
                'language' => $this->language,
                'file_id' => $imageId,
            ];

            if ($this->organization) {
                $this->organization->update($data);
            } else {
                $this->organization = OrganizationSetting::create($data);
            }
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => 'Saved',
                'message' => 'Organization updated successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to save organization profile'
            ]);
        }
    }
    
    public function handleTabChange($tab){
         $this->tab = $tab;
    }
    
}
