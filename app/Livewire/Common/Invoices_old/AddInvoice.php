<?php

namespace App\Livewire\Common\Invoices;

use App\Helpers\SettingHelper;
use App\Models\Invoices;
use App\Models\Tourists;
use App\Models\Currency;
use App\Models\Quotations;
use App\Models\InvoiceItems;
use App\Models\InvoiceSettings;
use App\Models\Items;
use App\Models\Leads;
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
        foreach ($estimate->items as $tourJson) {
            if ($tourJson->is_tour == 1) {
                $this->tableDataJson = json_decode($tourJson->json, true);
                $this->markupammount = $this->tableDataJson['markupammount'] ?? 1.25;
                $this->usdammount = $this->tableDataJson['usdammount'] ?? 80;
            }
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
            } else {
                $this->selectedItems[] = [
                    'name' => $tourJson->item_name,
                    'description' => $tourJson->description ?? 'No description',
                    'amount' => $tourJson->amount,
                    'is_tour' => $tourJson->is_tour,
                ];
            }
        }
    }


    public function mount($estimate_id = null, $lead_id = null, $revised_id = null)
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

        if ($estimate_id) {
            $this->estimateId = $estimate_id;
            $this->estimate_no = SettingHelper::getInvoiceNumber();
            $this->loadInvoice();
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

    public function calculateTotals($code = null)
    {
        if (empty($this->tableDataJson['tourPackage']['days']) || !$this->currency || !$this->usdammount) {
            foreach ($this->selectedItems as &$item) {
                if ($this->currency == '$') {
                    // Convert INR to USD if not already converted
                    if (isset($item['amount'])) {
                        if (!isset($item['original_inr_amount'])) {
                            $item['original_inr_amount'] = $item['amount'];
                        }
                        $item['amount'] = number_format(
                            ($item['original_inr_amount'] ?? $item['amount']) / ($this->usdammount ?? 80),
                            2
                        );
                    } else {
                        $item['amount'] = 0;
                    }
                } else {
                    // Keep as INR
                    $item['amount'] = isset($item['original_inr_amount'])
                        ? $item['original_inr_amount']
                        : floatval($item['amount'] ?? 0);
                }
            }

            // ✅ Keep your previous NEW DEV LINE logic
            $this->totalAmount = collect($this->selectedItems)
                ->sum(
                    fn($item) => !empty($item['is_tour'])
                        ? $this->withmarkup_total
                        : floatval($item['amount'] ?? 0)
                );

            return;
        }

        if ($this->currency == '$' && $this->tour_id) {
            foreach ($this->selectedItems as &$item) {
                if ($item['is_tour'] == 0) {
                    if (isset($item['amount'])) {
                        if (!isset($item['original_inr_amount'])) {
                            $item['original_inr_amount'] = $item['amount'];
                        }
                        if ($this->estimateId) {
                            $inr_amount = bcmul($item['amount'], $this->tableDataJson['usdammount'], 2);
                            $new_usd_amount = bcdiv($inr_amount, $this->usdammount, 2);
                            $item['amount'] = $new_usd_amount;
                        } else {
                            $item['amount'] = number_format(
                                ($item['original_inr_amount'] ?? $item['amount']) / ($this->usdammount ?? 80),
                                2
                            );
                        }
                    } else {
                        $item['amount'] = 0;
                    }
                }
            }
        } else {
            foreach ($this->selectedItems as &$item) {
                if ($item['is_tour'] == 0) {
                    $item['amount'] = isset($item['original_inr_amount'])
                        ? $item['original_inr_amount']
                        : $item['amount'];
                }
            }
        }

        // Step 1: Calculate total from all days
        $total = collect($this->tableDataJson['tourPackage']['days'])->sum('totalForTheDay');

        $markupPersent = ($this->markupammount / 100 + 1);

        // Step 2: Add GST (5%)
        $totalWithGst = $total * 1.05;

        // Step 3: Add Markup
        $withMarkup = $totalWithGst * (float)($markupPersent ?? 1.25);

        // Step 4: USD Conversion
        $usd = $withMarkup / (float)($this->usdammount ?? 80);

        if ($this->currency == '$') {
            $this->withmarkup_total = SettingHelper::conditionalRound($usd);
        } else {
            $this->withmarkup_total = SettingHelper::conditionalRound($withMarkup);
        }

        $perPerson = ($usd > 0) ? $usd / 2 : 0;

        $this->tableDataJson['tourPackage']['summary']['Total']['Total for the Day'] = round($total, 2);
        $this->tableDataJson['tourPackage']['summary']['Total + GST']['Total for the Day'] = round($totalWithGst, 2);
        $this->tableDataJson['tourPackage']['summary']['With Markup %']['Total for the Day'] = SettingHelper::conditionalRound($withMarkup);
        $this->tableDataJson['tourPackage']['summary']['USD']['Total for the Day'] = SettingHelper::conditionalRound($usd);

        $this->totalAmount = collect($this->selectedItems)
            ->sum(
                fn($item) => !empty($item['is_tour'])
                    ? SettingHelper::conditionalRound($this->withmarkup_total)
                    : floatval($item['amount'] ?? 0)
            );
    }
}
