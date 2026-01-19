<div class="row g-3">

    <!-- Invoice Card -->
    @if (count($invoices) > 0)
        <div class="col-lg-6">
            <div class="crm-card p-3">
                <h6 class="fw-semibold mb-3">Invoices</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>NO#</th>
                                <th>Tourist</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $index => $item)
                                @php
                                    $invoiceRoute = route('common.view-invoice', $item->uuid);
                                @endphp
                                <tr wire:key="{{ $item->id }}">
                                    <td> <a href="{{ $invoiceRoute }}" class="fw-500 text-primary">
                                            {{ $item->invoice_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td> {{ $item->tourist->primary_contact ?? 'NA' }}</td>
                                    <td>{{ \App\Helpers\SettingHelper::formatCurrency(
                                        $item->amount ?? 0,
                                        \App\Helpers\SettingHelper::getGenrealSettings('number_format'),
                                    ) }}
                                    </td>
                                    <td>
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
                                                {{ $statusName }}
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Invoices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Proforma Card -->
    @if (count($prformainvoices) > 0)
        <div class="col-lg-6">
            <div class="crm-card p-3">
                <h6 class="fw-semibold mb-3">Proforma</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>NO#</th>
                                <th>Tourist</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prformainvoices as $index => $item)
                                @php
                                    $estimateRoute = route('common.view-proformainvoice', $item->uuid);
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-primary">
                                            {{ $item->proforma_invoice_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ $item?->tourist?->primary_contact ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            switch ($item->status) {
                                                case 0:
                                                    $status = 'text-dark bg-light-warning';
                                                    break;
                                                case 1:
                                                    $status = 'text-warning bg-light-warning';
                                                    break;
                                                case 2:
                                                    $status = 'text-dark bg-light-success';
                                                    break;
                                                case 3:
                                                    $status = 'text-dark bg-warning';
                                                    break;
                                                default:
                                                    $status = 'text-muted bg-light';
                                            }

                                            $statusName =
                                                App\Helpers\SettingHelper::getProFormaInvoiceStatus($item->status) ??
                                                'NA';
                                        @endphp

                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            <div
                                                class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
                                                {{ $statusName }}
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4" style="color: #6b7280;">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-data fs-1 mb-2" style="color: #d1d5db;"></i>
                                            <span>No Proforma Invoices found.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Quotation Card -->
    @if (count($quotations) > 0)
        <div class="col-lg-6">
            <div class="crm-card p-3">
                <h6 class="fw-semibold mb-3">Quotations</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>NO#</th>
                                <th>Tourist</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($quotations as $index => $item)
                                @php
                                    $estimateRoute = route('common.view-quotation', $item->uuid);
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-primary">
                                            {{ $item->quotation_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ $item?->tourist?->primary_contact ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}

                                        </a>
                                    </td>
                                    <td>
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

                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            <div
                                                class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
                                                {{ $statusName }}
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Quotations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Trip Expense Card -->
    @if (count($trip_expenses) > 0)
        <div class="col-lg-6">
            <div class="crm-card p-3">
                <h6 class="fw-semibold mb-3">Trip Expense</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            @forelse ($trip_expenses as $index => $item)
                                <tr>
                                    <td>
                                        {{ $item->category->name ?? 'NA' }} @if ($item->subcategory->name)
                                            / {{ $item->subcategory->name }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        ₹{{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Expenses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Expense Card -->
    @if (count($expenses) > 0)
        <div class="col-lg-6">
            <div class="crm-card p-3">
                <h6 class="fw-semibold mb-3">Expense</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            @forelse ($expenses as $index => $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('common.view-quotation', $item?->quotation?->uuid) }}"
                                            class="text-primary">
                                            {{ $item?->quotation?->quotation_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        ₹{{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Expenses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Income Card -->
    @if (count($incomes) > 0)
        <div class="col-lg-6">
            <div class="crm-card p-3">
                <h6 class="fw-semibold mb-3">Income</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            @forelse ($incomes as $index => $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('common.view-proformainvoice', $item?->proforma?->uuid) }}"
                                            class="text-primary">
                                            {{ $item?->proforma?->proforma_invoice_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        ₹{{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Income found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
