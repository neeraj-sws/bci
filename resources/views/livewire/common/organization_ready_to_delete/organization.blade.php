<div class="container  mt-sm-0 mt-3">
    
    
    <h3 class="mb-4 position-relative d-inline-block fw-bold mainheadingtext">
    @if($isAdmin) Organization @else User @endif Profile
    <span class="gradient-border-bottom"></span>
  </h3>
  
  <div class="row">
    @if($isAdmin)
    
    <div class="row">
           <div class="col-8">
  <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
    <div class="row g-4">

      <!-- Logo Upload Box - Enhanced with Hover Effect -->
      <div class="col-md-6">
        <label class="form-label fw-bold">
          Your Logo <span class="text-danger">*</span>
        </label>

        <div
          class="logo-upload-container radius12 d-flex align-items-center justify-content-center position-relative overflow-hidden transition3 text-center"
          onclick="document.getElementById('logoInput').click()"
          onmouseover="this.querySelector('.upload-overlay').style.opacity = '1'"
          onmouseout="this.querySelector('.upload-overlay').style.opacity = '0'"
          >
          @if($logo)
            <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" />
          @elseif($organization && $organization->file_id)
            <img src="{{ asset('assets/images/' . $organization->logo->file) }}" alt="Logo" />
          @else
            <div class="p-3">
              <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                <line x1="16" y1="5" x2="22" y2="5"></line>
                <line x1="19" y1="2" x2="19" y2="8"></line>
                <circle cx="9" cy="9" r="2"></circle>
                <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
              </svg>
              <div>Click to upload or drag and drop</div>
              <div class="fs-12 midgreycolor">PNG, JPG up to 5MB</div>
            </div>
          @endif
          
          <!-- Hover Overlay -->
          <div class="upload-overlay d-flex align-items-center justify-content-center text-white">
            <div class="p-3 text-center">
              <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                <line x1="16" y1="5" x2="22" y2="5"></line>
                <line x1="19" y1="2" x2="19" y2="8"></line>
              </svg>
              <div>Upload new logo</div>
            </div>
          </div>
          
          <input type="file" id="logoInput" class="d-none" wire:model="logo" accept="image/*" />
        </div>

        @error('logo')
          <div class="text-danger mt-2 fs-12">{{ $message }}</div>
        @enderror
      </div>

      <!-- Organization Name -->
      <div class="col-md-6">
        <label class="form-label fw-bold">
          Organization Name <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control @error('organization_name') is-invalid @enderror" 
          wire:model="organization_name" 
          placeholder="Enter organization name" 
          
        />
        @error('organization_name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- City/Suburb -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          City/Suburb <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control @error('city') is-invalid @enderror" 
          wire:model="city" 
          placeholder="City/Suburb"
        />
        @error('city')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- State -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          State <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control @error('state') is-invalid @enderror" 
          wire:model="state" 
          placeholder="State"
        />
        @error('state')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Zip Code -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Zip Code
        </label>
        <input type="text" class="form-control" 
          wire:model="zip_code" 
          placeholder="Zip Code" 
         />
      </div>

      <!-- Country -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Country <span class="text-danger">*</span>
        </label>
        <input type="text" class="form-control @error('country') is-invalid @enderror" 
          wire:model="country" 
          placeholder="Country" 
          />
        @error('country')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Time Zone -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Time Zone
        </label>
      <input type="text" class="form-control" wire:model="time_zone" />
      </div>

      <!-- Company/Tax ID -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Company/Tax ID
        </label>
        <input type="text" class="form-control" 
          wire:model="company_tax_id" 
          placeholder="Company/Tax ID" 
         />
      </div>

      <!-- Phone -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Phone
        </label>
        <input type="text" class="form-control" 
          wire:model="phone" 
          placeholder="Phone Number" 
          />
      </div>

      <!-- Fax Number -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Fax Number
        </label>
        <input type="text" class="form-control" 
          wire:model="fax_number" 
          placeholder="Fax Number" 
          />
      </div>

      <!-- Website -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Website
        </label>
        <input type="url" class="form-control" 
          wire:model="website" 
          placeholder="Website URL" 
         />
      </div>

      <!-- Fiscal Year -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Fiscal Year
        </label>
        <select class="form-select select2" 
        id="fiscal_year"
          wire:model="fiscal_year" 
        >
          <option value="">Select Fiscal Year</option>
          @foreach ($ficalYears as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Currency -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Base Currency
        </label>
        <select class="form-select select2"
        id='currency' 
          wire:model="currency" 
        >
          <option value="">Select Currency</option>
          @foreach ($currencys as $id => $currencyname)
            <option value="{{ $id }}">{{ $currencyname }}</option>
          @endforeach
        </select>
      </div>

      <!-- Language -->
      <div class="col-md-4">
        <label class="form-label fw-bold">
          Language
        </label>
        <input type="text" class="form-control" 
          wire:model="language" 
          placeholder="Language" 
          />
      </div>

      <!-- Street Address -->
      <div class="col-12">
        <label class="form-label fw-bold">
          Street Address <span class="text-danger">*</span>
        </label>
        <textarea rows="3" class="form-control textareaminheight @error('street_address') is-invalid @enderror" 
          wire:model="street_address" 
          placeholder="Street Address"></textarea>
        @error('street_address')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Submit Button -->
      <div class="col-12 mt-4 d-flex justify-content-end">
        <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
          Save Changes
          <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" wire:loading.delay></span>
        </button>
      </div>
    </div>
  </form>
     </div>
        <div class="col-4">
  <livewire:common.organization.users />
    </div>
  </div>
  
    @else
  <livewire:common.organization.users />
    @endif
    </div>
</div>