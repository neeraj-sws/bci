<div class="container mt-sm-0 mt-3" id="amanity">
    
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
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Tour Name</label>
                                <input type="text" placeholder="Tour Name" class="form-control @error('name') is-invalid @enderror"
                                    wire:model="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Client Name <span class="text-danger">*</span></label>
                                    <select id="client_id" class="form-select select2" wire:model="client_id"
                                        placeholder="Client">
                                        <option value=""></option>
                                        @foreach ($clients as $id => $company_name)
                                            <option value="{{ $id }}" @if ($client_id ===  $id) selected @endif>{{ $company_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    wire:model="start_date" >
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    wire:model="end_date" >
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                <label for="title" class="form-label">Description <span class="text-danger">*</span></label>
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
                                <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="{{ $isEditing ? 'update' : 'store' }}"></i>
                            </button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary greygradientbtn">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-xxl-nowrap flex-wrap">
                    <div class="btn-group p-2 rounded border mb-xxl-0 mb-2" role="group">
                        <button wire:click="setTab('all')" 
                            class="btn btn-sm {{ $tab === 'all' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-start">
                                All 
                            <span class="badge bg-primary ms-2">{{ $allCount }}</span>
                        </button>

                        <button wire:click="setTab('active')" 
                                class="btn btn-sm {{ $tab === 'active' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-start">
                            Active 
                            <span class="badge bg-primary ms-2">{{ $activeCount }}</span>
                        </button>

                        <button wire:click="setTab('inactive')" 
                                class="btn btn-sm {{ $tab === 'inactive' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-end">
                            De-Active 
                            <span class="badge bg-primary ms-2">{{ $inactiveCount }}</span>
                        </button>
                    </div>

                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Search..." wire:model.live.debounce.300ms="search" > 
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
                                    <th>Tour Name</th>
                                    <th>Client Name</th>
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
                                            <span class="">
                                                {{ $item->client->company_name }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                                <input class="form-check-input" type="checkbox" role="switch" id="{{$index + 1}}"
                                                    wire:change="toggleStatus({{ $item->id }})"
                                                    @checked($item->status)>
                                        </td>
                                        <td class="align-middle py-1 text-center">
                                            <a class="me-2" href="javascript:void(0)" wire:click="edit({{ $item->id }})" title="Edit">
                                                <i class="bx bx-edit text-dark fs-5"></i>
                                            </a>
                                            <a class="me-2"  href="javascript:void(0)" wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                                <i class="bx bx-trash text-danger fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Tours found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        </div>
    </div>
</div>
