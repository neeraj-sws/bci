<header style="background: #ffffff;">
    <div class="topbar d-flex align-items-center" style="height: 70px; padding: 0 25px;">
        <nav class="navbar navbar-expand" style="width: 100%;">
            <div class="mobile-toggle-menu" style="font-size: 1.5rem; color: #4a5568; cursor: pointer;">
                <i class="bx bx-menu"></i>
            </div>

            <div class="user-box dropdown ms-auto" style="position: relative;">
                
                                <livewire:common.user-header.user-header-notification>


                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                    <div class="user-info ps-3" style="text-align: right;">
                        <p class="user-name mb-0" style="font-weight: 600; color: #2d3748; font-size: 0.9rem;">
                            {{ Auth::user()->name }}</p>
                        <p class="designation mb-0" style="color: #718096; font-size: 0.8rem;">
                            {{ Auth::user()->email }}</p>
                    </div>
                    <div
                        style="width: 40px; height: 40px; background: #4c6ef5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 10px; color: white; font-weight: bold;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end"
                    style="border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-radius: 8px; padding: 10px 0; margin-top: 10px; min-width: 220px;">
                    <li>
                        <a href="{{ route('user.logout') }}" class="dropdown-item"
                            style="padding: 8px 20px; font-size: 0.9rem; color: #e53e3e; display: flex; align-items: center;">
                            <i class="bx bx-log-out-circle"
                                style="margin-right: 10px; font-size: 1.1rem; color: #e53e3e;"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
