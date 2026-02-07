<div class="container" id="amanity">
 <div class="row g-4">
      @include('livewire.common.leads.leads-master')
    <div>  

    <div class="row g-4">
        @can('lead-setting manage')
        <!-- Form Card -->
        <div class="col-md-5">
            <div class="card">

                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <label for="title" class="form-label">Lead Type <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control text-capitalize @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Type name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="title" class="form-label">Type Color <span
                                    class="text-danger">*</span></label>
                            <input type="color" class="form-control @error('color') is-invalid @enderror"
                                wire:model="color">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary px-5" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update changes' : 'Save changes' }}
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="{{ $isEditing ? 'update' : 'store' }}"></i>
                            </button>
                            <button type="button" wire:click="resetForm"
                                class="btn btn-sm btn-secondary">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan

        <!-- Table Card -->
        <div class="@can('lead-setting manage') col-md-7 @else col-md-12 @endcan">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Search..."
                            wire:model.live.debounce.300ms="search"> <span
                            class="position-absolute top-50 product-show translate-middle-y">
                            <i class="bx bx-search"></i></span>
                    </div>
                </div>




                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Lead Type</th>
                                    @can('lead-setting manage')
                                    <th style="width: 80px;">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span style="color: {{ $item->color }}"
                                                    class="d-flex align-items-center gap-2 fw-600">
                                                    {{ $item->name }}
                                                </span>
                                            </div>
                                        </td>
                                        @can('lead-setting manage')
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
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No {{ $pageTitle }} found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
