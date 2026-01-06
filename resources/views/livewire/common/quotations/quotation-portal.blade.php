<div class="container">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/app.css') }}?t={{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/bootstrap-extended.css') }}" rel="stylesheet">
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
    height: 80px; /* example fixed height */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden; /* hides any overflow outside container */
    background-color: transparent; /* optional */
}

.logo-img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* keeps aspect ratio */
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
    <div class="page-content">
        <!-- Full Page Loader -->
        <div wire:loading class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading Estimate...</p>
        </div>

        <div wire:loading.remove>
            @if ($estimate && isset($estimate['id']))


                <div class="toolbar hidden-print">
                    <div class="text-end">
                        <a href="{{ route('estimate.pdf', ['id' => $estimate->uuid]) }}" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o"></i> Export as PDF
                        </a>

                        <a href="{{ route('estimate.view', ['id' => $estimate->uuid]) }}" class="btn btn-primary">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </div>
                    <hr>
                </div>


                <div class="page card">
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
                                <div class="quote-id fs-5">
                                    {{ ucfirst($estimateSettings->quotation_title ?? 'Quotation') }}#
                                </div>
                                <div class="quote-id">{{ $estimate['quotation_no'] ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="thin-line" style="width: 114%;margin-left: -5%;"></div>

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
                          
                            @if (!empty($client['contact_phone']))
                                <div class="company-info">@if(!empty($client['country']) ) +{{ $client['country']['phonecode'] }}-@endif{{ $client['contact_phone'] }}</div>
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
                                    <span class="quote-id">Tour Details:</span>
                                    <span>{{ $tour['description'] ?? '-' }}</span>
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
                            <div class="thin-line" style="width: 114%;margin-left: -5%;"></div>
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
    <div class="thin-line" style="width: 114%;margin-left: -5%;"></div>
    
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
                                        -
                                        {{ \App\Helpers\SettingHelper::formatCurrency($estimate['discount_amount'] ?? 0) }} {{ $item['currency_label'] }}
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
                                <ul>
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
                                <ul>
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
            @else
                <div class="alert alert-warning text-center py-5">
                    <h4>No Estimate Found</h4>
                </div>
            @endif
        </div>
    </div>
</div>
