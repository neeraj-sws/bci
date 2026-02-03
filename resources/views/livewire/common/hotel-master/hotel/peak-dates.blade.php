<div>
    @php
        $filteredRooms = $this->getFilteredRoomCategories();
    @endphp

    @if ($filteredRooms->count() > 0)
        @foreach ($filteredRooms as $room)
            @php
                $peakDatesForRoom = $this->getFilteredPeakDates($room);
            @endphp

            {{-- ROOM CATEGORY HEADING --}}
            <div style="padding: 16px 0; margin-bottom: 12px; border-bottom: 2px solid #E5E7EB;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-door-open" style="font-size: 20px; color: #DC2626;"></i>
                    <h5 class="mb-0" style="font-size: 18px; font-weight: 700; color: #0F172A;">
                        {{ $room->title }}
                    </h5>
                    <span style="background: #FEE2E2; color: #DC2626; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                        {{ $peakDatesForRoom->count() }} {{ $peakDatesForRoom->count() === 1 ? 'Peak' : 'Peaks' }}
                    </span>
                </div>
            </div>

            {{-- PEAK DATE CARDS FOR THIS ROOM --}}
            @foreach ($peakDatesForRoom as $peak)
                @php
                    $occupancies = $this->getPeakOccupancies($peak);
                    $dateRange = $this->getPeakDateRange($peak);
                    $daysCount = $this->calculateDays($dateRange['start_date'], $dateRange['end_date']);
                    $surcharge = $this->getPeakSurcharge($peak);
                @endphp

                <div class="crm-card mb-3"
                    style="overflow: hidden; border-left: 3px solid {{ $peak->status ? '#DC2626' : '#9CA3AF' }};">
                    
                    {{-- COLLAPSED HEADER --}}
                    <div wire:click="togglePeak({{ $peak->peak_dates_id }})"
                        style="padding: 16px; cursor: pointer; {{ $expandedPeak === $peak->peak_dates_id ? 'background: #FAFBFC;' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h6 class="mb-0" style="font-size: 15px; font-weight: 600; color: #0F172A;">
                                        @if ($dateRange['start_date'] && $dateRange['end_date'])
                                            {{ \Carbon\Carbon::parse($dateRange['start_date'])->format('d M') }} –
                                            {{ \Carbon\Carbon::parse($dateRange['end_date'])->format('d M, Y') }}
                                        @else
                                            {{ $peak->title }}
                                        @endif
                                    </h6>
                                    @if ($peak->is_new_year)
                                        <i class="bx bx-party" style="color: #DC2626; font-size: 16px;" title="New Year Peak"></i>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-3" style="font-size: 11px; color: #6B7280;">
                                    <span>{{ $daysCount }} {{ $daysCount === 1 ? 'Day' : 'Days' }}</span>
                                    <span>•</span>
                                    @if ($peak->status)
                                        <span style="color: #16A34A; font-weight: 600;">Active</span>
                                    @else
                                        <span style="color: #9CA3AF; font-weight: 600;">Inactive</span>
                                    @endif
                                </div>
                                @if ($surcharge > 0)
                                    <div class="mt-2">
                                        <span style="font-size: 11px; color: #6B7280;">Surcharge: </span>
                                        <span style="font-size: 14px; font-weight: 700; color: #DC2626;">
                                            +₹{{ number_format($surcharge, 0) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm"
                                style="background: #F3F4F6; color: #374151; border-radius: 6px; padding: 6px 12px; font-size: 12px;">
                                <i class="bx bx-chevron-{{ $expandedPeak === $peak->peak_dates_id ? 'up' : 'down' }}"></i>
                            </button>
                        </div>
                    </div>

                    {{-- EXPANDED VIEW --}}
                    @if ($expandedPeak === $peak->peak_dates_id)
                        <div style="padding: 16px; border-top: 1px solid #E5E7EB; background: #FAFBFC;">
                            
                            {{-- OCCUPANCY-WISE PRICING --}}
                            @if ($occupancies->count() > 0)
                                <div class="mb-3">
                                    <h6 class="mb-3" style="font-size: 13px; font-weight: 600; color: #374151;">
                                        <i class="bx bx-group me-1" style="font-size: 14px;"></i>
                                        Occupancy-wise Pricing
                                    </h6>
                                    <div class="row g-2">
                                        @foreach ($occupancies as $occ)
                                            <div class="col-md-6">
                                                <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #E5E7EB;">
                                                    {{-- Occupancy Title --}}
                                                    <div style="font-size: 12px; color: #6B7280; font-weight: 600; margin-bottom: 8px;">
                                                        {{ $occ->occupancy->title ?? 'N/A' }}
                                                    </div>
                                                    
                                                    {{-- Weekday & Weekend Rates --}}
                                                    <div class="d-flex gap-2">
                                                        {{-- Weekday --}}
                                                        <div class="flex-fill" style="background: #FEF2F2; padding: 8px; border-radius: 6px; text-align: center;">
                                                            <div style="font-size: 10px; color: #6B7280; margin-bottom: 4px;">
                                                                Weekday
                                                            </div>
                                                            <div style="font-size: 15px; font-weight: 700; color: #DC2626;">
                                                                ₹{{ number_format($occ->rate ?? 0, 0) }}
                                                            </div>
                                                        </div>
                                                        
                                                        {{-- Weekend --}}
                                                        <div class="flex-fill" style="background: #FEF3C7; padding: 8px; border-radius: 6px; text-align: center;">
                                                            <div style="font-size: 10px; color: #6B7280; margin-bottom: 4px;">
                                                                Weekend
                                                            </div>
                                                            <div style="font-size: 15px; font-weight: 700; color: #F59E0B;">
                                                                ₹{{ number_format($occ->weekend_rate ?? 0, 0) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <p style="color: #9CA3AF; font-size: 12px; margin: 0;">No pricing configured for this season.</p>
                                </div>
                            @endif

                            {{-- NOTES --}}
                            @if ($peak->notes)
                                <div class="mt-3 p-2" style="background: white; border-radius: 6px; border-left: 2px solid #E5E7EB;">
                                    <small style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 4px;">
                                        Notes
                                    </small>
                                    <p class="mb-0" style="font-size: 12px; color: #374151;">{{ $peak->notes }}</p>
                                </div>
                            @endif

                            {{-- CHILD POLICIES --}}
                            @php
                                $groupedPolicies = $this->getChildPoliciesGrouped($peak);
                            @endphp

                            @if ($groupedPolicies->count() > 0)
                                <div class="mt-4">
                                    <h6 class="mb-3" style="font-size: 13px; font-weight: 600; color: #374151;">
                                        <i class="bx bx-child me-1" style="font-size: 14px;"></i>
                                        Child Policies
                                    </h6>

                                    <div class="row g-3">
                                        @foreach ($groupedPolicies as $age => $policies)
                                            @php
                                                $policy = $policies->first();
                                            @endphp

                                            <div class="col-md-6">
                                                <div style="background: #FEF3C7; border-radius: 8px; padding: 12px;">
                                                    {{-- Age Header --}}
                                                    <div style="font-size: 12px; font-weight: 600; color: #92400E; margin-bottom: 8px;">
                                                        Age: {{ $age }}
                                                    </div>

                                                    {{-- Rate Boxes --}}
                                                    <div class="d-flex gap-2">
                                                        {{-- With Bed --}}
                                                        <div class="flex-fill" style="background: white; border-radius: 6px; padding: 8px; text-align: center;">
                                                            <div style="font-size: 10px; color: #6B7280; margin-bottom: 4px;">
                                                                With Bed
                                                            </div>
                                                            <div style="font-size: 14px; font-weight: 700; color: #F59E0B;">
                                                                ₹{{ number_format($policy->child_with_bed_rate ?? 0, 0) }}
                                                            </div>
                                                        </div>

                                                        {{-- Without Bed --}}
                                                        <div class="flex-fill" style="background: white; border-radius: 6px; padding: 8px; text-align: center;">
                                                            <div style="font-size: 10px; color: #6B7280; margin-bottom: 4px;">
                                                                Without Bed
                                                            </div>
                                                            <div style="font-size: 14px; font-weight: 700; color: #F59E0B;">
                                                                ₹{{ number_format($policy->child_without_bed_rate ?? 0, 0) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endforeach
    @else
        {{-- EMPTY STATE --}}
        <div class="crm-card" style="padding: 40px 20px; text-align: center;">
            <i class="bx bx-calendar-x" style="font-size: 48px; color: #E5E7EB;"></i>
            <h6 class="mt-2 mb-1" style="color: #6B7280; font-size: 14px; font-weight: 600;">No Peak Dates</h6>
            <p class="mb-0" style="color: #9CA3AF; font-size: 12px;">
                No peak dates configured for the selected season.
            </p>
        </div>
    @endif
</div>
