<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Proforma Invoice PDF</title>
    <style>
        @page {
            margin: 0;
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            src: url("{{ public_path('assets/fonts/Inter-Regular.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            src: url("{{ public_path('assets/fonts/InterMedium.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            src: url("{{ public_path('assets/fonts/InterBold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            src: url("{{ public_path('assets/fonts/InterSemiBold.ttf') }}") format('truetype');
        }

        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .page {
            width: 85%;
            /* Reduce width to allow more room for padding */
            margin: 25px auto;
            background: #fff;
            padding: 40px 50px;
            /* more space left/right */
            box-sizing: border-box;
        }


        /* Ribbon */
        .ribbon-wrapper {
            width: 120px;
            height: 120px;
            position: absolute;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 10;
        }

        .ribbon {
            position: absolute;
            display: block;
            width: 174px;
            padding: 10px 0;
            background: #f16302;
            color: white;
            text-align: center;
            font-weight: 700;
            transform: rotate(-45deg);
            top: 15px;
            left: -60px;
            font-size: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
        }

        /* Header */
        .logo-wrap {
            width: 180px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .company-info {
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            font-weight: 400;
            width: 80%;
            word-break: break-word;
        }

        .company-info strong {
            font-weight: 600;
            color: #111
        }

        .gstin {
            font-weight: 400;
            letter-spacing: 1px;
            color: #333;
        }

        .quote-box {
            text-align: right;
            font-size: 14px;
            font-weight: 500;
        }

        .quote-id {
            font-weight: 700;
            margin-top: 4px;
            font-size: 13px;
        }

        .thin-line {
            height: 1px;
            background: #222;
            margin: 15px 0;
            width: 120%;
            margin-left: -11%;
        }

        /* Customer Section */
        .section-title {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 16px;
            color: #111;
        }

        .customer-name {
            font-size: 14px;
            font-weight: 600;
            color: #222;
        }

        .tour-name {
            font-weight: 700;
            font-size: 13px;
            color: #111;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .items-table thead th {
            padding: 10px 5px;
            text-align: left;
            font-size: 14px;
            font-weight: 700;
            color: #000;
            text-transform: capitalize
        }

        .items-table th,
        .items-table td {
            padding: 10px 5px;
            vertical-align: top;
            font-size: 13px;
            font-weight: 500
        }

        .col-no {
            width: 30px;
        }

        .col-total {
            text-align: right;
            font-weight: 400;
        }

        .totals {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
            font-size: 14px;
            margin-top: 20px;
        }

        .totals .row {
            display: table;
            /* fallback for Dompdf flex issues */
            width: 100%;
            padding: 6px 0;
        }

        .totals .row .label,
        .totals .row .amount {
            display: table-cell;
            vertical-align: middle;
            /* center align vertically */
        }

        .totals .row .label {
            font-weight: 700;
            font-size: 14px;
        }

        .totals .row .amount {
            font-weight: 600;
            /* or 700 for total */
            font-size: 14px;
            text-align: right;
            /* push amount to right */
            padding-left: 10px;
            /* space between label and amount */
        }

        /* Optional: Total Payable bigger and bolder */
        .total-payable-final .label {
            font-size: 15px;
        }

        .total-payable-final .amount {
            font-size: 15px;
            font-weight: 700;
        }


        .terms {
            font-size: 13px;
            color: #555;
            line-height: 1.6;
            margin-top: 20px;
        }

        .terms ul {
            list-style-position: outside;
            margin: 0 0 0 25px;
            padding-left: 0;
        }

        .terms li {
            margin-bottom: 4px;
        }

        .terms .label {
            color: #111;
            font-weight: 700;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .tiny-divider {
            height: 0.5px;
            background: #ddd;
            margin: 25px 0 15px 0;
        }

        .footer-note {
            font-size: 13px;
            color: #777;
            text-align: center;
            font-weight: 400;
        }

        .rupee::before {
            content: "\20B9";
        }
    </style>
</head>

<body>
    @php
        $status = App\Helpers\SettingHelper::getProFormaInvoiceStatus($prinvoice->status);
        $organization = \App\Models\Companies::where('company_id', $prinvoice['company_id'])->first();
        $logo = optional($organization->logo)->file;
        $client = $prinvoice['tourist'] ?? null;
        $tour = $prinvoice['tour'] ?? null;

        $showStatus = $showStatus ?? true;
    @endphp

    @if ($showStatus && $status)
        <div class="ribbon-wrapper">
            <div class="ribbon">{{ $status }}</div>
        </div>
    @endif

                      @if ($prinvoice->status == 2)
                      <img src="{{ public_path('assets/images/paid.png') }}" class="logo-icon" alt="logo icon"
                    style="    position: absolute;
                        width: 170px;
                        display: block;
                        margin: 0 auto;
                        opacity: 0.5;
                        left: 40%;
                        top: 12%;" />
                      @endif

    <div id="pdf" class="page">

        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:50%; vertical-align:middle;">
                    <div class="logo-wrap" style="width:180px;">
                        @if ($logo)
                            <img src="{{ public_path("uploads/companies/{$organization->id}/" . $logo) }}"
                                alt="{{ $organization->name ?? 'Logo' }}" class="logo-img" />
                        @endif
                    </div>
                </td>
                <td style="width:50%; text-align:right; vertical-align:middle;">
                    <div class="quote-box">
                        <div class="quote-id">
                            {{ ucfirst($prinvoiceSettings->pr_invoice_title ?? 'PrformaInvoice') }}#
                        </div>
                        <div class="quote-id">
                            {{ $prinvoice['proforma_invoice_no'] ?? 'N/A' }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="thin-line"></div>

        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="width:50%; vertical-align: middle;">
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
                </td>
                <td style="width:50%; text-align:right; vertical-align: top;">
                    <div class="quote-box">
                        <div class="quote-id">Date:
                            <span>{{ \Carbon\Carbon::parse($prinvoice['proforma_invoice_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $prinvoice['company_id']) ?? 'd M Y') }}</span>
                        </div>
                        <div class="quote-id">Valid Until:
                            <span>{{ \Carbon\Carbon::parse($prinvoice['expiry_date'] ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format', $prinvoice['company_id']) ?? 'd M Y') }}</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- CUSTOMER -->
        <div style="margin-top:20px;margin-bottom:20px">
            <div class="section-title">Customer Details</div>
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
                <div class="tour-name" style="margin-top:8px">Tour Name:
                    <strong style="font-weight:600;">{{ $tour['name'] ?? '-' }}</strong>
                </div>
                <div class="tour-name" style="margin-top:4px">Tour Details:
                    <strong style="font-weight:600;">{{ $tour['description'] ?? '-' }}</strong>
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
        <div class="thin-line"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th>{{ \App\Helpers\SettingHelper::getColoumName('items', $prinvoice['company_id']) ?? 'Item' }}
                    </th>
                    <th class="col-total" style="text-align: right">
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
                        <td>{{ $index + 1 }}</td>
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
                        <td colspan="3" style="text-align:center;">No items found in this estimate.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="thin-line"></div>

        <!-- TOTALS -->
        <div class="totals">
           @if ($prinvoice['sub_amount'])
                <div class="row total-payable">
                    <div class="label">Sub Total:</div>
                    <div class="amount">{{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['sub_amount'] ?? 0) }}
                        {{ $item['currency_label'] }}</div>
                </div>
            @endif
            @if ($prinvoice['discount_amount'] && $prinvoice['discount_amount'] > 0)
                <div class="row total-payable">
                    <div class="label">Total Discount:</div>
                    <div class="amount">-
                        {{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['discount_amount'] ?? 0) }}
                        {{ $item['currency_label'] }}</div>
                </div>
            @endif
            <div class="row total-payable total-payable-final">
                <div class="label">Total Payable:</div>
                <div class="amount">
                    {{ \App\Helpers\SettingHelper::formatCurrency($prinvoice['amount'], \App\Helpers\SettingHelper::getGenrealSettings('number_format', $prinvoice['company_id'])) }}
                    &nbsp;{{ $prinvoice['currency_label'] }}</div>
            </div>
        </div>


        <!-- NOTES -->
        @if (!empty($prinvoice['quotation']['notes']))
            <div class="terms">
                <div class="label">Booking Procedure</div>
                <ul style="list-style-type: none;">
                    @foreach (explode("\n", $prinvoice['quotation']['notes']) as $note)
                        @if (trim($note) !== '')
                            <li>{{ $note }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- TERMS -->
        @if (!empty($prinvoice['quotation']['terms_and_condition']))
            <div class="terms">
                <div class="label">Terms & Conditions</div>
                <ul style="list-style-type: none;">
                    @foreach (explode("\n", $prinvoice['quotation']['terms_and_condition']) as $term)
                        @if (trim($term) !== '')
                            <li>{{ $term }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif



        <div class="tiny-divider"></div>
        <div class="footer-note">
            Proforma Invoice was created digitally and is valid without signature.
        </div>

        @yield('content')
    </div>
</body>

</html>
