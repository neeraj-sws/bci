<!-- resources/views/livewire/admin/resorts/resorts-manager.blade.php -->
<div class="container mt-sm-0 mt-3">
    <div
        class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4 @if ($isFormOpen) d-none @endif">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">Resorts</h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resorts</li>
                </ol>
            </nav>
        </div>
        <button class="btn bluegradientbtn" wire:click="create">
            <i class="bx bx-plus me-1"></i> Add New Resort
        </button>
    </div>

    <!-- Resort List -->
    <div class="card border-0 shadow-sm radius12 overflow-hidden @if ($isFormOpen) d-none @endif">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5" placeholder="Search Resorts..."
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
                            <th class="tableheadingcolor px-3 py-2">Name</th>
                            <th class="tableheadingcolor px-3 py-2">Contact Person</th>
                            <th class="tableheadingcolor px-3 py-2">Phone</th>
                            <th class="tableheadingcolor px-3 py-2">Park</th>
                            <th class="tableheadingcolor px-3 py-2">Zone</th>
                            <th class="tableheadingcolor px-3 py-2">Location Gate</th>
                            <th class="tableheadingcolor px-3 py-2">Number of Category</th>
                            <th class="tableheadingcolor px-3 py-2">Image</th>
                            <th class="tableheadingcolor px-3 py-2 width80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resorts as $resort)
                            <tr class="table-bottom-border transition2" wire:key="{{ $resort->id }}">
                                <td class="px-3 py-1">
                                    <span class="text-dark">{{ $resort->name }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $resort->primary_contact   }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $resort->phone }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $resort->park->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $resort->zone->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $resort->location_gate ?? 'N/A' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ count($resort->categories ?? []) }} Rooms
                                        Category</span>
                                </td>
                                <td class="px-3 py-1">
                                    <a target="_blank" href="{{$resort->drive_link}}"><i
                                            class="lni lni-google-drive"></i></a>
                                </td>
                                <td class="px-3 py-1 text-center">
                                    <a href="javascript:void(0)" wire:click="edit({{ $resort->id }})" title="Edit">
                                        <i class="bx bx-edit text-dark fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" wire:click="delete({{ $resort->id }})" title="Delete">
                                        <i class="bx bx-trash text-danger fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 darkgreytext">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                        <span>No resorts found. Click "Add New Resort" to create one.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($resorts->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                    <div class="text-muted small">
                        Showing {{ $resorts->firstItem() }} to {{ $resorts->lastItem() }} of {{ $resorts->total() }} entries
                    </div>
                    <div>
                        {{ $resorts->links('livewire::bootstrap') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Resort Form -->
    @if($isFormOpen)
     
            <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">
                {{ $isEditing ? 'Edit Resort' : 'Add New Resort' }}
            </h6>
        <div class="card border-0 shadow-sm radius12">
           
            <div class="card-body">
                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                    <div class="row">
                        <!-- Resort Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="name" placeholder="Resort name">
                                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="phone" placeholder="Phone number">
                                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Park <span class="text-danger">*</span></label>
                                <select id='park_id' class="form-select select2" wire:model="park_id">
                                    <option value="">Select Park</option>
                                    @foreach($parks as $id => $name)
                                        <option value="{{ $id }}" @if ($park_id === $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('park_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Location Gate <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="location_gate"
                                    placeholder="Location gate">
                                @error('location_gate') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" wire:model="address" placeholder="Address"></textarea>
                                @error('address') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Primary Contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="primary_contact"
                                    placeholder="Contact person name">
                                @error('primary_contact') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Secondary Phone</label>
                                <input type="text" class="form-control" wire:model="secondary_phone"
                                    placeholder="Secondary phone">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Zone <span class="text-danger">*</span></label>
                                <select id='zone_id' class="form-select select2" wire:model="zone_id">
                                    <option value="">Select Zone</option>
                                    @foreach($zones as $id => $name)
                                        <option value="{{ $id }}" @if ($zone_id === $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('zone_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Drive Link <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" wire:model="drive_link"
                                    placeholder="Google Drive link">
                                @error('drive_link') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Room Categories Section -->
                    <div class="mt-4">
                        <h6 class="mb-3 fw-600">Room Categories</h6>

                        <div class="table-responsive mb-2 innertable">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Regular Rate</th>
                                        <th>High Season Rate</th>
                                        <th>Extra Child Rate</th>
                                        <th>Extra Adult Rate</th>
                                        <th class="width80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $category)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control"
                                                    wire:model="categories.{{ $index }}.name">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    wire:model="categories.{{ $index }}.regular_rate">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    wire:model="categories.{{ $index }}.high_season_rate">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    wire:model="categories.{{ $index }}.extra_child_rate">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    wire:model="categories.{{ $index }}.extra_adult_rate">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger redgradientbtn"
                                                    wire:click="removeCategory({{ $index }})">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addCategory">
                            <i class="bx bx-plus"></i> Add Room of Category
                        </button>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn bluegradientbtn">
                            {{ $isEditing ? 'Update changes' : 'Save changes' }}
                        </button>
                        <button type="button" wire:click="closeForm"
                            class="btn btn-secondary greygradientbtn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>