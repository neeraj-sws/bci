 <div class="sidebar-wrapper" data-simplebar="init"
     style="background: linear-gradient(135deg, #000000 0%, #764ba2 100%); max-height: 100vh; overflow-y: auto;">
     <div class="simplebar-wrapper m-0" style="max-height: calc(100vh - 60px); overflow-y: auto;">
         <div class="simplebar-height-auto-observer-wrapper">
             <div class="simplebar-height-auto-observer"></div>
         </div>
         <div class="simplebar-mask" style="overflow: hidden;">
             <div class="simplebar-offset" style="max-height: 100%; overflow-y: auto;">
                 <div class="simplebar-content-wrapper" style="overflow-y: auto;">
                     <div class="simplebar-content mm-active p-0" style="overflow-y: auto;">
                         <style>
                             .sidebar-wrapper {
                                 overflow-y: auto !important;
                                 max-height: 100vh;
                                 -webkit-overflow-scrolling: touch;
                             }

                             .simplebar-wrapper,
                             .simplebar-offset,
                             .simplebar-content-wrapper,
                             .simplebar-content {
                                 overflow-y: auto !important;
                                 max-height: 100%;
                             }

                             #menu {
                                 max-height: calc(100vh - 120px);
                                 overflow-y: auto !important;
                                 padding-right: 8px;
                             }

                             /* Custom scrollbar styling */
                             #menu::-webkit-scrollbar {
                                 width: 6px;
                             }

                             #menu::-webkit-scrollbar-track {
                                 background: rgba(255, 255, 255, 0.1);
                                 border-radius: 10px;
                             }

                             #menu::-webkit-scrollbar-thumb {
                                 background: rgba(255, 193, 7, 0.5);
                                 border-radius: 10px;
                                 transition: background 0.3s;
                             }

                             #menu::-webkit-scrollbar-thumb:hover {
                                 background: rgba(255, 193, 7, 0.8);
                             }

                             #menu li a:hover {
                                 background: rgba(118, 75, 162, 0.6) !important;
                                 border-left-color: #ffc107 !important;
                                 transform: translateX(2px);
                             }

                             #menu li.active>a,
                             #menu li>a.active {
                                 background: rgba(118, 75, 162, 0.8) !important;
                                 border-left-color: #ffc107 !important;
                                 border-left-width: 4px !important;
                                 font-weight: 600;
                                 box-shadow: 0 0 15px rgba(255, 193, 7, 0.3);
                             }

                             #menu li.mm-active>a {
                                 background: rgba(118, 75, 162, 0.8) !important;
                                 border-left-color: #ffc107 !important;
                                 border-left-width: 4px !important;
                                 box-shadow: 0 0 15px rgba(255, 193, 7, 0.3);
                             }
                         </style>
                         <div class="sidebar-header p-3">
                             <div>
                                 <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon" />

                             </div>
                             <div class="toggle-icon ms-auto text-black"><i class="bx bx-arrow-to-left"></i>
                             </div>
                         </div>
                         <ul class="metismenu mm-show py-3 px-2" id="menu">
                             <!-- Hotel Configuration Items -->
                             <li class="mb-2">
                                 <a href="{{ route('common.dashboard') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-agenda" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Dashboard</span>
                                 </a>
                             </li>
                             @php
                                 $hotelActive = in_array(request()->route()->getName(), [
                                     'common.hotel-list',
                                     'common.create-hotel',
                                     'common.update-hotel',
                                     'common.hotel-detail',
                                 ]);
                             @endphp
                             <li class="mb-2 {{ $hotelActive ? 'active mm-active' : '' }}">
                                 <a href="{{ route('common.hotel-list') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-home" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Hotels</span>
                                 </a>
                             </li>

                             <li class="mb-2">
                                 <a href="{{ route('common.room-category') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-layers" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Room Category</span>
                                 </a>
                             </li>

                             <li class="mb-2">
                                 <a href="{{ route('common.room-category-rates') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-money-location" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Room Rates</span>
                                 </a>
                             </li>

                             <li class="mb-2">
                                 <a href="{{ route('common.peak-dates') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-calendar" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Peak Dates</span>
                                 </a>
                             </li>

                             <li class="mb-2">
                                 <a href="{{ route('common.peak-date-price') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-wallet" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Peak Pricing</span>
                                 </a>
                             </li>

                             <li class="mb-2">
                                 <a href="{{ route('common.child-policies') }}" class="text-white"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.1); border-left: 3px solid rgba(255,255,255,0.3);">
                                     <i class="lni lni-users" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Child Policies</span>
                                 </a>
                             </li>

                             <!-- Masters Menu -->
                             <li class="mb-2">
                                 <a class="has-arrow text-white" href="javascript:;"
                                     style="border-radius: 10px; padding: 12px 15px; transition: all 0.3s ease; display: flex; align-items: center; background: rgba(255,255,255,0.15); border-left: 3px solid rgba(255,255,255,0.4);">
                                     <i class="lni lni-grid" style="font-size: 20px; min-width: 30px;"></i>
                                     <span style="margin-left: 10px;">Masters</span>
                                 </a>

                                 <ul class="mm-collapse ps-3 list-unstyled border-0"
                                     style="background: rgba(0,0,0,0.2); border-radius: 8px; margin-top: 8px;">
                                     <li class="mt-2">
                                         <a href="{{ route('common.hotel-categories') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-grid" style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Categories</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.hotel-type') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-list" style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Types</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.rate-type') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-tag" style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Rate Types</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.ocupancy') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-users" style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Occupancy</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.meal-type') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-package" style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Meal Plans</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.chains-list') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-link" style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Chains</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.marketing-company-list') }}"
                                             class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-briefcase"
                                                 style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Marketing Cos</span>
                                         </a>
                                     </li>
                                     <li class="mt-2">
                                         <a href="{{ route('common.seasons') }}" class="text-white ps-4"
                                             style="border-radius: 8px; padding: 10px 12px; transition: all 0.3s ease; display: flex; align-items: center;">
                                             <i class="lni lni-calendar"
                                                 style="font-size: 18px; min-width: 25px;"></i>
                                             <span style="margin-left: 8px;">Seasons</span>
                                         </a>
                                     </li>
                                 </ul>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
