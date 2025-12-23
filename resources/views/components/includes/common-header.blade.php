<header class="bg-white">
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand w-100">
            <!-- Mobile Toggle -->
            <div class="mobile-toggle-menu fs-3 cursor-pointer">
                <i class="bx bx-menu"></i>
            </div>
            
            <!-- User Dropdown -->
            <div class="user-box dropdown ms-auto position-relative">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret text-decoration-none pe-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <div class="profileicon rounded-circle d-flex align-items-center justify-content-center me-2">
                        <span class="text-white fw-bold">A</span>
                    </div>
                    <div class="user-info text-start">
                        <p class="user-name mb-0">Admin</p>
                        <small class="designation mb-0 darkgreytext">Administrator</small>
                    </div>
                   
                </a>
                <ul class="dropdown-menu dropdown-menu-end border shadow-sm py-2 mt-2 rounded-4">
                    <li>
                        <a href="{{ route('common.dashboard') }}" class="dropdown-item d-flex align-items-center px-3 py-2 darkgreytext">
                            <i class="bx bx-home-circle me-2 fs-6 darkgreytext"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0 mt-2 table-bottom-border"></div>
                    </li>
                    <li>
                        <a href="{{ route('admin.logout') }}" class="dropdown-item d-flex align-items-center px-3 py-2 redcolor">
                            <i class="bx bx-log-out-circle me-2 fs-6 redcolor"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>