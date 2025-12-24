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
                               <select class="form-select" wire:model.defer="hotel_type_id">
                                   <option value="">Select</option>
                                   @foreach ($hotel_types as $type)
                                       <option value="{{ $type->id }}">{{ $type->title }}</option>
                                   @endforeach
                               </select>
                               @error('hotel_type_id')
                                   <small class="text-danger">{{ $message }}</small>
                               @enderror
                           </div>

                           <div class="col-md-6">
                               <label>Hotel Category <span class="text-danger">*</span></label>
                               <select class="form-select" wire:model.defer="hotel_category_id">
                                   <option value="">Select</option>
                                   @foreach ($hotel_categories as $cat)
                                       <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                   @endforeach
                               </select>
                               @error('hotel_category_id')
                                   <small class="text-danger">{{ $message }}</small>
                               @enderror
                           </div>

                           <div class="col-md-6">
                               <label>Parent Chain</label>
                               <input type="number" class="form-control" wire:model.defer="parent_chain_id">
                           </div>

                           <div class="col-md-6">
                               <label>Marketing Company</label>
                               <input type="number" class="form-control" wire:model.defer="marketing_company_id">
                           </div>

                           <div class="col-md-6">
                               <label>Status</label>
                               <select class="form-select" wire:model.defer="status">
                                   <option value="1">Active</option>
                                   <option value="0">Inactive</option>
                               </select>
                           </div>

                           <div class="col-md-12">
                               <label>Location</label>
                               <input type="text" class="form-control" wire:model.defer="location">
                           </div>

                           <div class="d-flex gap-2 mt-3">
                               <button type="submit" class="btn bluegradientbtn">
                                   {{ $isEditing ? 'Update Hotel' : 'Save Hotel' }}
                               </button>
                               <a href="{{ route('common.hotels') }}" class="btn btn-secondary">Cancel</a>
                           </div>

                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
