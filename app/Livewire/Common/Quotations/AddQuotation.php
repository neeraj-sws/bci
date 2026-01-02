<?php

namespace App\Livewire\Common\Quotations;

use App\Helpers\SettingHelper;
use App\Models\Tourists;
use App\Models\Currency;
use App\Models\Companies;
use App\Models\QuotationItems;
use App\Models\Quotations;
use App\Models\QuotationSettings;
use App\Models\Items;
use App\Models\Leads;
use App\Models\Tours;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.common-app')]
class AddQuotation extends Component
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

    public $tableDataJson = [];

    public $start_date, $end_date;
    public $withmarkup_total, $markupammount, $usdammount;
    public $item_id, $item_amount, $saveItem = false, $item_name;

    //  NEW DEV 
    public $revision_no, $version_of;
    public $tourDays;

    // NEW DEV 
    public $companies, $company_id;

    public function loadRevised()
    {
        $estimate = Quotations::with('items')->findOrFail($this->version_of);

        $this->client_id = $estimate->tourist_id;
        $this->estimate_no = $estimate->quotation_no;
        $this->estimate_title = $estimate->quotation_title;
        $this->po_number = $estimate->po_number;
        $this->estimate_date = $estimate->quotation_date;

        // NEW DEV 
        $this->company_id = $estimate->company_id;
        $this->subTotal = $estimate->sub_amount;
        $this->discount = $estimate->discount_amount;

        $this->tour_id = $estimate->tour_id;
        $this->expiry_date = $estimate->expiry_date;
        $this->terms_and_condition = $estimate->terms_and_condition;
        $this->notes = $estimate->notes;
        $this->totalAmount = $estimate->amount;
        $this->end_date = $estimate->end_date;
        $this->start_date = $estimate->start_date;

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
            }
            // NEW DEV 
            else {
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
    public function loadEstimate()
    {
        $estimate = Quotations::with('items')->findOrFail($this->estimateId);

        $this->client_id = $estimate->leads->tourist_id;
        $this->estimate_no = $estimate->quotation_no;
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
    public function loadLead()
    {
        $lead = Leads::where('uuid', $this->leadId)->firstOrFail();
        $this->leadId = $lead->id;
        $this->client_id = $lead->tourist_id;
        $this->currency = $lead->tourist->base_currency_code;
    }
    public function mount($estimate = null, $lead_id = null, $revised_id = null)
    {
        $this->route = 'common';
        if (request()->query('client_id')) {
            $this->client_id = request()->query('client_id');
        }
        if ($lead_id) {
            $this->leadId = $lead_id;
            $this->loadLead();
        }

        // $this->estimateSettings = QuotationSettings::first();
        $this->estimate_title = 'Quotation';
        $this->pageTitle = ($estimate ? 'Edit ' : 'New ') . 'Quotations';


        $this->clients = Tourists::all()->pluck('primary_contact', 'tourist_id');
        $this->tours = Tours::where('status', 1)->pluck('name', 'tour_id');
        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'id');
        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'id');

        // NEW DEV 
        $this->companies = Companies::select('company_id', 'company_name', 'company_email')
            ->get()
            ->mapWithKeys(function ($tourist) {
                return [$tourist->id => $tourist->company_name . ' - ' . $tourist->company_email];
            })
            ->toArray();


        // $this->markupammount = SettingHelper::getMarkup();
        $this->currencys = Currency::all()->pluck('currency', 'code');
        // $this->usdammount = SettingHelper::getUsdPrice();

        if ($estimate) {
            $this->estimateId = $estimate;
            $this->loadEstimate();
        } elseif ($revised_id) {
            // NEW DEV 
            $this->version_of = base64_decode($revised_id);
            $this->loadRevised();
        } else {
            $this->estimate_no = SettingHelper::getEstimateNumber();
            $this->expiry_date = Carbon::now()->addDays(4)->format('Y-m-d');
            $this->estimate_date = Carbon::now()->format('Y-m-d');
            $this->terms_and_condition = $this?->estimateSettings->terms_condition ?? '';
            $this->notes = $this->estimateSettings?->customer_note ?? '';
            $this->company_id = Companies::where('is_primary', 1)?->first()?->company_id ?? null;
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
        $this->tours = Tours::where('status', 1)
         ->orderByDesc('updated_at')
        ->pluck('name', 'tour_id');

        $this->items = Items::where('status', 1)->get()->pluck('full_name', 'id');

        return view('livewire.common.quotations.quotation-add');
    }
    public function removeItem($index)
    {
        if ($this->selectedItems[$index]['is_tour']) {
            $this->tour_id = null;
            $this->markupammount = SettingHelper::getMarkup();
            $this->usdammount = SettingHelper::getUsdPrice();
            // NEW DEV 
            $this->tableDataJson = []; // reset the tour JSON data
            $this->withmarkup_total = 0; // reset totals related to tou

            // NEW DEV 
            $this->tourDays = null;
            $this->dispatch('tour-days-updated');
        };
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);
        $this->calculateTotals();
    }
    public function edit()
    {
        $this->showModal = !$this->showModal;
        $this->dispatch('open-new-item-modal');
    }
    public function rules()
    {
        $table = (new Quotations)->getTable();

        $estimateNoRule = 'required|string|max:255|unique:' . $table . ',quotation_no';
        if (!empty($this->estimateId)) {
            $estimateNoRule .= ',' . $this->estimateId . ',quotation_id';
        }

        if ($this->version_of) {
            $estimateNoRule .= ',' . $this->version_of . ',quotation_id';
        }

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
            'company_id' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'estimate_title.required' => 'The Estimate Title field is required.',
            'estimate_date.required' => 'The Estimate Date field is required.',
            'estimate_date.date_format' => 'The Estimate Date must be in Y-m-d format (e.g., 2025-10-06).',
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
    public function addEstimate()
    {
        $this->validate($this->rules());

        // NEW DEV 
        $currencyLabel = $this->currencys->get($this->currency, "INR");
        $tableDataArray = $this->tableDataJson;

        // NEW EXTRA DEV 
        $inrSummary = 0;
        $usdSummary = 0;
        foreach ((array) $this->selectedItems as $item) {
            if (($item['is_tour'] ?? 0) == 0) {
                $inrSummary += floatval($item['inr_amount'] ?? 0);
                $usdSummary += floatval($item['usd_amount'] ?? 0);
            }
        }
        //

        $estimate = Quotations::create([
            'quotation_no' => $this->estimate_no,
            'quotation_title' => $this->estimate_title,
            'po_number' => $this->po_number,
            'quotation_date' => $this->estimate_date,
            'tour_id' => $this->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->totalAmount,
            'notes' => $this->notes,
            'terms_and_condition' => $this->terms_and_condition,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,
            'lead_id' => $this->leadId,
            'tourist_id' => $this->client_id,
            'user_id' => Auth::guard('web')->user()->id,
            'company_id' => $this->company_id,
            'sub_amount' => $this->subTotal,
            'discount_amount' => $this->discount,


            // 'inr_amount' => $inrSummary + $tableDataArray['tourPackage']['summary']['With Markup %']['Total for the Day'] ?? 0,
            // 'usd_amount' => $usdSummary + $tableDataArray['tourPackage']['summary']['USD']['Total for the Day'],
            'currency_label' => $currencyLabel,

            'total_remaning_amount' => $this->totalAmount,
            'total_paid_amount' => 0

        ]);
        $leadUuid = Str::uuid()->toString() . '-' . $estimate->id . '-' . Str::uuid()->toString();
        $encodedUuid = base64_encode($leadUuid);
        $estimate->uuid = $encodedUuid;
        $estimate->save();
        if ($this->leadId) {
            $lead = Leads::findOrFail($this->leadId);
            $lead->update([
                "stage_id" => 4
            ]);
            $tourist = Tourists::findOrFail($this->client_id);
            if ($tourist && $tourist->flag == 1) {
                $tourist->update(['flag' => 2]);
            }
        }
        if ($this->version_of) {
            $version_of = Quotations::findOrFail($this->version_of);
            $version_of->update([
                "total_revised" => $version_of->total_revised + 1,
                "status" => 4
            ]);

            SettingHelper::InvEstActivityLog(26, null, $version_of->id, null);

            if ($version_of->lead_id) {
                $lead = Leads::findOrFail($version_of->lead_id);
                $lead->update([
                    "stage_id" => 5
                ]);
            }
            $current = $this->estimate_no;
            if (preg_match('/-R-(\d+)$/', $current, $matches)) {
                $revision_no = (int)$matches[1] + 1;
                $new_quotation_no = preg_replace('/-R-\d+$/', '-R-' . $revision_no, $current);
            } else {
                $revision_no = 1;
                $new_quotation_no = $current . '-R-' . $revision_no;
            }

            $estimate->update([
                "quotation_no" => $new_quotation_no,
                "revision_no" => $version_of->total_revised,
                "version_of" => $version_of->id,
                "lead_id" => $version_of->lead_id
            ]);
            if ($version_of->revised_no) {
                $estimate->update([
                    "revised_no" => $version_of->revised_no
                ]);
            } else {
                $estimate->update([
                    "revised_no" => $version_of->quotation_no
                ]);
            }
        }

        $tableDataArray['markupammount'] = $this->markupammount;
        $tableDataArray['usdammount'] = $this->usdammount;
        $tableDataArray['currency'] = $this->currency;
        $tableData = json_encode($tableDataArray);

        foreach ($this->selectedItems as $item) {
            QuotationItems::create([
                'quotation_id'     => $estimate->id,
                'item_name'         => $item['name'],
                'description'         => $item['description'],
                'amount'         => $item['is_tour'] ? $this->withmarkup_total : $item['amount'],

                'inr_amount'         => $item['is_tour'] ? $tableDataArray['tourPackage']['summary']['With Markup %']['Total for the Day'] ?? 0 : $item['inr_amount'],
                'usd_amount'         => $item['is_tour'] ? $tableDataArray['tourPackage']['summary']['USD']['Total for the Day'] ?? 0 : $item['usd_amount'],
                'currency_label'     => $currencyLabel,


                'is_tour'         => $item['is_tour'],
                'json'         => $item['is_tour'] ? $tableData : null
            ]);
        }

        if (!$this->version_of) {
            SettingHelper::generateAndSaveNextEstimateNumber($this->company_id);
        }

        if ($this->leadId) {
            SettingHelper::leadActivityLog(7, $this->leadId, null);
            SettingHelper::InvEstActivityLog(14, null, $estimate->id, null);
        } else {
            SettingHelper::InvEstActivityLog(9, null, $estimate->id, null);
        }

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);

        $this->redirect(route($this->route . '.view-quotation',$estimate->uuid));
    }
    // NEW 
    public function updateEstimate()
    {
        // dd($this->selectedItems);

        $this->validate($this->rules());

        $estimate = Quotations::findOrFail($this->estimateId);
        $estimate->update([
            'quotation_no' => $this->estimate_no,
            'quotation_title' => $this->estimate_title,
            'po_number' => $this->po_number,
            'quotation_date' => $this->estimate_date,
            'tour_id' => $this->tour_id,
            'expiry_date' => $this->expiry_date,
            'amount' => $this->totalAmount,
            'notes' => $this->notes,
            'terms_and_condition' => $this->terms_and_condition,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,

            'tourist_id' => $this->client_id,

            // NEW DEV 
            'company_id' => $this->company_id,

            'sub_amount' => $this->subTotal,
            'discount_amount' => $this->discount,
        ]);

        QuotationItems::where('quotation_id', $estimate->id)->delete();

        $tableDataArray = $this->tableDataJson;
        $tableDataArray['markupammount'] = $this->markupammount;
        $tableDataArray['usdammount'] = $this->usdammount;
        $tableDataArray['currency'] = $this->currency;
        $tableData = json_encode($tableDataArray);
        // NEW DEV 
        $currencyLabel = $this->currencys->get($this->currency, "INR(₹)");
        foreach ($this->selectedItems as $item) {
            QuotationItems::create([
                'quotation_id'     => $estimate->id,
                'item_name'         => $item['name'],
                'description'         => $item['description'],
                'amount'         => $item['is_tour'] ? $this->withmarkup_total : $item['amount'],

                'inr_amount'         => $item['is_tour'] ? $this->withmarkup_total : $item['inr_amount'],
                'usd_amount'         => $item['is_tour'] ? $this->usdammount : $item['usd_amount'],
                'currency_label'     => $currencyLabel,


                'is_tour'         => $item['is_tour'],
                'json'         => $item['is_tour'] ? $tableData : null
            ]);
        }


        SettingHelper::InvEstActivityLog(10, null, $estimate->id, null);


        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Quotation updated successfully!'
        ]);

        $this->redirect(route($this->route . '.view-quotation', $estimate->uuid));
    }
    public function updatedTourId($id)
    {
        $tour = Tours::with('tourJsons')->find($id);
        if ($this->start_date && $tour->day) {
            $this->tourDays = Carbon::parse($this->start_date)
                ->addDays(((int) $tour->day) - 1)
                ->format('Y-m-d');

                $this->end_date = $this->tourDays;
            $this->dispatch('tour-days-updated');
        }

        $this->selectedItems = array_filter($this->selectedItems, fn($i) => empty($i['is_tour']));

        if ($tour && $tour->tourJsons->isNotEmpty()) {
            $tourJson = $tour->tourJsons->first();
            $this->tableDataJson = json_decode($tourJson->json, true);
            $this->estimate_title = $tour->name;
            // NEW DEV 
            if (isset($this->tableDataJson['tourPackage']['summary']['Total + GST'])) {

                $this->markupammount = $this->tableDataJson['markupammount'] ?? 1.25;
                $this->usdammount = $this->tableDataJson['usdammount'] ?? 80;

                $total = collect($this->tableDataJson['tourPackage']['days'])->sum('totalForTheDay');
                $markupPercent = ($this->markupammount / 100 + 1);
                $totalWithGst = $total * 1.05;
                $withMarkup = $totalWithGst * (float)$markupPercent;
                $rate = (float)($this->usdammount ?? 80);

                $this->withmarkup_total = ($this->currency == '$')
                    ? SettingHelper::conditionalRound($withMarkup / $rate)
                    : SettingHelper::conditionalRound($withMarkup);

                $this->selectedItems[] = [
                    'name' => $tour->name,
                    'amount' => $this->withmarkup_total,
                    'description' => $tour->description ?? 'No description',
                    'is_tour' => 1
                ];
            }
        } else {
            $this->tableDataJson = [];
        }
        $this->calculateTotals();
    }
    public function recalculateDay($index)
    {
        if (!isset($this->tableDataJson['tourPackage']['days'][$index])) {
            return;
        }

        $day = &$this->tableDataJson['tourPackage']['days'][$index];

        $roomPerNight = (float) ($day['roomPerNight'] ?? 0);
        $numberOfRooms = (int) ($day['numberOfRooms'] ?? 0);
        $vehicleCost   = (float) ($day['vehicleCost'] ?? 0);
        $safariCost    = (float) ($day['safariCost'] ?? 0);
        $safariNumber  = (int) ($day['safariNumber'] ?? 0);
        $monumentFee   = (float) ($day['monumentFee'] ?? 0);
        $entryNumbers  = (int) ($day['entryNumbers'] ?? 0);
        $hotelAdvance  = (float) ($day['hotelAdvance'] ?? 0);

        // ---- Formula ----
        $day['hotelTotal']   = $roomPerNight * $numberOfRooms;
        $day['totalForTheDay'] = ($roomPerNight * $numberOfRooms)
            + $vehicleCost
            + ($safariCost * $safariNumber)
            + ($monumentFee * $entryNumbers);

        $day['hotelBalance'] = $day['hotelTotal'] - $hotelAdvance;


        $this->calculateTotals();
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

        // Step 2: Calculate package summary if days exist
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

        // Step 3: Calculate total from items
        $this->totalAmount = collect($this->selectedItems)
            ->sum(fn($item) => $item['is_tour'] ? $this->withmarkup_total : floatval($item['amount'] ?? 0));
        $this->subTotal = $this->totalAmount;

        // Step 4: Apply discount
        if (!empty($this->discount)) {
            $this->totalAmount -= $this->discount;
            if ($this->totalAmount < 0) {
                $this->totalAmount = 0;
            }
        }
    }
    public function addItem()
    {
        $this->showItemModal = !$this->showItemModal;
        $this->reset(['item_id', 'item_amount', 'saveItem', 'item_name']);
    }
    public function updatedItemId()
    {
        $this->item_amount = Items::find($this->item_id)?->rate ?? null;
    }
    public function itemStore()
    {
        $this->validate([
            'item_id' => $this->saveItem ? 'nullable' : 'required|exists:items,item_id',
            'item_amount' => 'required',
            'item_name' => $this->saveItem ? 'required|unique:items,name' : 'nullable',
        ]);
        if ($this->saveItem) {
            $item = Items::create([
                "name" => $this->item_name,
                "rate" => $this->item_amount,
                "status" => 1
            ]);
            $this->selectedItems[] = [
                'name' => $item->full_name,
                'description' => $item->description ?? 'No description',
                'amount' => $this->item_amount ?? 0,
                'is_tour' => 0,
            ];
            $this->dispatch('open-new-item-modal');
        } else {
            $item = Items::find($this->item_id);
            if ($item) {
                $this->selectedItems[] = [
                    'name' => $item->full_name,
                    'description' => $item->description ?? 'No description',
                    'amount' => $this->item_amount ?? 0,
                    'is_tour' => 0,
                ];
            }
        }
        $this->calculateTotals();
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' =>  'Item Added Successfully'
        ]);
        $this->reset(['item_id', 'item_amount', 'showItemModal', 'item_name', 'saveItem']);
    }
    public function updatedMarkupammount($value)
    {
        $this->calculateTotals();
    }
    public function updatedUsdammount($value)
    {
        $this->calculateTotals();
    }
    public function handleItemAmount($index)
    {
        $this->calculateTotals($index);
    }
    public function ItemAdd()
    {
        $this->saveItem = true;
        $this->dispatch('focus-item-input');
    }
    public function ClientAdd()
    {
        $this->redirect(route($this->route . '.client-create'), navigate: true);
    }
    public function updatedCurrency($code)
    {
        // if ($this->tour_id) {
        $this->calculateTotals();
        // }
    }
    public function handleUsdBlur()
    {
        $this->calculateTotals();
    }
    public function updatedStartDate($code)
    {
        if($this->tour_id){
            $tour = Tours::with('tourJsons')->find($this->tour_id);
            if ($code && $tour->day) {
                $this->tourDays = Carbon::parse($code)
                    ->addDays(((int) $tour->day) - 1)
                    ->format('Y-m-d');

                    $this->end_date = $this->tourDays;
                $this->dispatch('tour-days-updated');
            }
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
        $this->estimate_no = SettingHelper::getEstimateNumber($id);

        $this->estimateSettings = QuotationSettings::where('company_id', $id)->first();
        $this->terms_and_condition = $this?->estimateSettings->terms_condition ?? '';
        $this->notes = $this->estimateSettings?->customer_note ?? '';
    }
    private function getEmptyDay(int $dayNumber): array
{
    return [
        "particular" => "Day " . str_pad($dayNumber, 2, '0', STR_PAD_LEFT),
        "activitiesCovered" => "",
        "roomPerNight" => 0,
        "numberOfRooms" => 0,
        "vehicleCost" => 0,
        "safariCost" => 0,
        "safariNumber" => 0,
        "monumentFee" => 0,
        "entryNumbers" => 0,
        "totalForTheDay" => 0,
        "hotelTotal" => 0,
        "hotelAdvance" => 0,
        "hotelBalance" => 0,
    ];
}
    public function addDay()
    {
        if (empty($this->tableDataJson['tourPackage']['days'])) {
            return;
        }
        $days = &$this->tableDataJson['tourPackage']['days'];
        $nextDayNumber = count($days) + 1;
        $days[] = $this->getEmptyDay($nextDayNumber);
        $this->recalculateAfterDayChange();
    }
    public function removeDay($index)
    {
        if (!isset($this->tableDataJson['tourPackage']['days'][$index])) {
            return;
        }
        unset($this->tableDataJson['tourPackage']['days'][$index]);
        $this->tableDataJson['tourPackage']['days'] = array_values(
            $this->tableDataJson['tourPackage']['days']
        );
        $this->recalculateAfterDayChange();
    }
    private function recalculateAfterDayChange()
    {
        foreach ($this->tableDataJson['tourPackage']['days'] as $i => $day) {
            $this->recalculateDay($i);
        }
        $this->calculateTotals();
    }
}
