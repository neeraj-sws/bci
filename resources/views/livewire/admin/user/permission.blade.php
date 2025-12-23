<div class="container mt-3">
    <h4>{{ $pageTitle }}</h4>

    <div class="row g-1 mt-4">

        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="title" class="form-label">Role Name <span class="text-danger">*</span></label>

                            <select id="selectedRole" wire:model="selectedRole" class="form-select select2 mb-3">
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $id => $name)
                                    <option value="{{ $id }}">{{ ucfirst($name) }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ 'Save changes' }}
                                <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="save"></i>
                            </button>
                            <button type="button" wire:click="resetForm"
                                class="btn btn-secondary greygradientbtn">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="col-md-12">
            <div class="card">

                @if ($selectedRole)
                    <div class="space-y-6">
                        @foreach ($groupedPermissions as $module => $permissions)
                            <div class="border p-3 rounded-lg">
                                <p class="fw-bold   mb-2">{{ ucfirst($module) }}</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                    @foreach ($permissions as $permission)
                                        <label class="flex items-center space-x-2 me-3">
                                            <input type="checkbox" value="{{ $permission['name'] }}"
                                                {{-- ✅ use name, not ID --}} wire:model="selectedPermissions" class="rounded">
                                            <span>{{ $permission['display_name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

    </div>




</div>
