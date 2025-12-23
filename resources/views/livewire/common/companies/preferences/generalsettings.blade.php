<div class="container my-4">
            
    <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
        <div class="row g-4">

            <div class="mb-1">
                <div class="form-group">
                    <label for="title" class="form-label">Fiscal Year</label>
                    <select id="fiscal_year" class="form-select select2" wire:model="fiscal_year"
                        placeholder="Select Fiscal Year">
                        <option value=""></option>
                        @foreach ($ficalYears as $id => $name)
                            <option value="{{ $id }}" @if ($fiscal_year === $id) selected @endif>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="title" class="form-label">Base Currency</label>
                    <select id="currency" class="form-select select2" wire:model="currency"
                        placeholder="Select Base Currency">
                        <option value=""></option>
                        @foreach ($currencys as $id => $currencyName)
                            <option value="{{ $id }}" @if ($currency == $id) selected @endif>
                                {{ $currencyName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

           <div class="mt-2">
             <div class="row ">
                <div class="col-6 mb-1">
                    <label for="title" class="form-label">USD Rate <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('usd_rate') is-invalid @enderror"
                        wire:model="usd_rate" placeholder="USD Rate">
                    @error('usd_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6 mb-1">
                    <label for="title" class="form-label">Markup % <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('markup_rate') is-invalid @enderror"
                        wire:model="markup_rate" placeholder="Markup %">
                    @error('markup_rate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
           </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="date_format" class="form-label">Date Format</label>
                    <select id="date_format" class="form-select select2" wire:model="date_format">
                        <option value=""></option>
                        <option value="Y-m-d" @if ($date_format === 'Y-m-d') selected @endif>{{ date('Y-m-d') }}
                        </option>
                        <option value="d/m/Y" @if ($date_format === 'd/m/Y') selected @endif>{{ date('d/m/Y') }}
                        </option>
                        <option value="m/d/Y" @if ($date_format === 'm/d/Y') selected @endif>{{ date('m/d/Y') }}
                        </option>
                        <option value="d M Y" @if ($date_format === 'd M Y') selected @endif>{{ date('d M Y') }}
                        </option>
                        <option value="D, d M Y" @if ($date_format === 'D, d M Y') selected @endif>
                            {{ date('D, d M Y') }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="paper_size" class="form-label">Paper Size <span class="text-danger">*</span></label>
                    <select id="paper_size" class="form-select select2" wire:model="paper_size">
                        <option value=""></option>
                        <option value="a4" @if ($paper_size === 'a4') selected @endif>A4</option>
                        <option value="letter" @if ($paper_size === 'letter') selected @endif>Letter</option>
                        <option value="legal" @if ($paper_size === 'legal') selected @endif>Legel</option>
                    </select>
                </div>
                @error('paper_size')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-1">
                <div class="form-group">
                    <label for="number_format" class="form-label">Number Format <span
                            class="text-danger">*</span></label>
                    <select id="number_format" class="form-select select2" wire:model="number_format" disabled>
                        <option value=""></option>
                        <option value="comma_dot" @if ($number_format === 'comma_dot') selected @endif>1,234,567.00
                        </option>
                        <option value="dot_comma" @if ($number_format === 'dot_comma') selected @endif>1.234.567,00
                        </option>
                        <option value="space_comma" @if ($number_format === 'space_comma') selected @endif>1 234 567,00
                        </option>
                        <option value="none_dot" @if ($number_format === 'none_dot') selected @endif>1234567.00</option>
                        <option value="none_comma" @if ($number_format === 'none_comma') selected @endif>1234567,00
                        </option>
                    </select>
                </div>
                @error('number_format')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>



            <div>
                <div class="mb-4">
                    <label class="font-bold block mb-2">PDF Attachment <span class="text-danger">*</span></label></br>
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="pdf_attachment"
                            class="form-checkbox text-red-600 @error('pdf_attachment') is-invalid @enderror" disabled />
                        <span class="ml-2">Attach PDF by default while sending invoices and quotations</span>
                    </label>
                    @error('pdf_attachment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="font-bold block mb-2">Notifications <span class="text-danger">*</span></label></br>

                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" wire:model="notify"
                            class="form-checkbox text-red-600 @error('notify') is-invalid @enderror" />
                        <span class="ml-2">Notify when quotations and invoices is opened</span>

                        @error('notify')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </label></br>

                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" wire:model="notify2"
                            class="form-checkbox text-red-600 @error('notify2') is-invalid @enderror" />
                        <span class="ml-2">Notify when quotation is approved or declined</span>
                        @error('notify2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </label></br>

                    <label class="inline-flex items-center mt-2">
                        <input type="checkbox" wire:model="notify3"
                            class="form-checkbox text-red-600 @error('notify3') is-invalid @enderror" />
                        <span class="ml-2">Notify when a payment is made</span>
                        @error('notify3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </label>
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
