<div class="mx-5 mt-sm-0 mt-3">
    <style>
        .dropdown-toggle::after {
            display: none;
            /* Remove the default arrow */
        }
    </style>
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

        @if (count($leads) > 0)
            <a wire:click='add' class="btn bluegradientbtn">
                <i class="bx bx-plus me-1"></i> Add New {{ $pageTitle }} ({{ count($leads) }})
                <span style="cursor: pointer" wire:loading wire:target="add">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                </span>
            </a>
        @endif
    </div>

    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div class="mb-0">
                <input class="form-control form-control-solid" placeholder="Pick Dates" id="kt_daterangepicker_1" />
            </div>


            <select id="company_id" class="form-select select2 w-50" wire:model="company_id" placeholder="Company Name">
                <option value=""></option>
                @foreach ($companies as $id => $company_name)
                    <option value="{{ $id }}" @if ($company_id == $id) selected @endif>
                        {{ $company_name }}</option>
                @endforeach
            </select>
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
                            <div class="tab-title">All Quotations ({{ $counts['all'] }})</div>
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

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(6)" class="nav-link @if ($statusFilter === 6) active @endif"
                        data-bs-toggle="pill" href="#primary-pills-contact" role="tab" aria-selected="false"
                        tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
                            </div>
                            <div class="tab-title">Proforma Invoiced ({{ $counts['prinvoiced'] }})</div>
                        </div>
                    </a>
                </li>
                       <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(7)" class="nav-link @if ($statusFilter === 7) active @endif"
                        data-bs-toggle="pill" href="#primary-pills-contact" role="tab" aria-selected="false"
                        tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
                            </div>
                            <div class="tab-title">Invoiced ({{ $counts['invoiced'] }})</div>
                        </div>
                    </a>
                </li>
            </ul>




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
                    <table class="table table-hover align-middle mb-0" style="min-width: 100%;min-height: 200px;">
                        <thead style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);">
                            <tr>
                                <th width="5%" style="padding:12px 5px; font-weight: 600; color: #374151;">#
                                </th>
                                <th width="10%" style="padding: 12px 15px; font-weight: 600; color: #374151;">Date
                                </th>
                                <th width="17%" style="padding: 12px 16px; font-weight: 600; color: #374151;">
                                    {{ $pageTitle }} #
                                </th>
                                <th width="7%" style="padding: 12px 16px; font-weight: 600; color: #374151;">Lead
                                    #</th>

                                <th width="10%" style="padding: 12px 16px; font-weight: 600; color: #374151;">
                                    Tourist</th>
                                <th width="15%" class="text-start" style="padding: 12px 16px; font-weight: 600; color: #374151;">
                                    Start/End Date
                                </th>
                                <th width="12%" style="padding: 12px 16px; font-weight: 600; color: #374151;">
                                    Status</th>
                                <th width="15%"
                                    style="width: 120px; padding: 12px 16px; font-weight: 600; color: #374151;">
                                    Quotation AMT
                                </th>
                                <th width="15%"
                                    style="width: 120px; padding: 12px 16px; font-weight: 600; color: #374151;">Received AMT
                                </th>
                                <th width="15%"
                                    style="width: 120px; padding: 12px 16px; font-weight: 600; color: #374151;">Pending AMT
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $index => $item)
                                @php
                                    $estimateRoute = route($route . '.view-quotation', $item->uuid);
                                @endphp

                                <tr wire:key="{{ $item->id }}"
                                    style="border-bottom: 1px solid #f3f4f6; transition: all 0.2s ease;">
                                    <td>
                                        <a href="{{ $estimateRoute }}" class="text-dark">
                                            {{ $index + 1 }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ $estimateRoute }}" class="text-dark">
                                            {{ \Carbon\Carbon::parse($item->quotation_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ $item->quotation_no ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ $item?->leads?->id ? route('common.lead-view', $item->leads->id) : '#' }}"
                                            class="fw-500 text-primary">
                                            #{{ $item?->leads?->id ?? '' }}
                                        </a>

                                    </td>


                                    <td class="p-3">
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ $item?->tourist?->primary_contact ?? 'NA' }}
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ \Carbon\Carbon::parse($item->start_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->end_date ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
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

                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            <div
                                                class="badge rounded-pill {{ $status }} p-2 text-uppercase px-3">
                                                <i class="bx bxs-circle me-1"></i>{{ $statusName }}
                                            </div>
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                            {{ \App\Helpers\SettingHelper::formatCurrency($item->amount ?? 0, 'comma_dot') }}

                                        </a>
                                    </td>

                                    <td class="p-3">
                                        @if ($item->total_paid_amount)
                                            <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                                {{ \App\Helpers\SettingHelper::formatCurrency($item->total_paid_amount ?? 0, 'comma_dot') }}
                                            </a>
                                        @else
                                            <span>0.00</span>
                                        @endif
                                    </td>

                                    <td class="p-3">
                                        @if ($item->total_remaning_amount)
                                            <a href="{{ $estimateRoute }}" class="fw-500 text-dark">
                                                {{ \App\Helpers\SettingHelper::formatCurrency($item->total_remaning_amount ?? 0, 'comma_dot') }}
                                            </a>
                                        @else
                                            <span>0.00</span>
                                        @endif
                                    </td>

                                    <td class="p-3">


                                        <div wire:key="{{ $item->id }}" class="dropdown ms-auto">
                                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                                            </a>
                                            <ul class="dropdown-menu" style="">

                                                @if (in_array($item->status, [0, 1]))
                                                    <li><a class="dropdown-item"
                                                            href="{{ route($route . '.revised-quotation', ['revised_id' => base64_encode($item->id)]) }}">Revice
                                                            Estimate</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                @endif
                                                <li><a class="dropdown-item"
                                                        href="{{ route($route . '.view-quotation', $item->uuid) }}">View
                                                        Details</a></li>
                                            </ul>
                                        </div>

                                    </td>

                                </tr>


                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4" style="color: #6b7280;">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bx bx-data fs-1 mb-2" style="color: #d1d5db;"></i>
                                            <span>No Quotations found. Click "Add New {{ $pageTitle }}" to create
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

        <div class="modal fade @if ($showModal) show d-block @endif"
            style="background: rgba(0,0,0,.65);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg rounded-3">

                    <div class="d-flex justify-content-between align-items-center border-bottom px-4 py-3">
                        <h5 class="m-0 fw-bold">Select Lead</h5>
                        <a wire:click="$set('showModal', false)" class="cursor-pointer"><i
                                class="fs-4 fadeIn animated bx bx-x"></i></a>
                    </div>

                    <div class="p-4" style="max-height:70vh; overflow-y:auto;">
                        @if ($leads && count($leads) > 0)
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tourist</th>
                                        <th>Created at</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $item)
                                        <tr>
                                            <td class="px-3 py-2">
                                                <span class="fw-semibold">
                                                    <span style="color: {{ $item->type->color ?? 'black' }};">
                                                        <i class="bx bxs-circle font-13"></i>
                                                    </span>
                                                    {{ $item?->tourist?->primary_contact ?? 'NA' }}
                                                </span>
                                                <div class="small mt-1"><i class="bx bx-phone"></i>
                                                    {{ $item->contact ?? 'NA' }}</div>
                                                <div class="small">
                                                    <i class="bx bx-mail-send"></i>
                                                    <a
                                                        href="mailto:{{ $item->email ?? '' }}">{{ $item->email ?? 'NA' }}</a>
                                                </div>
                                            </td>

                                            <td class="px-3 py-2">
                                                <span class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                                </span>
                                            </td>

                                            <td class="px-3 py-2 text-end">
                                                <a wire:click="convertEstimate('{{ $item->uuid }}')"
                                                    class="btn btn-sm btn-primary px-3">
                                                    Select
                                                    <span style="cursor: pointer" wire:loading
                                                        wire:target="convertEstimate('{{ $item->uuid }}')">
                                                        <span class="spinner-border spinner-border-sm me-1"
                                                            role="status" aria-hidden="true"></span>
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center m-0">No leads found.</p>
                        @endif
                    </div>

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
