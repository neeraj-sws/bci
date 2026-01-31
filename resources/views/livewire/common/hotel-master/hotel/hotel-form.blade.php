   <div>
       <div class="row">
           <div class="col-12">
               <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">
                   {{ $isEditing ? 'Edit' : 'Add' }}
               </h6>
               <div class="card">
                   <div class="card-body">
                       <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                           <div class="row g-3">

                               <div class="col-md-6">
                                   <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control text-capitalize" wire:model.defer="name">
                                   @error('name')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">Hotel Type <span class="text-danger">*</span></label>
                                   <select class="form-select select2" id="hotel_type_id"
                                       placeholder="Select Hotel Type" wire:model.live="hotel_type_id">
                                       <option value="">Select</option>
                                       @foreach ($hotel_types as $type)
                                           <option value="{{ $type->id }}" @selected($type->id == $hotel_type_id)>
                                               {{ $type->title }}</option>
                                       @endforeach
                                   </select>
                                   @error('hotel_type_id')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">Status</label>
                                   <select class="form-select select2" id="status" wire:model.defer="status">
                                       <option value="1">Active</option>
                                       <option value="0">Inactive</option>
                                   </select>
                               </div>
                               <!-- Country -->
                               <div class="col-md-6">
                                   <label class="form-label">Country <span class="text-danger">*</span></label>
                                   <select id='country_id' class="form-select select2" wire:model="country_id"
                                       placeholder="Select Country">
                                       <option value="">Select Country</option>
                                       @foreach ($countrys as $id => $name)
                                           <option value="{{ $id }}"
                                               @if ($country_id == $id) selected @endif>{{ $name }}
                                           </option>
                                       @endforeach
                                   </select>
                                   @error('country_id')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">State <span class="text-danger">*</span> </label>
                                   <select id='state' class="form-select select2" wire:model="state"
                                       placeholder="Select State">
                                       <option value="">Select State</option>
                                       @foreach ($states as $id => $name)
                                           <option value="{{ $id }}"
                                               @if ($state == $id) selected @endif>{{ $name }}
                                           </option>
                                       @endforeach
                                   </select>
                                   @error('state')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">City <span class="text-danger">*</span> </label>
                                   <select id='city' class="form-select select2" wire:model="city"
                                       placeholder="Select City">
                                       <option value="">Select City</option>
                                       @foreach ($citys as $id => $name)
                                           <option value="{{ $id }}"
                                               @if ($city == $id) selected @endif>{{ $name }}
                                           </option>
                                       @endforeach
                                   </select>
                                   @error('city')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>
                               <div class="col-md-6">
                                   <label class="form-label">Rate Type </label>
                                   <select class="form-select select2" id="rate_type" wire:model="rate_type"
                                       placeholder="Select Rate Type">
                                       <option value="">Select</option>
                                       @foreach ($rateTypes as $value)
                                           <option value="{{ $value->id }}" @selected($value->id == $rate_type)>
                                               {{ $value->title }}</option>
                                       @endforeach
                                   </select>
                                   @error('rate_type')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">Park </label>
                                   <select class="form-select select2" id="park_id" wire:model="park_id"
                                       placeholder="Select Park">
                                       <option value="">Select</option>
                                       @foreach ($parks as $park)
                                           <option value="{{ $park->park_id }}" @selected($park->park_id == $park_id)>
                                               {{ $park->name }}</option>
                                       @endforeach
                                   </select>
                                   @error('park_id')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">Hotel Category <span class="text-danger">*</span></label>
                                   <select class="form-select select2" id="hotel_category_id"
                                       placeholder="Select Hotel Category" wire:model.defer="hotel_category_id">
                                       <option value="">Select</option>
                                       @foreach ($hotel_categories as $cat)
                                           <option value="{{ $cat->id }}" @selected($cat->id == $hotel_category_id)>
                                               {{ $cat->title }}</option>
                                       @endforeach
                                   </select>
                                   @error('hotel_category_id')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label class="form-label">Meal Type <span class="text-danger">*</span></label>
                                   <select class="form-select select2" id="meal_type" wire:model="meal_type"
                                       placeholder="Select Meal Type" multiple>
                                       <option value="">Select</option>
                                       @foreach ($mealTypes as $value)
                                           <option value="{{ $value->id }}" @selected(in_array($value->id, $meal_type))>
                                               {{ $value->title }}
                                           </option>
                                       @endforeach
                                   </select>
                                   @error('meal_type')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               @if ($hotel_type_id == 2)
                                   <div class="col-md-6" id="parent_chain_wrapper">
                                       <label class="form-label">
                                           Parent Chain
                                           <a href="javascript:void(0)" wire:click="openModal">+ Add</a>
                                       </label>
                                       <select class="form-select select2" id="parent_chain_id"
                                           placeholder="Select Parent Chain" wire:model.defer="parent_chain_id">
                                           <option value="">Select</option>
                                           @forelse ($chainHotels as $hotel)
                                               <option value="{{ $hotel->chain_id }}" @selected($hotel->chain_id == $parent_chain_id)>
                                                   {{ $hotel->title }}</option>
                                           @empty
                                               <option value="" disabled>No chain hotels available</option>
                                           @endforelse
                                       </select>
                                   </div>
                               @endif
                               @if ($hotel_type_id == 1)
                                   <div class="col-md-6" id="marketing_company_wrapper">
                                       <label class="form-label">
                                           Marketing Company
                                           <a href="javascript:void(0)" wire:click="openModal">+ Add</a>
                                       </label>
                                       <select class="form-select select2" id="marketing_company_id"
                                           placeholder="SelectMarketing Company"
                                           wire:model.defer="marketing_company_id">
                                           <option value="">Select</option>
                                           @forelse ($marketedHotels as $hotel)
                                               <option value="{{ $hotel->marketing_company_id }}"
                                                   @selected($hotel->marketing_company_id == $marketing_company_id)>
                                                   {{ $hotel->title }}</option>
                                           @empty
                                               <option value="" disabled>No marketed hotels available</option>
                                           @endforelse
                                       </select>
                                   </div>
                               @endif

                               <div class="col-md-6">
                                   <label class="form-label">Preferred Airport </label>
                                   <input type="text" class="form-control text-capitalize"
                                       wire:model.defer="preferred_airport">
                                   @error('preferred_airport')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>
                               <div class="col-md-6">
                                   <label class="form-label">Preferred Railway Station </label>
                                   <input type="text" class="form-control text-capitalize"
                                       wire:model.defer="preferred_railway_station">
                                   @error('preferred_railway_station')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>
                               <div class="d-flex gap-2 mt-3">
                                   <button type="submit" class="btn bluegradientbtn">
                                       {{ $isEditing ? 'Update Hotel' : 'Save Hotel' }}
                                   </button>
                                   <a href="{{ route('common.hotel-list') }}" class="btn btn-secondary">Cancel</a>
                               </div>

                           </div>
                       </form>
                   </div>
               </div>
           </div>
       </div>
       @if ($showModel)
           <div class="modal show d-block" style="background:#0000008a">
               <div class="modal-dialog">
                   <div class="modal-content p-4">

                       <h5 class="mb-3">{{ $modalTitle }}</h5>

                       <div class="mb-3">
                           <label>Title</label>
                           <input type="text" class="form-control" wire:model.defer="newTitle">
                           @error('newTitle')
                               <small class="text-danger">{{ $message }}</small>
                           @enderror
                       </div>

                       <div class="d-flex gap-2">
                           <button class="btn btn-primary" wire:click="saveModalData" wire:loading.attr="disabled">

                               <span wire:loading.remove wire:target="saveModalData">
                                   Save
                               </span>

                               <span wire:loading wire:target="saveModalData">
                                   <span class="spinner-border spinner-border-sm"></span>
                                   Saving...
                               </span>
                           </button>


                           <button class="btn btn-secondary" wire:click="$set('showModel', false)">
                               Cancel
                           </button>
                       </div>

                   </div>
               </div>
           </div>
       @endif
   </div>
