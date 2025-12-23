<div class="container mt-sm-0 mt-3" id="amanity">

    <div
        class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4 @if ($isadd) d-none @endif">
        <div class="mb-2">
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
        <button class="btn bluegradientbtn" wire:click="create">
            <i class="bx bx-plus me-1"></i> Add New Taxi
        </button>
    </div>
    <div class="card border-0 shadow-sm radius12 overflow-hidden @if ($isadd) d-none @endif">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5" placeholder="Search Taxi..."
                    wire:model.live.debounce.300ms="search">
                <span class="position-absolute product-show translate-middle-y">
                    <i class="bx bx-search"></i>
                </span>
            </div>
        </div>


        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tableminwidth">
                    <thead class="lightgradient">
                        <tr>
                            <th class="tableheadingcolor px-3 py-2">S.no</th>
                            <th class="tableheadingcolor px-3 py-2">City</th>
                            <th class="tableheadingcolor px-3 py-2">Park</th>
                            <th class="tableheadingcolor px-3 py-2">Zone</th>
                            <th class="tableheadingcolor px-3 py-2">Sedan</th>
                            <th class="tableheadingcolor px-3 py-2">Crysta</th>
                            <th class="tableheadingcolor px-3 py-2">Sedan Retained 2N 3D</th>
                            <th class="tableheadingcolor px-3 py-2">Crysta Retained 2N 3D</th>
                            <th class="tableheadingcolor px-3 py-2">Sedan Retained 3N 4D</th>
                            <th class="tableheadingcolor px-3 py-2">Crysta Retained 3N 4D</th>
                            <th class="tableheadingcolor px-3 py-2 width80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="table-bottom-border transition2" wire:key="{{ $item->id }}">
                                <td class="px-3 py-1 darkgreytext">{{ $index + 1 }}</td>
                                <td class="px-3 py-1">
                                    <span class="">
                                        {{ $item->city->name ?? 'NA' }}
                                    </span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="">
                                        {{ $item->park->name ?? 'NA' }}
                                    </span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="text-dark">{{ $item->zone->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->sedan ?? 'NA' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->crysta ?? 'NA' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->sedan_retained ?? 'NA' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->crysta_retained ?? 'NA' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->sedan_retained_two ?? 'NA' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->crysta_retained_two ?? 'NA' }}</span>
                                </td>

                                <td class="px-3 py-1 text-center">
                                    <a href="javascript:void(0)" wire:click="edit({{ $item->id }})" title="Edit">
                                        <i class="bx bx-edit text-dark fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                        <i class="bx bx-trash text-danger fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 darkgreytext">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                        <span>No Taxi's found. Click "Add New Taxi" to create one.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                    <div class="text-muted small">
                        Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} entries
                    </div>
                    <div>
                        {{ $items->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- FORM --}}
    <div class="col-12 @if (!$isadd) d-none @endif">
        <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">
                {{ $isEditing ? 'Edit' : 'Add' }} {{ $pageTitle }}
            </h6>
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="row mb-3">
                        <div class="mb-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="city" class="form-label">Select City <span
                                        class="text-danger">*</span></label>
                                <select id="city" class="form-select select2" wire:model="city"
                                    placeholder="Select City">
                                    <option value=""></option>
                                    @foreach ($cities as $id => $name)
                                        <option value="{{ $id }}" @if ($city === $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="title" class="form-label">Select Park <span
                                        class="text-danger">*</span></label>
                                <select id="park" class="form-select select2" wire:model="park"
                                    placeholder="Select park">
                                    <option value=""></option>
                                    @foreach ($parks as $id => $name)
                                        <option value="{{ $id }}" @if ($park === $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('park')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="zone" class="form-label">Select Zone <span
                                        class="text-danger">*</span></label>
                                <select id="zone" class="form-select select2" wire:model="zone"
                                    placeholder="Select zone">
                                    <option value=""></option>
                                    @foreach ($zones as $id => $name)
                                        <option value="{{ $id }}" @if ($zone === $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('zone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Sedan</label>
                            <input type="text" placeholder="Name sedan"
                                class="form-control @error('sedan') is-invalid @enderror" wire:model="sedan">
                            @error('sedan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Crysta</label>
                            <input type="text" placeholder="Name Crysta"
                                class="form-control @error('crysta') is-invalid @enderror" wire:model="crysta">
                            @error('crysta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Sedan Retained 2N 3D</label>
                            <input type="text" placeholder="Name Sedan"
                                class="form-control @error('sedan_retained') is-invalid @enderror"
                                wire:model="sedan_retained">
                            @error('sedan_retained')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Crysta Retained 2N 3D</label>
                            <input type="text" placeholder="Name Crysta"
                                class="form-control @error('crysta_retained') is-invalid @enderror"
                                wire:model="crysta_retained">
                            @error('crysta_retained')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Sedan Retained 3N 4D</label>
                            <input type="text" placeholder="Name Sedan"
                                class="form-control @error('sedan_retained_two') is-invalid @enderror"
                                wire:model="sedan_retained_two">
                            @error('sedan_retained_two')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Crysta Retained 3N 4D</label>
                            <input type="text" placeholder="Name Crysta"
                                class="form-control @error('crysta_retained_two') is-invalid @enderror"
                                wire:model="crysta_retained_two">
                            @error('crysta_retained_two')
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
                        <button type="button" wire:click="close"
                            class="btn btn-secondary greygradientbtn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>