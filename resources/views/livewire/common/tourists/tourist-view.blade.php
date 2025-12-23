<div class="container py-4">
    <!-- Profile Header -->



    <div class="d-flex justify-content-end gap-3">
        <a href="{{ route($route . '.tourist-edit', $clienId) }}" class="btn btn-primary"><i class="fa fa-print"></i>
            Edit</a>
       {{-- <div class="btn-group">
            <button type="button" class="btn btn-primary" fdprocessedid="ykfs4">New Transaction</button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                data-bs-toggle="dropdown" fdprocessedid="qtrdwa"> <span class="visually-hidden">Toggle
                    Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a href="{{ route($route . '.add-invoice', ['client_id' => $clientInfo->id]) }}" class="dropdown-item"
                    href="javascript:;">New Invoice</a>
                <a href="{{ route($route . '.add-quotation', ['client_id' => $clientInfo->id]) }}" class="dropdown-item"
                    href="javascript:;">New Quotation</a>
            </div>
        </div> --}}
    </div>
    
    <div class="profile-header p-4 text-black mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-6 fw-bold mb-1">{{ $clientInfo->primary_contact ?? 'NA' }}</h1>
                <p class="mb-2"><i class="bi bi-geo-alt-fill"></i> Country: {{ $clientInfo->country->name ?? 'NA' }}
                </p>
                <p class="mb-2"><i class="bi bi-telephone-fill"></i> Phone: {{ $clientInfo->contact_phone ?? 'NA' }}
                </p>
                <p class="mb-0"><i class="bi bi-currency-rupee"></i> Currency:
                    {{ $clientInfo?->base_currency_code ?? 'NA' }}</p>


            </div>
            <div class="col-md-4 text-md-end">
                <div class="bg-white text-dark p-3 rounded d-inline-block">
                    <h6 class="mb-1 text-muted">Contact Person</h6>
                    <p class="mb-1 fw-bold">{{ $clientInfo->primary_contact ?? 'NA' }}</p>
                    <a href="mailto:{{ $clientInfo->contact_email ?? 'NA' }}" class="mb-1">{{ $clientInfo->contact_email ?? 'NA' }}</a></br>
                    <a href="{{ $clientInfo?->other?->website }}" class="text-primary">{{ $clientInfo?->other?->website ?? 'NA' }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card bg-white p-4 text-center">
                <h6 class="text-muted">TOTAL SALES</h6>
                <h3 class="fw-bold text-primary">{{ $clientInfo?->base_currency_code ?? '₹' }}{{ $totalSales }}
                </h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card bg-white p-4 text-center">
                <h6 class="text-muted">TOTAL RECEIPT</h6>
                <h3 class="fw-bold text-success">{{ $clientInfo?->base_currency_code ?? '₹' }}{{ $totalPaid }}
                </h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card bg-white p-4 text-center">
                <h6 class="text-muted">BALANCE DUE</h6>
                <h3 class="fw-bold text-danger">{{ $clientInfo?->base_currency_code ?? '₹' }}{{ $totalDue }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices"
                type="button" role="tab">Invoices</button>
        </li>
       {{-- <li class="nav-item" role="presentation">
            <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button"
                role="tab">Expenses</button>
        </li> --}}
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="quotations-tab" data-bs-toggle="tab" data-bs-target="#quotations"
                type="button" role="tab">Quotations</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content bg-white p-4 rounded shadow-sm">
        <div class="tab-pane fade show active" id="invoices" role="tabpanel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Date</th>
                            <th>Invoice #</th>
                            <th>Quotation #</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($clientInfo->invoices as $index => $item)
                            @php
                                $invoiceRoute = route($route . '.view-invoice', $item->uuid);
                            @endphp

                            <tr wire:key="{{ $item->id }}"
                                style="border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease;">
                                <td>
                                    <a href="{{ $invoiceRoute }}" class="text-dark">
                                        {{ $index + 1 }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $invoiceRoute }}" class="text-dark">
                                        {{ \Carbon\Carbon::parse($item->invoice_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $invoiceRoute }}" class="fw-500 text-dark">
                                        {{ $item->invoice_no ?? 'NA' }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $invoiceRoute }}" class="fw-500 text-dark">
                                        {{ $item->quotation->quotation_no ?? 'NA' }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $invoiceRoute }}" class="fw-500 text-dark">
                                        {{ $item->tourist->primary_contact ?? 'NA' }}
                                    </a>
                                </td>
                                <td class="p-3">
                                             @php
                                            switch ($item->status) {
                                                case 0:
                                                    $status = 'text-dark bg-light-dark';
                                                    break;
                                                case 1:
                                                    $status = 'text-warning bg-light-warning';
                                                    break;
                                                case 3:
                                                    $status = 'text-danger bg-light-danger';
                                                    break;
                                                default:
                                                    $status = 'text-success bg-light-success';
                                                    break;
                                            }
                                            $statusName =
                                                App\Helpers\SettingHelper::getInvoiceStatus($item->status) ?? 'NA';
                                        @endphp

                                    <a href="{{ $invoiceRoute }}" class="fw-500 text-dark">
                                        <div class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
                                            <i class="bx bxs-circle me-1"></i>{{ $statusName }}
                                        </div>
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $invoiceRoute }}" class="fw-500 text-dark">
                                        {{ \App\Helpers\SettingHelper::formatCurrency(
                                            $item->amount ?? 0,
                                            \App\Helpers\SettingHelper::getGenrealSettings('number_format'),
                                        ) }}

                                    </a>
                                </td>
                            </tr>


                        @empty

                            <tr>
                                <td colspan="12">
                                    <div class="empty-state py-5 text-center">
                                        <i class="bi bi-file-earmark-text display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">No invoices found</h5>
                                        <p class="text-muted">Create your first invoice to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
       {{-- <div class="tab-pane fade" id="expenses" role="tabpanel">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Vendor Name</th>
                            <th>Amount</th>
                            <th>Tour</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientInfo->expenses as $index => $item)
                            <tr wire:key="{{ $item->id }}">
                                <td class="align-middle py-1">{{ $item->date }}</td>
                                <td class="align-middle py-1">
                                    <span class="">
                                        {{ $item?->category?->name }}
                                    </span>
                                </td>
                                <td class="align-middle py-1 text-center">
                                    <span class="">
                                        {{ $item?->vendor_name }}
                                    </span>
                                </td>
                                <td class="align-middle py-1">
                                    <span class="">
                                        {{ $item->amount }}
                                    </span>
                                </td>
                                <td class="align-middle py-1">
                                    <span class="">
                                        {{ $item->tour->name }}
                                    </span>
                                </td>
                            </tr>
                        @empty

                            <tr>
                                <td colspan="12">
                                    <div class="empty-state py-5 text-center">
                                        <i class="bi bi-file-earmark-text display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">No Expense found</h5>
                                        <p class="text-muted">Create your Client Expense to get started</p>
                                        <a href="{{ route($route . '.expense') }}" class="btn btn-primary">Create
                                            Expense</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>

        </div> --}}
        <div class="tab-pane fade" id="quotations" role="tabpanel">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Date</th>
                            <th>Quotation #
                            </th>
                            <th>Client</th>
                            <th>Status</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($clientInfo->quotation as $index => $item)
                            @php
                                $QuotationRoute = route($route . '.view-quotation', $item->uuid);
                            @endphp

                            <tr wire:key="{{ $item->id }}"
                                style="border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease;">
                                <td>
                                    <a href="{{ $QuotationRoute }}" class="text-dark">
                                        {{ $index + 1 }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $QuotationRoute }}" class="text-dark">
                                        {{ \Carbon\Carbon::parse($item->Quotation_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $QuotationRoute }}" class="fw-500 text-dark">
                                        {{ $item->quotation_no ?? 'NA' }}
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $QuotationRoute }}" class="fw-500 text-dark">
                                        {{ $item->tourist->primary_contact ?? 'NA' }}
                                    </a>
                                </td>
                                <td class="p-3">
                                          @php
                                            switch ($item->status) {
                                                case 0:
                                                    $status = 'text-dark bg-light-dark';
                                                    break;
                                                case 1:
                                                    $status = 'text-warning bg-light-warning';
                                                    break;
                                                case 2:
                                                    $status = 'text-success bg-light-success';
                                                    break;
                                                case 3:
                                                    $status = 'text-danger bg-light-danger'; // Discard/Rejected
                                                    break;
                                                case 4:
                                                    $status = 'text-danger bg-light-danger'; // Revised (RED)
                                                    break;
                                                case 5:
                                                    $status = 'text-secondary bg-light-secondary';
                                                    break;
                                                case 6:
                                                    $status = 'text-info bg-light-info';
                                                    break;
                                                    case 7:
                                                    $status = 'text-dark bg-light-success';
                                                    break;
                                                default:
                                                    $status = 'text-muted bg-light';
                                            }

                                            $statusName = App\Helpers\SettingHelper::getStatus($item->status) ?? 'NA';
                                        @endphp
                                    <a href="{{ $QuotationRoute }}" class="fw-500 text-dark">
                                        <div class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
                                            <i class="bx bxs-circle me-1"></i>{{ $statusName }}
                                        </div>
                                    </a>
                                </td>
                                <td class="p-3">
                                    <a href="{{ $QuotationRoute }}" class="fw-500 text-dark">
                                        {{ \App\Helpers\SettingHelper::formatCurrency(
                                            $item->amount ?? 0,
                                            \App\Helpers\SettingHelper::getGenrealSettings('number_format'),
                                        ) }}

                                    </a>
                                </td>
                            </tr>


                        @empty

                            <tr>
                                <td colspan="12">
                                    <div class="empty-state py-5 text-center">
                                        <i class="bi bi-file-earmark-text display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">No Quotation found</h5>
                                        <p class="text-muted">Create your first Quotation to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
