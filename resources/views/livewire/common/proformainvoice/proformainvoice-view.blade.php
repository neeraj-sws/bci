<div class="container">
    <div>

        <div class="toolbar hidden-print">
            <div class="text-end">

                <a href="{{ route('proformainvoice.pdf', ['id' => $prinvoice->uuid]) }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf-o"></i> Export as PDF
                </a>

                <a href="{{ route('proformainvoice.view', ['id' => $prinvoice->uuid]) }}" class="btn btn-primary">
                    <i class="fa fa-print"></i> Print
                </a>

                <a wire:click='openModel()' class="btn btn-primary"><i class="fa fa-print"></i>
                    Send
                    <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="openModel"></i>
                </a>

                @if ($prinvoice->status !== 2)
                    <a wire:click='confirmupdatePr()' class="btn btn-primary"><i class="fa fa-print"></i>
                        Record Payment
                        <i class="spinner-border spinner-border-sm" wire:loading.delay
                            wire:target="confirmupdatePr()"></i>
                    </a>
                @endif
                
                 @if($prinvoice->status == 0)
                  <a wire:click='markasPaid()' class="btn btn-warning"><i class="fa fa-print"></i>
                    Mark as Sent
                    <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="markasPaid"></i>
                </a>
                @endif
                
            </div>
            <hr>
        </div>


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
                    $status = App\Helpers\SettingHelper::getProFormaInvoiceStatus($prinvoice->status);
                @endphp

                @if ($status)
                    <div class="ribbon-wrapper">
                        <div class="ribbon">{{ $status }}</div>
                    </div>
                @endif
                
                  @if ($prinvoice->status == 2)
                      <img src="{{ asset('assets/images/paid.png') }}" class="logo-icon" alt="logo icon"
                    style="    position: absolute;
                        width: 170px;
                        display: block;
                        margin: 0 auto;
                        opacity: 0.5;
                        left: 40%;
                        top: 12%;" />
                      @endif

                <div class="brand-row align-items-center">
                    <div class="logo-wrap mt-4">
                        @php
                            $organization = \App\Models\Companies::where(
                                'company_id',
                                $prinvoice['company_id'],
                            )->first();
                            $logo = optional($organization->logo)->file;
                        @endphp
                        @if ($logo)
                            <img src="{{ asset("uploads/companies/{$organization->id}/" . $logo) }}"
                                alt="{{ $organization->company_name ?? 'Logo' }}" class="logo-img" />
                        @endif
                    </div>

                    <div class="quote-box">
                        <div class="quote-id fs-5">
                            {{ ucfirst($prinvoiceSettings->pr_invoice_title ?? 'PrformaInvoice') }}#
                        </div>
                        <div class="quote-id">{{ $prinvoice['proforma_invoice_no'] ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>

                <div class="brand-row" style="margin-bottom:25px">
                    <div class="company-info">
                        <strong>{{ $organization->company_address ?? '' }}</strong><br>
                        @if (!empty($organization->company_email))
                            {{ $organization->company_email }}<br>
                        @endif
                        Ph: {{ $organization->company_contact ?? '' }}<br>
                        @if (!empty($organization->company_tax_id))
                            <div class="gstin">{{ $organization->company_tax_id }}</div>
                        @endif
                    </div>

                    <div class="quote-box">
                        <div class="quote-id">Date:
                            <span>{{ \Carbon\Carbon::parse($prinvoice['proforma_invoice_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $prinvoice['company_id']) ?? 'd M Y') }}</span>
                        </div>
                        <div class="quote-id">Valid Until:
                            <span>{{ \Carbon\Carbon::parse($prinvoice['expiry_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $prinvoice['company_id']) ?? 'd M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- CUSTOMER -->
                <div style="margin-bottom:30px">
                    <div class="section-title">Customer Details</div>
                    @php
                        $client = $prinvoice['tourist'] ?? null;
                        $tour = $prinvoice['tour'] ?? null;
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
                        $tourItem = collect($prinvoice['quotation']['items'])->firstWhere('is_tour', 1);
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
                                {{ \App\Helpers\SettingHelper::getColoumName('items', $prinvoice['company_id']) ?? 'Item' }}
                            </th>
                            <th class="text-capitalize col-total text-end">
                                {{ \App\Helpers\SettingHelper::getColoumName('amount', $prinvoice['company_id']) ?? 'Amount' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $itemCount = count($prinvoice['quotation']['items']);
                        @endphp

                        @forelse($prinvoice['quotation']['items'] as $index => $item)
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
                                <td class="col-total text-end">
                                    @if ($index === intval($itemCount / 2))
                                        <strong>
                                            {{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['amount'], \App\Helpers\SettingHelper::getGenrealSettings('number_format', $prinvoice['company_id'])) }}
                                            &nbsp;{{ $prinvoice['currency_label'] }}
                                        </strong>
                                    @endif
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
                 @if ($prinvoice['sub_amount'] && $prinvoice['sub_amount'] != $prinvoice['amount'])
                        <div class="row total-payable">
                            <strong class="col-6">Sub Total:</strong>
                            <span class="amount col-6 text-end">
                                {{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['sub_amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $prinvoice['company_id'])) }}&nbsp;
                                {{ $item['currency_label'] }}
                            </span>
                        </div>
                    @endif

                    @if ($prinvoice['discount_amount'] && $prinvoice['discount_amount'] > 0)
                        <div class="row total-payable">
                            <strong class="col-6">Total Discount:</strong>
                            <span class="amount col-6 text-end">
                                - {{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['discount_amount'] ?? 0) }}
                                {{ $item['currency_label'] }}
                            </span>
                        </div>
                    @endif

                    <div class="row total-payable">
                        <strong class="col-6">Total Payable:</strong>
                        <span class="amount col-6 text-end">
                            {{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['amount'] ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $prinvoice['company_id'])) }}&nbsp;
                            {{ $item['currency_label'] }}
                        </span>
                    </div>
                </div>

                <!-- TERMS -->
                @if (!empty($prinvoice['quotation']['terms_and_condition']))
                    <div class="terms">
                        <div class="label">Terms & Conditions</div>
                        <ul>
                            @foreach (explode("\n", $prinvoice['quotation']['terms_and_condition']) as $term)
                                @if (trim($term) !== '')
                                    <li>{{ $term }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- NOTES -->
                @if (!empty($prinvoice['quotation']['notes']))
                    <div class="terms">
                        <div class="label">Notes</div>
                        <ul>
                            @foreach (explode("\n", $prinvoice['quotation']['notes']) as $note)
                                @if (trim($note) !== '')
                                    <li>{{ $note }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="tiny-divider"></div>

                <div class="footer-note">
                    Proforma Invoice was created digitally and is valid without signature.
                </div>

            </div>
        </div>
        @if (count($recordPaymentHistory) > 0)
            <div class="container mt-5">
                <h4>Payments</h4>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Payment Method </th>
                            <th scope="col">Notes</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recordPaymentHistory as $history)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($history->payment_date)->format('F d, Y h:iA') }}
                                </td>
                                <td>
                                    @php
                                        $paymentMethods = [
                                            1 => 'Bank payment',
                                            2 => 'Cash',
                                            3 => 'Check',
                                            4 => 'Credit card',
                                            5 => 'Paypal',
                                            6 => 'Authorize.Net',
                                            7 => '2Checkout',
                                            8 => 'Braintree',
                                            9 => 'Stripe',
                                            10 => 'Other',
                                        ];
                                        
                                        $paymentMethodName = $paymentMethods[$history->payment_method] ?? 'NA'; // Default to 'NA' if no match
                                    @endphp
                                    {{ $paymentMethodName }}
                                </td>
                                
                                    <td>{{ $history?->notes ?? 'NA' }}</td>

                                <td>{{ \App\Helpers\SettingHelper::formatCurrency($history?->paid_amount ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $prinvoice['company_id'])) }} {{ $item['currency_label'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No payments received..</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
        
        
                {{-- PAYMENT --}}
        <div id="canvasBackdrop" wire:ignore></div>
        <div id="rightCanvas" wire:ignore.self>
            <div class="canvas-header">
                Payment for {{ $prinvoice->proforma_invoice_no }}

            </div>
            <div class="canvas-body">
                <form wire:submit.prevent="recordPayment" style="
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
">

                    <div class="row mt-2">


      <div class="col-md-12 mb-3 d-flex gap-3">
                            <label class="form-label"> <span class="text-danger">*</span> Tourist</label>
                            <h5> {{ $client['primary_contact'] ?? 'N/A' }} </h5>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label"> <span class="text-danger">*</span> Payment Date</label>
                            <input type="text" class="form-control datepicker" wire:model="payment_date"
                               data-nostart="null" data-restrict-future="true" data-group="booking1">
                            @error('payment_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-12 mb-3">
                            <label class="form-label"><span class="text-danger">*</span> Amount</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">{{ $prinvoice->currency_label }}</span>

                                {{-- <input type="text" class="form-control" x-data="{ value: @entangle('paid_amount').live }"
                                    x-init="$watch('value', v => $el.value = Number(v || 0).toLocaleString())"
                                    x-on:input="
                                            let raw = $event.target.value.replace(/,/g, '');
                                            value = raw;
                                            $event.target.value = Number(raw || 0).toLocaleString();
                                        "> --}}
                                        
                                   <input type="text"
                                        class="form-control"
                                        x-data="{
                                            value: @entangle('paid_amount').live,
                                            format(v) {
                                                v = (v ?? '').toString().replace(/[^0-9.]/g, '');
                                    
                                                const parts = v.split('.');
                                                if (parts.length > 2) parts.splice(2);
                                    
                                                this.value = v;
                                    
                                                if (v.includes('.')) {
                                                    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '.' + (parts[1] ?? '');
                                                }
                                                return v.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                            }
                                        }"
                                        x-init="
                                            $watch('value', v => {
                                                $el.value = format(v);
                                            })
                                        "
                                        x-on:input="
                                            value = $el.value.replace(/,/g, '');
                                            $el.value = format(value);
                                        "
                                    />

                            </div>

                            @error('paid_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="paymentMethod" id="orgForm_inputCountry"
                                class="form-select form-select-lg " wire:model="payment_method">
                                <option value="1">Bank payment</option>
                                <option value="2">Cash</option>
                                <option value="3">Check</option>
                                <option value="4">Credit card</option>
                                <option value="5">Paypal</option>
                                <option value="6">Authorize.Net</option>
                                <option value="7">2Checkout</option>
                                <option value="8">Braintree</option>
                                <option value="9">Stripe</option>
                                <option value="10">Other</option>
                            </select>
                        </div>


                        <!-- Contact Email -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Reference #</label>
                            <input type="text" class="form-control" wire:model="reference"
                                placeholder="Reference #">
                            @error('reference')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" wire:model="notes"> </textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


             

                    </div>
                    
                               <div class="canvas-footer d-flex justify-content-end gap-2 mt-2">

                            <a id="closeCanvas" class="btn btn-secondary greygradientbtn">Cancel</a>

                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                Save Payment
                                <span wire:loading wire:target="recordPayment">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>

                </form>
            </div>
        </div>
        {{--  --}}



    </div>
</div>
