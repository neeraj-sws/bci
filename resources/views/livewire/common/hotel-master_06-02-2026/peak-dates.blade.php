<div class="container mt-sm-0 mt-3" id="amanity">

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
                                Room Category <span class="text-danger">*</span>
                            </label>
                            <select id="room_category_id"
                                class="form-select select2 @error('room_category_id') is-invalid @enderror"
                                wire:model.live="room_category_id">
                                <option value="">Select Room Category</option>
                                @foreach ($roomCategoys as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('room_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row my-3">
                            <div class="col-lg-6 col-sm-6">
                                <label class="form-label mb-1">Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control datepicker" data-role="start"
                                    data-group="booking1" data-range="proper" wire:model="start_date">
                                @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <label class="form-label mb-1">End Date <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control datepicker" data-role="end"
                                    data-group="booking1" data-range="proper" wire:model="end_date">
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <!-- Notes -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model.live="show_notes"
                                id="show_notes">
                            <label class="form-check-label" for="show_notes">
                                Show Notes
                            </label>
                        </div>
                        @if ($show_notes)
                            <div class="mb-3">
                                <label class="form-label">
                                    Notes <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" wire:model.defer="notes"
                                    placeholder="Explain why this period is peak..."></textarea>

                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        {{-- Occupancy Selection --}}
                        <div class="mb-3">
                            <label class="form-label">Occupancy <span class="text-danger">*</span></label>
                            @if(!$room_category_id)
                                <div class="alert alert-info py-2 px-3 mb-2">
                                    <i class="bx bx-info-circle"></i> Please select a Room Category first
                                </div>
                            @elseif(empty($occupances))
                                <div class="alert alert-warning py-2 px-3 mb-2">
                                    <i class="bx bx-error-circle"></i> No occupancies configured for this room category. Please add occupancies in Room Category first.
                                </div>
                            @endif
                            <select class="form-select select2" id="selected_occupancies"
                                wire:model.live="selected_occupancies" multiple
                                @if(!$room_category_id || empty($occupances)) disabled @endif>
                                @foreach ($occupances as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('selected_occupancies')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dynamic Rate Inputs --}}
                        @if (count($roomRatesData) > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Rates for Selected Occupancies</label>
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
                                                        <strong>{{ $occupances[$data['ocupancy_id']] ?? 'N/A' }}</strong>
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
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
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

                <!-- Search -->
                <div class="card-header d-flex justify-content-end">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Search..."
                            wire:model.live="search">
                        <span class="position-absolute product-show translate-middle-y">
                            <i class="bx bx-search"></i>
                        </span>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th>#</th>
                                    <th wire:click="sortby('title')" style="cursor: pointer;" >Title
                                         @if ($sortBy === 'title')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th>Hotel</th>
                                    <th>Duration</th>
                                    <th>Rates</th>
                                    <th>Status</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{  $items->firstItem() + $index }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->hotel->name ?? '-' }}</td>
                                        <td>{{ $item->start_date }} → {{ $item->end_date }}</td>
                                        <td>
                                            @if ($item->occupancies && $item->occupancies->count() > 0)
                                                <small>
                                                    @foreach ($item->occupancies as $occ)
                                                        <div class="mb-1">
                                                            <strong>{{ $occ->occupancy->title ?? 'N/A' }}:</strong>
                                                            Weekday:
                                                            {{ \App\Helpers\SettingHelper::formatCurrency($occ->rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }},
                                                            Weekend:
                                                            {{ \App\Helpers\SettingHelper::formatCurrency($occ->weekend_rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }}
                                                        </div>
                                                    @endforeach
                                                </small>
                                            @else
                                                <small class="text-muted">No rates</small>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="text-center">
                                            <a wire:click="edit({{ $item->id }})"><i class="bx bx-edit"></i></a>
                                            <a wire:click="confirmDelete({{ $item->id }})"><i
                                                    class="bx bx-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No Peak Dates found.</td>
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
