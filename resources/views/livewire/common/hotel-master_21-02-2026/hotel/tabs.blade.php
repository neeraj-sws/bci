<div>
    <!-- Tab Navigation -->
    <div class="crm-card mb-4" style="padding: 0;">
        <div style="border-bottom: 2px solid #F3F4F6; padding: 0 24px;">
            <div class="d-flex gap-1">
                <button
                    wire:click="setActiveTab('overview')"
                    class="btn btn-link text-decoration-none position-relative"
                    style="padding: 16px 20px; color: {{ $activeTab === 'overview' ? '#0F172A' : '#6B7280' }}; font-weight: {{ $activeTab === 'overview' ? '600' : '400' }}; border: none; background: none;">
                    <i class="bx bx-info-circle me-2"></i>Overview
                    @if($activeTab === 'overview')
                        <div style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #0F172A;"></div>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('room-pricing')"
                    class="btn btn-link text-decoration-none position-relative"
                    style="padding: 16px 20px; color: {{ $activeTab === 'room-pricing' ? '#0F172A' : '#6B7280' }}; font-weight: {{ $activeTab === 'room-pricing' ? '600' : '400' }}; border: none; background: none;">
                    <i class="bx bx-door-open me-2"></i>Room Pricing
                    @if($activeTab === 'room-pricing')
                        <div style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #0F172A;"></div>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('peak-dates')"
                    class="btn btn-link text-decoration-none position-relative"
                    style="padding: 16px 20px; color: {{ $activeTab === 'peak-dates' ? '#0F172A' : '#6B7280' }}; font-weight: {{ $activeTab === 'peak-dates' ? '600' : '400' }}; border: none; background: none;">
                    <i class="bx bx-calendar-star me-2"></i>Peak Dates
                    @if($activeTab === 'peak-dates')
                        <div style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #0F172A;"></div>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('child-policies')"
                    class="btn btn-link text-decoration-none position-relative"
                    style="padding: 16px 20px; color: {{ $activeTab === 'child-policies' ? '#0F172A' : '#6B7280' }}; font-weight: {{ $activeTab === 'child-policies' ? '600' : '400' }}; border: none; background: none;">
                    <i class="bx bx-child me-2"></i>Child Policies
                    @if($activeTab === 'child-policies')
                        <div style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #0F172A;"></div>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('settings')"
                    class="btn btn-link text-decoration-none position-relative"
                    style="padding: 16px 20px; color: {{ $activeTab === 'settings' ? '#0F172A' : '#6B7280' }}; font-weight: {{ $activeTab === 'settings' ? '600' : '400' }}; border: none; background: none;">
                    <i class="bx bx-cog me-2"></i>Settings
                    @if($activeTab === 'settings')
                        <div style="position: absolute; bottom: -2px; left: 0; right: 0; height: 2px; background: #0F172A;"></div>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div>
        @if($activeTab === 'overview')
            @livewire('common.hotel-master.hotel.overview', ['hotelId' => $hotelId], key('overview-'.$hotelId))
        @elseif($activeTab === 'room-pricing')
            @livewire('common.hotel-master.hotel.room-pricing', ['hotelId' => $hotelId], key('room-pricing-'.$hotelId))
        @elseif($activeTab === 'peak-dates')
            @livewire('common.hotel-master.hotel.peak-dates', ['hotelId' => $hotelId], key('peak-dates-'.$hotelId))
        @elseif($activeTab === 'child-policies')
            @livewire('common.hotel-master.hotel.child-policies', ['hotelId' => $hotelId], key('child-policies-'.$hotelId))
        @elseif($activeTab === 'settings')
            @livewire('common.hotel-master.hotel.settings', ['hotelId' => $hotelId], key('settings-'.$hotelId))
        @endif
    </div>
</div>
