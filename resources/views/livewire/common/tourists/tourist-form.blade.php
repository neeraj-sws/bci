<div class="container  mt-sm-0 mt-3">
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
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">
                {{ $itemId ? 'Edit' : 'Add' }} {{ $pageTitle }}
            </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route($route . '.tourist') }}"><i
                                class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">{{ $pageTitle }}</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $itemId ? 'Edit' : 'Add' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <!-- Company Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control text-capitalize" wire:model="company_name"
                                placeholder="Company name">
                            @error('company_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Primary Contact -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tourist Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-capitalize" wire:model.blur="primary_contact"
                                placeholder="Primary Contact">
                            @error('primary_contact')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" wire:model="contact_email"
                                placeholder="Contact Email">
                        </div>

                        <!-- Contact Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="number" class="form-control" wire:model="contact_phone"
                                placeholder="Contact Phone">
                        </div>

                        <!-- Currency -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Currency</label>
                            <select id='currency' class="form-select select2" wire:model="currency">
                                <option value="">Select Currency</option>
                                @foreach ($currencys as $id => $currency_name)
                                    <option value="{{ $id }}"
                                        @if ($currency == $id) selected @endif>{{ $currency_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source (Reference)</label>
                            <input type="reference" class="form-control" wire:model="reference"
                                placeholder="Tourist Reference">
                            @error('reference')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Country -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>
                            <select id='country' class="form-select select2" wire:model="country">
                                <option value="">Select Country</option>
                                @foreach ($countrys as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($country == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <select id='state' class="form-select select2" wire:model="state">
                                <option value="">Select State</option>
                                @foreach ($states as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($state == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">City/Suburb</label>
                            <select id='city_suburb' class="form-select select2" wire:model="city_suburb">
                                <option value="">Select City</option>
                                @foreach ($citys as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($city_suburb == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" class="form-control" wire:model="address"
                                placeholder="Street Address">
                        </div>

                        <!-- Postal/Zip Code -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Postal/Zip Code</label>
                            <input type="text" class="form-control" wire:model="zip_code"
                                placeholder="Postal/Zip Code">
                        </div>

                            <div class="row">
                                <!-- Birthday -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Birthday</label>
                                    {{-- data-start-year="2000"  --}}
                                    <input data-nostart="null"  data-restrict-future="true" id='birthday' type="text"
                                        class="form-control datepicker" wire:model="birthday">
                                </div>

                                <!-- Anniversary -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Anniversary</label>
                                    <input data-nostart="null" id='company_anniversary' type="text"
                                        data-restrict-future="true" class="form-control datepicker"
                                        wire:model="company_anniversary">
                                </div>
                            </div>


                        <div class="col-12 mt-4">
                            <h5 class="mb-3 border-bottom pb-2">Other Details</h5>
                            <div class="row">
                                <!-- Tax ID -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company/Tax ID</label>
                                    <input type="text" class="form-control" wire:model="tax_id"
                                        placeholder="Company Tax ID">
                                </div>

                                <!-- Website -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Website</label>
                                    <input type="url" class="form-control" wire:model="website"
                                        placeholder="Website URL">
                                </div>



                                <!-- Payment Terms -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Terms</label>
                                    <input type="text" class="form-control" wire:model="payment_terms"
                                        placeholder="Payment Terms">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                            {{ $itemId ? 'Update changes' : 'Save changes' }}
                            <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="save"></i>
                        </button>
                        <a href="{{ route($route . '.tourist') }}"
                            class="btn btn-secondary greygradientbtn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($showExistingModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tourist Already Exists</h5>
                </div>
                <div class="modal-body text-start">
                    @php
                        $tourist = $existingTourist;
                    @endphp
                    @if($tourist)
                        <p>
                            The tourist <strong>{{ $tourist->primary_contact }}</strong> already exists.
                        </p>
                        <table class="table table-bordered">
                           <tr><th>Company</th><td>{{ $tourist?->company_name ?? 'NA' }}</td></tr>
<tr><th>Email</th><td>{{ $tourist?->contact_email ?? 'NA' }}</td></tr>
<tr><th>Phone</th><td>{{ $tourist?->contact_phone ?? 'NA' }}</td></tr>
<tr><th>Currency</th><td>{{ $tourist?->base_currency_code ?? 'NA' }}</td></tr>
                        </table>
                    @endif
                    <p>Do you want to continue with this tourist’s details?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary greygradientbtn" wire:click="handleTourist(2)">Create New Tourist</button>
                    <button type="button" class="btn bluegradientbtn" wire:click="handleTourist(1)">Continue</button>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>
