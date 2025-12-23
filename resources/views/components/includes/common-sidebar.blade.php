 <div class="sidebar-wrapper" data-simplebar="init">
     <div class="simplebar-wrapper m-0">
         <div class="simplebar-height-auto-observer-wrapper">
             <div class="simplebar-height-auto-observer"></div>
         </div>
         <div class="simplebar-mask">
             <div class="simplebar-offset">
                 <div class="simplebar-content-wrapper">
                     <div class="simplebar-content mm-active p-0">
                         <div class="sidebar-header p-3">
                             <div>
                                 <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon" />

                             </div>
                             <div class="toggle-icon ms-auto text-black"><i class="bx bx-arrow-to-left"></i>
                             </div>
                         </div>

                         @php
                             $route = 'common';
                         @endphp
                         <ul class="metismenu mm-show py-3 px-2" id="menu">
                             @can('dashboard')
                                 <li class="mb-1">
                                     <a href="{{ route('common.dashboard') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-agenda"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Dashboard</span>
                                         </div>
                                     </a>
                                 </li>
                             @endcan




                             @can('tourists list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.tourist') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="fadeIn animated bx bx-user"></i>
                                         </div>
                                         <div class="menu-title"><span>Tourists </span></div>
                                     </a>
                                 </li>
                             @endcan

                             @canany(['leads list', 'lead-type list', 'lead-stages list', 'leads-source view'])
                                 <li class="mb-1">
                                     <a href="{{ route('common.lead') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-bar-chart"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Leads </span>
                                         </div>
                                     </a>
                                 </li>



                                 <li class="mb-1 d-none">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-bar-chart"></i>
                                         </div>
                                         <div class="menu-title"><span>Leads Module</span></div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                         @can('leads list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.lead') }}" class="text-white ps-4">
                                                     <i class="lni lni-bubble me-2"></i>Leads
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('lead-type view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-pipeline') }}" class="text-white ps-4">
                                                     <i class="lni lni-cloud-network me-2"></i>Leads Type
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('lead-stages list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-stages') }}" class="text-white ps-4">
                                                     <i class="lni lni-grow me-2"></i>Leads Stage
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('lead-source list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-source') }}" class="text-white ps-4">
                                                     <i class="lni lni-jsfiddle me-2"></i>Leads Source
                                                 </a>
                                             </li>
                                         @endcan
                                     </ul>
                                 </li>
                             @endcanany


                             @can('quotations list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.quotation') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-book"></i>
                                         </div>
                                         <div class="menu-title">
                                             {{ 'Quotations ' }}
                                         </div>
                                     </a>
                                 </li>
                             @endcan

                             @can('proforma-invoice list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.proforma') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-agenda"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Proforma </span>
                                         </div>
                                     </a>
                                 </li>
                             @endcan


                             @can('income list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.invoice') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-bookmark"></i>
                                         </div>
                                         <div class="menu-title"><span>Invoices </span></div>
                                     </a>
                                 </li>
                             @endcan

                             @can('tour-master list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.tour') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-map"></i>
                                         </div>
                                         <div class="menu-title"><span>Tour Master</span></div>
                                     </a>
                                 </li>
                             @endcan

                             @canany(['parks list', 'zones list', 'gates list'])
                                 <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-cart"></i>
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
                                                     <i class="lni lni-grid me-2"></i>Zones
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('gates list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.gates') }}" class="text-white ps-4">
                                                     <i class="lni lni-grid me-2"></i>Gates
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
                                             <i class="lni lni-user"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Vendor Master</span>
                                         </div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                         @can('vehicles list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.vehicles') }}" class="text-white ps-4">
                                                     <i class="lni lni-user me-2"></i>Vehicle
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
                                                     <i class="lni lni-user me-2"></i>Vendors
                                                 </a>
                                             </li>
                                         @endcan

                                         @can('vendor-service-locations list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.vendor-service-location') }}"
                                                     class="text-white ps-4">
                                                     <i class="lni lni-travel me-2"></i>Vendor Service Location
                                                 </a>
                                             </li>
                                         @endcan
                                     </ul>
                                 </li>
                             @endcanany


                             @can('resort-master list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.resort') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="fadeIn animated bx bx-hotel"></i>
                                         </div>
                                         <div class="menu-title"><span>Resort Master</span></div>
                                     </a>
                                 </li>
                             @endcan


                             @canany(['expenses list', 'income list'])
                                 <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-user"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Income / Expenses</span>
                                         </div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">

                                         @can('income list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.income') }}" class="text-white ps-4">
                                                     <i class="fadeIn animated bx bx-money me-2"></i>Income
                                                 </a>
                                             </li>
                                         @endcan

                                         @can('expenses list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.expense') }}" class="text-white ps-4">
                                                     <i class="fadeIn animated bx bx-money me-2"></i>Expenses
                                                 </a>
                                             </li>
                                         @endcan

                                     </ul>
                                 </li>
                             @endcanany


                             @canany(['users list'])
                                 <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-cart"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>User Management</span>
                                         </div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                         <li class="mt-1">
                                             <a href="{{ route('admin.users') }}" class="text-white ps-4">
                                                 <i class="lni lni-envato me-2"></i>Users
                                             </a>
                                         </li>
                                         <li class="mt-1">
                                             <a href="{{ route('admin.roles') }}" class="text-white ps-4">
                                                 <i class="lni lni-grid me-2"></i>Roles
                                             </a>
                                         </li>
                                         <li class="mt-1">
                                             <a href="{{ route('admin.permisions') }}" class="text-white ps-4">
                                                 <i class="lni lni-grid me-2"></i>Permissions
                                             </a>
                                         </li>
                                     </ul>
                                 </li>
                             @endcanany


                             @can('companies list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.companies') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-apartment"></i>
                                         </div>
                                         <div class="menu-title"><span>Company Setting </span></div>
                                     </a>
                                 </li>
                             @endcan






                             @canany(['leads list', 'lead-tags list'])
                                 <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-cart"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Other Settings</span>
                                         </div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                         @can('leads list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-pipeline') }}" class="text-white ps-4">
                                                     <i class="bx bx-cog me-2"></i>Lead Setting
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('lead-tags list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.lead-tags') }}" class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Lead Tag
                                                 </a>
                                             </li>
                                         @endcan


                                         @can('income-expense-category list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.income-expense-categorey') }}"
                                                     class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Income/Expense Category
                                                 </a>
                                             </li>
                                         @endcan

                                          @can('income-expense-subcategory list')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.income-expense-sub-categorey') }}"
                                                     class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Income/Expense SubCategory
                                                 </a>
                                             </li>
                                         @endcan

                                     </ul>
                                 </li>
                             @endcanany

                             {{-- HOTEL MASTER  --}}
                             <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-cart"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Hotel Master</span>
                                         </div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                             <li class="mt-1">
                                                 <a href="{{ route('common.hotel-type') }}" class="text-white ps-4">
                                                     <i class="bx bx-cog me-2"></i>Hotel Type
                                                 </a>
                                             </li>
                                             <li class="mt-1">
                                                 <a href="{{ route('common.hotel-categories') }}" class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Hotel Categories
                                                 </a>
                                             </li>
                                             <li class="mt-1">
                                                 <a href="{{ route('common.rate-type') }}"
                                                     class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Rate Type
                                                 </a>
                                             </li>
                                             <li class="mt-1">
                                                 <a href="{{ route('common.ocupancy') }}"
                                                     class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Ocupancy
                                                 </a>
                                             </li>
                                             <li class="mt-1">
                                                 <a href="{{ route('common.meal-type') }}"
                                                     class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Meal Type
                                                 </a>
                                             </li>
                                     </ul>
                            </li>



                             @can('calculator view')
                                 <li class="mb-1">
                                     <a href="{{ route('common.calculator') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-calculator"></i>
                                         </div>
                                         <div class="menu-title"><span>Calculator</span></div>
                                     </a>
                                 </li>
                             @endcan



                             @can('items list')
                                 <li class="mb-1">
                                     <a href="{{ route('common.item') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-cart-full"></i>
                                         </div>
                                         <div class="menu-title"><span>Items</span></div>
                                     </a>
                                 </li>
                             @endcan


                         </ul>




                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
