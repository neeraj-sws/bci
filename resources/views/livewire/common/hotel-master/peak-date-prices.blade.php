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

                        <!-- Peak Date -->
                        <div class="mb-3">
                            <label class="form-label">Peak Date <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('peak_date_id') is-invalid @enderror"
                                wire:model.live="peak_date_id" id="peak_date_id">
                                <option value="">Select Peak Date</option>
                                @foreach ($peakDates as $peakDate)
                                    <option value="{{ $peakDate->id }}">
                                        {{ $peakDate->title }} - {{ $peakDate->hotel->name ?? '' }} - {{ $peakDate->roomCategory->title ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('peak_date_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (!$peak_date_id)
                            <div class="alert alert-info py-2 px-3 mb-3">
                                <i class="bx bx-info-circle"></i> Please select a Peak Date first
                            </div>
                        @elseif (empty($occupancies))
                            <div class="alert alert-warning py-2 px-3 mb-3">
                                <i class="bx bx-error-circle"></i> No occupancies configured for this peak date's room
                                category. Please add occupancies first.
                            </div>
                        @endif

                        <!-- Season -->
                        <div class="mb-3">
                            <label class="form-label">Season <span class="text-danger">*</span></label>
                            <select class="form-select @error('season_id') is-invalid @enderror"
                                wire:model.defer="season_id">
                                <option value="">Select Season</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->season_id }}">{{ $season->title }}</option>
                                @endforeach
                            </select>
                            @error('season_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row mb-3">
                            <div class="col-lg-6 col-sm-6">
                                <label class="form-label mb-1">Start Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" data-role="start"
                                    data-group="booking1" data-range="proper" wire:model="start_date">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <label class="form-label mb-1">End Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" data-role="end"
                                    data-group="booking1" data-range="proper" wire:model="end_date">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Occupancy Selection -->
                        <div class="mb-3">
                            <label class="form-label">Occupancy <span class="text-danger">*</span></label>
                            @if(!$peak_date_id)
                                <div class="alert alert-info py-2 px-3 mb-2">
                                    <i class="bx bx-info-circle"></i> Please select a Peak Date first
                                </div>
                            @elseif(empty($occupancies))
                                <div class="alert alert-warning py-2 px-3 mb-2">
                                    <i class="bx bx-error-circle"></i> No occupancies configured for this room category. Please add occupancies in Room Category first.
                                </div>
                            @endif
                            <select class="form-select select2" id="selected_occupancies"
                                wire:model.live="selected_occupancies" multiple
                                @if(!$peak_date_id || empty($occupancies)) disabled @endif>
                                @foreach ($occupancies as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('selected_occupancies')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                                            class="form-control form-control-sm @error('roomRatesData.' . $index . '.rate') is-invalid @enderror"
                                                            wire:model.defer="roomRatesData.{{ $index }}.rate"
                                                            placeholder="Enter weekday rate">
                                                        @error('roomRatesData.' . $index . '.rate')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01"
                                                            class="form-control form-control-sm @error('roomRatesData.' . $index . '.weekend_rate') is-invalid @enderror"
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

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled"
                                @if (!$peak_date_id || empty($occupancies)) disabled @endif>
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
                                @foreach ($roomCategories as $category)
                                    <option value="{{ $category->room_categoris_id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" wire:model.live="filter_season_id">
                                <option value="">All Seasons</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->season_id }}">{{ $season->title }}</option>
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
                                <input type="text" class="form-control form-control-sm ps-4" placeholder="Search..."
                                    wire:model.live="search">
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
                                    $groupedItems = $items->groupBy(function ($item) {
                                        return $item->peak_date_id . '-' . $item->season_id . '-' . $item->start_date . '-' . $item->end_date;
                                    });
                                @endphp

                                @forelse ($groupedItems as $key => $group)
                                    @php
                                        $firstItem = $group->first();
                                    @endphp
                                    <tr wire:key="group-{{ $key }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $firstItem->peakDate->title ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ $firstItem->peakDate->hotel->name ?? '-' }}</small>
                                        </td>
                                        <td>{{ $firstItem->season->title ?? '-' }}</td>
                                        <td>
                                            <small>{{ $firstItem->start_date }} → {{ $firstItem->end_date }}</small>
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
                                            <a href="javascript:void(0)" wire:click="edit({{ $firstItem->id }})" class="me-1" ><i
                                                    class="bx bx-edit fs-5"></i></a>
                                            <a href="javascript:void(0)" wire:click="confirmDelete({{ $firstItem->id }})"><i
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
