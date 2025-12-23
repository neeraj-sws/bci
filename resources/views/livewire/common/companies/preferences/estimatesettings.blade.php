<div class="container my-4">

    <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
        <div class="row g-4">

            <div class="mb-1">
                <label for="title" class="form-label">Estimate Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('estimate_number') is-invalid @enderror"
                    wire:model="estimate_number" placeholder="Estimate Number">
                @error('estimate_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="estimate_title" class="form-label">Estimate Name</label>
                    <select id="estimate_title" class="form-select select2" wire:model="estimate_title">
                        <option value="" ></option>
                        <option value="estimate" @if ($estimate_title === 'estimate' ) selected @endif>Estimate</option>
                        <option value="quotation" @if ($estimate_title === 'quotation' ) selected @endif>Quotation</option>
                        <option value="quote" @if ($estimate_title === 'quote' ) selected @endif>Quote</option>
                    </select>
                </div>
                 @error('estimate_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label class="form-label">Terms & Conditions <span class="text-danger">*</span></label>
                <textarea class="form-control @error('terms_condition') is-invalid @enderror" wire:model="terms_condition"
                    rows="8"></textarea>
                @error('terms_condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label class="form-label">Customer Notes <span class="text-danger">*</span></label>
                <textarea class="form-control @error('customer_note') is-invalid @enderror" wire:model="customer_note" rows="8"></textarea>
                @error('customer_note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>




            <div class="col-12 mt-4 d-flex justify-content-end">
                <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                    Save Changes
                    <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"
                        wire:loading.delay></span>
                </button>
            </div>
        </div>
    </form>
</div>
