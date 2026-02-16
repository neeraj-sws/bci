<div class="sidebar-wrapper" data-simplebar="init">
    <div class="simplebar-wrapper m-0">
        <div class="simplebar-mask">
            <div class="simplebar-offset">
                <div class="simplebar-content-wrapper">
                    <div class="simplebar-content p-0">

                        {{-- HEADER --}}
                        <div class="sidebar-header p-3">
                            <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon">
                            <div class="toggle-icon ms-auto text-black">
                                <i class="bx bx-arrow-to-left"></i>
                            </div>
                        </div>

                        {{-- MENU --}}
                        <ul class="metismenu py-3 px-2" id="menu">

                            {{-- Dashboard --}}
                            <li class="mb-1 {{ request()->routeIs('common.dashboard') ? 'mm-active' : '' }}">
                                <a href="{{ route('common.dashboard') }}" class="text-white">
                                    <div class="parent-icon">
                                        <i class="lni lni-dashboard"></i>

                                    </div>
                                    <div class="menu-title">Dashboard</div>
                                </a>
                            </li>

                            {{-- Hotels --}}
                            @php
                                $hotelActive = request()->routeIs('common.hotel*');
                            @endphp
                            <li class="mb-1 {{ $hotelActive ? 'mm-active' : '' }}">
                                <a href="{{ route('common.hotel-list') }}" class="text-white">
                                    <div class="parent-icon">
                                        <i class="lni lni-home"></i>
                                    </div>
                                    <div class="menu-title">Hotels</div>
                                </a>
                            </li>

                            {{-- Room Rates --}}
                            <li class="mb-1 {{ request()->routeIs('common.room-category-rates') ? 'mm-active' : '' }}">
                                <a href="{{ route('common.room-category-rates') }}" class="text-white">
                                    <div class="parent-icon">
                                        <i class="lni lni-money-location"></i>
                                    </div>
                                    <div class="menu-title">Room Rates</div>
                                </a>
                            </li>

                            {{-- Peak Dates --}}
                            {{-- <li class="mb-1 {{ request()->routeIs('common.peak-dates') ? 'mm-active' : '' }}">
                                <a href="{{ route('common.peak-dates') }}" class="text-white">
                                    <div class="parent-icon">
                                        <i class="lni lni-calendar"></i>
                                    </div>
                                    <div class="menu-title">Peak Dates</div>
                                </a>
                            </li> --}}

                            {{-- Peak Pricing --}}
                            <li class="mb-1 {{ request()->routeIs('common.peak-date-price') ? 'mm-active' : '' }}">
                                <a href="{{ route('common.peak-date-price') }}" class="text-white">
                                    <div class="parent-icon">
                                        <i class="lni lni-wallet"></i>
                                    </div>
                                    <div class="menu-title">Peak Date Pricing</div>
                                </a>
                            </li>

                            {{-- Child Policies --}}
                            <li class="mb-1 {{ request()->routeIs('common.child-policies') ? 'mm-active' : '' }}">
                                <a href="{{ route('common.child-policies') }}" class="text-white">
                                    <div class="parent-icon">
                                        <i class="lni lni-users"></i>
                                    </div>
                                    <div class="menu-title">Child Policies</div>
                                </a>
                            </li>

                            {{-- Masters --}}
                            <li class="mb-1">
                                <a class="has-arrow text-white" href="javascript:;">
                                    <div class="parent-icon">
                                        <i class="lni lni-grid"></i>
                                    </div>
                                    <div class="menu-title">Masters</div>
                                </a>

                                <ul class="mm-collapse ps-3 list-unstyled border-0">
                                    <li>
                                        <a href="{{ route('common.hotel-categories') }}" class="text-white ps-4">
                                            <i class="lni lni-grid me-2"></i> Categories
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.hotel-type') }}" class="text-white ps-4">
                                            <i class="lni lni-list me-2"></i> Types
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.rate-type') }}" class="text-white ps-4">
                                            <i class="lni lni-tag me-2"></i> Rate Types
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.ocupancy') }}" class="text-white ps-4">
                                            <i class="lni lni-users me-2"></i> Occupancy
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.meal-type') }}" class="text-white ps-4">
                                            <i class="lni lni-package me-2"></i> Meal Plans
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.chains-list') }}" class="text-white ps-4">
                                            <i class="lni lni-link me-2"></i> Chains
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.marketing-company-list') }}" class="text-white ps-4">
                                            <i class="lni lni-briefcase me-2"></i> Marketing Cos
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('common.seasons') }}" class="text-white ps-4">
                                            <i class="lni lni-calendar me-2"></i> Seasons
                                        </a>
                                    </li>
                                    <li class="mb-1 {{ request()->routeIs('common.room-category') ? 'mm-active' : '' }}">
                                        <a href="{{ route('common.room-categorys') }}" class="text-white">
                                            <div class="parent-icon">
                                                <i class="lni lni-calendar"></i>
                                            </div>
                                            <div class="menu-title">Room Categories</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                        {{-- /MENU --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
