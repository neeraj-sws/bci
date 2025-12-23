<div class="container my-4">

    <form wire:submit.prevent="save" class="radius12 bg-white settingforms columnsettings">
        <div class="row g-4">
            <div>
                <!-- ITEMS -->
                <div class="mb-3">
                    <label class="form-label fw-bold">1. Items: <span class="text-danger">*</span></label><br>

                    @foreach (['items' => 'Items', 'products' => 'Products', 'services' => 'Services'] as $value => $label)
                        <div class="form-check">
                            <input type="radio" wire:model="items" class="form-check-input"
                                value="{{ $value }}" id="item_{{ $value }}">
                            <label class="form-check-label" for="item_{{ $value }}">{{ $label }}</label>
                        </div>
                    @endforeach

                    <div class="form-check">
                        <input type="radio" wire:model="items" class="form-check-input" value="other" id="itemOther">
                        <label class="form-check-label" for="itemOther">Other:</label>
                        <input type="text" class="form-control mt-1" placeholder="Specify other item"
                            wire:model="items_other" />
                    </div>

                    @error('items')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- UNITS -->
                <div class="mb-3">
                    <label class="form-label fw-bold">2. Units: <span class="text-danger">*</span></label><br>

                    @foreach (['qty' => 'Qty', 'hours' => 'Hours'] as $value => $label)
                        <div class="form-check">
                            <input type="radio" wire:model="units" class="form-check-input"
                                value="{{ $value }}" id="unit_{{ $value }}">
                            <label class="form-check-label" for="unit_{{ $value }}">{{ $label }}</label>
                        </div>
                    @endforeach

                    <div class="form-check">
                        <input type="radio" wire:model="units" class="form-check-input" value="other" id="unitOther">
                        <label class="form-check-label" for="unitOther">Other:</label>
                        <input type="text" class="form-control mt-1" placeholder="Specify other unit"
                            wire:model="units_other" />
                    </div>

                    @error('units')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- AMOUNT -->
                <div class="mb-3">
                    <label class="form-label fw-bold">3. Amount: <span class="text-danger">*</span></label><br>

                    <div class="form-check">
                        <input type="radio" wire:model="amount" class="form-check-input" value="amount"
                            id="amount_standard">
                        <label class="form-check-label" for="amount_standard">Amount</label>
                    </div>

                    <div class="form-check">
                        <input type="radio" wire:model="amount" class="form-check-input" value="other"
                            id="amountOther">
                        <label class="form-check-label" for="amountOther">Other:</label>
                        <input type="text" class="form-control mt-1" wire:model="amount_other" />
                    </div>

                    @error('amount')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- CUSTOM FIELDS -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Choose custom fields:</label><br>

                    <div class="form-check">
                        <input type="checkbox" wire:model="date" class="form-check-input" id="date">
                        <label class="form-check-label" for="date">Date</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" wire:model="time" class="form-check-input" id="time">
                        <label class="form-check-label" for="time">Time</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" @if ($custom) checked @endif
                            id="customField">
                        <label class="form-check-label" for="customField">Custom:</label>
                        <input type="text" class="form-control mt-1" wire:model="custom" />
                    </div>

                    @error('custom')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- HIDE COLUMNS -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Choose columns you wish to hide on invoices and
                        quotations:</label><br>

                    <div class="form-check">
                        <input type="checkbox" wire:model="hide_quantity" class="form-check-input" id="hideQty">
                        <label class="form-check-label" for="hideQty"> Hide Quantity</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" wire:model="hide_rate" class="form-check-input" id="hideRate">
                        <label class="form-check-label" for="hideRate"> Hide Rate</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" wire:model="hide_amount" class="form-check-input" id="hideAmount">
                        <label class="form-check-label" for="hideAmount"> Hide Amount</label>
                    </div>
                </div>
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
