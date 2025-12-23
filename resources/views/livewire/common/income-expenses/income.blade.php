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
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="title" class="form-label">Date <span class="text-danger">*</span></label>
                                <input data-nostart="null" type="text" placeholder="Date"
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
                                        placeholder="Income Category">
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
                                    <label for="title" class="form-label">Sub Category </label>
                                    <select id="sub_category_id" class="form-select select2"
                                        wire:model="sub_category_id" placeholder="Income Category">
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
                                            <label for="title" class="form-label">Quotation# <span
                                                    class="text-danger">*</span></label>
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
                                        <div class="form-group">
                                            <label for="title" class="form-label">Proforma#<span
                                                    class="text-danger">*</span></label>
                                            <select id="proforma_invoice_id" class="form-select select2"
                                                wire:model="proforma_invoice_id" placeholder="Proforma#">
                                                <option value=""></option>
                                                @foreach ($proformas as $id => $company_name)
                                                    <option wire:key='{{ $id }}' value="{{ $id }}"
                                                        @if ($proforma_invoice_id == $id) selected @endif>
                                                        {{ $company_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('proforma_invoice_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="title" class="form-label">Tour Reference <span
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
                                            <label for="title" class="form-label">Client Name</label>
                                            <select id="client_id" class="form-select select2" wire:model="client_id"
                                                placeholder="Client Name" disabled>
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
                                </div>
                            @endif


                            <div class="mb-3">
                                <label for="title" class="form-label">Amount <span
                                        class="text-danger">*</span></label>
                                <input type="number" placeholder="Amount"
                                    class="form-control @error('amount') is-invalid @enderror" wire:model="amount"
                                    @if ($proforma_invoice_id && $type == 1) readonly @endif>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="title" class="form-label">Payment Reference# <span
                                        class="text-danger">*</span></label>
                                <input type="text" placeholder="Payment Reference#"
                                    class="form-control @error('payment_reference') is-invalid @enderror"
                                    wire:model="payment_reference">
                                @error('payment_reference')
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

        <!-- Table Card -->
        <div class="col-md-7">
            <div class="card">




                {{-- <div class="card-header d-flex justify-content-between align-items-center flex-xxl-nowrap flex-wrap">

                    <div class="btn-group p-2 rounded border mb-xxl-0 mb-2" role="group">
                        <button wire:click="setTab(1)"
                            class="btn btn-sm {{ $tab == '1' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-start">
                            Tour Income
                            <span class="badge bg-primary ms-2">{{ $tour }}</span>
                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="setTab(1)"></i>
                        </button>
                        <button wire:click="setTab(2)"
                            class="btn btn-sm {{ $tab == '2' ? 'bluegradientbtn active shadow' : 'lightgradientbtn' }} px-4 py-2 rounded-end">
                            Other Income
                            <span class="badge bg-primary ms-2">{{ $offive }}</span>
                            <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="setTab(2)"></i>
                        </button>
                    </div>
                </div> --}}

                <div class="card-body">
                    <div class="table-responsive ecs-table">
                        <table class="table">
                            <thead class="lightgradient">
                                <tr>
                                    <th>Date</th>
                                    @if ($tab === 1)
                                        <th>Quotation#</th>
                                        <th>Proforma#</th>
                                    @endif
                                    <th>Amount</th>
                                    <th>Notes</th>
                                    <th class="width80">Actions</th>
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
                                            <td class="align-middle py-1">
                                                @if ($item?->proforma)
                                                    <a href="{{ route('common.view-proformainvoice', $item?->proforma?->uuid) }}"
                                                        class="">
                                                        {{ $item?->proforma?->proforma_invoice_no }}
                                                    </a>
                                                @else
                                                    <span>NA</span>
                                                @endif
                                            </td>
                                        @endif

                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item?->amount }} {{ $item?->quotation?->currency_label }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-1">
                                            <span class="">
                                                {{ $item?->notes }}
                                            </span>
                                        </td>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No {{ $pageTitle }} found.</td>
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
