<div>
    <div class="container  mt-sm-0 mt-3 pb-4" style="max-width: 1400px; margin: 0 auto;">
        <div class="dashboard-header pb-0 mb-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600 mb-0">Dashboard</h6>
            <div class="text-end text-muted small">
                <p class="mb-0">Last updated: <span
                        id="current-date">{{ Carbon\Carbon::now()->format('D, d M Y') }}</span></p>
            </div>
        </div>


        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2">
            <div class="col">
                
                <!-- Legend -->
                <div class="d-flex align-items-center my-3">
                    <div class="me-3 d-flex align-items-center">
                        <div class=" rounded-circle me-1" style="width: 15px; height: 15px;background:#d1e7dd"></div>
                        <small>Today's Follow-Up</small>
                    </div>
                    <div class="me-3 d-flex align-items-center">
                        <div class=" rounded-circle me-1" style="width: 15px; height: 15px;background:#cff4fc"></div>
                        <small>Upcoming Follow-Up</small>
                    </div>
                    <div class="me-3 d-flex align-items-center">
                        <div class=" rounded-circle me-1" style="width: 15px; height: 15px;background:#f8d7da"></div>
                        <small>Missed Follow-Up</small>
                    </div>
                </div>


                <div class="card radius-10 ">
                    <div class="card-body" style="position: relative;">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Follow-Up Queue</p>
                                <h5 class="my-2">{{ count($followUps) }}</h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer"
                                    data-bs-toggle="dropdown"> <i class="bx bx-dots-horizontal-rounded font-22"></i>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>
                    
        

          <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2">Follow-Up Date</th>
                                    <th class="px-3 py-2 text-center">Lead#</th>
                                    <th class="px-3 py-2 text-center">Tourist#</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($followUps as $index => $item)
                                    @php
                                        $date = Carbon\Carbon::parse($item->followup_date);

                                        $rowClass = $date->isToday()
                                            ? 'table-success'
                                            : ($date->isFuture()
                                                ? 'table-info'
                                                : 'table-danger');
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="px-3 py-2 fw-semibold">
                                            {{ $item?->followup_date ? Carbon\Carbon::parse($item->followup_date)->format('d M, Y') : '' }}
                                            {{ $item?->followup_time ? Carbon\Carbon::parse($item->followup_time)->format('h:i A') : '' }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <a href="{{ $item?->lead?->id ? route('common.lead-view', $item->lead->id) : '#' }}"
                                                class="fw-500 text-primary">
                                                #{{ $item?->lead?->id ?? '' }}
                                            </a>
                                        </td>

                                        <td class="px-3 py-2 text-center">
                                            <span>{{ $item?->lead?->tourist?->primary_contact . ' - ' . $item?->lead?->tourist?->contact_phone ?? '' }}</span>
                                        </td>

                                    </tr>
                                @empty

                                    <tr>
                                        <td colspan="12" class="text-center py-4 darkgreytext">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                                <span>No data found.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
           <div class="col">
               
                               <!-- Legend -->
                <div class="d-flex align-items-center my-3">
                    <div class="me-3 d-flex align-items-center">
                        <div class=" rounded-circle me-1" style="width: 15px; height: 15px;background:#cff4fc"></div>
                        <small>Accepted Quotation</small>
                    </div>
                    <div class="me-3 d-flex align-items-center">
                        <div class=" rounded-circle me-1" style="width: 15px; height: 15px;background:#fff3cd"></div>
                        <small>Sent Quotation</small>
                    </div>
                    <div class="me-3 d-flex align-items-center">
                        <div class=" rounded-circle me-1" style="width: 15px; height: 15px;background:#eaeaea"></div>
                        <small>Draft Quotation</small>
                    </div>
                </div>
                
                <div class="card radius-10 ">
                    <div class="card-body" style="position: relative;">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total Quotation</p>
                                <h5 class="my-2">
                                    {{ \App\Helpers\SettingHelper::formatCurrency($qutoationSum ?? 0, 'comma_dot') }}
                                </h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer"
                                    data-bs-toggle="dropdown"> <i class="bx bx-dots-horizontal-rounded font-22"></i>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2">Qutoation#</th>
                                      <th class="px-3 py-2 text-center">Tourist#</th>
                                    <th class="px-3 py-2 text-end">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($qutoations as $index => $item)
                                        @php
                                            $status = $item->status ?? 0;
                                    
                                            $rowClass = match ($status) {
                                                2 => 'table-info',
                                                1 => 'table-warning',
                                                default => 'table-light',
                                            };
                                        @endphp
                                    <tr class="{{ $rowClass }}" >

                                        <td class="px-3 py-2 text-start">
                                            <a href="{{ $item?->id ? route('common.view-quotation', $item->uuid) : '#' }}"
                                                class="fw-500 text-primary">
                                                #{{ $item?->quotation_no ?? '' }}
                                        </td>
                                        
                                        <td class="px-3 py-2 text-center">
                                            <span>{{ $item?->tourist?->primary_contact . ' - ' . $item?->tourist?->contact_phone ?? '' }}</span>
                                        </td>

                                        <td class="px-3 py-2 fw-semibold text-end">
                                            {{ $item?->amount ? \App\Helpers\SettingHelper::formatCurrency($item->amount, 'comma_dot') : '0' }}
                                        </td>
                                    </tr>
                                @empty

                                    <tr>
                                        <td colspan="12" class="text-center py-4 darkgreytext">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                                <span>No data found.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- <div class="col">
                <div class="card radius-10 ">
                    <div class="card-body" style="position: relative;">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total Proforma</p>
                                <h5 class="my-2">
                                    {{ \App\Helpers\SettingHelper::formatCurrency($proformaSum ?? 0, 'comma_dot') }}
                                </h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer"
                                    data-bs-toggle="dropdown"> <i class="bx bx-dots-horizontal-rounded font-22"></i>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2">Proforma#</th>
                                    <th class="px-3 py-2 text-end">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($proformas as $index => $item)
                                    <tr>

                                        <td class="px-3 py-2 text-start">
                                            <a href="{{ $item?->id ? route('common.view-proformainvoice', $item->uuid) : '#' }}"
                                                class="fw-500 text-primary">
                                                #{{ $item?->proforma_invoice_no ?? '' }}
                                        </td>

                                        <td class="px-3 py-2 fw-semibold text-end">
                                            {{ $item?->amount ? \App\Helpers\SettingHelper::formatCurrency($item->amount, 'comma_dot') : '0' }}
                                        </td>
                                    </tr>
                                @empty

                                    <tr>
                                        <td colspan="12" class="text-center py-4 darkgreytext">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                                <span>No data found.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col">
                <div class="card radius-10 ">
                    <div class="card-body" style="position: relative;">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0">Total Invoice</p>
                                <h5 class="my-2">
                                    {{ \App\Helpers\SettingHelper::formatCurrency($invoicesSum ?? 0, 'comma_dot') }}
                                </h5>
                            </div>
                            <div class="dropdown ms-auto">
                                <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer"
                                    data-bs-toggle="dropdown"> <i class="bx bx-dots-horizontal-rounded font-22"></i>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>


                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3 py-2">Invoice#</th>
                                    <th class="px-3 py-2 text-end">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($invoices as $index => $item)
                                    <tr>

                                        <td class="px-3 py-2 text-start">
                                            <a href="{{ $item?->id ? route('common.view-invoice', $item->uuid) : '#' }}"
                                                class="fw-500 text-primary">
                                                #{{ $item?->invoice_no ?? '' }}
                                        </td>

                                        <td class="px-3 py-2 fw-semibold text-end">
                                            {{ $item?->amount ? \App\Helpers\SettingHelper::formatCurrency($item->amount, 'comma_dot') : '0' }}
                                        </td>
                                    </tr>
                                @empty

                                    <tr>
                                        <td colspan="12" class="text-center py-4 darkgreytext">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                                <span>No data found.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div> --}}

        </div>

    </div>
</div>
