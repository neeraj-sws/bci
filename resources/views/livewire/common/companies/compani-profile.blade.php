    <div class="col-12">
                <style>
        /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
    </style>
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row">

                        <div class="col-md-6">
                            <label class="form-label">
                                Company Logo <span class="text-danger">*</span>
                            </label>

                            <div class="logo-upload-container radius12 d-flex align-items-center justify-content-center position-relative overflow-hidden transition3 text-center"
                                onclick="document.getElementById('logoInput').click()"
                                onmouseover="this.querySelector('.upload-overlay').style.opacity = '1'"
                                onmouseout="this.querySelector('.upload-overlay').style.opacity = '0'">

                                <div wire:loading wire:target="company_file_id"
                                    class="position-absolute top-50 start-50 translate-middle text-center">
                                    <div class="spinner-border text-dark" role="status">
                                        <span class="visually-hidden">Uploading...</span>
                                    </div>
                                    <div class="mt-1 text-white">Uploading...</div>
                                </div>

                                @if ($company_file_id)
                                    <img src="{{ $company_file_id->temporaryUrl() }}" alt="Logo Preview" />
                                @elseif($organization && $organization->company_file_id)
                                    <img src="{{ asset("uploads/companies/{$organization->id}/" . $organization?->logo?->file) }}"
                                        alt="Logo" />
                                @else
                                    <div class="p-3">
                                        <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="32"
                                            height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                        <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                            <line x1="16" y1="5" x2="22" y2="5">
                                            </line>
                                            <line x1="19" y1="2" x2="19" y2="8">
                                            </line>
                                        </svg>
                                        <div>Upload new logo</div>
                                    </div>
                                </div>





                                <input type="file" id="logoInput" class="d-none" wire:model="company_file_id"
                                    accept="image/*" />
                            </div>

                            @error('company_file_id')
                                <div class="text-danger mt-2 fs-12">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-capitalize" wire:model="company_name"
                                placeholder="Company name">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Primary Contact -->
                        <div class="col-md-6 my-3">
                            <label class="form-label">Contact <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model="company_contact"
                                placeholder="Contact">
                            @error('company_contact')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="col-md-6 my-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" wire:model="company_email" placeholder="Email">
                            @error('company_email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>



                        <!-- Country -->
                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
                            <label class="form-label">City/Suburb <span class="text-danger">*</span></label>
                            <select id='city' class="form-select select2" wire:model="city">
                                <option value="">Select City</option>
                                @foreach ($citys as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($city == $id) selected @endif>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Zip Code -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label ">
                                Zip Code
                            </label>
                            <input type="text" class="form-control" wire:model="zip_code"
                                placeholder="Zip Code" />
                                         @error('zip_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Time Zone -->
                        <div class="col-md-4 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Timezone</label>
                                <select class="form-control" wire:model="time_zone">
                                     <option value="">Select Timezone</option>
                                    @foreach (timezone_identifiers_list() as $tz)
                                        <option value="{{ $tz }}" @selected($tz == $time_zone)>
                                            {{ $tz }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        
                        
                                  <div class="col-md-4 mb-3">
                            <label class="form-label">
                                SAC/Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" wire:model="sac_code"
                                placeholder="SAC/Code" />
                                
                                        @error('sac_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Company/Tax ID -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Company/Tax ID
                            </label>
                            <input type="text" class="form-control" wire:model="company_tax_id"
                                placeholder="Company/Tax ID" />
                        </div>


                        <!-- Fax Number -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Fax Number
                            </label>
                            <input type="text" class="form-control" wire:model="fax_number"
                                placeholder="Fax Number" />
                        </div>

                        <!-- Website -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Website
                            </label>
                            <input type="url" class="form-control" wire:model="website"
                                placeholder="Website URL" />
                        </div>

                        <!-- Fiscal Year -->
                        {{-- <div class="col-md-4 mb-3 d-none" >
                            <label class="form-label">
                                Fiscal Year
                            </label>
                            <select class="form-select select2" id="fiscal_year" wire:model="fiscal_year">
                                <option value="">Select Fiscal Year</option>
                                @foreach ($ficalYears as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($fiscal_year == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Currency -->
                        {{-- <div class="col-md-4 mb-3 d-none">
                            <label class="form-label">
                                Base Currency
                            </label>
                            <select class="form-select select2" id='currency' wire:model="currency">
                                <option value="">Select Currency</option>
                                @foreach ($currencys as $id => $currencyname)
                                    <option value="{{ $id }}"
                                        @if ($currency == $id) selected @endif>{{ $currencyname }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Language -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Language
                            </label>
                            <input type="text" class="form-control" wire:model="language"
                                placeholder="Language" />
                        </div>


                        <!-- Street Address -->
                        <div class="col-12">
                            <label class="form-label">
                                Street Address <span class="text-danger">*</span>
                            </label>
                            <textarea rows="3" class="form-control textareaminheight @error('company_address') is-invalid @enderror"
                                wire:model="company_address" placeholder="Street Address"></textarea>
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>




                    </div>

                    <div class="d-flex gap-2 my-3">
                        <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                            {{ $organization ? 'Update changes' : 'Save changes' }}
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                    aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
