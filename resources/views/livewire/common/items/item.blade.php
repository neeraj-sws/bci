<div class="mx-5 mt-sm-0 mt-3" id="amanity">

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
        @can('items manage')
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="title" class="form-label">Name</label>
                                <input type="text" placeholder="Item Name"
                                    class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">SKU</label>
                                <input type="text" placeholder="SKU"
                                    class="form-control @error('sku') is-invalid @enderror" wire:model="sku">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Rate <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Rate"
                                    class="form-control @error('rate') is-invalid @enderror" wire:model="rate">
                                @error('rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">UNIT</label>
                                <input type="text" placeholder="UNIT"
                                    class="form-control @error('unit') is-invalid @enderror" wire:model="unit">
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Tax <span
                                            class="text-danger">*</span></label>
                                    <select id="tax_id" class="form-select select2" wire:model="tax_id"
                                        placeholder="Tax">
                                        <option value=""></option>
                                        @foreach ($taxes as $id => $tax_name)
                                            <option value="{{ $id }}"
                                                @if ($tax_id === $id) selected @endif>{{ $tax_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tax_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Type <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('type') is-invalid @enderror" type="radio"
                                        name="type" id="type_service" value="1" wire:model="type">
                                    <label class="form-check-label" for="type_service">Service</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('type') is-invalid @enderror" type="radio"
                                        name="type" id="type_goods" value="2" wire:model="type">
                                    <label class="form-check-label" for="type_goods">Goods</label>
                                </div>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Status</label>
                                <select id="filter_category" class="form-select" wire:model.live='status'
                                    placeholder="Select Category">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" placeholder="Item Description" class="form-control @error('description') is-invalid @enderror"
                                    wire:model="description"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
        @endcan

        <!-- Table Card -->
        <div class="@can('items manage') col-md-7 @else col-md-12 @endcan">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-xxl-nowrap flex-wrap">
                    <div class="btn-group p-2 rounded border mb-xxl-0 mb-2" role="group">
                        <button wire:click="setTab('active')"
                            class="btn btn-sm {{ $tab === 'active' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-start">
                            Active Items
                            <span class="badge bg-primary ms-2">{{ $activeCount }}</span>
                        </button>
                        <button wire:click="setTab('inactive')"
                            class="btn btn-sm {{ $tab === 'inactive' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-end">
                            Inactive Items
                            <span class="badge bg-primary ms-2">{{ $inactiveCount }}</span>
                        </button>
                    </div>

                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Search..."
                            wire:model.live.debounce.300ms="search">
                        <span class="position-absolute product-show translate-middle-y">
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
                                    <th wire:click="shortby('name')" style="cursor: pointer;">Name
                                        @if ($sortBy === 'name')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th>Type</th>
                                    <th wire:click="shortby('rate')" style="cursor: pointer;">Rate
                                        @if ($sortBy === 'rate')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
        @can('items manage')
                                    <th>Status</th>

                                    @endcan
        @can('items manage')
                                    <th class="width60">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">{{ $index + 1 }}</td>
                                        <td class="align-middle py-1">
                                            <span>
                                                {{ $item->name }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span>
                                                {{ $item->type == '1' ? 'Service' : 'Goods' }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span>
                                                {{ $item->rate }}
                                            </span>
                                        </td>
        @can('items manage')
                                        <td class="align-middle py-1">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="{{ $index + 1 }}"
                                                    wire:change="toggleStatus({{ $item->id }})"
                                                    @checked($item->status)>
                                            </div>
                                        </td>
                                        @endcan
        @can('items manage')
                                        <td class="text-center align-middle py-1">
                                            <a href="javascript:void(0)" wire:click="edit({{ $item->id }})"
                                                title="Edit">
                                                <i class="bx bx-edit text-dark fs-5"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                                <i class="bx bx-trash text-danger fs-5"></i>
                                            </a>
                                        </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Items found.</td>
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
