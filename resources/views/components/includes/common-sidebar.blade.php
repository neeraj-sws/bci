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
                                             <i class="lni lni-dashboard"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Dashboard</span>
                                         </div>
                                     </a>
                                 </li>
                             @endcan
                             @can('tourists list')
                                 <li
                                     class="mb-1 {{ request()->routeIs('common.tourist') ||
                                     request()->routeIs('common.tourist-create') ||
                                     request()->routeIs('common.tourist-edit') ||
                                     request()->routeIs('common.view-tourist')
                                         ? 'mm-active'
                                         : '' }}">

                                     <a href="{{ route('common.tourist') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-user"></i>
                                         </div>
                                         <div class="menu-title"><span>Tourists </span></div>
                                     </a>
                                 </li>
                             @endcan

                             @canany(['leads list', 'lead-type list', 'lead-stages list', 'leads-source view'])
                                 <li
                                     class="mb-1 {{ request()->routeIs('common.lead') ||
                                     request()->routeIs('common.lead-create') ||
                                     request()->routeIs('common.lead-edit') ||
                                     request()->routeIs('common.lead-view') ||
                                     request()->routeIs('common.lead-revised')
                                         ? 'mm-active'
                                         : '' }}">
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
                                             <i class="lni lni-book"></i>
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
                                 <li
                                     class="mb-1 {{ request()->routeIs('common.quotation*') || request()->routeIs('common.*-quotation') ? 'mm-active' : '' }}">
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
                             @can('customer-trips list')
                                 <li class="mb-1 {{ request()->is('customer-trips*') ? 'mm-active' : '' }}">
                                     <a href="{{ route('common.trip') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-map-marker"></i>
                                         </div>
                                         <div class="menu-title"><span>Customer Trips</span></div>
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
                                 <li
                                     class="mb-1 {{ request()->routeIs('common.tour') ||
                                     request()->routeIs('common.tour-create') ||
                                     request()->routeIs('common.tour-edit') ||
                                     request()->routeIs('common.tour-copy')
                                         ? 'mm-active'
                                         : '' }}">
                                     <a href="{{ route('common.tour') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-map"></i>
                                         </div>
                                         <div class="menu-title"><span>Tour Master</span></div>
                                     </a>
                                 </li>
                             @endcan

                             @can('hotel-master manage')
                                 <li class="mb-1">
                                     <a href="{{ route('common.park') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-control-panel"></i>
                                         </div>
                                         <div class="menu-title"><span>Setting Master</span></div>
                                     </a>
                                 </li>
                             @endcan

                             @can('hotel-master manage')
                                 <li class="mb-1">
                                     <a href="{{ route('common.hotel-list') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-apartment"></i>
                                         </div>
                                         <div class="menu-title"><span>Hotel Master</span></div>
                                     </a>
                                 </li>
                             @endcan

                             <li class="mb-1">
                                 <a class="has-arrow text-white mb-2" href="javascript:;">
                                     <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                         <i class="lni lni-control-panel"></i>
                                     </div>
                                     <div class="menu-title">
                                         <span>Extra</span>
                                     </div>
                                 </a>
                                 <ul class="mm-collapse ps-3 list-unstyled border-0">
                                     @can('calculator view')
                                         <li class="mb-1">
                                             <a href="{{ route('common.calculator') }}" class="text-white">
                                                 <div
                                                     class="parent-icon d-flex align-items-center justify-content-center me-2">
                                                     <i class="lni lni-calculator"></i>
                                                 </div>
                                                 <div class="menu-title"><span>Calculator</span></div>
                                             </a>
                                         </li>
                                     @endcan

                                     @can('items list')
                                         <li class="mb-1">
                                             <a href="{{ route('common.item') }}" class="text-white">
                                                 <div
                                                     class="parent-icon d-flex align-items-center justify-content-center me-2">
                                                     <i class="lni lni-cart-full"></i>
                                                 </div>
                                                 <div class="menu-title"><span>Items</span></div>
                                             </a>
                                         </li>
                                     @endcan
                                 </ul>
                             </li>



                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
