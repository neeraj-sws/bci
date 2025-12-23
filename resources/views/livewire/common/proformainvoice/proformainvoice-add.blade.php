<div class="container">
    <div class="toolbar hidden-print">
        <div class="text-end d-flex gap-3 align-items-center">
            <a wire:click='recordPayment()' class="btn btn-success px-5" fdprocessedid="v2qwlg">Record Payment
                <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="recordPayment"></i>
            </a>
            <form wire:submit.prevent="{{ 'addPrInvoice' }}">
                <button type="submit" class="btn bluegradientbtn my-3">
                    {{ 'Save Proforma Invoice' }}
                    <span style="cursor: pointer" wire:loading wire:target="addPrInvoice">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </form>

        </div>
        <hr>
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
            align-items: center;
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
    <div id="pdf" class="page card">
        <div class="card-body">
            @php
                $status = App\Helpers\SettingHelper::getProFormaInvoiceStatus(0);
            @endphp
            @if ($status)
                <div class="ribbon-wrapper">
                    <div class="ribbon">{{ $status }}</div>
                </div>
            @endif
            <div class="brand-row">
                <div class="logo-wrap mt-4">
                    @php
                        $organization = \App\Models\Companies::where('company_id', $quotation['company_id'])->first();
                        $logo = optional($organization->logo)->file;
                    @endphp
                    @if ($logo)
                        <img src="{{ asset("uploads/companies/{$organization->id}/" . $logo) }}"
                            alt="{{ $organization->company_name ?? 'Logo' }}" class="logo-img" />
                    @endif
                </div>

                <div class="quote-box">
                    <div class="quote-id fs-5"> {{ ucfirst($prinvoiceSettings->pr_invoice_title ?? 'Prinvoice') }}
                    </div>
                    <div class="quote-id">{{ $pr_no ?? 'N/A' }}</div>
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
                        <span>{{ \Carbon\Carbon::parse($prinvoice_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $quotation['company_id']) ?? 'd M Y') }}</span>
                    </div>
                    <div class="quote-id">Valid Until:
                        <span>{{ \Carbon\Carbon::parse($expiry_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $quotation['company_id']) ?? 'd M Y') }}</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:30px">
                <div class="section-title">Customer Details</div>
                @php
                    $client = $quotation['tourist'] ?? null;
                    $tour = $quotation['tour'] ?? null;
                @endphp
                <div class="company-info customer-name">{{ $client['primary_contact'] ?? 'N/A' }}</div>
                @if (!empty($client['address']))
                    <div class="company-info">{{ $client['address'] }}</div>
                @endif
                @if (!empty($client['contact_phone']))
                    <div class="company-info">{{ $client['contact_phone'] }}</div>
                @endif

                @if ($tour)
                    <div class="tour-name" style="margin-top:14px">
                        <span class="quote-id">Tour Name:</span> <span>{{ $tour['name'] ?? '-' }}</span>
                    </div>
                    <div class="tour-name" style="margin-top:8px">
                        <span class="quote-id">Tour Details:</span> <span>{{ $tour['description'] ?? '-' }}</span>
                    </div>
                @endif
            </div>

            <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th class="text-capitalize w-50">
                            {{ \App\Helpers\SettingHelper::getColoumName('items', $quotation['company_id']) ?? 'Item' }}
                        </th>
                        <th class="text-capitalize col-total text-end">
                            {{ \App\Helpers\SettingHelper::getColoumName('amount', $quotation['company_id']) ?? 'Amount' }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAmount = 0;
                        $itemCount = count($quotation['items']);
                    @endphp

                    @forelse($quotation['items'] as $index => $item)
                        @php
                            $totalAmount += $item['amount'] ?? 0;
                        @endphp
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

                            {{-- Show total amount only in the middle row --}}
                            <td class="col-total text-end">
                                @if ($index === intval($itemCount / 2))
                                    <strong>
                                        {{ \App\Helpers\SettingHelper::formatCurrency($amount, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $quotation['company_id'])) }}
                                        &nbsp;{{ $quotation['currency_label'] }}
                                    </strong>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No items found in this estimate.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="thin-line" style="width: 113.5%;margin-left: -5%;"></div>

            <div class="totals">
                @if ($sub_amount && $discount_amount)
                    <div class="row total-payable">
                        <strong class="col-6">Sub Total:</strong>
                        <span class="amount col-6 text-end">
                            {{ \App\Helpers\SettingHelper::formatCurrency($sub_amount ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $quotation['company_id'])) }}&nbsp;
                            {{ $item['currency_label'] }}
                        </span>
                    </div>
                @endif

                @if ($sub_amount && $discount_amount > 0)
                    <div class="row total-payable">
                        <strong class="col-6">Total Discount:</strong>
                        <span class="amount col-6 text-end">
                            - {{ \App\Helpers\SettingHelper::formatCurrency($discount_amount ?? 0) }}
                            {{ $item['currency_label'] }}
                        </span>
                    </div>
                    <hr>
                @endif
                <div class="row total-payable">
                    <strong class="col-6">Total Payable:</strong>
                    <span class="amount col-6 text-end">
                        {{ \App\Helpers\SettingHelper::formatCurrency($amount ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format', $quotation['company_id'])) }}&nbsp;
                        {{ $item['currency_label'] }}
                    </span>
                </div>
            </div>

            @if (!empty($quotation['terms_and_condition']))
                <div class="terms">
                    <div class="label">Terms & Conditions</div>
                    <ul>
                        @foreach (explode("\n", $quotation['terms_and_condition']) as $term)
                            @if (trim($term) !== '')
                                <li>{{ $term }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (!empty($quotation['notes']))
                <div class="terms">
                    <div class="label">Notes</div>
                    <ul>
                        @foreach (explode("\n", $quotation['notes']) as $note)
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
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Record Payment</h5>
                    </div>
                    <div class="modal-body text-start">

                        <div>
                            <label for="toatl_amount" class="form-label">Total Amount</label>
                            <input type="number" id="toatl_amount" class="form-control" value="{{ $toatl_amount }}"
                                disabled>

                        </div>

                        <div class="mt-3">
                            <label for="toatl_amount" class="form-label">Total Paid Amount</label>
                            <input type="number" id="toatl_amount" class="form-control"
                                value="{{ $total_paid_amount }}" disabled>
                        </div>

                        <div class="mt-3">
                            <label for="toatl_amount" class="form-label">Total Remaining Amount</label>
                            <input type="number" id="toatl_amount" class="form-control"
                                value="{{ $total_remaning_amount }}" disabled>
                        </div>

                        <div class="mt-3">
                            <label for="toatl_amount" class="form-label">Pay Amount</label>
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
                            <p style="margin: 0; font-size: 15px; color: #2d6a4f;">💡An invoice will be issued to the
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
                            wire:loading.attr="disabled" wire:target="recordPaymentStore,updatedPayAmount">Record
                            Payment
                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="recordPaymentStore"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('scripts')
@endpush
