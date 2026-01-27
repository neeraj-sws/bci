<div>
    @if ($childPolicies->count() > 0)

        <!-- Info Banner -->
        <div class="crm-card mb-4" style="padding: 16px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);">
            <div class="row text-white align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-1" style="font-weight: 600; font-size: 15px;">
                        <i class="bx bx-info-circle me-2"></i>Understanding Child Policies
                    </h6>
                    <p class="mb-0" style="font-size: 13px; opacity: 0.95;">
                        Child policies vary by <strong>Room Category</strong> and <strong>Date Period</strong> (Regular
                        vs Peak Season).
                        Rates are based on child's age at the time of stay.
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-flex gap-3 justify-content-center">
                        <div>
                            <div style="font-size: 24px; font-weight: 700;">{{ $regularPolicies->count() }}</div>
                            <small style="opacity: 0.9;">Regular</small>
                        </div>
                        <div>
                            <div style="font-size: 24px; font-weight: 700;">{{ $peakPolicies->count() }}</div>
                            <small style="opacity: 0.9;">Peak Season</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- REGULAR SEASON POLICIES -->
        @if ($regularPolicies->count() > 0)
            <div class="mb-5">
                <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                    <div style="width: 4px; height: 28px; background: #10B981; border-radius: 2px;"></div>
                    <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #1F2937;">
                        <i class="bx bx-calendar me-2" style="color: #10B981;"></i>Regular Season Policies
                    </h4>
                </div>

                <div class="row">
                    @foreach ($regularPolicies as $roomCategoryId => $policiesForRoom)
                        @php
                            $roomCategory = $policiesForRoom->first()->roomCategory;
                            $sortedPolicies = $policiesForRoom->sortBy('free_child_age');
                        @endphp

                        <div class="col-lg-6 mb-4">
                            <div class="crm-card" style="border-left: 4px solid #10B981; height: 100%;">
                                <!-- Room Category Header -->
                                <div style="padding: 18px; background: #F0FDF4; border-bottom: 2px solid #D1FAE5;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1"
                                                style="font-size: 17px; font-weight: 700; color: #065F46;">
                                                <i
                                                    class="bx bx-door-open me-2"></i>{{ $roomCategory ? $roomCategory->title : 'All Rooms' }}
                                            </h5>
                                            <small style="color: #059669; font-size: 12px;">
                                                <i class="bx bx-time-five me-1"></i>Regular Season Rates
                                            </small>
                                        </div>
                                        <span class="crm-badge"
                                            style="background: #10B981; color: white; font-size: 11px; padding: 4px 10px;">
                                            {{ $sortedPolicies->count() }} Age
                                            {{ $sortedPolicies->count() > 1 ? 'Groups' : 'Group' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Age-Based Policies -->
                                <div style="padding: 20px;">
                                    @foreach ($sortedPolicies as $policy)
                                        <div class="mb-3"
                                            style="background: #F9FAFB; border-radius: 10px; padding: 16px; border: 1px solid #E5E7EB;">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <h6 class="mb-0"
                                                        style="font-size: 15px; font-weight: 700; color: #1F2937;">
                                                        <i class="bx bx-child me-2" style="color: #10B981;"></i>Up to
                                                        {{ $policy->free_child_age }} Years
                                                    </h6>
                                                </div>
                                                @if ($policy->status)
                                                    <span class="crm-badge crm-badge-active"
                                                        style="font-size: 10px;">Active</span>
                                                @else
                                                    <span class="crm-badge crm-badge-inactive"
                                                        style="font-size: 10px;">Inactive</span>
                                                @endif
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div
                                                        style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #FDE047;">
                                                        <small
                                                            style="font-size: 10px; color: #92400E; font-weight: 600; display: block; margin-bottom: 4px; text-transform: uppercase;">
                                                            <i class="bx bx-bed me-1"></i>With Bed
                                                        </small>
                                                        <h5 class="mb-0"
                                                            style="font-size: 20px; font-weight: 800; color: #92400E;">
                                                            ₹{{ number_format($policy->child_with_bed_rate ?? 0, 0) }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div
                                                        style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #93C5FD;">
                                                        <small
                                                            style="font-size: 10px; color: #1E40AF; font-weight: 600; display: block; margin-bottom: 4px; text-transform: uppercase;">
                                                            <i class="bx bx-x me-1"></i>Without Bed
                                                        </small>
                                                        <h5 class="mb-0"
                                                            style="font-size: 20px; font-weight: 800; color: #1E40AF;">
                                                            ₹{{ number_format($policy->child_without_bed_rate ?? 0, 0) }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PEAK SEASON POLICIES -->
        @if ($peakPolicies->count() > 0)
            <div class="d-flex align-items-center mb-3" style="gap: 12px;">
                <div style="width: 4px; height: 28px; background: #F59E0B; border-radius: 2px;"></div>
                <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #1F2937;">
                    <i class="bx bx-trending-up me-2" style="color: #F59E0B;"></i>Peak Season Policies
                </h4>
            </div>
            <div class="row">
                @foreach ($peakPolicies as $peakDateId => $policiesForPeak)
                    @php
                        $peakDate = $policiesForPeak->first()->peakDate;
                        $groupedByRoomInPeak = $policiesForPeak->groupBy('room_category_id');
                    @endphp
                    <div class="col-lg-6 mb-4">
                        <div class="crm-card" style="border: 2px solid #F59E0B;">
                            <!-- Peak Date Header -->
                            <div
                                style="padding: 18px; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border-bottom: 2px solid #FBBF24;">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-1" style="font-size: 18px; font-weight: 700; color: #92400E;">
                                            <i
                                                class="bx bx-calendar-star me-2"></i>{{ $peakDate ? $peakDate->title : 'Peak Period' }}
                                        </h5>
                                        @if ($peakDate)
                                            <small style="color: #B45309; font-size: 12px; font-weight: 500;">
                                                <i class="bx bx-calendar-event me-1"></i>
                                                {{ date('M d, Y', strtotime($peakDate->start_date)) }} -
                                                {{ date('M d, Y', strtotime($peakDate->end_date)) }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="crm-badge"
                                            style="background: #F59E0B; color: white; font-size: 12px; padding: 6px 14px;">
                                            {{ $policiesForPeak->count() }}
                                            {{ $policiesForPeak->count() > 1 ? 'Policies' : 'Policy' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Room Categories within Peak Date -->
                            <div style="padding: 20px;">
                                <div class="row">
                                    @foreach ($groupedByRoomInPeak as $roomCategoryId => $policiesForRoom)
                                        @php
                                            $roomCategory = $policiesForRoom->first()->roomCategory;
                                            $sortedPolicies = $policiesForRoom->sortBy('free_child_age');
                                        @endphp

                                        <div class="col-lg-12 mb-3">
                                            <div
                                                style="background: white; border-radius: 10px; border: 2px solid #FDE68A; padding: 16px; height: 100%;">
                                                <h6 class="mb-3"
                                                    style="font-size: 15px; font-weight: 700; color: #92400E;">
                                                    <i
                                                        class="bx bx-door-open me-2"></i>{{ $roomCategory ? $roomCategory->title : 'All Rooms' }}
                                                </h6>

                                                @foreach ($sortedPolicies as $policy)
                                                    <div class="mb-2"
                                                        style="background: #FFFBEB; border-radius: 8px; padding: 12px; border: 1px solid #FEF3C7;">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <small
                                                                style="font-size: 13px; font-weight: 700; color: #78350F;">
                                                                <i class="bx bx-child me-1"></i>Up to
                                                                {{ $policy->free_child_age }} Years
                                                            </small>
                                                            @if ($policy->status)
                                                                <span class="crm-badge crm-badge-active"
                                                                    style="font-size: 9px;">Active</span>
                                                            @else
                                                                <span class="crm-badge crm-badge-inactive"
                                                                    style="font-size: 9px;">Inactive</span>
                                                            @endif
                                                        </div>

                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <div
                                                                    style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #FDE047;">
                                                                    <small
                                                                        style="font-size: 10px; color: #92400E; font-weight: 600; display: block; margin-bottom: 4px; text-transform: uppercase;">
                                                                        <i class="bx bx-bed me-1"></i>With Bed
                                                                    </small>
                                                                    <h5 class="mb-0"
                                                                        style="font-size: 20px; font-weight: 800; color: #92400E;">
                                                                        ₹{{ number_format($policy->child_with_bed_rate ?? 0, 0) }}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div
                                                                    style="background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%); padding: 12px; border-radius: 8px; text-align: center; border: 1px solid #93C5FD;">
                                                                    <small
                                                                        style="font-size: 10px; color: #1E40AF; font-weight: 600; display: block; margin-bottom: 4px; text-transform: uppercase;">
                                                                        <i class="bx bx-x me-1"></i>Without Bed
                                                                    </small>
                                                                    <h5 class="mb-0"
                                                                        style="font-size: 20px; font-weight: 800; color: #1E40AF;">
                                                                        ₹{{ number_format($policy->child_without_bed_rate ?? 0, 0) }}
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Summary Statistics -->
        <div class="crm-card" style="padding: 20px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);">
            <div class="row text-white">
                <div class="col-md-3 text-center">
                    <i class="bx bx-door-open" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $groupedByRoom->count() }}</h3>
                    <small style="opacity: 0.9;">Room Categories</small>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bx bx-calendar" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $regularPolicies->count() }}</h3>
                    <small style="opacity: 0.9;">Regular Season</small>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bx bx-trending-up" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $peakPolicies->count() }}</h3>
                    <small style="opacity: 0.9;">Peak Season</small>
                </div>
                <div class="col-md-3 text-center">
                    <i class="bx bx-check-circle" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $childPolicies->where('status', 1)->count() }}
                    </h3>
                    <small style="opacity: 0.9;">Active Policies</small>
                </div>
            </div>
        </div>
    @else
        <div class="crm-card" style="padding: 60px 20px; text-align: center;">
            <i class="bx bx-child" style="font-size: 64px; color: #E5E7EB;"></i>
            <h5 class="mt-3" style="color: #6B7280; font-size: 16px; font-weight: 600;">No Child Policies</h5>
            <p class="mb-0" style="color: #9CA3AF; font-size: 14px;">No child policies have been configured for this
                hotel</p>
        </div>
    @endif
</div>
