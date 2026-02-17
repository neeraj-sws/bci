<div class="mx-5 mt-sm-0 mt-3" id="amanity">
    <div class="page-breadcrumb flex-wrap d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>

        </div>

    </div>

    <div class="row g-4">
        <!-- Form Card -->
        @can('zones manage')
        <div class="col-md-5">
            <div class="card">

                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Select Park <span
                                            class="text-danger">*</span></label>
                                    <select id="park" class="form-select select2" wire:model="park"
                                        placeholder="Select park">
                                        <option value=""></option>
                                        @foreach ($parks as $id => $name)
                                            <option value="{{ $id }}"
                                                @if ($park === $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Zone Name</label>
                                <input type="text"
                                    class="form-control text-capitalize  @error('name') is-invalid @enderror"
                                    wire:model="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Nearest Airport</label>
                                <input type="text"
                                    class="form-control text-capitalize @error('nearest_airport') is-invalid @enderror"
                                    wire:model="nearest_airport">
                                @error('nearest_airport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Nearest Railway Station</label>
                                <input type="text"
                                    class="form-control text-capitalize @error('nearest_railway') is-invalid @enderror"
                                    wire:model="nearest_railway">
                                @error('nearest_railway')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Nearest City <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control text-capitalize @error('nearest_city') is-invalid @enderror"
                                    wire:model="nearest_city">
                                @error('nearest_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="full_day_safari_cost" class="form-label">Full Day Safari Cost <span
                                        class="text-danger">*</span></label>
                                <select id="full_day_safari_cost"
                                    class="form-select @error('full_day_safari_cost') is-invalid @enderror"
                                    wire:model.live="full_day_safari_cost">
                                    <option value="">--</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('full_day_safari_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 {{ $full_day_safari_cost == '1' ? 'd-block' : 'd-none' }}">
                                <input type="text" class="form-control @error('total_cost') is-invalid @enderror"
                                    wire:model="total_cost" placeholder="Safari Cost">
                                @error('total_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 {{ $full_day_safari_cost == '1' ? 'd-block' : 'd-none' }}">
                                <label for="title" class="form-label">Gates Allowed <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('allowed_gates') is-invalid @enderror"
                                    wire:model="allowed_gates">
                                @error('allowed_gates')
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
        <div class="@can('zones manage') col-md-7 @else col-md-12 @endcan">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Search..."
                            wire:model.live.debounce.300ms="search"> <span
                            class="position-absolute product-show translate-middle-y">
                            <i class="bx bx-search"></i></span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="width60">S.no</th>
                                    <th wire:click="shortby('name')" style="cursor: pointer;">
                                        Zone Name
                                        @if($sortBy === 'name')
                                            <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('park_id')" style="cursor: pointer;">
                                        Park Name
                                        @if($sortBy === 'park_id')
                                            <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th>Nearest Airport/Railway/City </th>
                                    @can('zones manage')
                                    <th class="width60">Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">{{ $items->firstItem() + $index }}</td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item->name ?? 'NA' }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item->park->name ?? 'NA' }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1 text-center">
                                            <span class="">
                                                {{ $item->nearest_airport }},
                                                {{ $item->nearest_railway }},{{ $item->nearest_city }}
                                            </span>
                                        </td>
                                            @can('zones manage')
                                        <td class="align-middle py-1 text-center">
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
                                        <td colspan="6" class="text-center">No Zones found.</td>
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
