   <div class="mx-5 mt-sm-0 mt-3">
       <div class="row">
           <div class="col-12">
               <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">
                   {{ $isEditing ? 'Edit Hotel' : 'Add New Hotel' }}
               </h6>
               <div class="card border-0 shadow-sm">
                   <div class="card-body p-4">
                       <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

                           <!-- ========== BASIC HOTEL INFORMATION ========== -->
                           <div class="form-section mb-4">
                               <h6 class="form-section-title text-uppercase fw-700 text-gray-800 fs-14 mb-3 pb-2" style="border-bottom: 2px solid #e8e8e8;">
                                  Basic Information
                               </h6>
                               <div class="row g-3">
                                   <!-- Hotel Name - Full Width -->
                                   <div class="col-md-6">
                                       <label class="form-label fw-600">Hotel Name <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control text-capitalize" placeholder="Enter hotel name"
                                           wire:model.defer="name">
                                       @error('name')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <!-- Hotel Type - 50% -->
                                   <div class="col-md-6">
                                       <label class="form-label fw-600">Hotel Type <span class="text-danger">*</span></label>
                                       <select class="form-select select2" id="hotel_type_id"
                                           placeholder="Select hotel type" wire:model.live="hotel_type_id">
                                           <option value="">Select Type</option>
                                           @foreach ($hotel_types as $type)
                                               <option value="{{ $type->id }}" @selected($type->id == $hotel_type_id)>
                                                   {{ $type->title }}</option>
                                           @endforeach
                                       </select>
                                       @error('hotel_type_id')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <!-- Hotel Category - 50% -->
                                   <div class="col-md-6">
                                       <label class="form-label fw-600">Hotel Category <span class="text-danger">*</span></label>
                                       <select class="form-select select2" id="hotel_category_id"
                                           placeholder="Select category" wire:model.defer="hotel_category_id">
                                           <option value="">Select Category</option>
                                           @foreach ($hotel_categories as $cat)
                                               <option value="{{ $cat->id }}" @selected($cat->id == $hotel_category_id)>
                                                   {{ $cat->title }}</option>
                                           @endforeach
                                       </select>
                                       @error('hotel_category_id')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <!-- Meal Type - 50% -->
                                   <div class="col-md-6">
                                       <label class="form-label fw-600">Meal Type <span class="text-danger">*</span></label>
                                       <select class="form-select select2" id="meal_type" wire:model="meal_type"
                                           placeholder="Select meal types" multiple>
                                           <option value="">Select</option>
                                           @foreach ($mealTypes as $value)
                                               <option value="{{ $value->id }}" @selected(in_array($value->id, $meal_type))>
                                                   {{ $value->title }}
                                               </option>
                                           @endforeach
                                       </select>
                                       @error('meal_type')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <!-- Rate Type - 50% -->
                                   <div class="col-md-6">
                                       <label class="form-label fw-600">Rate Type</label>
                                       <select class="form-select select2" id="rate_type" wire:model="rate_type"
                                           placeholder="Select rate type">
                                           <option value="">Select</option>
                                           @foreach ($rateTypes as $value)
                                               <option value="{{ $value->id }}" @selected($value->id == $rate_type)>
                                                   {{ $value->title }}</option>
                                           @endforeach
                                       </select>
                                       @error('rate_type')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <!-- Parent Chain / Marketing Company (Dynamic) -->
                                   @if ($hotel_type_id == 2)
                                       <div class="col-md-6" id="parent_chain_wrapper">
                                           <label class="form-label fw-600">
                                               Parent Chain
                                               <a href="javascript:void(0)" wire:click="openModal" class="text-primary text-decoration-none ms-2">
                                                   <i class="bx bx-plus"></i>Add New
                                               </a>
                                           </label>
                                           <select class="form-select select2" id="parent_chain_id"
                                               placeholder="Select parent chain" wire:model.defer="parent_chain_id">
                                               <option value="">Select</option>
                                               @forelse ($chainHotels as $hotel)
                                                   <option value="{{ $hotel->chain_id }}" @selected($hotel->chain_id == $parent_chain_id)>
                                                       {{ $hotel->title }}</option>
                                               @empty
                                                   <option value="" disabled>No chains available</option>
                                               @endforelse
                                           </select>
                                           @error('parent_chain_id')
                                               <small class="text-danger d-block mt-1">{{ $message }}</small>
                                           @enderror
                                       </div>
                                   @endif

                                   @if ($hotel_type_id == 1)
                                       <div class="col-md-6" id="marketing_company_wrapper">
                                           <label class="form-label fw-600">
                                               Marketing Company
                                               <a href="javascript:void(0)" wire:click="openModal" class="text-primary text-decoration-none ms-2">
                                                   <i class="bx bx-plus"></i>Add New
                                               </a>
                                           </label>
                                           <select class="form-select select2" id="marketing_company_id"
                                               placeholder="Select marketing company"
                                               wire:model.defer="marketing_company_id">
                                               <option value="">Select</option>
                                               @forelse ($marketedHotels as $hotel)
                                                   <option value="{{ $hotel->marketing_company_id }}"
                                                       @selected($hotel->marketing_company_id == $marketing_company_id)>
                                                       {{ $hotel->title }}</option>
                                               @empty
                                                   <option value="" disabled>No companies available</option>
                                               @endforelse
                                           </select>
                                           @error('marketing_company_id')
                                               <small class="text-danger d-block mt-1">{{ $message }}</small>
                                           @enderror
                                       </div>
                                   @endif
                               </div>
                           </div>

                           <!-- ========== LOCATION DETAILS ========== -->
                           <div class="form-section mb-4">
                               <h6 class="form-section-title text-uppercase fw-700 text-gray-800 fs-14 mb-3 pb-2" style="border-bottom: 2px solid #e8e8e8;">
                                   Location Details
                               </h6>
                               <div class="row g-3">
                                   <!-- Country, State, City in Same Row -->
                                   <div class="col-md-4">
                                       <label class="form-label fw-600">Country <span class="text-danger">*</span></label>
                                       <select id='country_id' class="form-select select2" wire:model="country_id"
                                           placeholder="Select country">
                                           <option value="">Select</option>
                                           @foreach ($countrys as $id => $name)
                                               <option value="{{ $id }}"
                                                   @if ($country_id == $id) selected @endif>{{ $name }}
                                               </option>
                                           @endforeach
                                       </select>
                                       @error('country_id')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <div class="col-md-4">
                                       <label class="form-label fw-600">State <span class="text-danger">*</span></label>
                                       <select id='state' class="form-select select2" wire:model="state"
                                           placeholder="Select state">
                                           <option value="">Select</option>
                                           @foreach ($states as $id => $name)
                                               <option value="{{ $id }}"
                                                   @if ($state == $id) selected @endif>{{ $name }}
                                               </option>
                                           @endforeach
                                       </select>
                                       @error('state')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <div class="col-md-4">
                                       <label class="form-label fw-600">City <span class="text-danger">*</span></label>
                                       <select id='city' class="form-select select2" wire:model="city"
                                           placeholder="Select city">
                                           <option value="">Select</option>
                                           @foreach ($citys as $id => $name)
                                               <option value="{{ $id }}"
                                                   @if ($city == $id) selected @endif>{{ $name }}
                                               </option>
                                           @endforeach
                                       </select>
                                       @error('city')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>
                               </div>
                           </div>

                           <!-- ========== NEARBY & CONNECTIVITY ========== -->
                           <div class="form-section mb-4">
                               <h6 class="form-section-title text-uppercase fw-700 text-gray-800 fs-14 mb-3 pb-2" style="border-bottom: 2px solid #e8e8e8;">
                                  Nearby & Connectivity
                               </h6>
                               <div class="row g-3">
                                   <div class="col-md-4">
                                       <label class="form-label fw-600">Park</label>
                                       <select class="form-select select2" id="park_id" wire:model="park_id"
                                           placeholder="Select park">
                                           <option value="">Select</option>
                                           @foreach ($parks as $park)
                                               <option value="{{ $park->park_id }}" @selected($park->park_id == $park_id)>
                                                   {{ $park->name }}</option>
                                           @endforeach
                                       </select>
                                       @error('park_id')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <div class="col-md-4">
                                       <label class="form-label fw-600">Preferred Airport</label>
                                       <input type="text" class="form-control" placeholder="e.g., Delhi Airport"
                                           wire:model.defer="preferred_airport">
                                       @error('preferred_airport')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>

                                   <div class="col-md-4">
                                       <label class="form-label fw-600">Preferred Railway Station</label>
                                       <input type="text" class="form-control" placeholder="e.g., Central Station"
                                           wire:model.defer="preferred_railway_station">
                                       @error('preferred_railway_station')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>
                               </div>
                           </div>

                           <!-- ========== STATUS SECTION ========== -->
                           <div class="form-section mb-4">
                               <h6 class="form-section-title text-uppercase fw-700 text-gray-800 fs-14 mb-3 pb-2" style="border-bottom: 2px solid #e8e8e8;">
                                   Settings
                               </h6>
                               <div class="row g-3">
                                   <div class="col-md-4">
                                       <label class="form-label fw-600">Status</label>
                                       <select class="form-select select2" id="status" wire:model.defer="status">
                                           <option value="1" @selected($status == 1)>Active</option>
                                           <option value="0" @selected($status == 0)>Inactive</option>
                                       </select>
                                       @error('status')
                                           <small class="text-danger d-block mt-1">{{ $message }}</small>
                                       @enderror
                                   </div>
                               </div>
                           </div>

                           <!-- ========== ACTION BUTTONS ========== -->
                           <div class="d-flex gap-2 mt-4 pt-3 border-top">
                               <button type="submit" class="btn bluegradientbtn px-4" wire:loading.attr="disabled">
                                  {{ $isEditing ? 'Update Hotel' : 'Save Hotel' }}
                               </button>
                               <a href="{{ route('common.hotel-list') }}" class="btn btn-outline-secondary px-4">
                                   Cancel
                               </a>
                           </div>

                       </form>
                   </div>
               </div>
           </div>
       </div>
       <!-- ========== MODAL DIALOG ========== -->
       @if ($showModel)
           <div class="modal show d-block" style="background: rgba(0,0,0,0.55);">
               <div class="modal-dialog modal-dialog-centered">
                   <div class="modal-content border-0 shadow-lg">
                       <div class="modal-header border-bottom bg-light">
                           <h5 class="modal-title fw-700 text-gray-800">
                               <i class="bx bx-plus-circle me-2" style="color: #0066cc;"></i>{{ $modalTitle }}
                           </h5>
                           <button type="button" class="btn-close" wire:click="$set('showModel', false)"></button>
                       </div>
                       <div class="modal-body p-4">
                           <div class="mb-3">
                               <label class="form-label fw-600">Title</label>
                               <input type="text" class="form-control" placeholder="Enter title" wire:model.defer="newTitle">
                               @error('newTitle')
                                   <small class="text-danger d-block mt-1">{{ $message }}</small>
                               @enderror
                           </div>
                       </div>
                       <div class="modal-footer border-top">
                           <button type="button" class="btn btn-outline-secondary" wire:click="$set('showModel', false)">
                               Close
                           </button>
                           <button class="btn bluegradientbtn" wire:click="saveModalData" wire:loading.attr="disabled">
                               <span wire:loading.remove wire:target="saveModalData">
                                   <i class="bx bx-check me-2"></i>Save
                               </span>
                               <span wire:loading wire:target="saveModalData">
                                   <span class="spinner-border spinner-border-sm me-2"></span>Saving...
                               </span>
                           </button>
                       </div>
                   </div>
               </div>
           </div>
       @endif
   </div>
