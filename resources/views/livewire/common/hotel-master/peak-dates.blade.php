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
                                Room Categories <span class="text-danger">*</span>
                            </label>
                            <select id="selected_room_categories"
                                class="form-select select2 @error('selected_room_categories') is-invalid @enderror"
                                wire:model.live="selected_room_categories" multiple
                                @if(!$hotel_id) disabled @endif>
                                <option value="">Select Room Categories</option>
                                @foreach ($roomCategoys as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @if(!$hotel_id)
                                <small class="text-muted d-block mt-2"><i class="bx bx-info-circle"></i> Please select a Hotel first</small>
                            @endif
                            @error('selected_room_categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

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
                                    <th>Room Category</th>
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
                                        <td>{{ $item->roomCategory->title ?? '-' }}</td>
                                        <td>
                                            <input type="checkbox" class="form-check-input"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" wire:click="edit({{ $item->id }})"><i class="bx bx-edit fs-5"></i></a>
                                            <a href="javascript:void(0)" wire:click="confirmDelete({{ $item->id }})"><i
                                                    class="bx bx-trash text-danger fs-5"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Peak Dates found.</td>
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
