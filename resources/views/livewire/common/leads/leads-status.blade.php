<div class="container" id="amanity">

<div class="row g-4">
      @include('livewire.common.leads.leads-master')
    <div> 
    <div class="row g-4">
         @can('leads_status manage')
        <!-- Form Card -->
        <div class="col-md-5">
            <div class="card">

                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <label for="title" class="form-label">Lead Status Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control text-capitalize @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Status name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="title" class="form-label">Select Lead Type <span
                                        class="text-danger">*</span></label>
                                <select id="pipeline_id" class="form-select select2" wire:model="pipeline_id"
                                    placeholder="Select Lead Type">
                                    <option value=""></option>
                                    @foreach ($pipelines as $id => $name)
                                        <option value="{{ $id }}"
                                            @if ($pipeline_id === $id) selected @endif>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>

                                  @error('pipeline_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>

                          <div class="mb-3">
                            <label for="title" class="form-label">Stage BG Color <span
                                    class="text-danger">*</span></label>
                            <input type="color" class="form-control @error('btn_bg') is-invalid @enderror"
                                wire:model.live="btn_bg">
                            @error('btn_bg')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Stage TEXT Color <span
                                    class="text-danger">*</span></label>
                            <input type="color" class="form-control @error('btn_text') is-invalid @enderror"
                                wire:model.live="btn_text">
                            @error('btn_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        @if ($btn_bg && $btn_text)
                        <div class="mb-3">
                            <label class="form-label">Preview</label>
                            <br>
                            <button type="button" class="btn"
                                style="background-color: {{ $btn_bg }}; color: {{ $btn_text }}; padding: 10px 20px; border: none; border-radius: 5px;">
                                Preview Button
                            </button>
                        </div>
                        @endif



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
        <div class="@can('leads_status manage') col-md-7 @else col-md-12 @endcan">
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
                                    <th>Lead Status</th>
                                    <th>Lead Type</th>
                                      @can('leads_status manage')
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
                                                <span class="d-flex align-items-center gap-2">
                                                    {{ $item->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="d-flex align-items-center gap-2">
                                                    {{ $item->type->name ?? 'NA' }}
                                                </span>
                                            </div>
                                        </td>
                                        @can('leads_status manage')
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
