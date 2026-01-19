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

                        <div class="mb-3">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select class="form-select select2 @error('hotel_id') is-invalid @enderror" id="hotel_id"
                                wire:model="hotel_id">
                                <option value="">Select Hotel</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Category Title <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control text-capitalize @error('title') is-invalid @enderror"
                                wire:model.defer="title" placeholder="Room category title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hotel -->
                        <div class="mb-3">
                            <label class="form-label">Rate Type </label>
                            <select class="form-select select2 @error('rate_type') is-invalid @enderror" id="rate_type"
                                wire:model="rate_type">
                                <option value="">Select Type</option>
                                @foreach ($rateTypes as $value)
                                    <option value="{{ $value->id }}" @selected($value->id == $rate_type) >{{ $value->title }}</option>
                                @endforeach
                            </select>
                            @error('rate_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Occupancy Selection --}}
                        <div class="mb-3">
                            <label class="form-label">Occupancy <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="selected_occupancies"
                                wire:model.live="selected_occupancies" multiple>
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
                                                            class="form-control form-control-sm @error('roomRatesData.'.$index.'.rate') is-invalid @enderror"
                                                            wire:model.defer="roomRatesData.{{ $index }}.rate"
                                                            placeholder="Enter weekday rate">
                                                        @error('roomRatesData.'.$index.'.rate')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01"
                                                            class="form-control form-control-sm @error('roomRatesData.'.$index.'.weekend_rate') is-invalid @enderror"
                                                            wire:model.defer="roomRatesData.{{ $index }}.weekend_rate"
                                                            placeholder="Enter weekend rate">
                                                        @error('roomRatesData.'.$index.'.weekend_rate')
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
                        {{--  --}}
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
                                {{ $isEditing ? 'Update Category' : 'Save Category' }}
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
                                    <th width="60">#</th>
                                    <th>Hotel</th>
                                    <th>Category Title</th>
                                    <th>Rates</th>
                                    <th>Status</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item?->rommtCategoryHotel?->name }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if($item->occupancies && $item->occupancies->count() > 0)
                                                <small>
                                                    @foreach($item->occupancies as $occ)
                                                        <div class="mb-1">
                                                            <strong>{{ $occ->occupancy->title ?? 'N/A' }}:</strong>
                                                            Weekday: {{ \App\Helpers\SettingHelper::formatCurrency($occ->rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }},
                                                            Weekend: {{ \App\Helpers\SettingHelper::formatCurrency($occ->weekend_rate ?? 0, \App\Helpers\SettingHelper::getGenrealSettings('number_format')) }}
                                                        </div>
                                                    @endforeach
                                                </small>
                                            @else
                                                <small class="text-muted">No rates</small>
                                            @endif
                                        </td>
                                        <td>
                                            <input class="form-check-input" type="checkbox"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" wire:click="edit({{ $item->id }})"
                                                class="me-1" title="Edit">
                                                <i class="bx bx-edit text-dark fs-5"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $item->id }})" title="Delete">
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
                    </div>

                    @if ($items->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                {{ $items->total() }}
                            </small>
                            {{ $items->links('livewire::bootstrap') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
