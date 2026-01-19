<div class="container mt-sm-0 mt-3" id="amanity">
    <style>
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        height: auto;
        padding: 4px 6px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    .select2-container--default
    .select2-selection--multiple
    .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
    }
    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice {
        max-width: 100%;
        white-space: normal;
    }
    .select2-container--default
    .select2-selection--multiple
    .select2-search--inline
    .select2-search__field {
        height: 24px;
        margin: 2px;
    }
    </style>
    <div class="page-breadcrumb flex-wrap d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Card -->
        <div class="col-md-5">
            <div class="card">

                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <label for="title" class="form-label">Trip Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Trip name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror


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
                                    <input type="text" class="form-control datepicker" data-role="end" data-group="booking1"
       data-range="proper"
                                        wire:model="end_date">
                                    @error('end_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Select Quotations</label>
                                <select size="6" id="selectedQuotations" class="form-select select2" multiple
                                    wire:model="selectedQuotations">
                                    @foreach ($qutotions as $id => $label)
                                        <option value="{{ $id }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('selectedQuotations')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-group my-3">
                                    <label for="title" class="form-label">Status</label>
                                    <select id="filter_category" class="form-select" wire:model.live='status'
                                        placeholder="Select Category">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>



                        </div>


                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update changes' : 'Save changes' }}
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="{{ $isEditing ? 'update' : 'store' }}"></i>
                            </button>
                            <button type="button" wire:click="resetForm"
                                class="btn btn-secondary greygradientbtn">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="{{ route('common.trip-archive') }}" 
                    class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="lni lni-archive"></i>
                        <span class="d-none d-md-inline">Archive</span>
                    </a>
                    <div class="position-relative">
                        <input type="text"
                            class="form-control ps-5"
                            placeholder="Search..."
                            wire:model.live.debounce.300ms="search">
                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                            <i class="bx bx-search"></i>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>Trip Name</th>
                                    <th>Status</th>
                                    <th class="width80">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">{{ $index + 1 }}</td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item->name }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $index + 1 }}"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="align-middle py-1 text-center">
                                            <a class="me-2" href="{{ route('common.trip-view', $item->id) }}"
                                                title="Trip Tracker">
                                                <i class="lni lni-eye text-dark fs-5"></i>
                                            </a>
                                            <a href="javascript:void(0)" wire:click="edit({{ $item->id }})"
                                                title="Edit">
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
                                        <td colspan="6" class="text-center">No Trips found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($items->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                            <div class="text-muted small">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                {{ $items->total() }}
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
</div>