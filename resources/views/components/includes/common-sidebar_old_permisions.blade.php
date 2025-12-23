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
                                 {{-- @php
                                     $organization = App\Models\OrganizationSetting::first();
                                 @endphp 

                                 <img src="{{ asset('assets/images/' . $organization->logo->file) }}" class="logo-icon"
                                     alt="logo icon" /> --}}
                                     
                                        <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon"
                                     alt="logo icon" />

                             </div>
                             <div class="toggle-icon ms-auto text-black"><i class="bx bx-arrow-to-left"></i>
                             </div>
                         </div>

                         @php
                             $route = 'common';
                         @endphp
                         <ul class="metismenu mm-show py-3 px-2" id="menu">
                             @can('dashboard view')
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




                             @can("tourist's view")
                                 <li class="mb-1">
                                     <a href="{{ route('common.tourist') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="fadeIn animated bx bx-user"></i>
                                         </div>
                                         <div class="menu-title"><span>Tourists </span></div>
                                     </a>
                                 </li>
                             @endcan

                             @canany(['leads view', 'pipeline view', 'leads_status view', 'leads_stages view',
                                 'leads_source view', 'lead category view'])
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
                                         @can('leads view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.lead') }}" class="text-white ps-4">
                                                     <i class="lni lni-bubble me-2"></i>Leads
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('pipeline view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-pipeline') }}" class="text-white ps-4">
                                                     <i class="lni lni-cloud-network me-2"></i>Leads Type
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('leads_status view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-status') }}" class="text-white ps-4">
                                                     <i class="lni lni-grid-alt me-2"></i>Leads Status
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('leads_stages view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-stages') }}" class="text-white ps-4">
                                                     <i class="lni lni-grow me-2"></i>Leads Stage
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('leads_source view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-source') }}" class="text-white ps-4">
                                                     <i class="lni lni-jsfiddle me-2"></i>Leads Source
                                                 </a>
                                             </li>
                                         @endcan
                                     </ul>
                                 </li>
                             @endcanany


                             @can('estimates view')
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

                             @can('proformainvoice')
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


                             @can('invoices view')
                                 <li class="mb-1">
                                     <a href="{{ route('common.invoice') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-bookmark"></i>
                                         </div>
                                         <div class="menu-title"><span>Invoices </span></div>
                                     </a>
                                 </li>
                             @endcan
                             
                             @can('tour view')
                                 <li class="mb-1">
                                     <a href="{{ route('common.tour') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-map"></i>
                                         </div>
                                         <div class="menu-title"><span>Tour Master</span></div>
                                     </a>
                                 </li>
                             @endcan
                             
                           @canany(['park view', 'zone view', 'gates view'])
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
                                         @can('park view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.park') }}" class="text-white ps-4">
                                                 <i class="lni lni-envato me-2"></i>Parks
                                             </a>
                                         </li>
                                         @endcan
                                          @can('zone view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.zone') }}" class="text-white ps-4">
                                                 <i class="lni lni-grid me-2"></i>Zones
                                             </a>
                                         </li>
                                         @endcan
                                          @can('gates view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.gates') }}" class="text-white ps-4">
                                                 <i class="lni lni-grid me-2"></i>Gates
                                             </a>
                                         </li>
                                         @endcan
                                     </ul>
                                 </li>
                            @endcanany
                            
                            
                            @canany(['vendors view', 'vendortype view', 'vendor-service-location view'])
                            <li class="mb-1">
                                 <a class="has-arrow text-white" href="javascript:;">
                                     <div
                                         class="parent-icon d-flex align-items-center justify-content-center me-2">
                                         <i class="lni lni-user"></i>
                                     </div>
                                     <div class="menu-title">
                                         <span>Vendor Master</span>
                                     </div>
                                 </a>
                                 <ul class="mm-collapse ps-3 list-unstyled border-0">
                                      @can('vendors view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.vehicles') }}" class="text-white ps-4">
                                                 <i class="lni lni-user me-2"></i>Vehicle
                                             </a>
                                         </li>
                                     @endcan
                                     
                                              @can('vendortype view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.vendor-type') }}" class="text-white ps-4">
                                                 <i class="lni lni-bricks me-2"></i>Vendor Type
                                             </a>
                                         </li>
                                     @endcan
                                     
                                     @can('vendors view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.vendors') }}" class="text-white ps-4">
                                                 <i class="lni lni-user me-2"></i>Vendors
                                             </a>
                                         </li>
                                     @endcan
                            
                                     @can('vendor-service-location view')
                                         <li class="mt-1">
                                             <a href="{{ route('common.vendor-service-location') }}" class="text-white ps-4">
                                                 <i class="lni lni-travel me-2"></i>Vendor Service Location
                                             </a>
                                         </li>
                                     @endcan
                                 </ul>
                             </li>
                            @endcanany
                            
                            
                             @can('resort view')
                                 <li class="mb-1">
                                     <a href="{{ route('common.resort') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="fadeIn animated bx bx-hotel"></i>
                                         </div>
                                         <div class="menu-title"><span>Resort Master</span></div>
                                     </a>
                                 </li>
                             @endcan
                             
                             
                             @canany(['expense view'])
                                <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div
                                             class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-user"></i>
                                         </div>
                                         <div class="menu-title">
                                             <span>Income / Expenses</span>
                                         </div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                         
                                                 @can('income view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.income') }}" class="text-white ps-4">
                                                     <i class="fadeIn animated bx bx-money me-2"></i>Income
                                                 </a>
                                             </li>
                                         @endcan
                                         
                                          @can('expense view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.expense') }}" class="text-white ps-4">
                                                     <i class="fadeIn animated bx bx-money me-2"></i>Expenses
                                                 </a>
                                             </li>
                                         @endcan
                                   
                                     </ul>
                                 </li>
                                @endcanany
                                
                                
                             @canany(['users view'])
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
                             
                             
                              @can('company view')
                                 <li class="mb-1">
                                     <a href="{{ route('common.companies') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-apartment"></i>
                                         </div>
                                         <div class="menu-title"><span>Company Setting </span></div>
                                     </a>
                                 </li>
                             @endcan
                             
                             

                  


                             @canany(['leads view', 'tag view'])
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
                                         @can('leads view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.leads-pipeline') }}" class="text-white ps-4">
                                                     <i class="bx bx-cog me-2"></i>Lead Setting
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('tag view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.lead-tags') }}" class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Lead Tag
                                                 </a>
                                             </li>
                                         @endcan
                                         
                                         
                                              @can('income-expense-category view')
                                             <li class="mt-1">
                                                 <a href="{{ route('common.income-expense-categorey') }}" class="text-white ps-4">
                                                     <i class="lni lni-agenda"></i>Income/Expense Category
                                                 </a>
                                             </li>
                                         @endcan
                                         
                                     </ul>
                                 </li>
                             @endcanany
                             
                             
                                                          
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



                             @can('item view')
                                 <li class="mb-1">
                                     <a href="{{ route('common.item') }}" class="text-white">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="lni lni-cart-full"></i>
                                         </div>
                                         <div class="menu-title"><span>Items</span></div>
                                     </a>
                                 </li>
                             @endcan







            
                

                             {{-- @canany(['organization view', 'preferences view', 'emails view', 'tax view', 'users view'])
                                 <li class="mb-1">
                                     <a class="has-arrow text-white" href="javascript:;">
                                         <div class="parent-icon d-flex align-items-center justify-content-center me-2">
                                             <i class="bx bx-cog"></i>
                                         </div>
                                         <div class="menu-title"><span>Settings</span></div>
                                     </a>
                                     <ul class="mm-collapse ps-3 list-unstyled border-0">
                                         @can('organization view')
                                             <li class="mb-1">
                                                 <a href="{{ route('common.profile') }}" class="text-white">
                                                     <div
                                                         class="parent-icon d-flex align-items-center justify-content-center me-2">
                                                         <i class="bx bx-user"></i>
                                                     </div>
                                                     <div class="menu-title"><span>Organization Profile</span></div>
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('preferences view')
                                             <li class="mb-1">
                                                 <a href="{{ route('common.preferences') }}" class="text-white">
                                                     <div
                                                         class="parent-icon d-flex align-items-center justify-content-center me-2">
                                                         <i class="bx bx-user"></i>
                                                     </div>
                                                     <div class="menu-title"><span>Preferences</span></div>
                                                 </a>
                                             </li>
                                         @endcan
                                         @can('emails view')
                                             <li class="mb-1">
                                                 <a href="{{ route('common.emails') }}" class="text-white">
                                                     <div
                                                         class="parent-icon d-flex align-items-center justify-content-center me-2">
                                                         <i class="lni lni-telegram-original"></i>
                                                     </div>
                                                     <div class="menu-title">
                                                         <span>Email Templates</span>
                                                     </div>
                                                 </a>
                                             </li>
                                         @endcan


                                         @can('tax view')
                                             <li class="mb-1">
                                                 <a href="{{ route('common.tax') }}" class="text-white">
                                                     <div
                                                         class="parent-icon d-flex align-items-center justify-content-center me-2">
                                                         <i class="lni lni-zip"></i>
                                                     </div>
                                                     <div class="menu-title">
                                                         <span>Taxes</span>
                                                     </div>
                                                 </a>
                                             </li>
                                         @endcan
                                     @endcanany --}}



                                 </ul>




                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
