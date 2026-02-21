<?php

namespace App\Livewire\Common\Leads;

use App\Helpers\SettingHelper;
use App\Models\Tourists;
use App\Models\Leads as Model;
use App\Models\Country;
use App\Models\Currency;
use App\Models\LeadFollowups;
use App\Models\LeadTypes;
use App\Models\LeadSources;
use App\Models\LeadStages;
use App\Models\LeadStatus;
use App\Models\UploadImages;
use App\Models\User;
use App\Models\LeadTags;
use Hamcrest\Core\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\{Layout, On, Rule};
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\City;
use App\Models\States;
// NEW DEV 07-11-25
use Illuminate\Support\Facades\Storage;


#[Layout('components.layouts.common-app')]

class Form extends Component
{
    use WithFileUploads;

    public $itemId;
    public $pageTitle = 'Lead';

    // Client fields
    #[Rule('required')]
    public $contact, $email, $remark;

    public $client_name, $addClient = false;

    public $currencys, $currency;

    public $pipeline_id = 1;
    public $client_id, $clients;
    public $stage_id;
    public $status_id;
    public $source_id;
    public $address;
    public $city;
    public $state;
    public $country_id, $reference;

    // Other details fields
    public $destination;
    public $travel_date;
    public $travel_days;

    public $budget;
    public $follow_up_date;
    public $follow_up_time;

    public array $files = [];
    public $existingImages = [];
    public $user_id, $users;


    public $stages;
    public $status = [];
    public $sources;
    public $countrys;
    public $groups;
    public $coloum, $route, $guard;

    public $states = [], $citys = [];

    public $tags,$selectedTags = [];

    const SALES_ROLE_ID = 2;

    public $pendingFile = null;

