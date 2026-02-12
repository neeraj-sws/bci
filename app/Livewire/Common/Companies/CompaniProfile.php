<?php

namespace App\Livewire\Common\Companies;

use App\Models\City;
use App\Models\Companies;
use App\Models\Country;
use App\Models\Currency;
use App\Models\FiscalYear;
use App\Models\States;
use App\Models\UploadImages;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.common-app')]
class CompaniProfile extends Component
{
    use WithFileUploads;

    public $organization;
    public $company_file_id;
    public $tab;
    public $pageTitle = 'Company';

    public $company_name;
    public $company_address, $company_email, $company_contact;

    public $city, $state, $zip_code, $country;
    public $time_zone, $company_tax_id, $fax_number;
    public $website, $fiscal_year, $currency, $language;
    public $ficalYears, $currencys;
    public $countrys,$sac_code;
    public $states = [], $citys = [];
    public function mount($id = null, $tab = 1)
    {
        $this->tab = $tab;
        if ($id) {
            $this->organization = Companies::findOrFail($id);
            $this->company_name    = $this->organization->company_name;
            $this->company_address = $this->organization->company_address;
            $this->company_email   = $this->organization->company_email;
            $this->company_contact = $this->organization->company_contact;
            $this->company_file_id = null;

            $this->city            = $this->organization->city;

            $this->sac_code            = $this->organization->sac_code;

            $this->state           = $this->organization->state;
            $this->zip_code        = $this->organization->zip_code;
            $this->country         = $this->organization->country;
            $this->time_zone       = $this->organization->time_zone;
            $this->company_tax_id  = $this->organization->company_tax_id;
            $this->fax_number      = $this->organization->fax_number;
            $this->website         = $this->organization->website;
            // $this->fiscal_year     = $this->organization->fiscal_year;
            // $this->currency        = $this->organization->currency;
            $this->language        = $this->organization->language;

            $this->updatedCountry($this->organization->country);
            $this->updatedState($this->organization->state);
        }
    }
    public function rules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email',
            'company_contact' => 'required|string|max:15',
            'company_address' => 'required',
            'company_file_id' => $this->organization && $this->organization->company_file_id ? 'nullable|image|max:2048' : 'required|image|max:2048',

 'sac_code' => 'required|max:255',

            'city' => 'required|max:255',
            'state' => 'required|max:225',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'required|max:225',
            'time_zone' => 'nullable|string|max:255',
            'company_tax_id' => 'nullable|string|max:255',
            'fax_number' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            // 'fiscal_year' => 'nullable|integer',
            // 'currency' => 'nullable|integer',
            'language' => 'nullable|string|max:200',
        ];
    }
    public function render()
    {
        // $this->ficalYears = FiscalYear::all()->pluck('name', 'id');
        // $this->currencys = Currency::all()->pluck('currency', 'id');
        $this->countrys = Country::pluck('name', 'country_id')->toArray();

        return view('livewire.common.companies.compani-profile');
    }
    public function save()
    {
        $this->validate($this->rules());

        try {
            // $imageId = $this->organization->company_file_id ?? null;

            // if ($this->company_file_id) {
            //     if ($this->organization && $this->organization->company_file_id) {
            //         $oldImage = UploadImages::where('upload_image_id', $this->organization->company_file_id)->first();
            //         if ($oldImage) {
            //             @unlink(public_path('assets/images/' . $oldImage->file));
            //             $oldImage->delete();
            //         }
            //     }

            //     $extension = $this->company_file_id->getClientOriginalExtension();
            //     $fileName = pathinfo($this->company_file_id->getClientOriginalName(), PATHINFO_FILENAME)
            //         . '_' . time() . '.' . $extension;
            //     $path = 'assets/images';
            //     $this->company_file_id->storeAs($path, $fileName, 'public_root');

            //     $image = UploadImages::updateOrCreate(
            //         ['upload_image_id' => $this->organization ? $this->organization->company_file_id : null],
            //         ['file' => $fileName]
            //     );

            //     $imageId = $image->id;
            // }



            $data = [
                'company_name' => ucwords($this->company_name),
                'company_email' => $this->company_email,
                'company_contact' => $this->company_contact,
                'company_address' => $this->company_address,
                // 'company_file_id' => $imageId,

                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'country' => $this->country,
                'time_zone' => $this->time_zone,
                'company_tax_id' => $this->company_tax_id,
                'fax_number' => $this->fax_number,
                'website' => $this->website,
                // 'fiscal_year' => $this->fiscal_year,
                // 'currency' => $this->currency,
                'language' => $this->language,

                'sac_code'=>$this->sac_code
            ];

            if ($this->organization) {
                $this->organization->update($data);

                $this->dispatch('swal:toast', [
                    'type' => 'success',
                    'title' => 'Saved',
                    'message' => $this->pageTitle . ' Updated successfully!'
                ]);
            } else {
                $this->organization = Companies::create($data);


                $leadUuid = Str::uuid()->toString() . '-' . $this->organization->id . '-' . Str::uuid()->toString();
                $encodedUuid = base64_encode($leadUuid);
                $this->organization->uuid = $encodedUuid;
                $this->organization->profile_steps = 1;
                $this->organization->save();

                $this->dispatch('swal:toast', [
                    'type' => 'success',
                    'title' => 'Saved',
                    'message' => $this->pageTitle . ' Created successfully!'
                ]);
            }

            // NEW DEV
            $imageId = $this->organization->company_file_id ?? null;
            if ($this->company_file_id) {
                if ($this->organization && $this->organization->company_file_id) {
                    $oldImage = UploadImages::where('upload_image_id', $this->organization->company_file_id)->first();
                    if ($oldImage) {
                        @unlink(public_path('uploads/companies/' . $this->organization->id . '/' . $oldImage->file));
                        $oldImage->delete();
                    }
                }
                $extension = $this->company_file_id->getClientOriginalExtension();
                $fileName = pathinfo($this->company_file_id->getClientOriginalName(), PATHINFO_FILENAME)
                    . '_' . time() . '.' . $extension;

                $path = "uploads/companies/{$this->organization->id}";

                if (!Storage::disk('public_root')->exists($path)) {
                    Storage::disk('public_root')->makeDirectory($path);
                }
                $this->company_file_id->storeAs($path, $fileName, 'public_root');

                $image = UploadImages::updateOrCreate(
                    ['upload_image_id' => $this->organization ? $this->organization->company_file_id : null],
                    ['file' => $fileName]
                );

                $imageId = $image->id;
            }
            $this->organization::findOrFail($this->organization->id)->update([
                'company_file_id' => $imageId,
            ]);
            //

            $this->dispatch('profileSave', $this->organization->id);
            $this->dispatch('tabUpdated', 2);
        } catch (\Exception $e) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Failed to save ' . $this->pageTitle . '. Please try again.'
            ]);
        }
    }
    public function updatedCountry($id)
    {
        $this->states = States::where('country_id', $id)->pluck('name', 'state_id')->toArray();
    }
    public function updatedState($id)
    {
        $this->citys = City::where('state_id', $id)->pluck('name', 'city_id')->toArray();
    }
}
