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


                            @canany(['parks list', 'zones list', 'gates list'])
                                <li class="mb-1">
                                    <a class="has-arrow text-white" href="javascript:;">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                           <i class="lni lni-tree"></i>

                                        </div>
                                        <div class="menu-title">
                                            <span>Park Master</span>
                                        </div>
                                    </a>
                                    <ul class="mm-collapse ps-3 list-unstyled border-0">
                                        @can('parks list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.park') }}" class="text-white ps-4">
                                                    <i class="lni lni-envato me-2"></i>Parks
                                                </a>
                                            </li>
                                        @endcan
                                        @can('zones list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.zone') }}" class="text-white ps-4">
                                                    <i class="lni lni-target"></i>Zones
                                                </a>
                                            </li>
                                        @endcan
                                        @can('gates list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.gates') }}" class="text-white ps-4">
                                                    <i class="lni lni-enter"></i>Safari Gates
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            @canany(['vendors list', 'vendor-service-locations list', 'vehicles list'])
                                <li class="mb-1">
                                    <a class="has-arrow text-white" href="javascript:;">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                            <i class="lni lni-travel"></i>
                                        </div>
                                        <div class="menu-title">
                                            <span>Vendor Master</span>
                                        </div>
                                    </a>
                                    <ul class="mm-collapse ps-3 list-unstyled border-0">
                                        @can('vehicles list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.vehicles') }}" class="text-white ps-4">
                                                    <i class="lni lni-car"></i>Vehicle
                                                </a>
                                            </li>
                                        @endcan

                                        {{-- @can('vendortype view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.vendor-type') }}" class="text-white ps-4">
                                                     <i class="lni lni-bricks me-2"></i>Vendor Type
                                                 </a>
                                             </li>
                                         @endcan --}}

                                        @can('vendors list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.vendors') }}" class="text-white ps-4">
                                                   <i class="lni lni-user"></i>Vendors
                                                </a>
                                            </li>
                                        @endcan

                                        @can('vendor-service-locations list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.vendor-service-location') }}" class="text-white ps-4">
                                                   <i class="lni lni-map-marker"></i>Vendor Service Location
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            {{-- @can('resort-master list')
                                <li class="mb-1">
                                    <a href="{{ route('common.resort') }}" class="text-white">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                            <i class="fadeIn animated bx bx-hotel"></i>
                                        </div>
                                        <div class="menu-title"><span>Resort Master</span></div>
                                    </a>
                                </li>
                            @endcan --}}

                            @canany(['expenses list', 'income list'])
                                <li class="mb-1">
                                    <a class="has-arrow text-white" href="javascript:;">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                           <i class="lni lni-control-panel"></i>
                                        </div>
                                        <div class="menu-title">
                                            <span>Income / Expenses</span>
                                        </div>
                                    </a>
                                    <ul class="mm-collapse ps-3 list-unstyled border-0">

                                        @can('income list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.income') }}" class="text-white ps-4">
                                                   <i class="lni lni-coin"></i>Income
                                                </a>
                                            </li>
                                        @endcan

                                        @can('expenses list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.expense') }}" class="text-white ps-4">
                                                    <i class="lni lni-money-location"></i>Expenses
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcanany

                            @canany(['users list', 'roles list', 'permissions list'])
                                <li class="mb-1">
                                    <a class="has-arrow text-white" href="javascript:;">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                          <i class="lni lni-users"></i>
                                        </div>
                                        <div class="menu-title">
                                            <span>User Management</span>
                                        </div>
                                    </a>
                                    <ul class="mm-collapse ps-3 list-unstyled border-0">
                                        @can('users list')
                                            <li class="mt-1">
                                                <a href="{{ route('admin.users') }}" class="text-white ps-4">
                                                    <i class="lni lni-user"></i>Users
                                                </a>
                                            </li>
                                        @endcan
                                        @can('roles list')
                                            <li class="mt-1">
                                                <a href="{{ route('admin.roles') }}" class="text-white ps-4">
                                                   <i class="lni lni-network"></i>Roles
                                                </a>
                                            </li>
                                        @endcan
                                        @can('permissions list')
                                            <li class="mt-1">
                                                <a href="{{ route('admin.permisions') }}" class="text-white ps-4">
                                                    <i class="lni lni-lock"></i>Permissions
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            @can('companies list')
                                <li
                                    class="mb-1 {{ request()->routeIs('common.companies') ||
                                    request()->routeIs('common.add-company') ||
                                    request()->routeIs('common.edit-company') ||
                                    request()->routeIs('common.tax')
                                        ? 'mm-active'
                                        : '' }}">
                                    <a href="{{ route('common.companies') }}" class="text-white">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                            <i class="lni lni-apartment"></i>
                                        </div>
                                        <div class="menu-title"><span>Company Setting </span></div>
                                    </a>
                                </li>
                            @endcan

                            @canany(['lead-setting list', 'lead-tags list', 'income-expense-category list',
                                'income-expense-subcategory list'])
                                <li class="mb-1">
                                    <a class="has-arrow text-white" href="javascript:;">
                                        <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                           <i class="lni lni-cog"></i>
                                        </div>
                                        <div class="menu-title">
                                            <span>Other Settings</span>
                                        </div>
                                    </a>
                                    <ul class="mm-collapse ps-3 list-unstyled border-0">
                                        @canany(['lead-setting list'])
                                            <li
                                                class="mt-1 {{ request()->routeIs('common.leads-pipeline') ||
                                                request()->routeIs('common.leads-stages') ||
                                                request()->routeIs('common.leads-source')
                                                    ? 'mm-active'
                                                    : '' }}">
                                                <a href="{{ route('common.leads-pipeline') }}" class="text-white ps-4">
                                                    <i class="lni lni-control-panel"></i>Lead Setting
                                                </a>
                                            </li>
                                        @endcan
                                        @can('lead-tags list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.lead-tags') }}" class="text-white ps-4">
                                                   <i class="lni lni-tag"></i>Lead Tag
                                                </a>
                                            </li>
                                        @endcan


                                        @can('income-expense-category list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.income-expense-categorey') }}"
                                                    class="text-white ps-4">
                                                   <i class="lni lni-layers"></i>Income/Expense Category
                                                </a>
                                            </li>
                                        @endcan

                                        @can('income-expense-subcategory list')
                                            <li class="mt-1">
                                                <a href="{{ route('common.income-expense-sub-categorey') }}"
                                                    class="text-white ps-4">
                                                    <i class="lni lni-list"></i>Income/Expense SubCategory
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcanany

                        </ul>
                        {{-- /MENU --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