    public function mount($id = null, $route = null, $coloum = null, $guard = null)
    {
        $this->route = 'common';
        $this->coloum = Auth::guard('web')->user()->hasRole('admin') ? null : 'user_id';
        $this->guard = 'web';

        $this->stage_id = LeadStages::first()->id;
        $this->status_id = LeadStatus::first()->id;
        $this->sources = LeadSources::all()->pluck('name', 'id')->toArray();
        $this->countrys = Country::pluck('name', 'country_id')->toArray();
        $this->groups = LeadTypes::all()->pluck('name', 'id')->toArray();

        $this->currency = SettingHelper::getDefaultCurrency();

        // $this->clients = Clients::pluck('company_name', 'id')->toArray();
        $this->clients = Tourists::select('tourist_id', 'primary_contact', 'contact_phone')
        ->orderBy('updated_at', 'desc')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->primary_contact . ' - ' . $tourist->contact_phone];
            })
            ->toArray();

        // $this->users = User::role('sales')->where('status', 1)->pluck('name', 'user_id')->toArray();

        $this->users = User::whereHas('roles', fn($q) =>
            $q->where('id', self::SALES_ROLE_ID)
        )->where('status', 1)->pluck('name', 'user_id')->toArray();

        $this->currencys = Currency::all()->pluck('currency', 'code');

        $this->tags = LeadTags::where('status',1)->pluck('name', 'lead_tag_id');


        if ($id) {
            $this->itemId = $id;
            $this->edit($id);
        }
    }

    public function edit($id)
    {
        $item = Model::where('uuid',$id)->firstOrFail();

        $this->itemId = $item->id;
        $this->pipeline_id = $item->type_id;
        $this->client_id = $item->tourist_id;
        $this->contact = $item->contact;
        $this->email = $item->tourist->contact_email;
        $this->address = $item->tourist->address;
        $this->stage_id = $item->stage_id;
        $this->state = $item->tourist->state;
        $this->status_id = $item->status_id;
        $this->source_id = $item->source_id;
        $this->remark = $item->notes;
        $this->user_id = $item->user_id;
        $this->currency = $item->tourist->base_currency_code;
        $this->reference = $item->tourist->reference;

        $this->address = $item->tourist->address;
        $this->city = $item->tourist->city_suburb;
        $this->state = $item->tourist->state;
        $this->country_id = $item->tourist->country_id;
        $this->destination = $item->destination;
        $this->travel_date = $item->travel_date;
        $this->travel_days = $item->travel_days;
        $this->budget = $item->budget;
        $this->follow_up_date = $item->follow_up_date;
        $this->follow_up_time = $item->follow_up_time;

        // NEW DEV
        $this->selectedTags = $item->tags ? explode(',', $item->tags) : [];
        $this->existingImages = UploadImages::where('lead_id', $item->id)->get();

        $this->updatedCountryId($item->tourist->country_id);
        $this->updatedState($item->tourist->state);
    }

    public function save()
    {
        $this->validate();

        if ($this->itemId) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function rules()
    {
        return [
            'contact' => 'required',
            'email' => 'required|email',
            'remark' => 'required',
            'source_id' => 'nullable',
            'client_name' => $this->addClient ? 'required' : 'nullable',
            'client_id' => $this->addClient ? 'nullable' : 'required',

            'travel_date' => 'nullable|date_format:Y-m-d',
            // 'travel_date' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            // 'follow_up_date' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'follow_up_date' => 'nullable|date_format:Y-m-d',
            'follow_up_time' => 'nullable|date_format:H:i', // Must be valid time like 14:30 or 09:05
        ];
    }


    public function messages()
    {
        return [
            'contact.required' => 'The contact field is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'remark.required' => 'Remark field is required.',
            'source_id.required' => 'Source ID is required.',
            'client_name.required' => 'Tourist name is required when adding a new Tourist.',
            'client_name.nullable' => 'Tourist name is optional when not adding a new Tourist.',
            'client_id.required' => 'Tourist is required unless adding a new Tourist.',
            'client_id.nullable' => 'Tourist is optional when adding a new Tourist.',

            'follow_up_time.date_format' => 'Follow-up time must be in HH:MM (24-hour) format, e.g., 14:30.',
        ];
    }


    public function store()
    {

        $this->validate($this->rules());


        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;
        $user_id = Auth::guard('web')->user()->hasRole('sales') ? Auth::guard('web')->user()->id : $this->user_id;
        // NEW DEV
        $tags =  implode(',', $this->selectedTags);
        $client = Model::create([
            'type_id' => $this->pipeline_id,
            'contact' => $this->contact,
            'email' => $this->email,
            'stage_id' => $this->stage_id,
            'status_id' => $this->status_id,
            'source_id' => $this->source_id,
            'notes' => $this->remark,
            'budget' => $this->budget,
            'follow_up_date' => $this->follow_up_date,
            'follow_up_time' => $this->follow_up_time,
            'destination' => ucwords($this->destination),
            'travel_date' => $this->travel_date,
            'travel_days' => $this->travel_days,
            'user_id' => $user_id,
            // NEW DEV
            'tags'=>$tags
        ]);

        $leadUuid = Str::uuid()->toString() . '-' . $client->id . '-' . Str::uuid()->toString();
        $encodedUuid = base64_encode($leadUuid);
        $client->uuid = $encodedUuid;
        $client->save();

        // NEW DEV
        if(count($this->selectedTags) > 0){
                foreach ($this->selectedTags as $item) {
                    LeadTags::firstOrCreate([
                        'name'     => $item
                    ]);
                }
        }

        if ($this->addClient) {
            $tourist = Tourists::create([
                "flag" => 1,
                "source_id" => $this->source_id,
                "date" => date('Y-m-d'),
                "primary_contact" => $this->client_name,
                "contact_phone" => $this->contact,
                "contact_email" => $this->email,
                "address" => $this->address,
                "city_suburb" => $this->city,
                "state" => $this->state,
                "country_id" => $this->country_id,
                "base_currency_code" => $this->currency,
                "reference" => $this->reference
            ]);
            $client->update([
                "tourist_id" => $tourist->id
            ]);
        } else {
            $client->update([
                "tourist_id" => $this->client_id
            ]);

              // Find and update existing tourist
                $tourist = Tourists::find($this->client_id);
                if ($tourist) {
                    $tourist->update([
                        "contact_phone" => $this->contact,
                        "contact_email" => $this->email,
                        "address" => $this->address,
                        "city_suburb" => $this->city,
                        "state" => $this->state,
                        "country_id" => $this->country_id,"base_currency_code" => $this->currency,
                    ]);
                }

        }

        // if (Auth::guard('web')->user()->hasRole('marketing')) {
        //     $client->update([
        //         "marketing_person" => Auth::guard('web')->user()->id
        //     ]);
        // }




        // NEW DEV IMAGE UPLOAD CHNAGE
        // if (count($this->files) > 0) {
        //     $path = 'assets/images';
        //     foreach ($this->files as $file) {
        //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        //         $file->storeAs($path, $fileName, 'public_root');
        //         UploadImages::create([
        //             'file' => $fileName,
        //             'ext' => $file->getClientOriginalExtension(),
        //             'lead_id' => $client->id,
        //         ]);
        //     }
        // }
        if (count($this->files) > 0) {
            $path = "uploads/leads/{$client->id}";
            if (!Storage::disk('public_root')->exists($path)) {
                Storage::disk('public_root')->makeDirectory($path);
            }
            foreach ($this->files as $file) {
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $fileName = "$name.$ext";
                $files = collect(Storage::disk('public_root')->files($path));
                $matching = $files->filter(function ($f) use ($name, $ext) {
                    return preg_match("/^" . preg_quote($name, '/') . "(\(\d+\))?\.$ext$/", basename($f));
                });
                if ($matching->isNotEmpty()) {
                    $fileName = "{$name}(" . $matching->count() . ").{$ext}";
                }
                $file->storeAs($path, $fileName, 'public_root');
                UploadImages::create([
                    'file' => $fileName,
                    'ext' => $ext,
                    'lead_id' => $client->id,
                ]);
            }
        }
        //

        SettingHelper::leadActivityLog(1, $client->id, $userId, $this->coloum);
        if ($this->user_id) {
            SettingHelper::leadActivityLog(6, $client->id, $userId, $this->coloum);
        }


        if ($this->follow_up_date) {
            $followUp = LeadFollowups::create([
                'lead_id' => $client->id,
                'followup_date' => $this->follow_up_date,
                'followup_time' => $this->follow_up_time,
                'stage_id' => $this->stage_id,
                'status_id' => $this->status_id,
                'comments' => $this->remark,
            ]);
            if ($this->coloum) {
                $followUp->update([
                    $this->coloum => $userId,
                ]);
            }
            SettingHelper::leadActivityLog(3, $client->id, $userId, $this->coloum);
        }


        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);

        $this->redirect(route($this->route . '.lead'), navigate: true);
    }

    public function update()
    {
        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;
        $lead = Model::findOrFail($this->itemId);
                // NEW DEV
        $tags =  implode(',', $this->selectedTags);
        $lead->update([
            'type_id' => $this->pipeline_id,
            'contact' => $this->contact,
            'email' => $this->email,
            'stage_id' => $this->stage_id,
            'status_id' => $this->status_id,
            'source_id' => $this->source_id,
            'notes' => $this->remark,
            'user_id' => Auth::guard('web')->user()->hasRole('sales') ? Auth::guard('web')->user()->id : $this->user_id,
            'destination' => ucwords($this->destination),
            'travel_date' => $this->travel_date,
            'travel_days' => $this->travel_days,
            'budget' => $this->budget,
            'follow_up_date' => $this->follow_up_date,
            'follow_up_time' => $this->follow_up_time,

             // NEW DEV
            'tags'=>$tags
        ]);

        if ($this->addClient) {
            $tourist = Tourists::create([
                "flag" => 1,
                "source_id" => $this->source_id,
                "date" => date('Y-m-d'),
                "primary_contact" => $this->client_name,
                "contact_phone" => $this->contact,
                "contact_email" => $this->email,
                "address" => $this->address,
                "city_suburb" => $this->city,
                "state" => $this->state,
                "country_id" => $this->country_id,
                "reference" => $this->reference
            ]);
            $lead->update([
                "tourist_id" => $tourist->id
            ]);
        } else {
            $lead->update([
                "tourist_id" => $this->client_id
            ]);

              // Find and update existing tourist
                $tourist = Tourists::find($this->client_id);
                if ($tourist) {
                    $tourist->update([
                        "contact_phone" => $this->contact,
                        "contact_email" => $this->email,
                        "address" => $this->address,
                        "city_suburb" => $this->city,
                        "state" => $this->state,
                        "country_id" => $this->country_id,"base_currency_code" => $this->currency,
                    ]);
                }

        }

        // NEW DEV
        if(count($this->selectedTags) > 0){
                foreach ($this->selectedTags as $item) {
                    LeadTags::firstOrCreate([
                        'name'     => $item
                    ]);
                }
        }

        // NEW DEV
        // if (count($this->files) > 0) {
        //     $path = 'assets/images';
        //     foreach ($this->files as $file) {
        //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        //         $file->storeAs($path, $fileName, 'public_root');
        //         UploadImages::create([
        //             'file' => $fileName,
        //             'ext' => $file->getClientOriginalExtension(),
        //             'lead_id' => $lead->id,
        //         ]);
        //     }
        // }
        if (count($this->files) > 0) {
            $path = "uploads/leads/{$lead->id}";
            if (!Storage::disk('public_root')->exists($path)) {
                Storage::disk('public_root')->makeDirectory($path);
            }
            foreach ($this->files as $file) {
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $fileName = "$name.$ext";

                $files = collect(Storage::disk('public_root')->files($path));
                $matching = $files->filter(function ($f) use ($name, $ext) {
                    return preg_match("/^" . preg_quote($name, '/') . "(\(\d+\))?\.$ext$/", basename($f));
                });

                if ($matching->isNotEmpty()) {
                    $fileName = "{$name}(" . $matching->count() . ").{$ext}";
                }

                $file->storeAs($path, $fileName, 'public_root');

                UploadImages::create([
                    'file' => $fileName,
                    'ext' => $ext,
                    'lead_id' => $lead->id,
                ]);
            }
        }
        //

        SettingHelper::leadActivityLog(2, $lead->id, $userId, $this->coloum);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);

        $this->redirect(route($this->route . '.lead'), navigate: true);
    }


    public function render()
    {
        return view('livewire.common.leads.leads-form');
    }

    public function updatedClientId($client_id)
    {
        $client = Tourists::find($client_id);
        $this->email = $client->contact_email;
        $this->contact = $client->contact_phone;
        $this->address = $client->address;
        $this->city = $client->city_suburb;
        $this->state = $client->state;
        $this->country_id = $client->country_id;
        $this->currency = $client->base_currency_code;

        $this->updatedCountryId($client->country_id);
        $this->updatedState($client->state);
    }

    public function deleteImage($id)
    {
        $image = UploadImages::find($id);
        if ($image) {
            // NEW DEV
            // @unlink(public_path('assets/images/' . $image->file));
            if ($this->itemId) {
                @unlink(public_path('uploads/leads/' . $this->itemId . '/' . $image->file));
            }
            //
            $image->delete();
            $this->existingImages = UploadImages::where('lead_id', $this->itemId)->get();
        }
    }

    public function changeclient()
    {
        $this->addClient = !$this->addClient;
    }

    public function updatedCountryId($id)
    {
        $this->states = States::where('country_id', $id)->pluck('name', 'state_id')->toArray();
    }

    public function updatedState($id)
    {
        $this->citys = City::where('state_id', $id)->pluck('name', 'city_id')->toArray();
    }

    public function updatedFollowUpDate($id)
    {
        if (!$id) {
            $this->follow_up_time = '';
        }else{
            $this->follow_up_time = '13:00';
        }
    }

    public function updatedFiles($data)
    {
        if ($this->itemId) {
            foreach ($this->files as $file) {
                $fileName = $file->getClientOriginalName();
                $path = "uploads/leads/{$this->itemId}/" . $fileName;
                if (Storage::disk('public_root')->exists($path)) {
                    $this->pendingFile = $file;
                    $this->dispatch('swal:confirm', [
                        'title' => 'File already exists!',
                        'text' => "The file '{$fileName}' already exists. Do you want to replace it?",
                        'icon' => 'warning',
                        'showCancelButton' => true,
                        'confirmButtonText' => 'Yes, replace it',
                        'cancelButtonText' => 'Cancel',
                        'action' => 'confirmReplace',
                        'cancelAction' => 'cancelReplace',
                    ]);
                    return;
                }
            }
        }
    }
    #[On('confirmReplace')]
    public function confirmReplace()
    {
        if ($this->pendingFile) {
            $this->pendingFile = null;

            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => '',
                'message' => "File was added with (1)."
            ]);
        }
    }
    #[On('cancelReplace')]
    public function cancelReplace()
    {
        if ($this->pendingFile) {
            $removeName = $this->pendingFile->getClientOriginalName();
            $this->files = array_filter($this->files, fn($file) => $file->getClientOriginalName() !== $removeName);
            $this->pendingFile = null;
            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => '',
                'message' => "File '{$removeName}' was not added."
            ]);
        }
    }

    public function removeFile($index)
{
    if(isset($this->files[$index])){
        // Remove the file from the array
        unset($this->files[$index]);
        // Reindex the array to avoid gaps
        $this->files = array_values($this->files);
    }
}

}
