<div class="mx-5 mt-sm-0 mt-3">
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center py-3">




            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(null)"
                        class="nav-link @if (is_null($statusFilter)) active @endif" data-bs-toggle="pill"
                        href="#primary-pills-home" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                            </div>
                            <div class="tab-title">All Invoices ({{ $counts['all'] }})</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(0)" class="nav-link @if ($statusFilter === 0) active @endif"
                        data-bs-toggle="pill" href="#primary-pills-profile" role="tab" aria-selected="false"
                        tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                            </div>
                            <div class="tab-title">Draft ({{ $counts['draft'] }})</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item " role="presentation">
                    <a wire:click="setStatusFilter(1)" class="nav-link @if ($statusFilter === 1) active @endif"
                        data-bs-toggle="pill" href="#primary-pills-contact" role="tab" aria-selected="false"
                        tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
                            </div>
                            <div class="tab-title">Sent ({{ $counts['sent'] }})</div>
                        </div>
                    </a>
                </li>
            </ul>

            <div class="mb-0">
                <input class="form-control form-control-solid" placeholder="Pick Dates" id="kt_daterangepicker_1" />
            </div>




            <div class="position-relative">
                <input type="text" class="form-control ps-5" placeholder="Search..."
                    wire:model.live.debounce.300ms="search">
                <span class="position-absolute product-show translate-middle-y">
                    <i class="bx bx-search"></i>
                </span>
            </div>


        </div>

        <div class="card-body p-0">
            <div class="row ">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 100%;">
                        <thead style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);">
                            <tr>
                                <th style="padding:12px 15px; font-weight: 600; color: #374151;">#</th>
                                <th style="padding: 12px 15px; font-weight: 600; color: #374151;"
                                    wire:click="shortby('invoice_date')" style="cursor: pointer;">
                                    Date
                                    @if ($sortBy === 'invoice_date')
                                        <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                    @endif
                                </th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #374151;"
                                    wire:click="shortby('invoice_no')" style="cursor: pointer;">
                                    {{ $pageTitle }} #
                                    @if ($sortBy === 'invoice_no')
                                        <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                    @endif
                                </th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Quotation #</th>
                                <th width="20%" class="text-start"
                                    style="padding: 12px 16px; font-weight: 600; color: #374151;">
                                    Start/End Date
                                </th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Tourist</th>
                                <th style="padding: 12px 16px; font-weight: 600; color: #374151;"
                                    wire:click="shortby('status')" style="cursor: pointer;">
                                    Status
                                    @if ($sortBy === 'status')
                                        <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                    @endif
                                </th>
                                <th style="width: 120px; padding: 12px 16px; font-weight: 600; color: #374151;"
                                    wire:click="shortby('amount')" style="cursor: pointer;">
                                    Amount
                                    @if ($sortBy === 'amount')
                                        <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $index => $item)
                                @php
                                    $invoiceRoute = route('common.view-invoice', $item->uuid);
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
                                        <a href="{{ route('common.view-quotation', $item->quotation->uuid) }}"
                                            class="fw-500 text-primary">
                                            {{ $item->quotation->quotation_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ $invoiceRoute }}" class="fw-500 text-dark">
                                            {{ \Carbon\Carbon::parse($item->start_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->end_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
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
                                            <div
                                                class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
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
                                    <td colspan="12" class="text-center py-4" style="color: #6b7280;">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-data fs-1 mb-2" style="color: #d1d5db;"></i>
                                            <span>No Invoice found. Click "Add New {{ $pageTitle }}" to create
                                                one.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($items->hasPages())
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="text-muted small">
                            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }}
                            entries
                        </div>
                        <div>
                            {{ $items->links('livewire::bootstrap') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>




    </div>

</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:init', function() {
            $("#kt_daterangepicker_1").daterangepicker({
                locale: {
                    cancelLabel: 'Clear',
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                },
            });
            $('#kt_daterangepicker_1').on('apply.daterangepicker', function(ev, picker) {
                let startDate = picker.startDate.format('YYYY-MM-DD');
                let endDate = picker.endDate.format('YYYY-MM-DD');
                @this.set('startdate', startDate);
                @this.set('enddate', endDate);
            });

            $('#kt_daterangepicker_1').on('cancel.daterangepicker', function(ev, picker) {
                @this.set('startdate', null);
                @this.set('enddate', null);
            });
        });
    </script>
@endpush
