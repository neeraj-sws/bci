<div class="container">
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
        <a href="{{ route($route.'.tour-create') }}" class="btn bluegradientbtn">
            <i class="bx bx-plus me-1"></i> Add New Tour
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
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
                        <input type="text" class="form-control ps-5" placeholder="Search..."
                            wire:model.live.debounce.300ms="search">
                        <span class="position-absolute product-show translate-middle-y">
                            <i class="bx bx-search"></i>
                        </span>
                    </div>
                </div>

               {{-- <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>Tour Name</th>
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
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $index + 1 }}"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>
                                        <td class="align-middle py-1 text-center">
                                            <a  class="me-2" 
                                                href="{{ route('common.tour-edit', $item->id) }}" title="Edit">
                                                <i class="bx bx-edit text-dark fs-5"></i>
                                            </a>
                                            <a  class="me-2"  href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $item->id }})" title="Delete">
                                                <i class="bx bx-trash text-danger fs-5"></i>
                                            </a>
                                            <a  class="me-2"  href="{{ route('common.tour-copy', $item->id) }}">
                                                <i class="fadeIn animated bx bx-copy fs-5"></i>
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
                    {{ $items->links() }}
                </div> --}}
                   <div class="card-body" style="overflow:visible!important;">
                    <div class="table-responsive ecs-table" style="overflow:visible!important;">
                        <table class="table" style="overflow:visible!important;">
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>Tour Name</th>
                                    <th>Status</th>
                                    <th class="width80">Actions</th>
                                </tr>
                            </thead>

                            <tbody style="overflow:visible!important;">
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">{{ $index + 1 }}</td>
                                        <td class="align-middle py-1">{{ $item->name }}</td>
                                        <td class="align-middle py-1">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $index + 1 }}"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>

                                        <td class="align-middle py-1 text-center"
                                            style="overflow:visible; position:relative;">
                                            <div class="btn-group dropup d-inline-block me-2 mb-2" style="overflow:visible;">
                                                <a class="text-secondary" href="#" data-bs-toggle="dropdown">
                                                    <i class="bx bx-info-circle fs-5 text-info"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end p-2"
                                                    style="min-width:260px;overflow:visible;z-index:999999!important;">
                                                    <li class="text-wrap small">{{ $item->description }}</li>
                                                </ul>
                                            </div>

                                            <a href="{{ route('common.tour-edit', $item->id) }}"><i
                                                    class="bx bx-edit text-dark fs-5"></i></a>
                                            <a href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $item->id }})"><i
                                                    class="bx bx-trash text-danger fs-5"></i></a>
                                            <a href="{{ route('common.tour-copy', $item->id) }}"><i
                                                    class="fadeIn animated bx bx-copy fs-5"></i></a>
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
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
