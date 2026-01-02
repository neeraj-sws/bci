<div class="container my-4">

    <form wire:submit.prevent="save" class="radius12 bg-white settingforms">
        <div class="row g-4">

            <!-- Subject Input -->
            <div class="mb-1">
                <label for="title" class="form-label">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror" wire:model="subject"
                    placeholder="Email Subject">
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-1">
                <label class="form-label">Message <span class="text-danger">*</span></label>
                <textarea
                    id="message"
                    class="form-control @error('message') is-invalid @enderror"
                    rows="10"
                    wire:model.defer="message"
                    placeholder="Write your email message here..."
                ></textarea>

                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <!-- Placeholder Section -->
            <div class="mb-1">
                <label class="form-label">Placeholder</label>
                <div class="col-md-10">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($placeholders as $placeholder)
                          <span
                            onclick="insertPlaceholder('[{{ $placeholder }}]', this)"
                            class="badge bg-light text-primary border border-primary px-3 py-2"
                            style="cursor:pointer"
                        >
                            {{ $placeholder }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
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