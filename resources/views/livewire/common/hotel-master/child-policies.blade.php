<div class="mx-5 mt-sm-0 mt-3">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <h6 class="breadcrumb-title fs-24 fw-600 text-black">{{ $pageTitle }}</h6>
    </div>

    <div class="row g-4">

        <!-- ================= FORM ================= -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

                        <!-- Hotel -->
                        <div class="mb-3">
                            <label class="form-label">
                                Hotel <span class="text-danger">*</span>
                            </label>
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
                                    <option value="{{ $id }}" @selected($room_category_id == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($is_peak_date)
                            <div class="mb-3">
                                <label class="form-label">
                                    Peak Date <span class="text-danger">*</span>
                                </label>
                                <select id="peak_date_id"
                                    class="form-select select2 @error('peak_date_id') is-invalid @enderror"
                                    wire:model="peak_date_id">
                                    <option value="">Select Peak Date</option>
                                    @foreach ($peakDates as $id => $title)
                                        <option value="{{ $id }}" @selected($peak_date_id == $id)>
                                            {{ $title }}</option>
                                    @endforeach
                                </select>

                                @error('peak_date_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('is_peak_date') is-invalid @enderror"
                                id="is_peak_date" wire:model.live="is_peak_date">
                            <label class="form-check-label" for="is_peak_date">
                                Peak Date
                            </label>

                            @error('is_peak_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Free Child Age -->
                        <div class="mb-3">
                            <label class="form-label">
                                Free Child Age (Years) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('free_child_age') is-invalid @enderror"
                                wire:model.defer="free_child_age" placeholder="e.g. 6-12 Years">
                            @error('free_child_age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Child With Bed -->
                        <div class="mb-3">
                            <label class="form-label">
                                Child With Bed Rate <span class="text-danger">*</span>
                            </label>
                            <input type="number" onfocus="Number(this.value) <= 0 && this.select()"
                                class="form-control text-end @error('child_with_bed_rate') is-invalid @enderror"
                                wire:model.defer="child_with_bed_rate" placeholder="Amount">
                            @error('child_with_bed_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Child Without Bed -->
                        <div class="mb-3">
                            <label class="form-label">
                                Child Without Bed Rate
                            </label>
                            <input type="number" onfocus="Number(this.value) <= 0 && this.select()"
                                class="form-control text-end @error('child_without_bed_rate') is-invalid @enderror"
                                wire:model.defer="child_without_bed_rate" placeholder="Amount">
                            @error('child_without_bed_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                {{ $isEditing ? 'Update Policy' : 'Save Policy' }}
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
                    <input type="text" class="form-control w-50" placeholder="Search by hotel..."
                        wire:model.debounce.300ms="search">
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="lightgradient">
                                <tr>
                                    <th>#</th>
                                    <th>Hotel</th>
                                    <th>Room Category</th>
                                    <th>Peak Date</th>
                                    <th>Free Age</th>
                                    <th>With Bed</th>
                                    <th>Without Bed</th>
                                    <th>Status</th>
                                    <th width="80">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $items->firstItem() + $index }}</td>
                                        <td>{{ $item->hotel->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $item->roomCategory->title ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->peakDate)
                                                <span class="badge bg-warning text-dark">
                                                    {{ $item->peakDate->title }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->free_child_age }} </td>
                                        <td>₹{{ number_format((float) $item?->child_with_bed_rate, 2) }}</td>
                                        <td>₹{{ number_format((float) $item?->child_without_bed_rate, 2) }}</td>
                                        <td>
                                            <input type="checkbox" class="form-check-input"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="text-center">
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
                                        <td colspan="9" class="text-center">
                                            No Child Policies Found
                                        </td>
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
