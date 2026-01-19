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
        <a href="{{ route($route . '.create-hotel') }}" class="btn bluegradientbtn">
            <i class="bx bx-plus me-1"></i> Add New Hotel
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-xxl-nowrap flex-wrap">
                    {{-- <div class="btn-group p-2 rounded border mb-xxl-0 mb-2" role="group">
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
                            In-Active
                            <span class="badge bg-primary ms-2">{{ $inactiveCount }}</span>
                        </button>
                    </div> --}}

                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Search..."
                            wire:model.live.debounce.300ms="search">
                        <span class="position-absolute product-show translate-middle-y">
                            <i class="bx bx-search"></i>
                        </span>
                    </div>
                </div>
                <div class="card-body" style="overflow:visible!important;">
                    <div class="table-responsive ecs-table" style="overflow:visible!important;">
                        <table class="table" style="overflow:visible!important;">
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>Hotel Name</th>
                                    <th>Hotel Type</th>
                                    <th>Hotel Category</th>
                                    <th>Hotel Rate Type</th>
                                    <th>Hotel Meal Type</th>
                                    <th>Status</th>
                                    <th class="width80">Actions</th>
                                </tr>
                            </thead>

                            <tbody style="overflow:visible!important;">
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">{{ $index + 1 }}</td>
                                        <td class="align-middle py-1">{{ $item->name }}</td>
                                        <td class="align-middle py-1">{{ $item->hotelType->title }}</td>
                                        <td class="align-middle py-1">{{ $item->hotelCategory->title }}</td>
                                        <td class="align-middle py-1">{{ $item?->hotelRateType?->title ?? 'NA' }}</td>
                                        <td class="align-middle py-1">
                                            @if (!empty($item?->hotelMealType))
                                                @forelse ($item?->hotelMealType as $plan)
                                                    <span class="badge bg-primary me-1">
                                                        {{ $plan?->mealType?->title }}
                                                    </span>
                                                @empty
                                                    NA
                                                @endforelse
                                            @endif
                                        </td>
                                        <td class="align-middle py-1">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="{{ $index + 1 }}"
                                                wire:change="toggleStatus({{ $item->id }})"
                                                @checked($item->status)>
                                        </td>

                                        <td class="align-middle py-1 text-center"
                                            style="overflow:visible; position:relative;">
                                            <a href="{{ route('common.hotel-detail', $item->id) }}"
                                                title="View Details"><i class="bx bx-show text-primary fs-5"></i></a>
                                            <a href="{{ route('common.update-hotel', $item->id) }}" title="Edit"><i
                                                    class="bx bx-edit text-dark fs-5"></i></a>
                                            <a href="javascript:void(0)"
                                                wire:click="confirmDelete({{ $item->id }})" title="Delete"><i
                                                    class="bx bx-trash text-danger fs-5"></i></a>

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
