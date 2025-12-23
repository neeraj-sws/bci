<div class="container mt-sm-0 mt-3" id="amanity">
    <div class="page-breadcrumb flex-wrap d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">{{ $pageTitle }}</h6>
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
                            <label for="title" class="form-label">User Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="User name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                wire:model="email" placeholder="User email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('selectedRole') is-invalid @enderror" wire:model.live="selectedRole">
                                <option value="">Select Role</option>
                                @foreach($roles as $key => $role)
                                    <option value="{{ $key }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions Section -->
                        <div class="mb-3">
                            <label class="form-label">Module Permissions</label>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($groupedPermissions as $module => $permissions)
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="bx bx-folder me-1"></i>
                                            {{ ucfirst($module) }} Module
                                        </h6>
                                        @foreach($permissions as $permission)
                                            <div class="form-check ms-3 mb-2">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    value="{{ $permission['name'] }}"
                                                    wire:model="userPermissions"
                                                    id="perm_{{ $permission['id'] }}"
                                                >
                                                <label class="form-check-label" for="perm_{{ $permission['id'] }}">
                                                    {{ $permission['display_name'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                @endforeach
                                
                                @if(empty($groupedPermissions))
                                    <div class="text-center text-muted py-3">
                                        <i class="bx bx-shield-x fs-1"></i>
                                        <p class="mt-2">No permissions found. Please run the seeder.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if (!$this->isEditing)
                            <div class="mb-3">
                                <label for="title" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    wire:model="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Retype Password <span class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    wire:model="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="inline-flex items-center mt-2">
                                    <input type="checkbox" wire:model="mail_sent"
                                        class="form-checkbox text-red-600 @error('mail_sent') is-invalid @enderror" />
                                    <span class="ml-2">Send an invite email</span>
                                    @error('mail_sent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </label>
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="title" class="form-label">Password (Leave blank to keep current password)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    wire:model="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Retype Password</label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    wire:model="password_confirmation">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <div class="form-group my-3">
                                <label for="title" class="form-label">Status</label>
                                <select id="filter_category" class="form-select" wire:model.live='status'
                                    placeholder="Select Category">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
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
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
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
                                                {{ $item->email }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span class="badge bg-primary">
                                                {{ $item->getRoleNames()->first() ?? 'No Role' }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $index + 1 }}"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status) @if ($item->is_admin == 1)
                                                    disabled
                                                @endif>
                                        </td>
                                        <td class="align-middle py-1 text-center">
                                            @if ($item->is_admin != 1)
                                                <a href="javascript:void(0)" wire:click="edit({{ $item->id }})"
                                                title="Edit">
                                                <i class="bx bx-edit text-dark fs-5"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                                <i class="bx bx-trash text-danger fs-5"></i>
                                            </a>
                                            @endif
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
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>