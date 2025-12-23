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
            <i class="bx bx-plus me-1"></i> Add New Gate
        </button>
    </div>
    <div class="card border-0 shadow-sm radius12 overflow-hidden @if ($isadd) d-none @endif">
        <div class="card-header d-flex justify-content-between align-items-cente py-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5" placeholder="Search gates..."
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
                            <th class="tableheadingcolor px-3 py-2">#</th>
                            <th class="tableheadingcolor px-3 py-2">Park</th>
                            <th class="tableheadingcolor px-3 py-2">Zone</th>
                            <th class="tableheadingcolor px-3 py-2">Gate Name</th>
                            <th class="tableheadingcolor px-3 py-2">Guide Fee</th>
                            <th class="tableheadingcolor px-3 py-2">Weekday Permit</th>
                            <th class="tableheadingcolor px-3 py-2">Weekend Permit</th>
                            <th class="tableheadingcolor px-3 py-2">Weekday Cost </th>
                            <th class="tableheadingcolor px-3 py-2">Weekend Cost </th>
                            <th class="tableheadingcolor px-3 py-2">Night Safari Cost</th>
                            <th class="tableheadingcolor px-3 py-2 width80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="table-bottom-border transition2" wire:key="{{ $item->id }}">
                                <td class="px-3 py-1 darkgreytext">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="d-flex align-items-center gap-2">
                                            {{ $item->park->name ?? 'NA' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="text-dark">{{ $item->zone->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->name }}</span>
                                </td>
                                <td class="px-3 py-1">₹{{ number_format($item->guide_fee, 2) }}</td>
                                <td class="px-3 py-1">₹{{ number_format($item->weekday_permit, 2) }}</td>
                                <td class="px-3 py-1">₹{{ number_format($item->weekend_permit, 2) }}</td>
                                                 <td class="px-3 py-1">₹{{ number_format($item->total_week_day, 2) }}</td>
                                <td class="px-3 py-1">₹{{ number_format($item->total_week_end, 2) }}</td>
                                <td class="px-3 py-1">{{ $item?->night_safari_permit ?? 'NA' }}</td>
                                <td class="px-3 py-1 text-center">
                                    <a href="javascript:void(0)" wire:click="edit({{ $item->id }})" title="Edit">
                                        <i class="bx bx-edit text-dark fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" wire:click="confirmDelete({{ $item->id }})"
                                        title="Delete">
                                        <i class="bx bx-trash text-danger fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 darkgreytext">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                        <span>No gates found. Click "Add New Gate" to create one.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
@if ($items->hasPages())
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                        <div class="text-muted small">
                            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }}
                            entries
                        </div>
                        <div>
                            {{ $items->links('livewire::bootstrap') }}
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
                        <div class="mb-3 col-sm-6">
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
                                @error('park')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-sm-6" >
                            <div class="form-group" >
                                <label for="title" class="form-label">Select Zone {{ $zone }} <span
                                        class="text-danger">*</span></label>
                        
                                <select id="zone" class="form-select select2" wire:model="zone"
                                    placeholder="Select zone">
                                    <option value=""></option>
                                    @foreach ($zones as $id => $name)
                                        <option wire:key='{{ $id }}' value="{{ $id }}"
                                            @if ($zone == $id) selected @endif>{{ $name }} {{ $id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('zone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Gate Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" placeholder="Gate Name"
                                class="form-control text-capitalize @error('name') is-invalid @enderror" wire:model="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Gypsy Charge <span
                                    class="text-danger">*</span></label>
                            <input type="number" placeholder="Gypsy Charge"
                                class="form-control @error('gypsy_charge') is-invalid @enderror"
                                wire:model.blur="gypsy_charge">
                            @error('gypsy_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Guide Fee <span
                                    class="text-danger">*</span></label>
                            <input type="number" placeholder="Guide Fee"
                                class="form-control @error('guide_fee') is-invalid @enderror"
                                wire:model.blur="guide_fee">
                            @error('guide_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Gate to Gate</label>
                            <input type="number" placeholder="Gate to Gate"
                                class="form-control @error('gate_to_gate') is-invalid @enderror"
                                wire:model="gate_to_gate">
                            @error('gate_to_gate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="number" class="form-label">Weekday Permit <span
                                    class="text-danger">*</span></label>
                            <input type="number" placeholder="Weekday Permit"
                                class="form-control @error('weekday_permit') is-invalid @enderror"
                                wire:model.blur="weekday_permit" @disabled($guide_fee === null || $gypsy_charge === null)>
                            @error('weekday_permit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Weekend Permit <span
                                    class="text-danger">*</span></label>
                            <input type="number" placeholder="Weekend Permit"
                                class="form-control @error('weekend_permit') is-invalid @enderror"
                                wire:model.blur="weekend_permit" @disabled($guide_fee === null || $gypsy_charge === null)>
                            @error('weekend_permit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Total Week Day</label>
                            <input type="number" placeholder="Total Week Day"
                                class="form-control @error('total_week_day') is-invalid @enderror"
                                wire:model="total_week_day" @disabled(!$weekday_permit)>
                            @error('total_week_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Total Week end</label>
                            <input type="number" placeholder="Total Week end"
                                class="form-control @error('total_week_end') is-invalid @enderror"
                                wire:model="total_week_end" @disabled(!$weekend_permit)>
                            @error('total_week_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Night Safari Permit</label>
                            <input type="text" placeholder="Night Safari Permit"
                                class="form-control @error('night_safari_permit') is-invalid @enderror"
                                wire:model="night_safari_permit">
                            @error('night_safari_permit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="title" class="form-label">Park Images drive link</label>
                            <input type="text" placeholder="Park drive Images"
                                class="form-control @error('drive_image') is-invalid @enderror"
                                wire:model="drive_image">
                            @error('drive_image')
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
