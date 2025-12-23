<div class="container-fluid p-0 login-container position-relative ">
    <!-- Overlay for better readability -->
    <div class="loginoverlay"></div>

    <div class="container p-4 z-1 position-relative">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 justify-content-center">
                    <div class="col maincolmax mx-auto">
                        <div class="card mt-5 mt-lg-0 radius12 border-0">
                            <div class="card-body p-0">
                                <div class="p-4 radius12">
                                    <!-- Logo Section -->
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('assets/images/logo.png') }}"
                                            alt="Company Logo" class="mb-3 login-logo">
                                        <p class="midgreycolor">Access your admin dashboard</p>
                                    </div>

                                    <div class="login-separater text-center mb-4 position-relative">
                                        <hr class="hr-border" />
                                        <span>OR</span>
                                    </div>

                                    <div class="form-body">
                                        <form class="row g-3" wire:submit.prevent="login">
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label fw-semibold">Email
                                                    Address</label>
                                                <input type="email" class="form-control" id="inputEmailAddress"
                                                    placeholder="Email Address" wire:model="email" />
                                                @error('email')
                                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputLastEnterPassword" class="form-label fw-semibold">Enter
                                                    Password</label>
                                                <div class="input-group overflow-hidden" id="show_hide_password">
                                                    <input type="password" class="form-control border-end-0"
                                                        wire:model="password" id="inputLastEnterPassword"
                                                        placeholder="Enter Password" />
                                                    <span class="input-group-text bg-transparent cursor-pointer px-2"
                                                        onclick="togglePassword()">
                                                        <i class='bi bi-eye-slash' id="password-icon"></i>
                                                    </span>
                                                </div>
                                                @error('password')
                                                    <small class="text-danger fw-semibold">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch d-flex align-items-center">
                                                    <input class="form-check-input fs-5 cursor-pointer" type="checkbox"
                                                        id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label ms-2 cursor-pointer fs-13"
                                                        for="flexSwitchCheckChecked">Remember
                                                        Me</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <a href="#" class="text-primary small text-decoration-none">Forgot
                                                    Password?</a>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn bluegradientbtn"
                                                        wire:loading.attr="disabled">
                                                        <i class="bx bxs-lock-open me-2"></i>Sign
                                                        in
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('inputLastEnterPassword');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        }
    </script>
@endpush