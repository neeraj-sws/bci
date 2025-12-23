<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\Tourists;
use App\Models\Quotations;
use App\Models\InvoiceItems;
use App\Models\Invoices;
use App\Models\InvoiceSettings;
use App\Models\Items;
use App\Models\Parks;
use App\Models\ResortCategorys;
use App\Models\Resorts;
use App\Models\Tours;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class AddInvoice extends Component
{
    public $pageTitle, $showModal = false, $showResortModal = false;
    public $invoiceSettings;
    public $clients, $items, $tours;

    public $invoice_no, $client_id, $invoice_title, $invoice_date, $po_number, $tour_id, $expiry_date, $terms_and_condition, $notes;

    public $selectedItems = [];
    public $subTotal = 0;
    public $discount = 0;
    public $totalAmount = 0;

    public $tour_name, $start_date, $end_date, $tour_status = 1, $tour_description;
    public $park_id, $resort_id, $room_id, $room_cat_id;
    public $parks = [], $resorts = [], $rooms = [], $roomcats = [];
    public $estimate_id;


    // NEW 
    public $invoiceId;
    public $leadId;


    public function loadInvoice()
    {
        $invoice = Invoices::with('items')->findOrFail($this->invoiceId);

        $this->client_id = $invoice->client_id;
        $this->invoice_no = $invoice->invoice_no;
        $this->invoice_title = $invoice->invoice_title;
        $this->po_number = $invoice->po_number;
        $this->invoice_date = $invoice->invoice_date;
        $this->tour_id = $invoice->tour_id;
        $this->expiry_date = $invoice->expiry_date;
        $this->terms_and_condition = $invoice->terms_and_condition;
        $this->notes = $invoice->notes;
        $this->discount = $invoice->discount;
        $this->subTotal = $invoice->sub_total;
        $this->totalAmount = $invoice->amount;

        $this->selectedItems = [];
        // dd($invoice->toArray());
        foreach ($invoice->items as $item) {
            $this->selectedItems[] = [
                'item_id' => $item->item_id,
                'rate' => $item->rate,
                'qty' => $item->qty,
                'amount' => $item->amount,
                'description' => $item->description,
                'is_resort' => $item->is_resort,
                'is_date' => $item->is_date,
                'is_time' => $item->is_time,
                'is_custome' => $item->is_custome,
                'custom_name' => $item->description,
            ];
        }
    }

    public function loadEstimate()
    {
        $estimate = Quotations::with('items')->findOrFail($this->estimate_id);

        $this->client_id = $estimate->client_id;
        $this->invoice_no = $estimate->estimate_no;
        $this->invoice_title = $estimate->estimate_title;
        $this->po_number = $estimate->po_number;
        $this->invoice_date = $estimate->estimate_date;
        $this->tour_id = $estimate->tour_id;
        $this->expiry_date = $estimate->expiry_date;
        $this->terms_and_condition = $estimate->terms_and_condition;
        $this->notes = $estimate->notes;
        $this->discount = $estimate->discount;
        $this->subTotal = $estimate->sub_total;
        $this->totalAmount = $estimate->amount;

        $this->leadId = $estimate->lead_id;


        $this->selectedItems = [];
        foreach ($estimate->items as $item) {
            $this->selectedItems[] = [
                'item_id' => $item->item_id,
                'rate' => $item->rate,
                'qty' => $item->qty,
                'amount' => $item->amount,
                'description' => $item->description,
                'is_resort' => $item->is_resort,
                'is_date' => $item->is_date,
                'is_time' => $item->is_time,
                'is_custome' => $item->is_custome,
                'custom_name' => $item->description,
            ];
        }
    }


    public function mount($invoice = null, $estimate_id = null)
    {
         if(request()->query('client_id')){
            $this->client_id = request()->query('client_id');
        }
        
        $this->invoiceSettings = InvoiceSettings::first();
        $this->invoice_title = ucfirst(($this->invoiceSettings?->invoice_title ?  $this->invoiceSettings->invoice_title : 'Quotation'));
        $this->pageTitle = ($invoice ? 'Edit ' : 'New ') . ucfirst(($this->invoiceSettings?->invoice_title ?  $this->invoiceSettings->invoice_title . 's' : 'Invoice'));

        $this->clients = Tourists::all()->pluck('company_name', 'id');
        $this->tours = Tours::where('status', 1)->pluck('name', 'tour_id');
        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'item_id');

        $this->parks = Parks::where('status', 1)->pluck('name', 'park_id');

        if ($invoice) {
            $this->invoiceId = $invoice;
            $this->loadinvoice();
        } else if ($estimate_id) {
            $this->estimate_id = $estimate_id;
            $this->loadEstimate();
        } else {
            $this->invoice_no = SettingHelper::getInvoiceNumber();
            $this->expiry_date = Carbon::now()->addDays(7)->format('Y-m-d');
            $this->invoice_date = Carbon::now()->format('Y-m-d');
            $this->terms_and_condition = $this?->invoiceSettings->terms_condition ?? '';
            $this->notes = $this->invoiceSettings?->customer_note ?? '';
            $this->addItem();
        }
    }



    public function addItem()
    {
        $this->selectedItems[] = [
            'item_id' => '',
            'rate' => 0,
            'qty' => 1,
            'amount' => 0,
            'description' => '',
            'is_resort' => '',
            'is_date' => '',
            'is_time' => '',
            'is_custome' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);
        $this->calculateTotals();
    }

    public function updateItem($index, $field, $value)
    {
        if (!isset($this->selectedItems[$index])) {
            return;
        }

        $this->selectedItems[$index][$field] = $value;
        if ($field === 'item_id' && !empty($value)) {
            $item = Items::find($value);
            if ($item) {
                $this->selectedItems[$index]['rate'] = $item->rate;
                $this->selectedItems[$index]['description'] = $item->description;
            }
        }
        $this->selectedItems[$index]['amount'] =
            $this->selectedItems[$index]['rate'] * $this->selectedItems[$index]['qty'];

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subTotal = array_reduce($this->selectedItems, function ($carry, $item) {
            return $carry + ($item['amount'] ?? 0);
        }, 0);
        $discount = (float) $this->discount;

        $this->totalAmount = $this->subTotal - ($this->subTotal * ($discount / 100));
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function render()
    {
        $this->clients = Tourists::all()->pluck('company_name', 'id');
        $this->tours = Tours::where('status', 1)->pluck('name', 'tour_id');
        $this->parks = Parks::where('status', 1)->pluck('name', 'park_id');

        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'item_id');

        return view('livewire.common.invoices.invoice-add');
    }


    public function tourAdd()
    {
        if (!$this->client_id) {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => '',
                'message' => 'Please Select a Client!'
            ]);
            return;
        }

        $this->showModal = true;
    }


    public function tourRules()
    {
        $table = (new Tours)->getTable();

        return [
            'tour_name' =>  'required|string|max:255|unique:' . $table . ',name',
            'client_id' => 'required',
            'tour_description' => 'required',
        ];
    }

    public function tourStore()
    {
        $this->validate($this->tourRules());

        $tour  = Tours::create([
            'name' => $this->tour_name,
            'client_id' => $this->client_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->tour_description,
            'status' => $this->tour_status,
        ]);

        if ($tour->status) {
            $this->tour_id = $tour->id;
        }
        $this->resetTourForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Tour Added Successfully'
        ]);
    }

    public function resetTourForm()
    {
        $this->reset([
            'tour_name',
            'start_date',
            'end_date',
            'tour_description',
            'tour_status',
            'showModal'
        ]);
        $this->resetValidation();
    }

    public function updatedParkId()
    {
        $this->resorts = Resorts::where('park_id', $this->park_id)->pluck('name', 'id');
    }

    public function updatedResortId()
    {
        $this->rooms = ResortCategorys::where('resort_id', $this->resort_id)->pluck('name', 'id');
    }
    public function updatedRoomId()
    {
        $rates = ResortCategorys::where('id', $this->room_id)
            ->select('regular_rate', 'high_season_rate', 'extra_child_rate', 'extra_adult_rate')
            ->first();
        $this->roomcats = [
            'regular_rate' => $rates->regular_rate ?? 0,
            'high_season_rate' => $rates->high_season_rate ?? 0,
            'extra_child_rate' => $rates->extra_child_rate ?? 0,
            'extra_adult_rate' => $rates->extra_adult_rate ?? 0,
        ];
    }

    public function resortAdd()
    {
        $this->showResortModal = true;
    }

    public function resortRules()
    {
        return [
            'park_id' =>  'required',
            'resort_id' => 'required',
            'room_id' => 'required',
            'room_cat_id' => 'required',
        ];
    }


    public function resortStore()
    {
        $this->validate($this->resortRules());
        $roomCategory = ResortCategorys::find($this->room_id);

        $roomCatName = $roomCategory->name;

        $rate = $roomCategory->regular_rate ?? 0;

        $this->selectedItems[] = [
            'item_id' => $roomCategory->id,
            'rate' => $rate,
            'qty' => 1,
            'amount' => $rate,
            'description' => $roomCatName,
            'is_resort' => 1,
            'custom_name' => $roomCatName,

            'is_date' => '',
            'is_time' => '',
            'is_custome' => '',
        ];

        $this->resetResortForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Resort Added Successfully'
        ]);
    }

    public function resetResortForm()
    {
        $this->reset([
            'park_id',
            'resort_id',
            'room_id',
            'room_cat_id',
            'resorts',
            'rooms',
            'roomcats',
            'showResortModal'
        ]);
        $this->resetValidation();
    }

    public function rules()
    {
        $table = (new Invoices)->getTable();

        $invoiceNoRule = 'required|string|max:255|unique:' . $table . ',invoice_no';

        if (!empty($this->invoiceId)) {
            $invoiceNoRule .= ',' . $this->invoiceId;
        }

        return [
            'invoice_no' => $invoiceNoRule,
            'client_id' => 'required',
            'invoice_title' => 'required',
            'invoice_date' => 'required',
            'expiry_date' => 'required',
            'totalAmount' => 'required',
            'notes' => 'required',
            'terms_and_condition' => 'required',

            // Item validations
            'selectedItems' => 'required|array|min:1',
            'selectedItems.*.item_id' => 'required|numeric|min:1',
        ];
    }


    public function messages()
    {
        return [
            'client_id.required' => 'Please add a Client',
            'tour_id.required' => 'Please add a Tour',
            'selectedItems.required' => 'Please add at least one item.',
            'selectedItems.*.item_id.required' => 'Please Select a Item',
        ];
    }


    public function addInvoice()
    {
        $this->validate($this->rules());

        $invoice = Invoices::create([
            'client_id' => $this->client_id,
            'invoice_no' => $this->invoice_no,
            'invoice_title' => $this->invoice_title,
            'po_number' => $this->po_number,
            'invoice_date' => $this->invoice_date,
            'tour_id' => $this->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->totalAmount,
            'notes' => $this->notes,
            'terms_and_condition' => $this->terms_and_condition,
            'discount' => $this->discount,
            'sub_total' => $this->subTotal,
            'estimate_id' => $this->estimate_id,

            'lead_id' => $this->leadId,

        ]);

        if ($this->estimate_id && $invoice) {
            Quotations::where('id', $this->estimate_id)->update([
                'status' => 2
            ]);
        };


        foreach ($this->selectedItems as $item) {
            InvoiceItems::create([
                'invoice_id'     => $invoice->id,
                'item_id'     =>  !empty($item['item_id']) ? $item['item_id'] : 0,
                'rate'        => $item['rate'],
                'qty'         => $item['qty'],
                'amount'      => $item['amount'],
                'description' => $item['description'],
                'is_resort' => !empty($item['is_resort']) ? 1 : 0,
                'is_date' => $item['is_date'],
                'is_time' => $item['is_time'],
                'is_custome' => $item['is_custome'],
            ]);
        }

        SettingHelper::generateAndSaveNextInvoiceNumber();

        if ($this->leadId) {
            SettingHelper::leadActivityLog(8, $this->leadId, null);
        }

        if ($this->estimate_id) {
            SettingHelper::InvEstActivityLog(18, null, $this->estimate_id, null);
        }
        SettingHelper::InvEstActivityLog(11, $invoice->id, null, null);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);

        $this->redirect(route('common.invoice'), navigate: true);
    }

    // NEW 
    public function updateInvoice()
    {
        $this->validate($this->rules());

        $invoice = Invoices::findOrFail($this->invoiceId);

        $invoice->update([
            'client_id' => $this->client_id,
            'invoice_no' => $this->invoice_no,
            'invoice_title' => $this->invoice_title,
            'po_number' => $this->po_number,
            'invoice_date' => $this->invoice_date,
            'tour_id' => $this->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->totalAmount,
            'notes' => $this->notes,
            'terms_and_condition' => $this->terms_and_condition,
            'discount' => $this->discount,
            'sub_total' => $this->subTotal,
        ]);

        InvoiceItems::where('invoice_id', $invoice->id)->delete();

        foreach ($this->selectedItems as $item) {
            InvoiceItems::create([
                'invoice_id'     => $invoice->id,
                'item_id'     =>  !empty($item['item_id']) ? $item['item_id'] : 0,
                'rate'        => $item['rate'],
                'qty'         => $item['qty'],
                'amount'      => $item['amount'],
                'description' => $item['description'],
                'is_resort' => !empty($item['is_resort']) ? 1 : 0,
                'is_date' => $item['is_date'],
                'is_time' => $item['is_time'],
                'is_custome' => $item['is_custome'],
            ]);
        }

        SettingHelper::InvEstActivityLog(12, $invoice->id, null, null);


        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Invoice updated successfully!'
        ]);

        $this->redirect(route('common.view-invoice', $this->invoiceId), navigate: true);
    }
}
