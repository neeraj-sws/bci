<?php

namespace App\Livewire\Common\Tourists;

use App\Helpers\SettingHelper;
use App\Models\TouristOtherDetails;
use App\Models\Tourists as Model;
use App\Models\Country;
use App\Models\Currency;
use App\Models\City;
use App\Models\States;
use Livewire\Attributes\{Layout, On, Rule};
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class TouristForm extends Component
{
    public $itemId;
    public $pageTitle = 'Tourists';

    // Client fields
    public $primary_contact;

    public $company_name;
    public $contact_email;
    public $contact_phone;
    public $address;
    public $city_suburb;
    public $state;
    public $zip_code;
    public $birthday;
    public $company_anniversary;

    // Other details fields
    public $payment_terms;
    public $website;
    public $tax_id;

    public $countrys;
    public $country;
    public $currencys;
    public $currency="₹",$reference;
    
    public $states=[],$citys=[];

    public $route;
    
    // NEW DEV 
    public $existingTourist; // store found tourist data
    public $showExistingModal = false; // control modal visibility

    public function mount($id = null)
    {
        $this->countrys = Country::pluck('name', 'country_id')->toArray();



        $this->currencys = Currency::pluck('currency', 'code')->toArray();

        if ($id) {
            $this->itemId = $id;
            $this->edit($id);
        }else{
            $this->country = '101';
            $this->updatedCountry($this->country);
        }
        $this->route = 'common';
    }
    
        public function store()
    {
        $client = Model::create([
            'flag'=>1,
            'date'=>date('Y-m-d'),
            'company_name' =>ucwords( $this->company_name),
            'primary_contact' => ucwords($this->primary_contact),
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'address' => $this->address,
            'city_suburb' => $this->city_suburb,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'country_id' => $this->country,
            'birthday' => $this->birthday,
            'anniversary' => $this->company_anniversary,
            'base_currency_code'=>$this->currency,'reference'=>$this->reference
        ]);

        $other = TouristOtherDetails::create([
            'tourist_id' => $client->id,
            'tax_id' => $this->tax_id,
            'website' => $this->website,
            'payment_terms' => $this->payment_terms,
        ]);

        $client->update(['other_id' => $other->id]);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);

        $this->redirect(route($this->route . '.tourist'), navigate: true);
    }

    public function edit($id)
    {
        // dd( Model::where('id',$id)->get()->toArray());
        $item = Model::findOrFail((int) $id);
        $other = TouristOtherDetails::find($item->other_id);

        $this->itemId = $item->id;
        $this->company_name = $item->company_name;
        $this->primary_contact = $item->primary_contact;
        $this->contact_email = $item->contact_email;
        $this->contact_phone = $item->contact_phone;
        $this->address = $item->address;
        $this->city_suburb = $item->city_suburb;
        $this->state = $item->state;
        $this->zip_code = $item->zip_code;
        $this->country = $item->country_id;
        $this->birthday = $item->birthday;
        $this->company_anniversary = $item->anniversary;
        $this->currency = $item->base_currency_code;
        $this->reference = $item->reference;
        if($other){
            $this->tax_id = $other->tax_id;
            $this->website = $other->website;
            $this->payment_terms = $other->payment_terms;
        }
        $this->updatedCountry($item->country_id);
        $this->updatedState($item->state);
    }
    
public function rules()
{
    return [
        'primary_contact' => ['required', 'string'],
        'company_name' => ['nullable', 'string'],
    ];
}

public function messages()
{
    return [
        'primary_contact.required' => 'The primary contact field is required.',
        'primary_contact.string' => 'The primary contact must be a valid string.',
        'company_name.required' => 'The company name field is required.',
        'company_name.string' => 'The company name must be a valid string.',
    ];
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

    public function update()
    {
        Model::findOrFail($this->itemId)->update([
            'company_name' => ucwords($this->company_name),
            'primary_contact' => ucwords($this->primary_contact),
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'address' => $this->address,
            'city_suburb' => $this->city_suburb,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'country_id' => $this->country,
            'birthday' => $this->birthday,
            'anniversary' => $this->company_anniversary,'reference'=>$this->reference,'base_currency_code'=>$this->currency,'reference'=>$this->reference
        ]);

        $details = TouristOtherDetails::where('tourist_id', $this->itemId)->first();

        if ($details) {
            $details->update([
                'tax_id' => $this->tax_id,
                'website' => $this->website,
                'payment_terms' => $this->payment_terms,
            ]);
        }

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);

        $this->redirect(route($this->route . '.tourist'), navigate: true);
    }

    public function render()
    {
        return view('livewire.common.tourists.tourist-form');
    }
    
    public function updatedCountry($id){
        $this->states = States::where('country_id',$id)->pluck('name', 'state_id')->toArray();
    }
    
    public function updatedState($id){
        $this->citys = City::where('state_id',$id)->pluck('name', 'city_id')->toArray();
    }
    
    public function updatedPrimaryContact($name){
         $tourist = Model::where('primary_contact', $name)->first();
         if ($tourist) {
            $this->existingTourist = $tourist;
            $this->showExistingModal = true;
         }
    }
    
    public function continueWithExistingTourist()
    {
        if (!$this->existingTourist) return;
        $this->edit($this->existingTourist);
        $this->showExistingModal = false;
    }
    
    public function handleTourist($type)
    {
        if (!$this->existingTourist) return;
        if($type ==1){
            $this->edit($this->existingTourist->id);
        }else{
            if (preg_match('/\((\d+)\)$/', $this->primary_contact, $m)) {
                $this->primary_contact = preg_replace('/\(\d+\)$/', '(' . ($m[1] + 1) . ')', $this->primary_contact);
            } else {
                $this->primary_contact .= '(1)';
            }
            $this->existingTourist = null;
        }
        $this->showExistingModal = false;
    }
}
