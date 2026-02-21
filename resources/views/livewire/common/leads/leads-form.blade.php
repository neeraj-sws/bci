<div class="mx-5 mt-sm-0 mt-3">
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
                    <li class="breadcrumb-item"><a href="{{ route($route.'.lead') }}"><i class="bx bx-home-alt"></i></a>
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




                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source</label>
                            <select id='source_id' class="form-select select2" wire:model="source_id">
                                <option value="">Select Lead Source</option>
                                @foreach ($sources as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($source_id == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Company Name -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Tourist Name <span class="text-danger">*</span>

                                <span wire:click="changeclient" style="cursor: pointer;color: #E41F07;"><i
                                        class="lni lni-plus me-2"></i>{{ $addClient ? 'Cancel' : 'Add New' }} <span
                                        wire:loading wire:target="changeclient">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"
                                            aria-hidden="true"></span>
                                    </span></span>
                            </label>

                            @if ($addClient)
                                <input type="text" class="form-control text-capitalize" wire:model="client_name"
                                    placeholder="Tourist Name">
                                @error('client_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            @else
                                <select id='client_id' class="form-select select2" wire:model.live="client_id">
                                    <option value="">Select Tourist</option>
                                    @foreach ($clients as $id => $name)
                                        <option value="{{ $id }}"
                                            @if ($client_id == $id) selected @endif>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            @endif

                        </div>

                        <!-- Primary Contact -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model="contact" placeholder="Contact">
                            @error('contact')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" wire:model="email" placeholder="Email">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lead Type</label>
                            <select id='pipeline_id' class="form-select select2" wire:model="pipeline_id">
                                <option value="">Select Type</option>
                                @foreach ($groups as $id => $name)
                                    <option value="{{ $id }}"
                                        @if ($pipeline_id == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (!Auth::guard('web')->user()->hasRole('sales'))
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sales Man</label>
                                <select id='user_id' class="form-select select2" wire:model="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $id => $name)
                                        <option value="{{ $id }}"
                                            @if ($user_id == $id) selected @endif>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if ($addClient)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reference</label>
                            <input type="reference" class="form-control" wire:model="reference" placeholder="Tourist Reference">
                            @error('reference')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif


                       <div class="col-6">
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="title" class="form-label">Base Currency</label>
                                <select id="currency" class="form-select select2" wire:model="currency"
                                    placeholder="Select Base Currency">
                                    <option value=""></option>
                                    @foreach ($currencys as $code => $currencyName)
                                        <option value="{{ $code }}"
                                            @if ($currency == $code) selected @endif>
                                            {{ $currencyName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

<div class="col-md-12 mb-3">
    <label class="form-label">Tags#</label>

    <div wire:ignore>
        <select id="select-tags" multiple data-placeholder="Select tags">
            @foreach($tags as $id => $name)
                <option value="{{ $name }}"
                    @if(in_array($name, $selectedTags ?? [])) selected @endif>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>
</div>


                        <div class="col-12 mt-4">
                            <h5 class="mb-3 border-bottom pb-2">Additional Information</h5>
                            <div class="row">

                                <!-- City/Suburb -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" wire:model="address"
                                        placeholder="Address">
                                </div>

                                <!-- Country -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <select id='country_id' class="form-select select2" wire:model="country_id">
                                        <option value="">Select Country</option>
                                        @foreach ($countrys as $id => $name)
                                            <option value="{{ $id }}"
                                                @if ($country_id == $id) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                   <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <select id='state' class="form-select select2" wire:model="state">
                                <option value="">Select State</option>
                                @foreach($states as $id => $name)
                                    <option value="{{ $id }}" @if ($state ==  $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                            <div class="col-md-6 mb-3">
                            <label class="form-label">City/Suburb</label>
                            <select id='city' class="form-select select2" wire:model="city">
                                <option value="">Select City</option>
                                @foreach($citys as $id => $name)
                                    <option value="{{ $id }}" @if ($city ==  $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>


                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="mb-3 border-bottom pb-2">Travel Details</h5>
                            <div class="row">

                                <!-- destination -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Destination</label>
                                    <input type="text" class="form-control text-capitalize" wire:model="destination"
                                        placeholder="Destination">
                                </div>

                                <!-- travel_date -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Preferred Travel Dates</label>
                                    <input type="text" class="form-control datepicker" wire:model="travel_date" data-allow-past="{{ config('app.data_allow_past') ? 'true' : 'false' }}" data-role="start" data-group="booking1">

                                              @error('travel_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Travel Duration (Days)</label>
                                    <input type="number" class="form-control" wire:model="travel_days">
                                </div>

                               {{-- <div class="col-md-6 mb-3">
                                    <label class="form-label">Budget Range</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"
                                                            id="basic-addon1">{{ $currency }}</span>
                                    <input type="number"  class="form-control" wire:model="budget">
                                    </div>
                                </div> --}}

                             <div class="col-md-6 mb-3">
                                <label class="form-label">Budget Range</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">{{ $currency }}</span>

                                    <input type="text"
                                        class="form-control"
                                        x-data="{ value: @entangle('budget').live }"
                                        x-init="$watch('value', v => $el.value = Number(v || 0).toLocaleString())"
                                        x-on:input="
                                            let raw = $event.target.value.replace(/,/g, '');
                                            value = raw;
                                            $event.target.value = Number(raw || 0).toLocaleString();
                                        "
                                    >
                                </div>
                            </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label ">Follow up Date</label>
                                    <input type="text" class="form-control datepicker" data-allow-past="{{ config('app.data_allow_past') ? 'true' : 'false' }}"
                                        wire:model.live="follow_up_date" data-role="end" data-group="booking1" data-max-from="travel_date">

                                                            @error('follow_up_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Follow up Time</label>
                                    <input type="text" class="form-control timepicker"
                                        wire:model="follow_up_time" @if(!$follow_up_date) disabled @endif>
                                            @error('follow_up_time')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Remark <span class="text-danger">*</span></label>
                                    <textarea class="form-control" wire:model="remark"> </textarea>
                                    @error('remark')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Attach File</label>

                                    <input type="file" wire:model="files" accept="image/*">

                                    <div wire:loading wire:target="files" class="mt-2 text-blue-600">
                                        Uploading images, please wait...
                                    </div>

                                    @if ($files)
                                        <div class="mt-3 flex flex-wrap gap-3">
                                            @foreach ($files as $index => $file)
                                                <div>
                                                    <img src="{{ $file->temporaryUrl() }}" alt="Preview"
                                                        style="max-height: 160px; object-fit: contain;" />
                                                         <button type="button"
                                                            wire:click="removeFile({{ $index }})"
                                                            class="absolute top-1 right-1 btn btn-danger">
                                                            Delete
                                                        </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if (count($existingImages) > 0)
                                        <div class="mt-5">
                                            <h4>Uploaded Images:</h4>
                                            <div class="flex flex-wrap gap-3 mt-2">
                                                @foreach ($existingImages as $image)
                                                    <div class="relative">
                                                        <img src="{{ asset('uploads/leads/' . $this->itemId . '/' .  $image->file) }}"
                                                            style="max-height: 160px; object-fit: contain;" />
                                                        <button type="button"
                                                            wire:click="deleteImage({{ $image->id }})"
                                                            class="absolute top-1 right-1 btn btn-danger">
                                                            Delete
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>


                            </div>
                        </div>



                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                            {{ $itemId ? 'Update changes' : 'Save changes' }}
                            <span
                                        wire:loading wire:target="save">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"
                                            aria-hidden="true"></span>
                                    </span>
                        </button>
                        <a href="{{ route($route . '.lead') }}" class="btn btn-secondary greygradientbtn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@push('scripts')
           <script>
document.addEventListener('livewire:init', () => {
    const selectEl = document.getElementById('select-tags');

    if (selectEl.tomselect) {
        selectEl.tomselect.destroy();
    }

    const tom = new TomSelect("#select-tags", {
        plugins: ['remove_button'],
        create: true,
        sortField: { field: "text", direction: "asc" },

        render: {
            dropdown: function(data, escape) {
                let html = '<div class="p-2 text-muted border-bottom bg-light">💡 Hint: Select or type to create new tags</div>';
                html += '<div class="ts-dropdown-content"></div>';
                return html;
            },
            option: function(data, escape) {
                return '<div class="d-flex align-items-center justify-content-between">' +
                    '<span>' + escape(data.text) + '</span>' +
                    (data.date ? '<span class="text-muted small">' + escape(data.date) + '</span>' : '') +
                '</div>';
            },
            item: function(data, escape) {
                return '<div>' + escape(data.text) + '</div>';
            }
        }
    });
    selectEl.addEventListener('change', function () {
        const componentId = selectEl.closest('[wire\\:id]').getAttribute('wire:id');
        Livewire.find(componentId).set('selectedTags', tom.getValue());
    });
});

</script>
@endpush
