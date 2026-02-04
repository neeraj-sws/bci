<div class="container mt-sm-0 mt-3" id="peak-date-prices">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title fs-24 fw-600 text-black">{{ $pageTitle }}</h6>
            <nav>
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Peak Date Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                wire:model.defer="title" placeholder="e.g. Christmas Peak">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Hotel -->
                        <div class="mb-3">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select id="hotel_id" class="form-select select2 @error('hotel_id') is-invalid @enderror"
                                wire:model.live="hotel_id">
                                <option value="">Select Hotel</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Room Categories <span class="text-danger">*</span>
                            </label>
                            <select id="selected_room_categories"
                                class="form-select select2 @error('selected_room_categories') is-invalid @enderror"
                                wire:model.live="selected_room_categories"
                                @if (!$hotel_id) disabled @endif>
                                <option value="">Select Room Categories</option>
                                @foreach ($roomCategories as $id => $name)
                                    <option value="{{ $id }}">
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                            @if (!$hotel_id)
                                <small class="text-muted d-block mt-2"><i class="bx bx-info-circle"></i> Please select a
                                    Hotel first</small>
                            @endif
                            @error('selected_room_categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row mb-3">
                            @if ($lowestStartDate && $highestEndDate)
                                <div class="col-12 mb-2">
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle"></i>
                                        Season will be auto-detected based on selected date range.
                                        Valid range: {{ $lowestStartDate }} to {{ $highestEndDate }}
                                    </small>
                                </div>
                            @endif
                            <div class="col-lg-6 col-sm-6">
                                <label class="form-label mb-1">Start Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" data-role="start"
                                    data-group="booking1" data-range="proper" data-start-from="{{ $lowestStartDate }}"
                                    wire:model.live="start_date"
                                    wire:key="start-date-{{ $lowestStartDate }}-{{ $highestEndDate }}"
                                    @if (!$selected_room_categories) disabled @endif>
                                @error('start_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <label class="form-label mb-1">End Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" data-role="end"
                                    data-group="booking1" data-range="proper" data-start-from="{{ $lowestStartDate }}"
                                    data-end-to="{{ $highestEndDate }}" wire:model.live="end_date"
                                    wire:key="end-date-{{ $lowestStartDate }}-{{ $highestEndDate }}"
                                    @if (!$selected_room_categories) disabled @endif>
                                @error('end_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dynamic Rate Inputs -->
                        @if (count($roomRatesData) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rates for Occupancies</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Occupancy</th>
                                                <th>Weekday Rate <span class="text-danger">*</span></th>
                                                <th>Weekend Rate <span class="text-danger">*</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roomRatesData as $index => $data)
                                                <tr>
                                                    <td class="align-middle">
                                                        <strong>{{ $occupancies[$data['occupancy_id']] ?? 'N/A' }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01"
                                                            onfocus="Number(this.value) <= 0 && this.select()"
                                                            class="form-control form-control-sm text-end @error('roomRatesData.' . $index . '.rate') is-invalid @enderror"
                                                            wire:model.defer="roomRatesData.{{ $index }}.rate"
                                                            placeholder="Enter weekday rate">
                                                        @error('roomRatesData.' . $index . '.rate')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01"
                                                            onfocus="Number(this.value) <= 0 && this.select()"
                                                            class="form-control form-control-sm text-end @error('roomRatesData.' . $index . '.weekend_rate') is-invalid @enderror"
                                                            wire:model.defer="roomRatesData.{{ $index }}.weekend_rate"
                                                            placeholder="Enter weekend rate">
                                                        @error('roomRatesData.' . $index . '.weekend_rate')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Status -->
                        <div class="mt-2 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>


                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled"
                                @if (!$peak_date_id && empty($roomRatesData)) disabled @endif>
                                {{ $isEditing ? 'Update ' . $pageTitle : 'Save ' . $pageTitle }}
                                <i class="spinner-border spinner-border-sm ms-1" wire:loading
                                    wire:target="{{ $isEditing ? 'update' : 'store' }}"></i>
                            </button>

                            <button type="button" wire:click="resetForm" class="btn btn-secondary greygradientbtn">
                                Reset
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- LIST -->
        <div class="col-md-7">
            <div class="card">

                <!-- Filters & Search -->
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" wire:model.live="filter_hotel_id">
                                <option value="">All Hotels</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->hotels_id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" wire:model.live="filter_room_category_id">
                                <option value="">All Room Categories</option>
                                @foreach ($roomCategories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" wire:model.live="filter_season_id">
                                <option value="">All Seasons</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->season_id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                                wire:click="clearFilters">
                                Clear Filters
                            </button>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm ps-4"
                                    placeholder="Search..." wire:model.live="search">
                                <span class="position-absolute product-show translate-middle-y">
                                    <i class="bx bx-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th>#</th>
                                    <th>Peak Date</th>
                                    <th>Season</th>
                                    <th>Date Range</th>
                                    <th>Occupancy & Rates</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    // Group by peak_date_id only since season/dates are now in peak_dates table
                                    $groupedItems = $items->groupBy('peak_date_id');
                                @endphp

                                @forelse ($groupedItems as $key => $group)
                                    @php
                                        $firstItem = $group->first();
                                    @endphp
                                    <tr wire:key="group-{{ $key }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $firstItem->peakDate->title ?? '-' }}</strong><br>
                                            <small
                                                class="text-muted">{{ $firstItem->peakDate->hotel->name ?? '-' }}</small><br>
                                            <small class="text-muted"><i class="bx bx-door-open"
                                                    style="font-size: 10px;"></i>
                                                {{ $firstItem->peakDate->roomCategory->title ?? '-' }}</small>
                                        </td>
                                        <td>{{ $firstItem->peakDate->season->title ?? '-' }}</td>
                                        <td>
                                            <small>{{ $firstItem->peakDate->start_date ?? '-' }} →
                                                {{ $firstItem->peakDate->end_date ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @foreach ($group as $item)
                                                <div class="mb-1">
                                                    <small>
                                                        <strong>{{ $item->occupancy->title ?? 'N/A' }}:</strong>
                                                        Weekday:
                                                        {{ \App\Helpers\SettingHelper::formatCurrency($item->rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }},
                                                        Weekend:
                                                        {{ \App\Helpers\SettingHelper::formatCurrency($item->weekend_rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" wire:click="edit({{ $firstItem->id }})"
                                                class="me-1"><i class="bx bx-edit fs-5"></i></a>
                                            <a href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $firstItem->id }})"><i
                                                    class="bx bx-trash text-danger fs-5"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Peak Date Prices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :paginator="$items" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
