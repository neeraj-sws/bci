<div class="container-fluid p-0 login-container position-relative">
    {{-- 
    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="">
                <div class="row g-0">

                    <div
                        class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/bg.webp') }}"
                                    class="img-fluid auth-img-cover-login" width="650" alt="">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                            <div class="card-body p-sm-5">
                                <div class="">
                                    <div class="mb-3 text-center">
                                        <img src="assets/images/logo.png" width="60" alt="">
                                    </div>
                                    <div class="text-center mb-4">
                                        <h5 class="">User Admin</h5>
                                        <p class="mb-0">Please log in to your account</p>
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
                                                    <input class="form-check-input fs-5 cursor-pointer"
                                                        type="checkbox" id="flexSwitchCheckChecked" checked>
                                                    <label class="form-check-label ms-2 cursor-pointer fs-13"
                                                        for="flexSwitchCheckChecked">Remember Me</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6 text-end">
                                                <a href="#"
                                                    class="text-primary small text-decoration-none">Forgot
                                                    Password?</a>
                                            </div>

                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn bluegradientbtn"
                                                        wire:loading.attr="disabled">
                                                        <i class="bx bxs-lock-open me-2"></i>Sign in
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
                <!--end row-->
            </div>
        </div>
    </div> --}}




    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-cover-left {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url({{ asset('assets/images/bg.webp') }}) center/cover no-repeat;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            border-radius: 10px 0 0 10px;
        }

        .auth-content {
            max-width: 500px;
            margin: 0 auto;
        }

        .auth-cover-right {
            background: white;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
            border-color: #4dabf7;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(78, 84, 200, 0.3);
        }

        .logo {
            font-weight: 700;
            font-size: 24px;
            color: #4e54c8;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }

        .feature-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .feature-list .bi {
            margin-right: 10px;
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider span {
            padding: 0 1rem;
            color: #777;
        }


        .copyright {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 2rem;
            text-align: center;
        }

        @media (max-width: 992px) {
            .auth-cover-left {
                display: none;
            }

            .auth-cover-right {
                border-radius: 10px;
            }
        }
    </style>
    <div class="container login-container">
        <div class="row g-0 shadow-lg w-100" style="max-width: 1200px; margin: 0 auto; border-radius: 10px;">
            <!-- Left side with background image -->
            <div class="col-lg-6 auth-cover-left d-none d-lg-flex">
                <div class="auth-content">
                    <h1 class="display-5 fw-bold mb-4">Welcome to Our Platform</h1>
                    <p class="lead mb-5">Sign in to access your personalized dashboard, manage your account, and more.
                    </p>

                    <ul class="feature-list">
                        <li><i class="bi bi-shield-check"></i> Secure authentication</li>
                        <li><i class="bi bi-speedometer2"></i> Personalized dashboard</li>
                        <li><i class="bi bi-people"></i> User management</li>
                    </ul>

                    {{-- <div class="mt-5">
                        <p class="small">Don't have an account? <a href="#" class="text-white">Contact
                                administrator</a></p> 
                    </div> --}}
                </div>
            </div>

            <!-- Right side with login form -->
            <div class="col-lg-6 auth-cover-right">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <span class="logo"> <img src="{{ asset('assets/images/logo.png') }}"
                                alt="Company Logo" style="height: 50px; margin-bottom: 20px;">
                        </span>
                        <h2 class="mt-3 fw-bold">Sign In</h2>
                        <p class="text-muted">Welcome back! Please sign in to your account</p>
                    </div>

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
                                <input type="password" class="form-control border-end-0" wire:model="password"
                                    id="inputLastEnterPassword" placeholder="Enter Password" />
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
                                    for="flexSwitchCheckChecked">Remember Me</label>
                            </div>
                        </div>

                        <div class="col-md-6 text-end">
                            <a href="#" class="text-primary small text-decoration-none">Forgot
                                Password?</a>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary w-100 mb-3" wire:loading.attr="disabled">
                                    <i class="bx bxs-lock-open me-2"></i>Sign in
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="copyright">
                        &copy; 2025 Big Cats India. All rights reserved.
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
