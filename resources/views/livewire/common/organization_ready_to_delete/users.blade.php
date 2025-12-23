<div class="">
    <div class="card radius12 bg-white">
        <div class="col-md-12 p-3">

            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="updateEmail">
                <div class="row g-4">
                    <!-- Email -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" wire:model="email" />
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-12 mt-2 d-flex justify-content-end">
                        <button type="submit" class="btn bluegradientbtn" wire:loading.attr="disabled">
                            Save Changes
                            <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"
                                wire:loading.delay></span>
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </div>
    
    
  <div class="card radius12 bg-white">
                                <div class="card-body">
                                    <form wire:submit.prevent="resetPassword">
                                        <div class="mb-3">
                                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                wire:model="password" placeholder="Enter new password">
                                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Retype Password <span class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                wire:model="password_confirmation" placeholder="Retype new password">
                                            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-primary px-5" wire:loading.attr="disabled">
                                            Reset Password
                                            <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="resetPassword"></i>
                                        </button>
                                
                                    </form>
                                </div>
                            </div>
                            
</div>
