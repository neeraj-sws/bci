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
                                   <label>Hotel Name <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control text-capitalize" wire:model.defer="name">
                                   @error('name')
                                       <small class="text-danger">{{ $message }}</small>
                                   @enderror
                               </div>

                               <div class="col-md-6">
                                   <label>Hotel Type <span class="text-danger">*</span></label>
                                   <select class="form-select select2" id="hotel_type_id"
                                       wire:model.live="hotel_type_id">
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
                                   <label>Status</label>
                                   <select class="form-select select2" id="status" wire:model.defer="status">
                                       <option value="1">Active</option>
                                       <option value="0">Inactive</option>
                                   </select>
                               </div>

                               <div class="col-md-6">
                                   <label>Location</label>
                                   <input type="text" class="form-control" wire:model.defer="location">
                               </div>
                               <div class="col-md-6">
                                   <label>Rate Type </label>
                                   <select class="form-select select2" id="rate_type" wire:model="rate_type">
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
                                   <label>Hotel Category <span class="text-danger">*</span></label>
                                   <select class="form-select select2" id="hotel_category_id"
                                       wire:model.defer="hotel_category_id">
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
                                   <label>Meal Type <span class="text-danger">*</span></label>
                                   <select class="form-select select2" id="meal_type" wire:model.defer="meal_type"
                                       multiple>
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
                                       <label>
                                           Parent Chain
                                           <a href="javascript:void(0)" wire:click="openModal">+ Add</a>
                                       </label>
                                       <select class="form-select select2" id="parent_chain_id"
                                           wire:model.defer="parent_chain_id">
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
                                       <label>
                                           Marketing Company
                                           <a href="javascript:void(0)" wire:click="openModal">+ Add</a>
                                       </label>
                                       <select class="form-select select2" id="marketing_company_id"
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
