<div class="mx-5 mt-sm-0 mt-3">

    <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
        @can('tourists manage')
        <a href="{{ route($route . '.tourist-create') }}" class="btn bluegradientbtn">
            <i class="bx bx-plus me-1"></i> Add New Tourist
        </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm radius12 overflow-hidden">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5" placeholder="Search Tourists..."
                    wire:model.live.debounce.300ms="search">
                <span class="position-absolute product-show translate-middle-y">
                    <i class="bx bx-search"></i>
                </span>
            </div>

            <div class="d-flex gap-2">

                <!-- Import Button -->
                <button title="Import"
                    class="btn btn-light rounded-circle d-flex justify-content-center align-items-center text-center"
                    style="width: 40px; height: 40px;" wire:click="openImportModel">

                    <!-- Normal Icon -->
                    <i class="bx bx-download fs-5 text-primary" wire:loading.remove wire:target="openImportModel"></i>

                    <!-- Spinner -->
                    <div class="spinner-border spinner-border-sm text-primary" role="status" wire:loading
                        wire:target="openImportModel">
                    </div>

                </button>


                <!-- Export Button -->
                <button title="Export"
                    class="btn btn-light rounded-circle d-flex justify-content-center align-items-center text-center"
                    style="width: 40px; height: 40px;" wire:click="exportExcel">

                    <!-- Normal Icon -->
                    <i class="bx bx-upload fs-5 text-danger" wire:loading.remove wire:target="exportExcel"></i>

                    <!-- Spinner -->
                    <div class="spinner-border spinner-border-sm text-success" role="status" wire:loading
                        wire:target="exportExcel">
                    </div>

                </button>

            </div>

        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 tableminwidth">
                    <thead class="lightgradient">
                        <tr>
                            <th class="tableheadingcolor px-3 py-2">#</th>
                            <th class="tableheadingcolor px-3 py-2" wire:click="shortby('primary_contact')"
                                style="cursor: pointer;">Name
                                @if ($sortBy === 'primary_contact')
                                    <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                @endif
                            </th>
                            <th class="tableheadingcolor px-3 py-2" wire:click="shortby('contact_phone')"
                                style="cursor: pointer;">Contact
                                @if ($sortBy === 'contact_phone')
                                    <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                @endif
                            </th>
                            <th class="tableheadingcolor px-3 py-2" wire:click="shortby('contact_email')"
                                style="cursor: pointer;">Email Id
                                @if ($sortBy === 'contact_email')
                                    <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                @endif
                            </th>
                            <th class="tableheadingcolor px-3 py-2">Country</th>
                            <th class="tableheadingcolor px-3 py-2" wire:click="shortby('birthday')"
                                style="cursor: pointer;">Birthday
                                @if ($sortBy === 'birthday')
                                    <i class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                @endif
                            </th>
                                @can('tourists manage')
                            <th class="tableheadingcolor px-3 py-2 width80">Actions</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="table-bottom-border transition2" wire:key="{{ $item->id }}">
                                <td class="px-3 py-1 darkgreytext">{{ $items->firstItem() + $index }}</td>
                                <td class="px-3 py-1">
                                    <span class="text-dark">{{ $item->primary_contact }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">
                                        @if ($item->country && $item->contact_phone)
                                            +{{ $item->country->phonecode }}-
                                        @endif{{ $item->contact_phone ?? 'NA' }}
                                    </span>
                                </td>
                                <td class="px-3 py-1">
                                    {{ $item->contact_email ?? 'NA' }}
                                </td>
                                <td class="px-3 py-1">
                                    {{ $item->country->name ?? 'NA' }}
                                </td>
                                <td class="px-3 py-1">
                                    {{ $item->birthday
                                        ? \Carbon\Carbon::parse($item->birthday)->format(
                                            App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y',
                                        )
                                        : 'NA' }}
                                </td>
                                @can('tourists manage')
                                <td class="text-center px-3 py-1">
                                    <a href="{{ route($route . '.tourist-edit', $item->id) }}" title="Edit">
                                        <i class="bx bx-edit text-dark fs-5"></i>
                                    </a>
                                    <a href="{{ route($route . '.view-tourist', $item->id) }}" title="Edit">
                                        <i class="lni lni-eye  text-dark fs-5"></i>
                                    </a>
                                    <a href="javascript:void(0)" wire:click="confirmDelete({{ $item->id }})"
                                        title="Delete">
                                        <i class="bx bx-trash text-danger fs-5"></i>
                                    </a>
                                </td>
                                @endcan
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 darkgreytext">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                        <span>No Tourist's found. Click "Add New Tourist" to create one.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($items->hasPages() || $items->total() > 0)
                <div
                    class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center py-3 gap-2">
                    <div class="d-flex align-items-center">
                        <label class="me-2 mb-0 small text-muted">Show</label>
                        <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="50">50</option>
                            <option value="50">100</option>
                            <option value="50">200</option>
                        </select>
                        <span class="ms-2 small text-muted">entries</span>
                    </div>
                    <div>
                        {{ $items->links('livewire::bootstrap') }}
                    </div>
                </div>
            @endif
        </div>
    </div>


    @if ($showModel)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-md">
                <div class="modal-content radius12 shadow-sm">

                    <div class="modal-header">
                        <h5 class="modal-title">Import Tourists</h5>
                        <button type="button" wire:click='closeImportModal'></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted mb-4">
                            To import your data, please use our sample file as a template.
                            If you don’t already have it, you can
                            <a class="text-decoration-underline"
                                href="{{ asset('uploads/sample-files/tourists.xls') }}" download>
                                download our sample file
                            </a> and review it. You can then use this template to create your own import file.
                        </p>



                        <div>
                            <label class="form-label">Choose File (CSV or Excel)</label>
                            <input type="file" wire:model="import_file" class="form-control"
                                accept=".csv,.xlsx,.xls">
                            <div wire:loading wire:target="import_file" class="mt-2">
                                <span class="spinner-border spinner-border-sm text-primary"></span>
                                <span class="text-muted ms-2">Uploading...</span>
                            </div>
                        </div>
                        @error('import_file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-light" wire:click='closeImportModal'>Cancel
                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="closeImportModal"></i>
                        </button>
                        <button class="btn bluegradientbtn" wire:click="importData">Import
                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="importData"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
