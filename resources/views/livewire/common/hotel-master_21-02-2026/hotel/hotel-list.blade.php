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
        <a href="{{ route($route . '.create-hotel') }}" class="btn bluegradientbtn">
            <i class="bx bx-plus me-1"></i> Add New Hotel
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Filters Section -->
                <div class="card-header">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-600">Park</label>
                            <select class="form-select select2" id="filterPark" wire:model.live="filterPark">
                                <option value="">All Parks</option>
                                @foreach ($parks as $parkId => $parkName)
                                    <option value="{{ $parkId }}">{{ $parkName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600">Country</label>
                            <select class="form-select select2" id="filterCountry" wire:model.live="filterCountry">
                                <option value="">All Countries</option>
                                @foreach ($countries as $countryId => $countryName)
                                    <option value="{{ $countryId }}">{{ $countryName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600">State</label>
                            <select class="form-select select2" id="filterState" wire:model.live="filterState" {{ !$filterCountry ? 'disabled' : '' }}>
                                <option value="">{{ $filterCountry ? 'All States' : 'Select Country First' }}</option>
                                @foreach ($states as $stateId => $stateName)
                                    <option value="{{ $stateId }}">{{ $stateName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600">City</label>
                            <select class="form-select select2" id="filterCity" wire:model.live="filterCity" {{ !$filterState ? 'disabled' : '' }}>
                                <option value="">{{ $filterState ? 'All Cities' : 'Select State First' }}</option>
                                @foreach ($cities as $cityId => $cityName)
                                    <option value="{{ $cityId }}">{{ $cityName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body border-bottom d-flex justify-content-between align-items-center flex-xxl-nowrap flex-wrap">
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

                    <div class="d-flex gap-2 align-items-center">
                        <div class="position-relative">
                            <input type="text" class="form-control ps-5" placeholder="Search..."
                                wire:model.live.debounce.300ms="search">
                            <span class="position-absolute product-show translate-middle-y">
                                <i class="bx bx-search"></i>
                            </span>
                        </div>

                        @if($filterPark || $filterCountry || $filterState || $filterCity || $search)
                            <button wire:click="clearFilters" class="btn btn-outline-secondary" title="Clear all filters">
                                <i class="bx bx-x"></i> Clear Filters
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body" style="overflow:visible!important;">
                    <div class="table-responsive ecs-table">
                        <table class="table" style="overflow:visible!important;">
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th wire:click="shortby('name')" style="cursor: pointer;">
                                         Name
                                        @if ($sortBy === 'name')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('hotel_type')" style="cursor:pointer">
                                         Type
                                        @if ($sortBy === 'hotel_type')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('hotel_category')" style="cursor:pointer">
                                         Category
                                        @if ($sortBy === 'hotel_category')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('rate_type')" style="cursor:pointer">
                                         Rate Type
                                        @if ($sortBy === 'rate_type')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('park')" style="cursor:pointer">
                                        Park
                                        @if ($sortBy === 'park')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('country')" style="cursor:pointer">
                                        Country
                                        @if ($sortBy === 'country')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('state')" style="cursor:pointer">
                                        State
                                        @if ($sortBy === 'state')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th wire:click="shortby('city')" style="cursor:pointer">
                                        City
                                        @if ($sortBy === 'city')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th> Meal Type</th>
                                    <th wire:click="shortby('status')" style="cursor:pointer">
                                        Status
                                        @if ($sortBy === 'status')
                                            <i
                                                class="bx bx-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-arrow-alt"></i>
                                        @endif
                                    </th>
                                    <th class="width80">Actions</th>
                                </tr>
                            </thead>

                            <tbody style="overflow:visible!important;">
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1"> {{ $items->firstItem() + $index }}</td>
                                        <td class="align-middle py-1">{{ $item->name }}</td>
                                        <td class="align-middle py-1">{{ $item->hotelType->title }}</td>
                                        <td class="align-middle py-1">{{ $item->hotelCategory->title }}</td>
                                        <td class="align-middle py-1">{{ $item?->hotelRateType?->title ?? 'NA' }}</td>
                                        <td class="align-middle py-1">{{ $item?->park?->name ?? 'NA' }}</td>
                                        <td class="align-middle py-1">{{ $item?->country?->name ?? 'NA' }}</td>
                                        <td class="align-middle py-1">{{ $item?->state?->name ?? 'NA' }}</td>
                                        <td class="align-middle py-1">{{ $item?->city?->name ?? 'NA' }}</td>
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
                                        <td colspan="12" class="text-center">No Hotel List found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :paginator="$items" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
