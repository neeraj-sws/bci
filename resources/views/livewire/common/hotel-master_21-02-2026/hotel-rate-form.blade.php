<div class="mx-5 mt-sm-0 mt-3">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title fs-24 fw-600 text-black">
                {{ $isEditing ? 'Edit Hotel Rate' : 'Add Hotel Rate' }}
            </h6>
            <nav>
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('common.hotel-rates') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $isEditing ? 'Edit' : 'Add' }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-body">

                    <form wire:submit.prevent="save">

                        <!-- Hotel -->
                        <div class="mb-3">
                            <label class="form-label">
                                Hotel <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('hotel_id') is-invalid @enderror" id="hotel_id"
                                wire:model.live="hotel_id">
                                <option value="">Select Hotel</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Room Category -->
                        <div class="mb-3">
                            <label class="form-label">
                                Room Category <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('room_category_id') is-invalid @enderror"
                                wire:model.defer="room_category_id">
                                <option value="">Select Room Category</option>
                                @foreach ($roomCategories as $room)
                                    <option value="{{ $room->id }}">{{ $room->title }}</option>
                                @endforeach
                            </select>
                            @error('room_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rate Type -->
                        <div class="mb-3">
                            <label class="form-label">
                                Rate Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('rate_type_id') is-invalid @enderror"
                                wire:model.defer="rate_type_id">
                                <option value="">Select Rate Type</option>
                                @foreach ($rateTypes as $rate)
                                    <option value="{{ $rate->id }}">{{ $rate->title }}</option>
                                @endforeach
                            </select>
                            @error('rate_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Season -->
                        <div class="mb-3">
                            <label class="form-label">
                                Season <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('season_id') is-invalid @enderror"
                                wire:model.defer="season_id">
                                <option value="">Select Season</option>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                            @error('season_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meal Plan -->
                        <div class="mb-3">
                            <label class="form-label">
                                Meal Plan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('meal_plan_id') is-invalid @enderror"
                                wire:model.defer="meal_plan_id">
                                <option value="">Select Meal Plan</option>
                                @foreach ($mealPlans as $meal)
                                    <option value="{{ $meal->id }}">{{ $meal->title }}</option>
                                @endforeach
                            </select>
                            @error('meal_plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Occupancy -->
                        <div class="mb-3">
                            <label class="form-label">
                                Occupancy <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('occupancy_id') is-invalid @enderror"
                                wire:model.defer="occupancy_id">
                                <option value="">Select Occupancy</option>
                                @foreach ($occupancies as $occ)
                                    <option value="{{ $occ->id }}">{{ $occ->title }}</option>
                                @endforeach
                            </select>
                            @error('occupancy_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Weekday Rate *</label>
                                <input type="number" class="form-control @error('weekday_rate') is-invalid @enderror"
                                    wire:model.defer="weekday_rate">
                                @error('weekday_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Weekend Rate *</label>
                                <input type="number" class="form-control @error('weekend_rate') is-invalid @enderror"
                                    wire:model.defer="weekend_rate">
                                @error('weekend_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Child Rate</label>
                                <input type="number" class="form-control" wire:model.defer="child_rate">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Extra Bed Rate</label>
                                <input type="number" class="form-control" wire:model.defer="extra_bed_rate">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update Rate' : 'Save Rate' }}
                                <i class="spinner-border spinner-border-sm ms-1" wire:loading></i>
                            </button>

                            <a href="{{ route('common.hotel-rates') }}" class="btn btn-secondary greygradientbtn">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
