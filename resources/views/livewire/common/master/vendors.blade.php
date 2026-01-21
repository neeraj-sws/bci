<div class="mt-sm-0 mt-3" id="amanity">

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
                            <label for="title" class="form-label">Vendor Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="text-capitalize form-control @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Vendor name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Vendor Type <span
                                            class="text-danger">*</span></label>
                                    <select id="type_id" class="form-select select2" wire:model="type_id"
                                        placeholder="Select Type">
                                        <option value=""></option>
                                        @foreach ($types as $id => $name)
                                            <option wire:key='{{ $id }}' value="{{ $id }}"
                                                @if ($type_id === $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                                      <div class="mt-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Vendor Sub Type <span
                                            class="text-danger">*</span></label>
                                    <select id="sub_type_id" class="form-select select2" wire:model="sub_type_id"
                                        placeholder="Select Type">
                                        <option value=""></option>
                                        @foreach ($subcategorys as $id => $name)
                                            <option wire:key='{{ $id }}' value="{{ $id }}"
                                                @if ($sub_type_id === $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_type_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="title" class="form-label">Primary Contact <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('contact') is-invalid @enderror"
                                    wire:model="contact">
                                @error('contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="my-3">
                                <label for="title" class="form-label">Secondary Contact</label>
                                <input type="text"
                                    class="form-control @error('secondary_contact') is-invalid @enderror"
                                    wire:model="secondary_contact">
                                @error('secondary_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if (count($vehiclesData) == 0 && $is_taxi)
                                <a class="text-primary mt-2 cursor-pointer text-decoration-underline"
                                    wire:click='showModel'>Add Vehicles
                                    <i class="spinner-border spinner-border-sm" wire:loading.delay
                                        wire:target="showModel"></i>
                                </a>
                            @elseif($is_taxi)
                                <div class="mt-2">
                                    <tr><a class="text-warning  cursor-pointer text-decoration-underline"
                                            wire:click='showModel'>Add Vehicles</a></tr>

                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-hover shadow-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Vehicle Name</th>
                                                    <th>Per Day Charge</th>
                                                    {{-- <th>Per Night Charge</th> --}}
                                                    <th style="width: 100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vehiclesData as $index => $data)
                                                    <tr>
                                                        <td>{{ $vehicles[$data['vehicle_id']] ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ \App\Helpers\SettingHelper::formatCurrency(
                                                                $data['day_charge'] ?? 0,
                                                                \App\Helpers\SettingHelper::getGenrealSettings('number_format'),
                                                            ) }}
                                                        </td>
                                                        {{-- <td>
                                                            {{ \App\Helpers\SettingHelper::formatCurrency(
                                                                $data['night_charge'] ?? 0,
                                                                \App\Helpers\SettingHelper::getGenrealSettings('number_format'),
                                                            ) }}
                                                        </td> --}}
                                                        <td>
                                                            <a class="btn btn-sm btn-info"
                                                                wire:click="editVehicle({{ $index }})">
                                                                <i class="bx bx-edit text-dark"></i>
                                                                <i class="spinner-border spinner-border-sm"
                                                                    wire:loading.delay
                                                                    wire:target="editVehicle({{ $index }})"></i>
                                                            </a>
                                                            <a class="btn btn-sm btn-danger"
                                                                wire:click="removeVehicle({{ $index }})">
                                                                <i class="bx bx-trash text-dark"></i>
                                                                <i class="spinner-border spinner-border-sm"
                                                                    wire:loading.delay
                                                                    wire:target="removeVehicle({{ $index }})"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif


                            @if ($is_taxi)
                                <div class="col mt-3">


                                @if($serviceAreaNames)
                                    <label class="form-label">Existing Location's</label></br>
                                    <span>{{ $serviceAreaNames ?? '' }}</span>
                                @endif

                                    <a class="text-primary mt-2 cursor-pointer text-decoration-underline"
                                        wire:click='openServiceAreaModal'>Add Service Location's
                                        <i class="spinner-border spinner-border-sm" wire:loading.delay
                                            wire:target="openServiceAreaModal"></i>
                                    </a>
                                </div>

                            @endif


                            <!-- Country -->
                            <div class="mt-3">
                                <div class="form-group">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <select id='country' class="form-select select2" wire:model="country">
                                        <option value="">Select Country</option>
                                        @foreach ($countrys as $id => $name)
                                            <option value="{{ $id }}"
                                                @if ($country == $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="form-group">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <select id='state' class="form-select select2" wire:model="state">
                                        <option value="">Select State</option>
                                        @foreach ($states as $id => $name)
                                            <option value="{{ $id }}"
                                                @if ($state == $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">City <span
                                            class="text-danger">*</span></label>
                                    <select id="city_id" class="form-select select2" wire:model="city_id"
                                        placeholder="Select City">
                                        <option value=""></option>
                                        @foreach ($cities as $id => $name)
                                            <option value="{{ $id }}"
                                                @if ($city_id === $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if ($is_taxi)
                                <div class="mt-3">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Base Location <span
                                                class="text-danger">*</span></label>
                                        <select id="service_area_id" class="form-select select2"
                                            wire:model="service_area_id" placeholder="Select City">
                                            <option value=""></option>
                                            @foreach ($baseLocations as $id => $name)
                                                <option value="{{ $id }}"
                                                    @if ($service_area_id === $id) selected @endif>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('service_area_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3">
                                <label for="title" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" wire:model="address"> </textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label for="title" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" wire:model="notes"> </textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



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
                                class="btn btn-secondary greygradientbtn">Close
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="resetForm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="col-md-7">
            <div class="card">
<div class="card-header">
    <div class="row g-3 align-items-end">

        <!-- Search -->
        <div class="col-md-3">
            <label class="form-label">Search</label>
            <div class="position-relative">
                <input type="text" class="form-control ps-5"
                       placeholder="Search..."
                       wire:model.live.debounce.300ms="search">
                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                    <i class="bx bx-search fs-5"></i>
                </span>
            </div>
        </div>

        <!-- Vendor Type -->
        <div class="col-md-3">
            <label class="form-label">Vendor Type</label>
            <select id="type" class="form-select select2" wire:model="type">
                <option value="">Select Type</option>
                @foreach ($types as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Service Location -->
        <div class="col-md-3">
            <label class="form-label">Service Location</label>
            <select id="location" class="form-select select2" wire:model="location">
                <option value="">Select City</option>
                @foreach ($baseLocations as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Vehicle -->
        <div class="col-md-3">
            <label class="form-label">Vehicle</label>
            <select id="vehicle" class="form-select select2" wire:model="vehicle">
                <option value="">Select Vehicle</option>
                @foreach ($vehicles as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

          <div class="col-3">
                 <button class="btn bluegradientbtn" wire:click="clearFilters">clear

                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="clearFilters"></i>
                        </button>
          </div>

    </div>
</div>


                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th class="width60">#</th>
                                    <th>Vendor Name</th>
                                    <th>Primary Contact</th>
                                    <th>Status</th>
                                    <th class="width80">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">{{ $items->firstItem() + $index }}</td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item->name }}
                                            </span>
                                        </td>

                                         <td class="px-3 py-1">
                                            <span class="fw-500 text-dark">@if($item->country && $item->contact) +{{$item->country->phonecode }}-@endif{{ $item->contact ?? 'NA' }}</span>
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
                                        <td colspan="12" class="text-center">No {{ $pageTitle }} found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                         <x-pagination :paginator="$items" />
                    </div>
                </div>
            </div>
        </div>



        <div class="modal @if ($showModal) show @endif" tabindex="-1"
            style="opacity:1; background-color:#0606068c; display:@if ($showModal) block @endif">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-4">
                    <form wire:submit.prevent="{{ $vechileEdit ? 'editVechileStore' : 'addVehicle' }}">
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-label">Vehicles <span class="text-danger">*</span></label>
                                <select id="vehicle_id" class="form-select select2" wire:model="vehicle_id">
                                    <option value="">Select Vehicle</option>
                                    @foreach ($vehicles as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Per Day Charge <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="day_charge"
                                    placeholder="Per Day Charge">
                                @error('day_charge')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mt-3">
                                <label class="form-label">Per Night Charge <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="night_charge"
                                    placeholder="Per Night Charge">
                                @error('night_charge')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary px-5">Save
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="{{ $vechileEdit ? 'editVechileStore' : 'addVehicle' }}"></i>
                            </button>
                            <button type="button" wire:click="resetVehicleForm"
                                class="btn btn-sm btn-secondary">Close
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="resetVehicleForm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>


    @if ($showLoModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Select Service Location's</h5>
                        <button type="button" class="btn-close" wire:click="$set('showLoModal', false)"></button>
                    </div>

                  {{--  <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Service Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceAreas as $area)
                                    <tr wire:click="toggleArea({{ $area->service_location_id }})"
                                        style="cursor: pointer;"
                                        class="{{ in_array($area->service_location_id, $selectedServiceArea) ? 'table-primary' : '' }}">
                                        <td>
                                            <input type="checkbox" wire:model="selectedServiceArea"
                                                value="{{ $area->service_location_id }}" wire:click.stop>
                                        </td>

                                        <td>{{ $area->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}

<div class="modal-body">
    <table style="width: 100%; border-collapse: collapse;">
        <tbody style="display: block; column-count: 4; column-gap: 5px; max-height: 400px; overflow-y: auto; padding: 0; margin: 0;">

            @foreach ($serviceAreas as $area)
                <tr
                    wire:key="area-{{ $area->service_location_id }}"
                    style="
                        display: block;
                        break-inside: avoid;
                        margin: 0;
                        padding: 6px;
                        border-bottom: 1px solid #dee2e6;
                        border-radius: 4px;
                        background-color: {{ in_array($area->service_location_id, $selectedServiceArea) ? '#cfe2ff' : 'transparent' }};
                    "
                >

                    <td style="display: inline-block; width: 22px; padding: 0; border: none; vertical-align: middle;">
                        <input type="checkbox"
                               wire:model="selectedServiceArea"
                               value="{{ $area->service_location_id }}"
                               wire:key="checkbox-{{ $area->service_location_id }}">
                    </td>

                    <td style="display: inline-block; width: calc(100% - 30px); padding: 0 5px; border: none; vertical-align: middle;">
                        {{ $area->name }}
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
</div>


                    <div class="modal-footer">
                        <button class="btn bluegradientbtn" wire:click="$set('showLoModal', false)">Done

                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="showLoModal"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
