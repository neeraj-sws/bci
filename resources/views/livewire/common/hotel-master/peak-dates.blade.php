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
                                wire:model.defer="hotel_id">
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
                             <select id="room_category_id" class="form-select select2 @error('room_category_id') is-invalid @enderror" wire:model="room_category_id">
                                <option value="">Select Ocupancy</option>
                                @foreach ($roomCategoys as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('room_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" wire:model.defer="start_date">
                                @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker" wire:model.defer="end_date">
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <!-- Notes -->
                        <div class="form-check mb-3">
                            <input class="form-check-input"
                                type="checkbox"
                                wire:model.live="show_notes"
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
                                <textarea
                                    class="form-control @error('notes') is-invalid @enderror"
                                    wire:model.defer="notes"
                                    placeholder="Explain why this period is peak..."></textarea>

                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <!-- New Year -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model.defer="is_new_year"
                                id="is_new_year">
                            <label class="form-check-label" for="is_new_year">
                                Is New Year Peak
                            </label>
                        </div>
                        {{-- Occupancy --}}
                            @if (count($roomRatesData) == 0)
                                <a class="text-primary mb-2 cursor-pointer text-decoration-underline"
                                    wire:click='showModel'>Add Occupancy
                                    <i class="spinner-border spinner-border-sm" wire:loading.delay
                                        wire:target="showModel"></i>
                                </a>
                                @error('roomRatesData') <div class="text-danger">{{ $message }}</div> @enderror
                            @else
                                <div class="mt-2">
                                    <tr><a class="text-warning  cursor-pointer text-decoration-underline"
                                            wire:click='showModel'>Add Occupancy</a></tr>

                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-hover shadow-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Occupancy</th>
                                                    <th>Rate</th>
                                                    <th style="width: 100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($roomRatesData as $index => $data)
                                                    <tr>
                                                        <td>{{ $occupances[$data['ocupancy_id']] ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ \App\Helpers\SettingHelper::formatCurrency(
                                                                $data['rate'] ?? 0,
                                                                \App\Helpers\SettingHelper::getGenrealSettings('number_format'),
                                                            ) }}
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-info"
                                                                wire:click="editRoomRate({{ $index }})">
                                                                <i class="bx bx-edit text-dark"></i>
                                                                <i class="spinner-border spinner-border-sm"
                                                                    wire:loading.delay
                                                                    wire:target="editRoomRate({{ $index }})"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-danger"
                                                                wire:click="removeRoomRate({{ $index }})">
                                                                <i class="bx bx-trash text-dark"></i>
                                                                <i class="spinner-border spinner-border-sm"
                                                                    wire:loading.delay
                                                                    wire:target="removeRoomRate({{ $index }})"></i>
                                                            </a>
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
                                {{ $isEditing ? 'Update '.$pageTitle : 'Save '.$pageTitle }}
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
                                    <th>Title</th>
                                    <th>Hotel</th>
                                    <th>Duration</th>
                                    <th>New Year</th>
                                    <th>Status</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->hotel->name ?? '-' }}</td>
                                        <td>{{ $item->start_date }} → {{ $item->end_date }}</td>
                                        <td>
                                            {!! $item->is_new_year
                                                ? '<span class="badge bg-success">Yes</span>'
                                                : '<span class="badge bg-secondary">No</span>' !!}
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
    {{-- NEW DEV --}}
    <div class="modal @if ($showRoomRateModal) show @endif" tabindex="-1"
            style="opacity:1; background-color:#0606068c; display:@if ($showRoomRateModal) block @endif">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-4">
                    <form wire:submit.prevent="{{ $roomRateEdit ? 'editRoomRateStore' : 'addRoomRates' }}">
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label">Ocupancy <span class="text-danger">*</span></label>
                                <select id="ocupancy_id" class="form-select select2" wire:model="ocupancy_id">
                                    <option value="">Select Ocupancy</option>
                                    @foreach ($occupances as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('ocupancy_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Rate <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="rate"
                                    placeholder="exg . Ocupancy Charge">
                                @error('rate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary px-5">Save
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="{{ $roomRateEdit ? 'editRoomRateStore' : 'addRoomRates' }}"></i>
                            </button>
                            <button type="button" wire:click="resetRoomRateForm"
                                class="btn btn-sm btn-secondary">Close
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="resetRoomRateForm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{--  --}}
</div>
