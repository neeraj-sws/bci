<div class="container mt-sm-0 mt-3">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title fs-24 fw-600 text-black">{{ $pageTitle }}</h6>
        </div>
    </div>

    <div class="row g-4">

        <!-- ================= FORM ================= -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

                        <!-- Hotel -->
                        <div class="mb-3">
                            <label class="form-label">Hotel <span class="text-danger">*</span></label>
                            <select class="form-select @error('hotel_id') is-invalid @enderror"
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

                        <!-- Peak Date -->
                        <div class="mb-3">
                            <label class="form-label">Peak Date (Optional)</label>
                            <select class="form-select @error('peak_date_id') is-invalid @enderror"
                                wire:model.defer="peak_date_id">
                                <option value="">No Peak Date</option>
                                @foreach ($peakDates as $pd)
                                    <option value="{{ $pd->id }}">
                                        {{ $pd->title }} ({{ $pd->start_date }} → {{ $pd->end_date }})
                                    </option>
                                @endforeach
                            </select>
                            @error('peak_date_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Supplement Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                wire:model.defer="title" placeholder="e.g. Gala Dinner">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                wire:model.defer="amount" placeholder="0.00">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mandatory -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="mandatory" @if ($mandatory) checked @endif wire:model.defer="mandatory">
                            <label class="form-check-label" for="mandatory">
                                Mandatory Supplement
                            </label>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update Supplement' : 'Save Supplement' }}
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

        <!-- ================= LIST ================= -->
        <div class="col-md-7">
            <div class="card">

                <!-- Search -->
                <div class="card-header d-flex justify-content-end">
                    <input type="text" class="form-control w-50" placeholder="Search supplements..."
                        wire:model.debounce.300ms="search">
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="lightgradient">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Hotel</th>
                                    <th>Amount</th>
                                    <th>Mandatory</th>
                                    <th>Status</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->hotel->name ?? '-' }}</td>
                                        <td>₹{{ number_format($item->amount, 2) }}</td>
                                        <td>
                                            @if ($item->mandatory)
                                                <span class="badge bg-danger">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="text-center">
                                            <a wire:click="edit({{ $item->id }})">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <a wire:click="confirmDelete({{ $item->id }})">
                                                <i class="bx bx-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            No Supplements Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($items->hasPages())
                        <div class="d-flex justify-content-between mt-3">
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
