{{-- <div class="container-fluid bg bg-white p-5 rounded-5">
    <style>
        .pending-card {
            transition: all 0.2s ease;
        }

        .pending-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        .container-fluid {
            border-radius: 20px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .hero {
            background: #162032;
            color: #fff;
        }

        .hero small {
            color: #86ff9c;
        }

        .kpi h3 {
            margin: 0;
            color: white
        }

        .badge-soft-success {
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
        }

        .badge-soft-warning {
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        .badge-soft-info {
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 600;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            color: #475569;
            margin-right: 20px
        }

        .nav-pills .nav-link.active {
            background: #0f172a;
            color: #fff;
        }

        .timeline {
            border-left: 2px solid #e5e7eb;
            padding-left: 16px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -9px;
            top: 4px;
            width: 10px;
            height: 10px;
            background: #0f172a;
            border-radius: 50%;
        }

        @media(max-width:991px) {

            .col-lg-8,
            .col-lg-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Fixed left panel */
        .left-panel {
            position: sticky;
            top: 20px;
        }

        .left-panel .card {
            height: 500px;
            overflow-y: auto
        }
    </style>

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap">
        <div>
            <h4 class="fw-semibold mb-1">{{ $trip->name ?? 'NA' }}</h4>
            <small
                class="text-muted fs-6">{{ \Carbon\Carbon::parse($trip->start_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                -
                {{ \Carbon\Carbon::parse($trip->end_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card hero p-4 kpi">
                <small>Trip (Gross) Profit</small>
                <h3>₹{{ \App\Helpers\SettingHelper::formatCurrency($net_profit ?? 0, 'comma_dot') }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <small>Trip Income</small>
                <h5 class="text-success">
                    ₹{{ \App\Helpers\SettingHelper::formatCurrency($total_income ?? 0, 'comma_dot') }}</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <small>Trip Expense</small>
                <h5 class="text-danger">
                    ₹{{ \App\Helpers\SettingHelper::formatCurrency($total_expense ?? 0, 'comma_dot') }}</h5>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card p-3">
                <div class="text-muted small mb-1">Total Active Quotations</div>
                <div class="fw-bold display-4">{{ $total_tourist }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- LEFT PANEL (4 COLS) -->
        <div class="col-lg-3 left-panel">

            <!-- Cashflow Timeline -->
            <div class="card p-3 mb-4">
                <h6 class="fw-semibold mb-3">Cashflow Timeline</h6>
                <div class="timeline">
                    @forelse ($cashFlow as $index => $item)
                        <div class="timeline-item"><strong
                                @if ($item->entry_type === 1) class="text-danger" @else class="text-success" @endif>₹{{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}</strong>
                            <div> {{ $item?->category?->name ?? 'NA' }}
                                @if ($item->entry_type === 1 && $item?->quotation?->uuid)
                                    -
                                    <a href="{{ route('common.view-quotation', $item?->quotation?->uuid) }}"
                                        class="text-primary">
                                        BCIQT
                                    </a>
                                @elseif ($item->entry_type === 2 && $item?->proforma?->uuid)
                                    -
                                    <a href="{{ route('common.view-proformainvoice', $item?->proforma?->uuid) }}"
                                        class="text-primary">
                                        PRINV
                                    </a>
                                @endif
                            </div><small class="text-muted">
                                {{ $item->created_at->format('d M') ?? 'NA' }}
                            </small>
                        </div>
                    @empty
                        <span>No data Found</span>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT PANEL (8 COLS) -->
        <div class="col-lg-9">

            <!-- TABS: Overview / Data -->
            <ul class="nav nav-pills mb-3" id="mainTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview"
                        type="button">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="data-tab" data-bs-toggle="pill" data-bs-target="#data"
                        type="button">Summary & Records</button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- OVERVIEW TAB -->
                <div class="tab-pane fade show active" id="overview">

                    <!-- PENDING QUOTATIONS -->
                    <div class="card mt-4 p-4 border-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Pending Payments</h6>
                            <small class="text-warning fs-7">Awaiting payment</small>
                        </div>

                        <div class="row g-3">

                            @forelse ($pending_qts as $index => $item)
                                @php
                                    $estimateRoute = route('common.view-quotation', $item->uuid);
                                @endphp
                                <!-- CARD -->
                                <div class="col-md-6">
                                    <div class="pending-card p-3 bg-white rounded-4 shadow-sm">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div class="fw-semibold fs-6">
                                                    {{ $item?->tourist?->primary_contact ?? 'NA' }}</div>
                                                <small>
                                                    <a href="{{ $estimateRoute }}" class="fw-500 text-primary">
                                                        {{ $item->quotation_no ?? 'NA' }}
                                                    </a>
                                                </small>
                                            </div>
                                            <span class="text-danger fw-semibold">
                                                {{ \App\Helpers\SettingHelper::formatCurrency($item->total_remaning_amount ?? 0, 'comma_dot') }}
                                                {{ $item->currency_label ?? '₹' }} Pending
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between mt-3 small">
                                            <div>
                                                <div class="text-muted">Total</div>
                                                <div class="fw-semibold">
                                                    {{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}
                                                    {{ $item->currency_label ?? '₹' }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="text-muted">Paid</div>
                                                <span class="text-success fw-semibold">
                                                    {{ \App\Helpers\SettingHelper::formatCurrency($item->total_paid_amount ?? 0, 'comma_dot') }}
                                                    {{ $item->currency_label ?? '₹' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <span>No Pending Quotations Found</span>
                            @endforelse

                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Expense Card -->
                        <div class="col-lg-6 col-md-6">
                            <div class="card p-3">
                                <h6 class="fw-semibold mb-3">Expense</h6>
                                @forelse ($expenseGroupSummary as $row)
                                    <div class="mb-2">
                                        <span class="d-flex justify-content-between">
                                            {{ $row->category?->name ?? 'Uncategorized' }} @if ($row->subcategory?->name)
                                                ({{ $row->subcategory->name }})
                                            @endif
                                            <strong class="text-danger">
                                                ₹{{ \App\Helpers\SettingHelper::formatCurrency($row->total_amount, 'comma_dot') }}
                                            </strong>
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-muted">No expense data found</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DATA TAB -->
                <div class="tab-pane fade" id="data">
                    <livewire:common.trip-master.trip-view.records :quotation-ids="$quotationIds" :id="$id" />
                </div>

            </div>

        </div>

    </div>
</div> --}}
<div class="container-fluid  p-2 ">
    <style>
        .crm-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
 
        .crm-badge-active {
            background: #DCFCE7;
            color: #16A34A;
        }
 
        .crm-badge-inactive {
            background: #FEE2E2;
            color: #DC2626;
        }
 
        .crm-badge-draft {
            background: #FEF3C7;
            color: #F59E0B;
        }
        .pending-card {
            transition: all 0.2s ease;
        }

        .pending-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        .container-fluid {
            border-radius: 20px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .hero {
            background: #162032;
            color: #fff;
        }

        .hero small {
            color: #86ff9c;
        }

        .kpi h3 {
            margin: 0;
            color: white
        }

        .badge-soft-success {
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
        }

        .badge-soft-warning {
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        .badge-soft-info {
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 600;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            color: #475569;
            margin-right: 20px
        }

        .nav-pills .nav-link.active {
            background: #0f172a;
            color: #fff;
        }

        .timeline {
            border-left: 2px solid #e5e7eb;
            padding-left: 16px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -11px;
            top: 4px;
            width: 10px;
            height: 10px;
            background: #0f172a;
            border-radius: 50%;
        }

        @media(max-width:991px) {

            .col-lg-8,
            .col-lg-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Fixed left panel */
        .left-panel {
            position: sticky;
            top: 20px;
        }

        .left-panel .card {
            height: 500px;
            overflow-y: auto
        }
        .crm-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: none;
        }
 
        .crm-primary {
            color: #0F172A;
        }
 
        .crm-success {
            color: #16A34A;
        }
 
        .crm-warning {
            color: #F59E0B;
        }
 
        .crm-danger {
            color: #DC2626;
        }
    </style>

    <div class="crm-card bg-white mb-4">
        <div class="card-body d-flex justify-content-between align-items-start flex-wrap p-4">

            <!-- Left -->
            <div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <h2 class="crm-primary mb-0" style="font-size: 24px; font-weight: 600;">
                        {{ $trip->name ?? 'NA' }}
                    </h2>

                    @if($trip->status == 1)
                        <span class="crm-badge crm-badge-active">Active</span>
                    @elseif($trip->status == 0)
                        <span class="crm-badge crm-badge-inactive">Inactive</span>
                    @else
                        <span class="crm-badge crm-badge-draft">Draft</span>
                    @endif
                </div>

                <small class="text-muted fs-6">
                    {{ \Carbon\Carbon::parse($trip->start_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                    -
                    {{ \Carbon\Carbon::parse($trip->end_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                </small>
            </div>

            <!-- Right -->
     <div class="d-flex gap-2">
            <a href="{{ route('common.trip') }}"
               class="btn btn-sm"
               style="background: #0f172a;; color: white; border-radius: 8px; padding: 10px 20px; font-weight: 600;">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>

        </div>
    </div>


    <div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
        <div class="crm-card" style="padding: 20px;background:#0f172a;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #DBEAFE; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-rupee" style="font-size: 24px; color: #0F172A;"></i>
                </div>
                <div>
                    <p class="mb-0 text-white" style="font-size: 12px; font-weight: 500;">Trip (Gross) Profit</p>
                    <h3 class="mb-0 crm-primary text-white" style="font-size: 28px; font-weight: 700;">₹{{ \App\Helpers\SettingHelper::formatCurrency($net_profit ?? 0, 'comma_dot') }}</h3>
                </div>
            </div>
        </div>
    </div>
 
    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #DCFCE7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-rupee" style="font-size: 24px; color: green;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Trip Income</p>
                    <h3 class="mb-0 text-success" style="font-size: 28px; font-weight: 700;">₹{{ \App\Helpers\SettingHelper::formatCurrency($total_income ?? 0, 'comma_dot') }}</h3>
                </div>
            </div>
        </div>
    </div>
 
    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #fd3550; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-rupee" style="font-size: 24px; color: white;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Trip Expense</p>
                    <h3 class="mb-0 crm-primary" style="font-size: 28px; font-weight: 700;color:#fd3550">₹{{ \App\Helpers\SettingHelper::formatCurrency($total_expense ?? 0, 'comma_dot') }}</h3>
                </div>
            </div>
        </div>
    </div>
 
    <div class="col-lg-3 col-md-6">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #E0F2FE; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fadeIn animated bx bx-user" style="font-size: 24px; color: #0F172A;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Total Active Quotations</p>
                    <h3 class="mb-0 crm-draft" style="font-size: 28px; font-weight: 700;">{{ $total_tourist }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row g-4">

        <div class="col-lg-3 left-panel">

            <div class="crm-card p-3 mb-4">
                <h6 class="fw-semibold mb-3">Cashflow Timeline</h6>
                <div class="timeline">
                    @forelse ($cashFlow as $index => $item)
                        <div class="timeline-item"><strong
                                @if ($item->entry_type === 1) class="text-danger" @else class="text-success" @endif>₹{{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}</strong>
                            <div> {{ $item?->category?->name ?? 'NA' }}
                                @if ($item->entry_type === 1 && $item?->quotation?->uuid)
                                    -
                                    <a href="{{ route('common.view-quotation', $item?->quotation?->uuid) }}"
                                        class="text-primary">
                                        BCIQT
                                    </a>
                                @elseif ($item->entry_type === 2 && $item?->proforma?->uuid)
                                    -
                                    <a href="{{ route('common.view-proformainvoice', $item?->proforma?->uuid) }}"
                                        class="text-primary">
                                        PRINV
                                    </a>
                                @endif
                            </div><small class="text-muted">
                                {{ $item->created_at->format('d M') ?? 'NA' }}
                            </small>
                        </div>
                    @empty
                        <span>No data Found</span>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT PANEL (8 COLS) -->
        <div class="col-lg-9">

            <!-- TABS: Overview / Data -->
            <ul class="crm-card p-3 nav nav-pills mb-3" id="mainTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-0" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview"
                        type="button">Overview</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-0" id="data-tab" data-bs-toggle="pill" data-bs-target="#data"
                        type="button">Summary & Records</button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- OVERVIEW TAB -->
                <div class="tab-pane fade show active" id="overview">

                    <!-- PENDING QUOTATIONS -->
                    <div class="crm-card mt-4 p-4 border-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold mb-0">Pending Payments</h6>
                            <small class="text-warning fs-7">Awaiting payment</small>
                        </div>

                        <div class="row g-3">

                            @forelse ($pending_qts as $index => $item)
                                @php
                                    $estimateRoute = route('common.view-quotation', $item->uuid);
                                @endphp
                                <!-- CARD -->
                                <div class="col-md-6">
                                    <div class="pending-card p-3 bg-white rounded-4 shadow-sm">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <div class="fw-semibold fs-6">
                                                    {{ $item?->tourist?->primary_contact ?? 'NA' }}</div>
                                                <small>
                                                    <a href="{{ $estimateRoute }}" class="fw-500 text-primary">
                                                        {{ $item->quotation_no ?? 'NA' }}
                                                    </a>
                                                </small>
                                            </div>
                                            <span class="text-danger fw-semibold">
                                                {{ \App\Helpers\SettingHelper::formatCurrency($item->total_remaning_amount ?? 0, 'comma_dot') }}
                                                {{ $item->currency_label ?? '₹' }} Pending
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between mt-3 small">
                                            <div>
                                                <div class="text-muted">Total</div>
                                                <div class="fw-semibold">
                                                    {{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}
                                                    {{ $item->currency_label ?? '₹' }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="text-muted">Paid</div>
                                                <span class="text-success fw-semibold">
                                                    {{ \App\Helpers\SettingHelper::formatCurrency($item->total_paid_amount ?? 0, 'comma_dot') }}
                                                    {{ $item->currency_label ?? '₹' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <span>No Pending Quotations Found</span>
                            @endforelse

                        </div>
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-lg-6 col-md-6">
                            <div class="crm-card p-3">
                                <h6 class="fw-semibold mb-3">Expense</h6>
                                @forelse ($expenseGroupSummary as $row)
                                    <div class="mb-2">
                                        <span class="d-flex justify-content-between">
                                            {{ $row->category?->name ?? 'Uncategorized' }} @if ($row->subcategory?->name)
                                                ({{ $row->subcategory->name }})
                                            @endif
                                            <strong class="text-danger">
                                                ₹{{ \App\Helpers\SettingHelper::formatCurrency($row->total_amount, 'comma_dot') }}
                                            </strong>
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-muted">No expense data found</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DATA TAB -->
                <div class="tab-pane fade" id="data">
                    <livewire:common.trip-master.trip-view.records :quotation-ids="$quotationIds" :id="$id" />
                </div>

            </div>

        </div>

    </div>
</div>