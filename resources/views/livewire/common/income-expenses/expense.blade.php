<div class="container mt-sm-0 mt-3" id="amanity">

    <div class="page-breadcrumb flex-wrap d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">{{ $pageTitle }} </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Card -->
        @can('expenses manage')
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="title" class="form-label">Expense Date <span
                                        class="text-danger">*</span></label>
                                <input data-nostart="null" type="text" placeholder="Expense Date"
                                    class="form-control datepicker @error('date') is-invalid @enderror"
                                    wire:model="date">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select id="category_id" class="form-select select2" wire:model="category_id"
                                        placeholder="Expense Category">
                                        <option value=""></option>
                                        @foreach ($categorys as $id => $name)
                                            <option wire:key='{{ $id }}' value="{{ $id }}"
                                                @if ($category_id === $id) selected @endif>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Sub Category</label>
                                    <select id="sub_category_id" class="form-select select2"
                                        wire:model="sub_category_id" placeholder="Expense Category">
                                        <option value=""></option>
                                        @foreach ($subcategorys as $id => $name)
                                            <option wire:key='{{ $id }}' value="{{ $id }}"
                                                @if ($sub_category_id === $id) selected @endif>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_category_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            @if ($type == 1)
                                <div class="loading">
                                     <div class="mb-3">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Trip#</label>
                                            <select id="trip_id" class="form-select select2"
                                                wire:model="trip_id" placeholder="Trip#">
                                                <option value=""></option>
                                                @foreach ($trips as $id => $company_name)
                                                    <option value="{{ $id }}"
                                                        @if ($trip_id === $id) selected @endif>
                                                        {{ $company_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('trip_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Quotation#</label>
                                            <select id="quotation_id" class="form-select select2"
                                                wire:model="quotation_id" placeholder="Quotation#">
                                                <option value=""></option>
                                                @foreach ($quotations as $id => $company_name)
                                                    <option value="{{ $id }}"
                                                        @if ($quotation_id === $id) selected @endif>
                                                        {{ $company_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('quotation_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Reference <span
                                                class="text-danger">*</span></label>
                                        <input type="text" placeholder="Reference"
                                            class="form-control @error('reference') is-invalid @enderror"
                                            wire:model="reference" disabled>
                                        @error('reference')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Tourist Name</label>
                                            <select id="client_id" class="form-select select2" wire:model="client_id"
                                                placeholder="Tourist Name" disabled>
                                                <option value=""></option>
                                                @foreach ($clients as $id => $company_name)
                                                    <option value="{{ $id }}"
                                                        @if ($client_id === $id) selected @endif>
                                                        {{ $company_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('client_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Tour Name</label>
                                            <select id="tour_id" class="form-select select2" wire:model="tour_id"
                                                placeholder="Tour Name" disabled>
                                                <option value=""></option>
                                                @foreach ($tours as $id => $name)
                                                    <option value="{{ $id }}"
                                                        @if ($tour_id === $id) selected @endif>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tour_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label for="title" class="form-label">Vendor Name <span
                                                class="text-danger">*</span></label>
                                        <select id="vendor_id" class="form-select select2" wire:model="vendor_id"
                                            placeholder="Vendor Name">
                                            <option value=""></option>
                                            @foreach ($vendores as $id => $name)
                                                <option value="{{ $id }}"
                                                    @if ($vendor_id === $id) selected @endif>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif




                            <div class="mb-3">
                                <label for="title" class="form-label">Amount <span
                                        class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">INR</span>
                                <input type="number" placeholder="Amount"
                                    class="form-control @error('amount') is-invalid @enderror" wire:model="amount">
                                     </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Notes <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" placeholder="Notes" class="form-control @error('notes') is-invalid @enderror"
                                    wire:model="notes"></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                                {{ $isEditing ? 'Update changes' : 'Save changes' }}
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="{{ $isEditing ? 'update' : 'store' }}"></i>
                            </button>
                            <button type="button" wire:click="resetForm"
                                class="btn btn-secondary greygradientbtn">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @endcan

        <!-- Table Card -->
        <div class="@can('expenses manage') col-md-7 @else col-md-12 @endcan">
            <div class="card">


                <div class="card-header d-flex justify-content-between align-items-center flex-xxl-nowrap flex-wrap gap-3">

                    <div class="form-group col-4">
                        <select id="catgorie_filter_id" class="form-select select2" wire:model="catgorie_filter_id"
                            placeholder="Expense Category">
                            <option value=""></option>
                            @foreach ($categorys as $id => $name)
                                <option value="{{ $id }}"
                                    @if ($catgorie_filter_id === $id) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('catgorie_filter_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-4">
                        <select id="sub_catgorie_filter_id" class="form-select select2"
                            wire:model="sub_catgorie_filter_id" placeholder="Expense Sub Category">
                            <option value=""></option>
                            @foreach ($Filtersubcategorys as $id => $name)
                                <option value="{{ $id }}"
                                    @if ($sub_catgorie_filter_id === $id) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_catgorie_filter_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    
                        <div class="col-3 text-end">
                            <button class="btn bluegradientbtn" wire:click="clearFilters">clear
                                <i class="spinner-border spinner-border-sm" wire:loading.delay
                                    wire:target="clearFilters"></i>
                            </button>
                        </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th>Date</th>
                                    @if ($tab === 1)
                                        <th>Quotation#</th>
                                    @endif
                                    <th>Amount</th>
                                    <th>Notes</th>
        @can('expenses manage')
                                    <th class="width80">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    <tr wire:key="{{ $item->id }}">
                                        <td class="align-middle py-1">
                                            {{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : 'NA' }}

                                        </td>
                                        @if ($tab === 1)
                                            <td class="align-middle py-1">
                                                @if ($item?->quotation)
                                                    <a href="{{ route('common.view-quotation', $item?->quotation?->uuid) }}"
                                                        class="">
                                                        {{ $item?->quotation?->quotation_no }}
                                                    </a>
                                                @else
                                                    <span>NA</span>
                                                @endif
                                            </td>
                                        @endif

                                        <td class="align-middle py-1">
                                            {{-- <span class="">
                                                {{ $item?->amount }} {{ $item?->quotation?->currency_label }}
                                            </span> --}}
                                            <span class="">
                                                {{ $item?->amount }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item?->notes }}
                                            </span>
                                        </td>
        @can('expenses manage')
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
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No Expenses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($items->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
                            <div class="text-muted small">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                {{ $items->total() }}
                                entries
                            </div>
                            <div>
                                {{ $items->links('livewire::bootstrap') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
