<div class="container">
    <div>
        <div class="toolbar hidden-print">
            <div class="text-end">
                @if (in_array($estimate?->status, [0]))
                    <a href="{{ route($route . '.edit-quotation', $estimate->id) }}" class="btn btn-primary"><i
                            class="fa fa-print"></i>
                        Edit</a>
                @endif
                <a href="{{ route('estimate.pdf', ['id' => $estimate->uuid]) }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf-o"></i> Export as PDF
                </a>
                <a href="{{ route('estimate.view', ['id' => $estimate->uuid]) }}" class="btn btn-primary">
                    <i class="fa fa-print"></i> Print
                </a>
                @if (!in_array($estimate?->status, [4, 3, 5, 6]))
                    <a wire:click='openModel()' class="btn btn-primary"><i class="fa fa-print"></i>
                        Send</a>
                    <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="openModel"></i>
                @endif
                @php
                    if ($estimate->lastprinvoice) {
                        $prInvoicePaid =
                            $estimate->lastprinvoice &&
                            $estimate->lastprinvoice->status === 2 &&
                            $estimate->total_remaning_amount;
                    } else {
                        $prInvoicePaid =
                            !$estimate->lastprinvoice && ($estimate->status == 2 || $estimate->status == 6);
                    }
                @endphp
                @if (!in_array($estimate?->status, [3, 5, 4, 6, 2, 7]))
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" fdprocessedid="ykfs4">More</button>
                        <button type="button"
                            class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" fdprocessedid="qtrdwa">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                            @if ($estimate?->status === 0)
                                <a wire:click='updateEstimate(1)' class="dropdown-item" href="javascript:;">Mark as
                                    Send</a>
                            @endif

                            @if (in_array($estimate?->status, [1, 0]))
                                <a wire:click='confirmDelete(2)' class="dropdown-item" href="javascript:;">Mark as
                                    Accepted</a>
                            @endif

                            @if (in_array($estimate?->status, [1, 0, 4]))
                                <a wire:click='updateEstimate(3)' class="dropdown-item" href="javascript:;">Mark as
                                    Discard</a>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($prInvoicePaid)
                    {{-- <a wire:click='convertProformInvoice()' class="btn btn-success px-5" fdprocessedid="v2qwlg">Convert
                        to Proforma
                    </a> --}}
                    <a wire:click='recordPayment()' class="btn btn-success px-5" fdprocessedid="v2qwlg">Convert
                        to Proforma
                        <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="recordPayment"></i>
                    </a>
                @endif

                @if (!$estimate->total_remaning_amount && $estimate->status != 7)
                    <a wire:click='convertInvoice()' class="btn btn-primary px-5" fdprocessedid="v2qwlg">Convert
                        to Invoice
                    </a>
                @endif
            </div>
            <hr>
        </div>
        @if ($estimate->lastprinvoice && $estimate->lastprinvoice->status === 2 && $estimate->total_remaning_amount)
            <div class="mt-4 alert alert-warning d-flex justify-content-between align-items-center mb-3 rounded-3 shadow-sm"
                style="background-color: #fff8e1; border-left: 5px solid #ff9800; padding: 15px 20px;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-exclamation text-warning me-2"></i>
                    <p class="mb-0" style="color: #333; font-size: 15px;">
                        <strong> <a
                                href="{{ route($route . '.view-proformainvoice', $estimate?->lastprinvoice?->uuid) }}"
                                class="fw-semibold text-decoration-underline text-dark ms-1">
                                {{ $estimate->lastprinvoice->proforma_invoice_no }}
                            </a> :</strong> The last proforma invoice has been paid. Please record the
                        remaining amount to unlock the final invoice and ensure transparency with the tourist.
                    </p>

                </div>
            </div>
        @endif
        @if ($totalExpense > 0)
            <div class="contatcDeopdown align-items-center mb-3 justify-content-between">
                <div
                    style="border: 1px solid rgb(252, 217, 161); background-color: rgb(255, 247, 230); padding: 5px 15px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: rgb(51, 51, 51); justify-content: center; width: 100%;">
                    <span style="color: rgb(220, 53, 69); font-size: 18px;">
                        <i class="lni lni-warning"></i>
                    </span>
                    <span>
                        This quotation has
                        Total Expense:
                        <strong>
                            {{ \App\Helpers\SettingHelper::formatCurrency($totalExpense ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $estimate['company_id'])) }}
                            {{ $estimate['currency_label'] }}</strong>.
                        Kindly
                        <span wire:click='openModel()'
                            style="color: rgb(59, 130, 246); text-decoration: none; cursor: pointer;">
                            Send Quotation
                            <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="openModel"></i>
                        </span>
                        manually this time.
                    </span>
                </div>
            </div>
        @endif
        <!-- Full Page Loader -->
        <div wire:loading wire:target="updateEstimate" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading Estimate...</p>
        </div>
        <div wire:loading.remove wire:target="updateEstimate" id="pdf" class="page card">
            <div class="card-body">

                @php
                    $status = App\Helpers\SettingHelper::getStatus($estimate->status);
                @endphp

                @if ($status)
                    <div class="ribbon-wrapper">
                        <div class="ribbon">{{ $status }}</div>
                    </div>
                @endif

                <div class="brand-row align-items-center">
                    <div class="logo-wrap mt-4">
                        @php
                            $organization = \App\Models\Companies::where(
                                'company_id',
                                $estimate['company_id'],
                            )->first();
                            $logo = optional($organization->logo)->file;
                        @endphp
                        @if ($logo)
                            <img src="{{ asset("uploads/companies/{$organization->id}/" . $logo) }}"
                                alt="{{ $organization->company_name ?? 'Logo' }}" class="logo-img" />
                        @endif
                    </div>

                    <div class="quote-box">
                        <div class="quote-id fs-5"> {{ ucfirst($estimateSettings->quotation_title ?? 'Quotation') }}#
                        </div>
                        <div class="quote-id">{{ $estimate['quotation_no'] ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>

                <div class="brand-row" style="margin-bottom:25px">
                    <div class="company-info">
                        {{ $organization->company_address ?? '' }}<br>
                         @if($organization->city_name)
                                {{ $organization->city_name->name ?? '' }}
                                @if($organization->state_name)
                                    , {{ $organization->state_name->name }}
                                @endif
                                 @if($organization->zip_code)
                                    {{ $organization->zip_code }}
                                @endif
                                @if($organization->country_name)
                                    , {{ $organization->country_name->name }}
                                @endif<br>
                        @endif
                        @if (!empty($organization->company_email))
                            {{ $organization->company_email }}<br>
                        @endif
                        Ph: +91-{{ $organization->company_contact ?? '' }}<br>
                        @if (!empty($organization->company_tax_id))
                            {{ $organization->company_tax_id }}
                        @endif
                    </div>

                    <div class="quote-box">
                        <div class="quote-id">Quote Date:
                            <span>{{ \Carbon\Carbon::parse($estimate['quotation_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $estimate['company_id']) ?? 'd M Y') }}</span>
                        </div>
                        <div class="quote-id">Valid Until:
                            <span>{{ \Carbon\Carbon::parse($estimate['expiry_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $estimate['company_id']) ?? 'd M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- CUSTOMER -->
                <div style="margin-bottom:30px">
                    <div class="section-title">Customer Details</div>
                    @php
                        $client = $estimate['tourist'] ?? null;
                        $tour = $estimate['tour'] ?? null;
                    @endphp
                    <div class="company-info customer-name">{{ $client['primary_contact'] ?? 'N/A' }}</div>



                               @if (!empty($client['address']))
                <div class="company-info">{{ $client['address'] }}</div>
            @endif
             @if (!empty($client['country']))
                <div class="company-info">{{ $client['country']['name'] }}</div>
            @endif
                    @php
                    $phone_number =  App\Helpers\SettingHelper::format_phone($client['contact_phone'])
                    @endphp
                    @if (!empty($client['contact_phone']))
                        <div class="company-info">@if(!empty($client['country']) ) +{{ $client['country']['phonecode'] }}-@endif{{ $phone_number }}</div>
                    @endif

                   {{-- @if ($tour)
                        <div class="tour-name" style="margin-top:14px">
                            <span class="quote-id">Tour Name:</span> <span>{{ $tour['name'] ?? '-' }}</span>
                        </div>
                        <div class="tour-name" style="margin-top:8px">
                            <span class="quote-id">Tour Details:</span> <span>{{ $tour['description'] ?? '-' }}</span>
                        </div>
                    @endif --}}

                    @php
                        $tourItem = collect($estimate['items'])->firstWhere('is_tour', 1);
                    @endphp

                    @if ($tourItem)
                        <div class="tour-name" style="margin-top:14px">
                            <span class="quote-id">Tour Name:</span>
                            <span>{{ $tourItem['item_name'] ?? '-' }}</span>
                        </div>

                        <div class="tour-name" style="margin-top:8px">
                            <span class="quote-id">Tour Details:</span>
                            <span>{{ $tourItem['description'] ?? '-' }}</span>
                        </div>
                    @endif

                </div>

                <!-- TABLE -->
                <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="col-no">#</th>
                            <th class="text-capitalize w-50">
                                {{ \App\Helpers\SettingHelper::getColoumName('items', $estimate['company_id']) ?? 'Item' }}
                            </th>
                            <th class="text-capitalize col-total text-end">
                                {{ \App\Helpers\SettingHelper::getColoumName('amount', $estimate['company_id']) ?? 'Amount' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estimate['items'] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td>
                                    @if (!empty($item['item_name']))
                                        {{ $item['item_name'] }}<br>
                                    @endif
                                    @if (!empty($item['description'] && !$item['is_tour']))
                                        <small class="company-info">{!! nl2br(e($item['description'])) !!}</small>
                                    @endif
                                    @if (!empty($item['is_custome']))
                                        <div><strong>Custom:</strong> {{ $item['is_custome'] }}</div>
                                    @endif
                                </td>
                                <td class="col-total">
                                    {{ \App\Helpers\SettingHelper::formatCurrency($item['amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $estimate['company_id'])) }}&nbsp;
                                    {{ $item['currency_label'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No items found in this estimate.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>

                <!-- TOTAL -->
                <div class="totals">
                    @if ($estimate['sub_amount'] && $estimate['sub_amount'] != $estimate['amount'])
                        <div class="row total-payable">
                            <strong class="col-6">Sub Total:</strong>
                            <span class="amount col-6 text-end">
                                {{ \App\Helpers\SettingHelper::formatCurrency($estimate['sub_amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $estimate['company_id'])) }}&nbsp;
                                {{ $item['currency_label'] }}
                            </span>
                        </div>
                    @endif

                    @if ($estimate['discount_amount'] && $estimate['discount_amount'] > 0)
                        <div class="row total-payable">
                            <strong class="col-6">Total Discount:</strong>
                            <span class="amount col-6 text-end">
                                - {{ \App\Helpers\SettingHelper::formatCurrency($estimate['discount_amount'] ?? 0) }}
                                {{ $item['currency_label'] }}
                            </span>
                        </div>
                    @endif

                    <div class="row total-payable">
                        <strong class="col-6">Total Payable:</strong>
                        <span class="amount col-6 text-end">
                            {{ \App\Helpers\SettingHelper::formatCurrency($estimate['amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $estimate['company_id'])) }}&nbsp;
                            {{ $item['currency_label'] }}
                        </span>
                    </div>
                </div>

                       <!-- NOTES -->
                @if (!empty($estimate['notes']))
                    <div class="terms">
                        <div class="label">Booking Procedure</div>
                        <ul class="list-unstyled">
                            @foreach (explode("\n", $estimate['notes']) as $note)
                                @if (trim($note) !== '')
                                    <li>{{ $note }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- TERMS -->
                @if (!empty($estimate['terms_and_condition']))
                    <div class="terms">
                        <div class="label">Terms & Conditions</div>
                        <ul class="list-unstyled">
                            @foreach (explode("\n", $estimate['terms_and_condition']) as $term)
                                @if (trim($term) !== '')
                                    <li>{{ $term }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif




                <div class="tiny-divider"></div>

                <div class="footer-note">
                    Quotation was created digitally and is valid without signature.
                </div>

            </div>
        </div>
        @if ($estimate->status === 6 || $estimate->status === 7)
            {{-- <button wire:click='openRecipiets' type="button" class="btn btn-success px-5 mt-3"
                fdprocessedid="plg4um"><i class="bx bx-refresh me-0 mr-1"></i>
                {{ $showRecipiets ? 'Close' : 'View Recipiets' }}
                <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="openRecipiets"></i>
            </button> --}}
            @if ($prInvoices)
                <div class="container mt-5">
                    <h4>Proforma Invoice</h4>
                    <table class="table table-bordered mt-4">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Date & Time</th>
                                <th scope="col">PRINV NO#</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prInvoices as $prInvoice)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($prInvoice->updated_at)->format('F d, Y h:iA') }}
                                    </td>
                                    <td>
                                        <a href="{{ route($route . '.view-proformainvoice', $prInvoice?->uuid) }}">
                                            {{ $prInvoice?->proforma_invoice_no }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            switch ($prInvoice?->status) {
                                                case 0:
                                                    $status = 'text-dark bg-light-warning';
                                                    break;
                                                case 1:
                                                    $status = 'text-warning bg-light-warning';
                                                    break;
                                                case 2:
                                                    $status = 'text-dark bg-light-success';
                                                    break;
                                                default:
                                                    $status = 'text-muted bg-light';
                                            }

                                            $statusName =
                                                App\Helpers\SettingHelper::getProFormaInvoiceStatus(
                                                    $prInvoice?->status,
                                                ) ?? 'NA';
                                        @endphp
                                        <div class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
                                            <i class="bx bxs-circle me-1"></i>{{ $statusName }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No History found in this
                                        estimate.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
        <div class="container mt-5">
            <h4>History</h4>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Action</th>
                        <th scope="col">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historys as $history)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($history->updated_at)->format('F d, Y h:iA') }}
                            </td>
                            <td>{{ $history?->msg?->message_type ?? 'NA' }}</td>
                            <td>{{ $history?->user?->name ?? 'Admin' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No History found in this
                                estimate.</td>
                        </tr>
                    @endforelse


                </tbody>
            </table>
        </div>
        <style>
            .page {
                margin: 0 auto;
                background: #fff;
                padding: 20px 40px 20px 40px;
                /* top right bottom left */
                box-shadow: 0 10px 0 rgba(0, 0, 0, 0.65);
                font-size: 17px;
                color: #111;
                overflow: hidden;
            }


            /* HEADER */
            .brand-row {
                display: flex;
                justify-content: space-between;
            }

            .logo-wrap {
                width: 200px;
                height: 80px;
                /* example fixed height */
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                /* hides any overflow outside container */
                background-color: transparent;
                /* optional */
            }

            .logo-img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                /* keeps aspect ratio */
            }


            .company-info {
                font-size: 16px;
                line-height: 1.6;
                color: #333;
                font-weight: 500;
                width: 30%
            }

            .company-info strong {
                color: #111;
                font-weight: 600;
            }

            .gstin {
                font-weight: 600;
                letter-spacing: 1px;
            }

            .quote-box {
                text-align: right;
                font-size: 16px;
                font-weight: 500;
            }

            .quote-id {
                font-weight: 700;
                margin-top: 6px;
            }

            .thin-line {
                height: 2px;
                background: #222;
                margin: 20px 0;
            }

            /* CUSTOMER / TOUR */
            .section-title {
                font-weight: 700;
                margin-bottom: 10px;
                font-size: 18px;
                color: #111;
            }

            .customer-name {
                font-size: 17px;
                font-weight: 600;
            }

            .tour-name {
                font-weight: 600;
                font-size: 16px;
            }

            /* TABLE */
            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 22px;
                /*border-bottom: 2px solid #222;*/
            }

            .items-table thead th {
                /*border-top: 2px solid #222;*/
                padding-bottom: 14px;
                text-align: left;
                font-size: 18px;
                font-weight: 700;
            }

            .items-table th,
            .items-table td {
                padding: 14px 10px;
                vertical-align: top;
                font-size: 16px;
            }

            .col-no {
                width: 40px;
            }

            .col-price,
            .col-total {
                text-align: right;
                font-weight: 500;
            }

            /* TOTALS */
            .totals {
                width: 420px;
                margin-left: auto;
                font-size: 16px;
                margin-top: 20px;
            }

            .totals .row {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                font-weight: 500;
            }

            .totals .amount {
                font-weight: 600;
            }

            .total-payable {
                font-size: 18px;
            }

            /* TERMS */
            .terms {
                font-size: 16px;
                color: #555;
                line-height: 1.8;
                margin-top: 22px;
            }

            .terms .label {
                color: #111;
                font-weight: 700;
                margin-bottom: 8px;
                font-size: 18px;
            }

            /* FOOTER */
            .tiny-divider {
                height: 1px;
                background: #ddd;
                margin: 28px 0;
            }

            .footer-note {
                font-size: 16px;
                color: #888;
                text-align: center;
            }
        </style>
        {{--  --}}
        @if ($showTourModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tour- {{ $tour?->name ?? 'NA' }}</h5>
                        </div>
                        <div class="modal-body text-start">
                            @if ($tour)

                                <div class="d-flex align-items-center mb-3">
                                    <input type="checkbox" id="is_attachment" wire:model.live="is_attachment"
                                        class="form-checkbox text-red-600 @error('is_attachment') is-invalid @enderror">
                                    <label for="is_attachment" class="ms-2 mb-0">
                                        Do you want to attach details?
                                    </label></br>
                                    @error('is_attachment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="@if ($is_attachment) d-block @else d-none @endif">
                                    <label for="attachment" class="form-label fw-bold">+ Attachment <span
                                            class="text-danger">*</span></label>

                                    <div class="input-group">
                                        <input type="file" class="form-control" id="attachment"
                                            wire:model="attachment" accept=".pdf">
                                        <label class="input-group-text" for="attachment">Choose File</label>
                                    </div>

                                    @if ($attachment)
                                        <div
                                            class="mt-2 alert alert-primary d-flex justify-content-between align-items-center p-2">
                                            <span class="text-truncate"
                                                style="max-width: 90%;">{{ $attachment->getClientOriginalName() }}</span>
                                            <button wire:click="$set('attachment', null)" class="btn-close"
                                                aria-label="Remove"></button>
                                        </div>
                                    @endif
                                    @if ($existingImage && !$attachment)
                                        <div
                                            class="mt-2 alert alert-secondary d-flex justify-content-between align-items-center p-2">
                                            <a href="{{ asset($existingImage) }}" target="_blank"
                                                class="text-truncate text-decoration-none text-dark"
                                                style="max-width: 90%;">
                                                {{ basename($existingImage) }}
                                            </a>

                                            <div class="gap-3 d-flex align-items-center">





                                                <a href="{{ asset($existingImage) }}" target="_blank"
                                                    class="text-decoration-none text-primary" title="View File">
                                                    <i class="lni lni-eye fs-5"></i>
                                                </a>
                                                <a href="{{ asset($existingImage) }}" download
                                                    class="text-decoration-none text-primary" title="Download File">
                                                    <i class="lni lni-cloud-download fs-5"></i>
                                                </a>
                                            </div>


                                        </div>
                                    @endif
                                    <div wire:loading wire:target="attachment" class="mt-2 text-primary">
                                        Uploading file, please wait...
                                    </div>
                                    @if (!$attachment && !$existingImage)
                                        <p class="mt-2 text-muted">No file uploaded yet.</p>
                                    @endif
                                </div>

                                @error('attachment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            @endif


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary greygradientbtn"
                                wire:click="closeModel()">Cancel
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="closeModel"></i>
                            </button>
                            <button type="button" class="btn bluegradientbtn" wire:click="sendAttachment()">Send
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="sendAttachment"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($showModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Proforma </h5>
                        </div>
                        <div class="modal-body text-start">

                            <div>
                                <label for="toatl_amount" class="form-label">Total Quotation Cost</label>
                                <input type="number" id="toatl_amount" class="form-control"
                                    value="{{ $toatl_amount }}" disabled>

                            </div>

                            <div class="mt-3">
                                <label for="toatl_amount" class="form-label">Total Paid Amount</label>
                                <input type="number" id="toatl_amount" class="form-control"
                                    value="{{ $total_paid_amount }}" disabled>
                            </div>

                            <div class="mt-3">
                                <label for="toatl_amount" class="form-label">Remaining Amount</label>
                                <input type="number" id="toatl_amount" class="form-control"
                                    value="{{ $total_remaning_amount }}" disabled>
                            </div>

                            <div class="mt-3">
                                <label for="toatl_amount" class="form-label">Proforma Amount</label>
                                <input type="number" id="toatl_amount" class="form-control" min="0"
                                    max="{{ $toatl_amount }}" wire:model.blur="pay_amount"
                                    placeholder="Enter amount (max {{ $toatl_amount }})"
                                    oninput="if (this.value > {{ $toatl_amount }}) this.value = {{ $toatl_amount }};
                if (this.value == '') this.value = 0;">

                                @error('pay_amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mt-3"
                                style="padding: 15px 20px; background-color: #f0fdf4; border-top: 1px solid #e1efe6;">
                                <p style="margin: 0; font-size: 15px; color: #2d6a4f;">💡An invoice will be issued to
                                    the
                                    customer once the payment has been submitted.</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary greygradientbtn"
                                wire:click="$set('showModal', false)">Cancel
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="showModal"></i>

                            </button>
                            <button type="button" class="btn bluegradientbtn" wire:click="recordPaymentStore"
                                wire:loading.attr="disabled" wire:target="recordPaymentStore,updatedPayAmount">Create
                                Proforma Invoice
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="recordPaymentStore"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
