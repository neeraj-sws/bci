<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\Companies;
use App\Models\Invoices;
use App\Models\Tourists;
use App\Models\Currency;
use App\Models\Quotations;
use App\Models\InvoiceItems;
use App\Models\InvoiceSettings;
use App\Models\Items;
use App\Models\Leads;
use App\Models\QuotationSettings;
use App\Models\Tours;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class AddInvoice extends Component
{
    public $pageTitle, $showModal = false, $showItemModal = false;
    public $estimateSettings;
    public $clients, $items, $tours, $currencys, $currency = "₹";

    public $estimate_no, $client_id, $estimate_title, $estimate_date, $po_number, $tour_id, $expiry_date, $terms_and_condition, $notes;

    public $selectedItems = [];
    public $subTotal = 0;
    public $discount = 0;
    public $totalAmount = 0;
    // NEW 
    public $estimateId;
    public $leadId;

    public $route;
    public $estimate_id;

    public $tableDataJson = [];

    public $start_date, $end_date;
    public $withmarkup_total, $markupammount, $usdammount;
    public $item_id, $item_amount, $saveItem = false, $item_name;
    // NEW DEV 
    public $companies, $company_id;
    public function loadInvoice()
    {
        $estimate = Quotations::with('items')->where('uuid', $this->estimateId)->first();
        $this->estimate_id = $estimate->id;
        $this->client_id = $estimate->leads->tourist_id;
        $this->estimate_title = $estimate->quotation_title;
        $this->po_number = $estimate->po_number;
        $this->estimate_date = $estimate->quotation_date;
        $this->tour_id = $estimate->tour_id;
        $this->expiry_date = $estimate->expiry_date;
        $this->terms_and_condition = $estimate->terms_and_condition;
        $this->notes = $estimate->notes;
        $this->totalAmount = $estimate->amount;
        $this->end_date = $estimate->end_date;
        $this->start_date = $estimate->start_date;
        $this->leadId = $estimate->leads->id;

        // NEW DEV 
        $this->company_id = $estimate->company_id;
        $this->subTotal = $estimate->sub_amount;
        $this->discount = $estimate->discount_amount;

        foreach ($estimate->items as $tourJson) {
            if ($tourJson->is_tour == 1) {
                $this->tableDataJson = json_decode($tourJson->json, true);
                $this->markupammount = $this->tableDataJson['markupammount'] ?? 1.25;
                $this->usdammount = $this->tableDataJson['usdammount'] ?? 80;

                if (isset($this->tableDataJson['tourPackage']['summary']['Total + GST'])) {
                    $this->currency = $this->tableDataJson['currency'];

                    if ($this->currency == '$') {
                        $this->withmarkup_total = $this->tableDataJson['tourPackage']['summary']['USD']['Total for the Day'] ?? 0;
                    } else {
                        $this->withmarkup_total = $this->tableDataJson['tourPackage']['summary']['With Markup %']['Total for the Day'] ?? 0;
                    }

                    $this->selectedItems[] = [
                        'name' => $tourJson->item_name,
                        'description' => $tourJson->description ?? 'No description',
                        'amount' => $tourJson->amount,
                        'is_tour' => $tourJson->is_tour,
                    ];
                }
            } else {
                $this->selectedItems[] = [
                    'name' => $tourJson->item_name,
                    'description' => $tourJson->description ?? 'No description',
                    'amount' => $tourJson->amount,
                    // NEW DEV 
                    'inr_amount' => $tourJson->inr_amount,
                    'original_inr_amount' => $tourJson->inr_amount,
                    'usd_amount' => $tourJson->usd_amount,
                    'currency_label' => $tourJson->currency_label,
                    // ends here
                    'is_tour' => $tourJson->is_tour,
                ];
            }
        }

        // NEW DEV 
        if (empty($this->tableDataJson) && !empty($this->selectedItems)) {
            $firstItem = collect($this->selectedItems)->firstWhere('is_tour', 0);
            if (!empty($firstItem['currency_label'])) {
                $this->currency = $firstItem['currency_label'] == 'USD' ? '$' : '₹';
                $this->usdammount = SettingHelper::getUsdPrice($estimate->company_id);
            }
        }
    }
    public function mount($estimate_id = null)
    {
        $this->route = 'common';

        $this->estimateSettings = InvoiceSettings::first();
        $this->estimate_title = 'Invoice';
        $this->pageTitle = ($estimate_id ? 'Edit ' : 'New ') . 'Invoices';

        $this->clients = Tourists::all()->pluck('primary_contact', 'tourist_id');
        $this->tours = Tours::where('status', 1)->pluck('name', 'tour_id');
        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'id');
        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'id');


        $this->markupammount = SettingHelper::getMarkup();
        $this->currencys = Currency::all()->pluck('currency', 'code');
        $this->usdammount = SettingHelper::getUsdPrice();

        // NEW DEV 
        $this->companies = Companies::select('company_id', 'company_name', 'company_email')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->company_name . ' - ' . $tourist->company_email];
            })
            ->toArray();

        if ($estimate_id) {
            $this->estimateId = $estimate_id;
            $this->loadInvoice();
            $this->updatedCompanyId($this->company_id);
        }
    }
    public function render()
    {
        $this->clients = Tourists::select('tourist_id', 'primary_contact', 'contact_phone')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->primary_contact . ' - ' . $tourist->contact_phone];
            })
            ->toArray();
        $this->tours = Tours::where('status', 1)->pluck('name', 'tour_id');

        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'id');

        return view('livewire.common.invoices.invoice-add');
    }
    public function edit()
    {
        $this->showModal = !$this->showModal;
    }
    public function rules()
    {
        $table = (new Invoices)->getTable();

        $estimateNoRule = 'required|string|max:255|unique:' . $table . ',invoice_no';

        return [
            'estimate_no' => $estimateNoRule,
            'estimate_title' => 'required|string',
            'estimate_date' => 'required|date_format:Y-m-d',
            'expiry_date' => 'required|date_format:Y-m-d',
            'totalAmount' => 'required',
            'notes' => 'required',
            'terms_and_condition' => 'required',
            'end_date' => 'required|date_format:Y-m-d',
            'start_date' => 'required|date_format:Y-m-d',
            'selectedItems' => 'required|array|min:1',
        ];
    }
    public function messages()
    {
        return [
            'estimate_title.required' => 'The Invoice Title field is required.',
            'estimate_date.required' => 'The Invoice Date field is required.',
            'estimate_date.date_format' => 'The Invoice Date must be in Y-m-d format (e.g., 2025-10-06).',
            'expiry_date.required' => 'The Expiry Date field is required.',
            'expiry_date.date_format' => 'The Expiry Date must be in Y-m-d format (e.g., 2025-10-06).',
            'start_date.required' => 'The Start Date field is required.',
            'start_date.date_format' => 'The Start Date must be in Y-m-d format (e.g., 2025-10-06).',
            'end_date.required' => 'The End Date field is required.',
            'end_date.date_format' => 'The End Date must be in Y-m-d format (e.g., 2025-10-06).',
            'selectedItems.required' => 'Please add at least one item.',
            'selectedItems.array' => 'The selected items must be an array.',
            'selectedItems.min' => 'Please add at least one item.',
        ];
    }
    public function addInvoice()
    {
        $this->validate($this->rules());

        $estimate = Invoices::create([
            'invoice_no' => $this->estimate_no,
            'invoice_title' => $this->estimate_title,
            'po_number' => $this->po_number,
            'invoice_date' => $this->estimate_date,
            'tour_id' => $this->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->totalAmount,
            'notes' => $this->notes,
            'terms_and_condition' => $this->terms_and_condition,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,

            'lead_id' => $this->leadId,
            'tourist_id' => $this->client_id,
            'quotation_id' => $this->estimate_id,
            'user_id' => Auth::guard('web')->user()->id
        ]);

        // NEW DEV 
        if ($this->leadId) {
            $lead = Leads::findOrFail($this->leadId);
            $lead->update([
                "stage_id" => 5
            ]);
        }
        if ($this->estimate_id) {
            Quotations::where('quotation_id', $this->estimate_id)
                ->update(['status' => 6]);
        }

        $tableDataArray = $this->tableDataJson;
        $tableDataArray['markupammount'] = $this->markupammount;
        $tableDataArray['usdammount'] = $this->usdammount;
        $tableDataArray['currency'] = $this->currency;
        $tableData = json_encode($tableDataArray);
        foreach ($this->selectedItems as $item) {
            InvoiceItems::create([
                'invoice_id'     => $estimate->id,
                'item_name'         => $item['name'],
                'description'         => $item['description'],
                'amount'         => $item['amount'],
                'is_tour'         => $item['is_tour'],
                'json'         => $item['is_tour'] ? $tableData : null
            ]);
        }

        SettingHelper::generateAndSaveNextInvoiceNumber();

        if ($this->estimate_id) {
            SettingHelper::leadActivityLog(8, $this->leadId, null);
            SettingHelper::InvEstActivityLog(18, null, $this->estimate_id, null);
        }
        SettingHelper::InvEstActivityLog(11, $estimate->id, null, null);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);

        $this->redirect(route($this->route . '.invoice'), navigate: true);
    }
    public function calculateTotals($editedIndex = null)
    {
        $rate = (float)($this->usdammount ?? 80);

        foreach ($this->selectedItems as $i => &$item) {
            // Save original INR amount if not already
            if (!isset($item['original_inr_amount'])) {
                $item['original_inr_amount'] = floatval($item['amount'] ?? 0);
            }

            $originalInr = floatval($item['original_inr_amount']);



            if ($item['is_tour'] == 0) {
                // If this is the item we just edited, take the edited value as the source
                if ($editedIndex !== null && $i == $editedIndex) {
                    if ($this->currency == '$') {
                        // USD was edited, recalc INR
                        $item['usd_amount'] = floatval($item['amount']);
                        // $item['inr_amount'] = round($item['usd_amount'] * $rate, 2);
                        $item['inr_amount'] = round($item['usd_amount'] * $rate);
                    } else {
                        // INR was edited, recalc USD
                        $item['inr_amount'] = floatval($item['amount']);
                        // $item['usd_amount'] = round($item['inr_amount'] / $rate, 2);
                        $item['usd_amount'] = round($item['inr_amount'] / $rate);
                    }
                } else {
                    // Normal calculation based on currency
                    if ($this->currency == '$') {
                        $item['amount'] = round($originalInr / $rate, 2);
                    } else {
                        $item['amount'] = $originalInr;
                    }

                    $item['inr_amount'] = $originalInr;
                    $item['usd_amount'] = round($originalInr / $rate, 2);
                }

                $item['currency_label'] = $this->currency;
            } else {
                // Tour items: don't change
                $item['amount'] = $originalInr;
            }
        }
        // Step 2: Calculate total from items
        $this->totalAmount = collect($this->selectedItems)
            ->sum(fn($item) => $item['is_tour'] ? $this->withmarkup_total : floatval($item['amount'] ?? 0));
        $this->subTotal = $this->totalAmount;

        // Step 3: Apply discount
        if (!empty($this->discount)) {
            $this->totalAmount -= $this->discount;
            if ($this->totalAmount < 0) {
                $this->totalAmount = 0;
            }
        }

        // Step 4: Calculate package summary if days exist
        if (!empty($this->tableDataJson['tourPackage']['days'])) {
            $total = collect($this->tableDataJson['tourPackage']['days'])->sum('totalForTheDay');
            $markupPercent = ($this->markupammount / 100 + 1);
            $totalWithGst = $total * 1.05;
            $withMarkup = $totalWithGst * (float)($markupPercent ?? 1.25);
            $usd = $withMarkup / $rate;

            $this->withmarkup_total = ($this->currency == '$')
                ? SettingHelper::conditionalRound($usd)
                : SettingHelper::conditionalRound($withMarkup);

            $this->tableDataJson['tourPackage']['summary']['Total']['Total for the Day'] = round($total, 2);
            $this->tableDataJson['tourPackage']['summary']['Total + GST']['Total for the Day'] = round($totalWithGst, 2);
            $this->tableDataJson['tourPackage']['summary']['With Markup %']['Total for the Day'] = SettingHelper::conditionalRound($withMarkup);
            $this->tableDataJson['tourPackage']['summary']['USD']['Total for the Day'] = SettingHelper::conditionalRound($usd);
        }
    }
    public function updatedDiscount($value)
    {
        $this->calculateTotals();
    }
    public function updatedCompanyId($id)
    {
        $this->markupammount = SettingHelper::getMarkup($id);
        $this->usdammount = SettingHelper::getUsdPrice($id);
        $this->estimate_no = SettingHelper::getInvoiceNumber($id);

        $this->estimateSettings = QuotationSettings::where('company_id', $id)->first();
        $this->terms_and_condition = $this?->estimateSettings->terms_condition ?? '';
        $this->notes = $this->estimateSettings?->customer_note ?? '';
    }
}
