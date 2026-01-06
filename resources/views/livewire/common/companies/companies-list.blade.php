<div class="container mt-sm-0 mt-3">

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
        @can('companies manage')
            <a href="{{ route($route . '.add-company') }}" class="btn bluegradientbtn">
                <i class="bx bx-plus me-1"></i> Add New Company
            </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm radius12 overflow-hidden">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5" placeholder="Search Company..."
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
                            <th class="tableheadingcolor px-3 py-2">Company Name</th>
                            <th class="tableheadingcolor px-3 py-2">Company Contact</th>
                            <th class="tableheadingcolor px-3 py-2">Company Email Id</th>
                            <th class="tableheadingcolor px-3 py-2">Profile Completed</th>
                                <th class="tableheadingcolor px-3 py-2 width80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr class="table-bottom-border transition2" wire:key="{{ $item->id }}">
                                <td class="px-3 py-1">
                                    <img src="{{ asset("uploads/companies/{$item->id}/" . $item?->logo?->file) }}" class="user-img me-3"
                                        alt="user avatar">
                                    <span class="text-dark">{{ $item->company_name }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    <span class="fw-500 text-dark">{{ $item->company_contact ?? 'NA' }}</span>
                                </td>
                                <td class="px-3 py-1">
                                    {{ $item->company_email ?? 'NA' }}
                                </td>
                                <td class="px-3 py-1 ">
                                    @php
                                        $totalSteps = 4;
                                        $percent = $item->profile_steps
                                            ? intval(($item->profile_steps / $totalSteps) * 100)
                                            : 0;
                                    @endphp
                                    {{ $percent }}%
                                </td>

                                    <td class="text-center px-3 py-1">
                                        <a class="me-2" href="{{ route($route . '.edit-company', $item->id) }}"
                                            title="Edit">
                                            <i class="bx bx-edit text-dark fs-5"></i>
                                        </a>
                                        <a class="me-2" href="javascript:void(0)"
                                            wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                            <i class="bx bx-trash text-danger fs-5"></i>
                                        </a>
                                             <input class="mt-2 form-check-input" type="checkbox" role="switch"
                                            id="{{ $index + 1 }}" wire:change="toggleStatus({{ $item->id }})"
                                            @checked($item->is_primary)>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 darkgreytext">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bx bx-data fs-1 mb-2 lightgreyicon"></i>
                                        <span>No Companies found. Click "Add New Company" to create one.</span>
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
</div>
