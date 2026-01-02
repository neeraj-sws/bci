<div class="container">
    <div>

        <div class="toolbar hidden-print">
            <div class="text-end">

                <a href="{{ route('invoice.pdf', ['id' => $invoice->uuid]) }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf-o"></i> Export as PDF
                </a>

                <a href="{{ route('invoice.view', ['id' => $invoice->uuid]) }}" class="btn btn-primary">
                    <i class="fa fa-print"></i> Print
                </a>

                <a wire:click='openModel()' class="btn btn-primary"><i class="fa fa-print"></i>
                    Send
                    <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="openModel"></i>
                </a>
            </div>
            <hr>
        </div>

        <div id="pdf" class="page card">
            <div class="card-body">

                @php
                    $status = App\Helpers\SettingHelper::getInvoiceStatus($invoice->status);
                @endphp

                @if ($status)
                    <div class="ribbon-wrapper">
                        <div class="ribbon">{{ $status }}</div>
                    </div>
                @endif
                
                              <img src="{{ asset('assets/images/paid.png') }}" class="logo-icon" alt="logo icon"
                    style="    position: absolute;
                        width: 170px;
                        display: block;
                        margin: 0 auto;
                        opacity: 0.5;
                        left: 40%;
                        top: 12%;" />

                <div class="brand-row align-items-center">
                    <div class="logo-wrap mt-4">
                        @php
                            $organization = \App\Models\Companies::where('company_id', $invoice['company_id'])->first();
                            $logo = optional($organization->logo)->file;
                        @endphp
                        @if ($logo)
                            <img src="{{ asset("uploads/companies/{$organization->id}/" . $logo) }}"
                                alt="{{ $organization->company_name ?? 'Logo' }}" class="logo-img" />
                        @endif
                    </div>

                    <div class="quote-box">
                        <div class="quote-id fs-5">
                            {{ ucfirst($invoiceSettings->invoice_title ?? 'Invoice') }}#
                        </div>
                        <div class="quote-id">{{ $invoice['invoice_no'] ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>

                <div class="brand-row" style="margin-bottom:25px">
                    <div class="company-info">
                        <strong>{{ $organization->company_address ?? '' }}</strong><br>
                        @if($organization->city_name)
                            <strong>
                                {{ $organization->city_name->name ?? '' }}
                                @if($organization->state_name)
                                    , {{ $organization->state_name->name }}
                                @endif
                                 @if($organization->zip_code)
                                    {{ $organization->zip_code }}
                                @endif
                                @if($organization->country_name)
                                    , {{ $organization->country_name->name }}
                                @endif
                            </strong><br>
                        @endif
                        @if (!empty($organization->company_email))
                            {{ $organization->company_email }}<br>
                        @endif
                        Ph: +91-{{ $organization->company_contact ?? '' }}<br>
                        @if (!empty($organization->company_tax_id))
                            <div class="gstin">{{ $organization->company_tax_id }}</div>
                        @endif
                    </div>

                    <div class="quote-box">
                        <div class="quote-id">Date:
                            <span>{{ \Carbon\Carbon::parse($invoice['invoice_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $invoice['company_id']) ?? 'd M Y') }}</span>
                        </div>
                       {{-- <div class="quote-id">Valid Until:
                            <span>{{ \Carbon\Carbon::parse($invoice['expiry_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $invoice['company_id']) ?? 'd M Y') }}</span>
                        </div> --}}
                    </div>
                </div>

                <!-- CUSTOMER -->
                <div style="margin-bottom:30px">
                    <div class="section-title">Customer Details</div>
                    @php
                        $client = $invoice['tourist'] ?? null;
                        $tour = $invoice['tour'] ?? null;
                    @endphp
                    <div class="company-info customer-name">{{ $client['primary_contact'] ?? 'N/A' }}</div>
                  
                    @if (!empty($client['contact_phone']))
                        <div class="company-info">{{ $client['contact_phone'] }}</div>
                    @endif
                    
                               @if (!empty($client['address']))
                <div class="company-info">{{ $client['address'] }}</div>
            @endif
             @if (!empty($client['country']))
                <div class="company-info">{{ $client['country']['name'] }}</div>
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
                        $tourItem = collect($invoice['quotation']['items'])->firstWhere('is_tour', 1);
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
                                {{ \App\Helpers\SettingHelper::getColoumName('items', $invoice['company_id']) ?? 'Item' }}
                            </th>
                             @if($organization->sac_code)
                            <th>SAC code</th>
                            @endif
                            <th class="text-capitalize col-total text-end">
                                {{ \App\Helpers\SettingHelper::getColoumName('amount', $invoice['company_id']) ?? 'Amount' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itemCount = count($invoice['quotation']['items']);
                        @endphp

                        @forelse($invoice['quotation']['items'] as $index => $item)
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
                                @if($organization->sac_code)
                                <td>{{ $organization?->sac_code }}</td>
                                @endif
                                <td class="col-total text-end">
                                    <strong>
                                        {{ \App\Helpers\SettingHelper::formatCurrency($item['amount'], \App\Helpers\SettingHelper::getGenrealSettings('number_format', $invoice['company_id'])) }}
                                        &nbsp;{{ $invoice['currency_label'] }}
                                    </strong>
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
                    @if ($invoice['sub_amount'] && $invoice['sub_amount'] != $invoice['amount'])
                        <div class="row total-payable">
                            <strong class="col-6">Sub Total:</strong>
                            <span class="amount col-6 text-end">
                                {{ \App\Helpers\SettingHelper::formatCurrency($invoice['sub_amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $invoice['company_id'])) }}&nbsp;
                                {{ $item['currency_label'] }}
                            </span>
                        </div>
                    @endif

                    @if ($invoice['discount_amount'] && $invoice['discount_amount'] > 0)
                        <div class="row total-payable">
                            <strong class="col-6">Total Discount:</strong>
                            <span class="amount col-6 text-end">
                                - {{ \App\Helpers\SettingHelper::formatCurrency($invoice['discount_amount'] ?? 0) }}
                                {{ $item['currency_label'] }}
                            </span>
                        </div>
                    @endif

                    <div class="row total-payable">
                        <strong class="col-6">Total Payable:</strong>
                        <span class="amount col-6 text-end">
                            {{ \App\Helpers\SettingHelper::formatCurrency($invoice['amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $invoice['company_id'])) }}&nbsp;
                            {{ $item['currency_label'] }}
                        </span>
                    </div>
                </div>

                {{-- <!-- TERMS -->
                @if (!empty($invoice['quotation']['terms_and_condition']))
                    <div class="terms">
                        <div class="label">Terms & Conditions</div>
                        <ul>
                            @foreach (explode("\n", $invoice['quotation']['terms_and_condition']) as $term)
                                @if (trim($term) !== '')
                                    <li>{{ $term }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- NOTES -->
                @if (!empty($invoice['quotation']['notes']))
                    <div class="terms">
                        <div class="label">Notes</div>
                        <ul>
                            @foreach (explode("\n", $invoice['quotation']['notes']) as $note)
                                @if (trim($note) !== '')
                                    <li>{{ $note }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif --}}


<div style="
    position: absolute;
    bottom: 30px;
    left: 40px;
    right: 40px;
">
    <div class="tiny-divider"></div>

    <div  class="footer-note">
        Invoice was created digitally and is valid without signature.
    </div>
</div>


            </div>
        </div>
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
                min-height: 1400px
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

    </div>
</div>
