<div class="container mt-sm-0 mt-3" id="room-category-rates">
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
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('hotel_id') is-invalid @enderror" id="hotel_id"
                                wire:model="hotel_id">
                                <option value="">Select Hotel</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->hotels_id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Room Category <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none p-0"
                                    wire:click="openAddCategoryModal" @if (!$hotel_id) disabled @endif>
                                    <i class="bx bx-plus-circle"></i> Add Room Category
                                </button>
                            </div>
                            <select class="form-select select2 @error('room_category_id') is-invalid @enderror"
                                id="room_category_id" wire:model="room_category_id"
                                @if (!$hotel_id) disabled @endif>
                                <option value="">Select Category</option>
                                @forelse ($roomCategories as $category)
                                    <option value="{{ $category->room_categoris_id }}" @selected($category->room_categoris_id == $room_category_id)>
                                        {{ $category->title }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('room_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (!$hotel_id)
                                <small class="text-muted">Please select a hotel first</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Season <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('season_id') is-invalid @enderror" id="season_id"
                                wire:model="season_id">
                                <option value="">Select Season</option>
                                @forelse ($seasons as $season)
                                    <option value="{{ $season->seasons_id }}">{{ $season->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('season_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Occupancy <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="selected_occupancies"
                                wire:model.live="selected_occupancies" multiple>
                                @foreach ($occupancies as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('selected_occupancies')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (count($roomRatesData) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rates (must match occupancies)</label>
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
                                            @forelse ($roomRatesData as $index => $data)
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
                                            @empty
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @error('roomRatesData')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mt-2 mb-3">
                            <label class="form-label">Status</label>
                            <p class="text-muted small mb-0">Status is inherited from Room Category.</p>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update Rates' : 'Save Rates' }}
                                <i class="spinner-border spinner-border-sm ms-1" wire:loading wire:target="save"></i>
                            </button>

                            <button type="button" wire:click="resetForm" class="btn btn-secondary greygradientbtn">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select class="form-select" wire:model.live="filter_hotel_id">
                                <option value="">Filter by Hotel</option>
                                @forelse ($hotels as $hotel)
                                    <option value="{{ $hotel->hotels_id }}">{{ $hotel->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" wire:model.live="filter_room_category_id"
                                @if (!$filter_hotel_id && !count($filterRoomCategories)) disabled @endif>
                                <option value="">Filter by Category</option>
                                @if ($filter_hotel_id && count($filterRoomCategories) > 0)
                                    @forelse ($filterRoomCategories as $category)
                                        <option value="{{ $category['room_categoris_id'] }}">{{ $category['title'] }}
                                        </option>
                                    @empty
                                    @endforelse
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" wire:model.live="filter_season_id">
                                <option value="">Filter by Season</option>
                                @forelse ($seasons as $season)
                                    <option value="{{ $season->seasons_id }}">{{ $season->name }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-secondary w-100 p-2"
                                wire:click="clearFilters" title="Clear All Filters">
                                <i class="bx bx-x fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th width="60">#</th>
                                    <th wire:click="sortby('room_category_id')" style="cursor: pointer;">Room Category
                                        @if ($sortBy === 'room_category_id')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortby('season_id')" style="cursor: pointer;">Season
                                        @if ($sortBy === 'season_id')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th>Rates</th>
                                    <th>Status</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rateSets as $index => $set)
                                    @php
                                        $key = $set->room_category_id . '-' . $set->season_id;
                                        $rates = $rateDetails[$key] ?? collect();
                                    @endphp
                                    <tr>
                                        <td>{{ $rateSets->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $set->roomCategory->title ?? 'N/A' }}</strong><br>
                                            <small class="text-muted"> <i class="bx bx-door-open"
                                                    style="font-size: 10px;"></i>
                                                {{ $set->roomCategory->rommtCategoryHotel->name ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @if ($set?->season?->name)
                                                <div class="text-center">
                                                    <p class="mb-1"> {{ $set->season->name }}</p>
                                                    <span class="small">{{ $set->season->start_date }}</span> →
                                                    <span class="small">{{ $set->season->end_date }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rates->isNotEmpty())
                                                <small>
                                                    @foreach ($rates as $rate)
                                                        <div class="mb-1">
                                                            <strong>{{ $rate->occupancy->title ?? 'N/A' }}:</strong>
                                                            <span class="text-nowrap">Weekday:
                                                                {{ \App\Helpers\SettingHelper::formatCurrency($rate->rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }},</span>
                                                            <span class="text-nowrap">Weekend:
                                                                {{ \App\Helpers\SettingHelper::formatCurrency($rate->weekend_rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }}</span>
                                                        </div>
                                                    @endforeach
                                                </small>
                                            @else
                                                <small class="text-muted">No rates</small>
                                            @endif
                                        </td>
                                        <td class="text-center" id="{{ $index }}">
                                            <input class="form-check-input" type="checkbox" id="{{ $index }}"
                                                wire:change="toggleStatus({{ $set->roomCategory->room_categoris_id }})"
                                                @checked($set->roomCategory->status)>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)"
                                                wire:click="edit({{ $set->room_category_id }}, {{ $set->season_id ?? 'null' }})"
                                                class="me-2" title="Edit">
                                                <i class="bx bx-edit text-dark fs-5"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                wire:click="deleteRates({{ $set->room_category_id }}, {{ $set->season_id ?? 'null' }})"
                                                title="Delete">
                                                <i class="bx bx-trash text-danger fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No {{ $pageTitle }} found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :paginator="$rateSets" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Room Category Modal -->
    <div class="modal fade @if ($showAddCategoryModal) show @endif" id="addCategoryModal" tabindex="-1"
        style="@if ($showAddCategoryModal) display: block; @endif"
        @if ($showAddCategoryModal) aria-modal="true" role="dialog" @endif>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Room Category</h5>
                    <button type="button" class="btn-close" wire:click="closeAddCategoryModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveNewCategory">
                        <div class="mb-3">
                            <label class="form-label">Category Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('newCategoryTitle') is-invalid @enderror"
                                wire:model.defer="newCategoryTitle" placeholder="e.g., Deluxe Room">
                            @error('newCategoryTitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rate Type </label>
                            <select class="form-select @error('newCategoryRateType') is-invalid @enderror"
                                wire:model.defer="newCategoryRateType">
                                <option value="">Select Type</option>
                                @forelse ($rateTypes as $rateType)
                                    <option value="{{ $rateType->rate_type_id }}">{{ $rateType->title }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('newCategoryRateType')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.defer="newCategoryStatus">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" wire:click="closeAddCategoryModal">
                                Cancel
                            </button>
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled"
                                wire:target="saveNewCategory">
                                <span wire:loading.remove wire:target="saveNewCategory">Create Category</span>
                                <span wire:loading wire:target="saveNewCategory">
                                    <i class="spinner-border spinner-border-sm"></i> Creating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if ($showAddCategoryModal)
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
