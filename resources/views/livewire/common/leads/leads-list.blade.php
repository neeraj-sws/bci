<div class="mx-5 mt-sm-0 mt-3">

    {{-- <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
        <div class="position-relative">
            <input type="text" class="form-control ps-5" placeholder="Search {{ $pageTitle }}..."
                wire:model.live.debounce.300ms="search">
            <span class="position-absolute product-show translate-middle-y">
                <i class="bx bx-search"></i>
            </span>
        </div>

        <div class="position-relative col-md-4">
            <div wire:ignore>
                <select id="select-tags" multiple data-placeholder="Select tags">
                    @foreach ($tags as $id => $name)
                        <option value="{{ $name }}" @if (in_array($name, $searchTag ?? [])) selected @endif>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>



    </div> --}}

    <div class="page-header mb-4">
        <div class="d-flex flex-wrap align-items-end justify-content-between">

            <!-- LEFT SIDE: TITLE -->
            <div  style="width:10%;">
                <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600 mb-1">{{ $pageTitle }}</h6>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                    </ol>
                </nav>
            </div>

            <!-- RIGHT SIDE FILTERS -->
            <div class="d-flex flex-wrap align-items-end gap-3 justify-content-end" style="width:90%;">

                <div class="col-auto" style="min-width:17%;">
                    <div><label class="form-label mb-1">Tourists</label></div>
                    <!-- unchanged -->
                    <select id='tourist_id' class="form-select select2" wire:model="tourist_id">
                        <option value="">Select Lead Status</option>
                        @foreach ($tourists as $id => $name)
                            <option value="{{ $id }}" @if ($tourist_id == $id) selected @endif>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto" style="min-width:17%;">
                    <div><label class="form-label mb-1">Source</label></div>
                    <!-- unchanged -->
                    <select id='source_id' class="form-select select2" wire:model="source_id">
                        <option value="">Select Lead Status</option>
                        @foreach ($sources as $id => $name)
                            <option value="{{ $id }}" @if ($source_id == $id) selected @endif>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto" style="min-width:17%;">
                    <div><label class="form-label mb-1">Filter by date</label></div>
                    <!-- unchanged -->
                         <input class="form-control form-control-solid" placeholder="Pick Dates"
                        id="kt_daterangepicker_1" />
                </div>

                <div class="col-auto" style="min-width:18%;">
                    <div><label class="form-label mb-1">Tags</label></div>
                    <!-- unchanged -->
                    <div wire:ignore>
                        <select id="select-tags" multiple data-placeholder="Select tags">
                            @foreach ($tags as $id => $name)
                                <option wire:key='{{$id}}' value="{{ $name }}" @if (in_array($name, $searchTag ?? [])) selected @endif>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 col-auto">
                    <a wire:click='clearFilters' class="btn bluegradientbtn">Clear</a>
                </div>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm radius12 overflow-hidden">

        <div class="card-body d-flex justify-content-between align-items-center py-3">




            <ul class="nav nav-pills " role="tablist">

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(1)" class="nav-link @if ($statusFilter === 1) active @endif"
                        data-bs-toggle="pill" href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Lead / Inquiry ({{ $counts['prospect'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(1)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(3)" class="nav-link @if ($statusFilter === 3) active @endif"
                        data-bs-toggle="pill" href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Qualified Leads ({{ $counts['qualified'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(3)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(4)" class="nav-link @if ($statusFilter === 4) active @endif"
                        data-bs-toggle="pill" href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Qutoation ({{ $counts['proposal'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(4)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(5)" class="nav-link @if ($statusFilter === 5) active @endif"
                        data-bs-toggle="pill" href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Negotiations ({{ $counts['negotiation'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(5)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>


                <li class="nav-item " role="presentation">
                    <a wire:click="setStatusFilter(8)" class="nav-link @if ($statusFilter === 8) active @endif"
                        data-bs-toggle="pill" href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Proforma ({{ $counts['proforma'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(8)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(7)"
                        class="nav-link @if ($statusFilter === 7) active @endif" data-bs-toggle="pill"
                        href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Won ({{ $counts['won'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(7)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a wire:click="setStatusFilter(6)"
                        class="nav-link @if ($statusFilter === 6) active @endif" data-bs-toggle="pill"
                        href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Lost ({{ $counts['lost'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(6)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>



                <li class="nav-item " role="presentation">
                    <a wire:click="setStatusFilter(2)"
                        class="nav-link @if ($statusFilter === 2) active @endif" data-bs-toggle="pill"
                        href="#" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Unqualified ({{ $counts['unqualified'] }})</div>
                            <div wire:loading wire:target="setStatusFilter(2)"
                                class="ms-2 spinner-border spinner-border-sm text-white" role="status"></div>
                        </div>
                    </a>
                </li>

            </ul>

        @can('leads manage')
            <a href="{{ route($route . '.lead-create') }}" class="btn bluegradientbtn">
                <i class="bx bx-plus me-1"></i> Add New {{ $pageTitle }}

            </a>
        @endcan


        </div>

    </div>


    <!--NEW DEV -->
  {{--  <div class="card border-0 shadow-sm radius12 overflow-hidden">
        <div class="card-body d-flex justify-content-between align-items-center py-3">
            <div class=" d-flex gap-3">
                <div class="d-none col-md-3 mb-3">
                    <label class="form-label">Status</label></br>
                    <select id='status_id' class="form-select select2" wire:model="status_id">
                        <option value="">Select Lead Status</option>
                        @foreach ($status as $id => $name)
                            <option value="{{ $id }}" @if ($status_id == $id) selected @endif>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tourists</label>
                    <select id='tourist_id' class="form-select select2" wire:model="tourist_id">
                        <option value="">Select Lead Status</option>
                        @foreach ($tourists as $id => $name)
                            <option value="{{ $id }}" @if ($tourist_id == $id) selected @endif>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Source</label>
                    <select id='source_id' class="form-select select2" wire:model="source_id">
                        <option value="">Select Lead Status</option>
                        @foreach ($sources as $id => $name)
                            <option value="{{ $id }}" @if ($source_id == $id) selected @endif>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Filter by date</label>
                    <input class="form-control form-control-solid" placeholder="Pick Dates"
                        id="kt_daterangepicker_1" />
                </div>

            </div>
            <a wire:click='clearFilters' class="btn bluegradientbtn">Clear</a>
        </div>
    </div> --}}


    {{-- <div class="row">
         @foreach ($items as $index => $item)
         <div class="col-xxl-3 col-xl-4 col-md-6">
                        <div class="card kanban-card border mb-4 shadow ui-sortable-handle">
                             <div class="card-header ">
                                  <div class="d-block">

                                            <div class="d-flex align-items-center">
                                                <a href="{{ route($route . '.lead-view', $item->id) }}" class="avatar rounded-circle bg-soft-info flex-shrink-0">
                                                <span
                                        style="color: {{ $item->type->color ?? 'black' }};font-weight:bold"><i class="bx bxs-circle font-24"></i></span></a>
                                                <h6 class="fw-medium fs-14 mb-0"><a href="{{ route($route . '.lead-view', $item->id) }}">{{ $item->tourist->primary_contact ?? $item->tourist->name }}</a>
                                                </h6>
                                            </div>
                                        </div>
                                 </div>
                                    <div class="card-body">

                                        <div class="d-flex flex-column">
                                            <p class="text-default d-inline-flex align-items-center mb-2">
                                               <i class="lni lni-money-protection pe-1"></i>
                                                ${{ $item?->budget ?? 'N/A' }}
                                            </p>
                                            <p class="text-default d-inline-flex align-items-center mb-2">
                                                <i class="fadeIn animated bx bx-comment-detail pe-1"></i>
                                                {{ $item->email ?? 'NA' }}
                                            </p>
                                            <p class="text-default d-inline-flex align-items-center mb-2">
                                               <i class="lni lni-phone pe-1"></i>
                                                {{ $item->contact ?? 'NA' }}
                                            </p>
                                            <p class="text-default d-inline-flex align-items-center mb-2">
                                              <i class="fadeIn animated bx bx-map pe-1"></i>
                                                 {{ $item->tourist->address ?? 'NA' }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                    </div>
          @endforeach
         </div> --}}
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 tableminwidth">
                <thead class="lightgradient">
                    <tr>
                        <th width="2%" class="tableheadingcolor px-3 py-2">S.No</th>
                        <th width="20%" class="tableheadingcolor px-3 py-2">Tourist</th>
                        <th width="17%" class="tableheadingcolor px-3 py-2">Tags#</th>
                        <th class="tableheadingcolor px-3 py-2">Created at</th>
                        <th class="tableheadingcolor px-3 py-2">Last updated at</th>
                        @if (in_array($statusFilter, [4, 5]))
                            <th class="tableheadingcolor px-3 py-2">Quotation#</th>
                            <th class="tableheadingcolor px-3 py-2">Total</th>
                        @endif
                        <th width="10%" class="tableheadingcolor px-3 py-2">Stage</th>
                        <th width="10%" class="tableheadingcolor px-3 py-2">Source</th>
                                @can('leads manage')
                        <th class="tableheadingcolor px-3 py-2 width80">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $index => $item)

                        <tr class="table-bottom-border transition2" wire:key="{{ $item->id }}">
                            <td class="px-3 py-1"> <a href="{{ route($route . '.lead-view', $item->id) }}">
                                    {{ $items->firstItem() + $index }}</a></td>
                            <td class="px-3 py-1">
                                <span class="fw-500 text-dark">
                                    <span style="color: {{ $item->type->color ?? 'black' }}; font-weight: bold;">
                                        <i class="bx bxs-circle font-13"></i>
                                    </span>
                                    {{ $item->tourist->primary_contact ?? $item->tourist->name }}
                                </span>

                                <div class="fw-500 text-dark mt-1">
                                    <i class="bx bx-phone font-13"></i>
                                   {{ $item?->contact ? substr($item->contact, 0, 5).' '.substr($item->contact, 5) : 'NA' }}
                                </div>

                                <div class="fw-500 text-dark mt-1">
                                    <i class="bx bx-mail-send font-13"></i>
                                    <a href="mailto:{{ $item->email ?? 'NA' }}" class="text-dark">
                                        {{ $item->email ?? 'NA' }}
                                    </a>
                                </div>
                            </td>


                            <td class="px-3 py-1">
                                <a href="{{ route($route . '.lead-view', $item->id) }}">
                                    <span class="fw-500 text-dark">{{ $item?->tags ? str_replace(',', ', ', $item->tags) : 'NA' }} </span>
                                </a>
                            </td>

                            <td class="px-3 py-1">
                                <a href="{{ route($route . '.lead-view', $item->id) }}">
                                    <span class="fw-500 text-dark">
                                        {{ \Carbon\Carbon::parse($item->created_at ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                    </span>
                                </a>
                            </td>

                            <td class="px-3 py-1">
                                <a href="{{ route($route . '.lead-view', $item->id) }}">
                                    <span class="fw-500 text-dark">
                                        {{ \Carbon\Carbon::parse($item->updated_at ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}
                                        {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }}
                                    </span>
                                </a>
                            </td>

                            @if (in_array($statusFilter, [4, 5]))
                                <td class="px-3 py-1">
                                    <a href="{{ route($route . '.view-quotation', $item?->quotation->uuid) }}"
                                        class="fw-500 text-primary">
                                        #{{ $item?->quotation->quotation_no ?? 'NA' }}
                                    </a>
                                </td>

                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">
                                        {{ \App\Helpers\SettingHelper::formatCurrency($item?->quotation->amount ?? 0, 'comma_dot') }}</span>
                                </td>
                            @endif



                            <td class="px-3 py-1">
                                <a href="{{ route($route . '.lead-view', $item->id) }}">
                                    <span class="fw-500 badge "
                                        style="color:{{ optional($item->stage)->btn_text }};background:{{ optional($item->stage)->btn_bg }}">{{ $item->stage->name ?? 'NA' }}</span></a>
                            </td>

                            <td class="px-3 py-1">
                                <a href="{{ route($route . '.lead-view', $item->id) }}">
                                    <span class="fw-500 text-dark">{{ $item->source->name ?? 'NA' }}</span></a>
                            </td>
                                @can('leads manage')
                            <td class="text-center px-3 py-1 align-items-center gap-3">
                                @can('leads manage')
                                    @if (!in_array($item->stage_id, [7, 5, 4]))
                                        <a class="me-2" href="{{ route($route . '.lead-edit', $item->uuid) }}"
                                            title="Edit">
                                            <i class="bx bx-edit text-dark fs-5"></i>
                                        </a>
                                    @endif
                                @endcan
                                @can('leads manage')
                                    <a class="me-2" href="{{ route($route . '.lead-view', $item->id) }}"
                                        title="View">
                                        <i class="lni lni-eye  text-dark fs-5"></i>
                                    </a>
                                @endcan
                                @can('leads manage')
                                    @if (in_array($item->stage_id, [1]))
                                        <a class="me-2" href="javascript:void(0)"
                                            wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                            <i class="bx bx-trash text-danger fs-5"></i>
                                        </a>
                                    @endif
                                @endcan

                                {{-- NEW DEV 21-10-15  --}}
                                @if (in_array($item->stage_id, [1, 3]))
                                    @can('leads manage')
                                        <a class="me-2" href="javascript:void(0)"
                                            wire:click="confirmStageUpdate({{ $item->id }},2)"
                                            title="Convert to Unqualified">
                                            <i class="lni lni-thumbs-down text-danger"></i>
                                        </a>
                                    @endcan
                                @endif


                                @if ($item->stage_id == 1)
                                    @can('leads manage')
                                        <a class="me-2" href="javascript:void(0)"
                                            wire:click="makeQualified({{ $item->id }})" title="Convert to Qualified">
                                            <i class="lni lni-thumbs-up"></i>
                                        </a>
                                    @endcan
                                @endif



                                {{-- NEW DEV 21-10-15  --}}
                                @if (in_array($item->stage_id, [2, 6]))
                                    @can('leads manage')
                                        <a href="javascript:void(0)" wire:click="confirmStageUpdate({{ $item->id }})"
                                            title="Revert Stage">
                                            <i class="fadeIn animated bx bx-refresh fs-5"></i>
                                        </a>
                                    @endcan
                                @endif

                                @can('leads manage')
                                    @if (!in_array($item->stage_id, [1, 2, 6, 7]))
                                        @if (!$item->quotation)
                                            <a title="Convert to quotation"
                                                href="{{ route($route . '.add-quotation', ['lead_id' => $item->uuid ?? $item->id]) }}"><i
                                                    class="lni lni-book fs-5"></i></a>
                                        @endif
                                    @endif
                                @endcan
                            </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 darkgreytext">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                    <span>No Leads found. Click "Add New {{ $pageTitle }}" to create
                                        one.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
             <x-pagination :paginator="$items" />
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
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
</div>

@push('scripts')
    <script>
        function initTomSelect() {
            const selectEl = document.getElementById('select-tags');
            if (!selectEl) return;

            if (selectEl.tomselect) {
                selectEl.tomselect.destroy();
            }

            const tom = new TomSelect("#select-tags", {
                plugins: ['remove_button'],
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },

                onItemAdd(value, item) {
                    this.input.blur(); // remove focus
                    this.input.focus(); // restore focus if needed
                },

                render: {
                    dropdown: function(data, escape) {
                        let html =
                            '<div class="p-2 text-muted border-bottom bg-light">💡 Hint: Select or type to create new tags</div>';
                        html += '<div class="ts-dropdown-content"></div>';
                        return html;
                    },
                    option: function(data, escape) {
                        return '<div class="d-flex align-items-center justify-content-between">' +
                            '<span>' + escape(data.text) + '</span>' +
                            (data.date ? '<span class="text-muted small">' + escape(data.date) + '</span>' :
                                '') +
                            '</div>';
                    },
                    item: function(data, escape) {
                        return '<div>' + escape(data.text) + '</div>';
                    }
                }
            });

            selectEl.addEventListener('change', function() {
                const componentId = selectEl.closest('[wire\\:id]').getAttribute('wire:id');
                Livewire.find(componentId).set('searchTag', tom.getValue());
            });
        }

        document.addEventListener('livewire:init', initTomSelect);
         document.addEventListener('refresh', initTomSelect);
        document.addEventListener('livewire:navigated', initTomSelect);
    </script>
@endpush
