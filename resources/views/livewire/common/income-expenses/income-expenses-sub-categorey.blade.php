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


                            <div class="col">
                                <div class="form-group my-3">
                                    <label for="title" class="form-label">Category type <span
                                            class="text-danger">*</span></label>
                                    <select id="filter_category" class="form-select" wire:model.live='type'
                                        placeholder="Select Category">
                                        <option value="1">select category type</option>
                                        <option value="1">Exspense</option>
                                        <option value="2">Income</option>
                                    </select>

                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>


                            <div class="col">
                                <div class="form-group my-3">
                                    <label for="title" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select id="category_id" class="form-select select2" wire:model='category_id'
                                        placeholder="Select Category">
                                        <option value=""></option>
                                        @foreach ($categorys as $id => $name)
                                            <option value="{{ $id }}"
                                                @if ($category_id === $id) selected @endif>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <label for="title" class="form-label">Sub Category Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Sub Category Name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror



                            <div class="col">
                                <div class="form-group my-3">
                                    <label for="title" class="form-label">Status</label>
                                    <select id="filter_category" class="form-select" wire:model.live='status'
                                        placeholder="Select Category">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
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

        <!-- Table Card -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="btn-group p-2 rounded border mb-xxl-0 mb-2" role="group">
                        <button wire:click="setTab(1)"
                            class="btn btn-sm {{ $tab == '1' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-start">
                            Expense
                            <span class="badge bg-primary ms-2">{{ $expensecount }}</span>
                            <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="setTab(1)"></i>
                        </button>
                        <button wire:click="setTab(2)"
                            class="btn btn-sm {{ $tab == '2' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-end">
                            Income
                            <span class="badge bg-primary ms-2">{{ $incomecount }}</span>
                            <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="setTab(2)"></i>
                        </button>
                    </div>

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
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>Category Name</th>
                                    <th>Sub Category Name</th>
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
                                                {{ $item->category->name }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item->name }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $index + 1 }}"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No {{ $pageTitle }} found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    @if ($items->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                            <div class="text-muted small">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                {{ $items->total() }}
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
