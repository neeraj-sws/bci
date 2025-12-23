<div class="container my-4">
 

    <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
        <div class="row g-4">

            <div class="mb-1">
                <label for="title" class="form-label">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                    wire:model="invoice_number" placeholder="Invoice Number">
                @error('invoice_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <label for="title" class="form-label">Invoice Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('invoice_title') is-invalid @enderror"
                    wire:model="invoice_title" placeholder="Invoice Title">
                @error('invoice_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="payment_terms" class="form-label">Default Payment Terms</label>
                    <select id="payment_terms" class="form-select select2" wire:model="payment_terms">
                        <option>Due On Receipt</option>
                        <option selected="" value="1">7 Day</option>
                        <option value="2">10 Day</option>
                        <option value="3">15 Day</option>
                        <option value="4">30 Day</option>
                        <option value="5">45 Day</option>
                        <option value="6">60 Day</option>
                        <option value="7">90 Day</option>
                    </select>
                </div>
            </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="column_layout" class="form-label">Column Layout</label>
                    <select id="column_layout" class="form-select select2" wire:model="column_layout">
                        <option value="0">Default Layout</option>
                        <option value="1">Multi-Column Layout</option>
                    </select>
                </div>
            </div>

            <div class="mb-1">
                <label class="form-label">Terms & Conditions <span class="text-danger">*</span></label>
                <textarea class="form-control @error('terms_condition') is-invalid @enderror" wire:model="terms_condition" rows="8"></textarea>
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
