<div>
    @if ($roomCategories->count() > 0)
        @foreach ($roomCategories as $room)
            <div class="crm-card mb-3" style="overflow: hidden;">
                <div style="padding: 16px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                <div class="flex-grow-1">

                                    <!-- TITLE + PEAK BOXES INLINE -->
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <h6 class="crm-primary mb-0" style="font-size:15px;font-weight:600;">
                                            {{ $room->title }}
                                        </h6>

                                        @php
                                            $upcomingPeaks = $this->getUpcomingPeaks($room);
                                            $filteredOccupancies = $this->getFilteredOccupancies($room);
                                            $filteredPeakDates = $this->getFilteredPeakDates($room);
                                        @endphp

                                        @foreach ($upcomingPeaks as $peak)
                                            @php
                                                $dateRange = $this->getPeakDateRange($peak);
                                            @endphp
                                            <div
                                                style="
                        background:#FEF2F2;
                        border:1px solid #FECACA;
                        border-radius:6px;
                        padding:4px 8px;
                        line-height:1.2;
                    ">
                                                <div style="font-size:11px;font-weight:600;color:#DC2626;">
                                                    {{ $dateRange['start_date'] ? \Carbon\Carbon::parse($dateRange['start_date'])->format('d M') : '' }}
                                                    –
                                                    {{ $dateRange['end_date'] ? \Carbon\Carbon::parse($dateRange['end_date'])->format('d M Y') : '' }}
                                                </div>
                                                <div style="font-size:10px;color:#991B1B;">
                                                    {{ $peak->title }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- META LINE -->
                                    <div class="d-flex align-items-center gap-2 mt-1"
                                        style="font-size:11px;color:#6B7280;">
                                        <span>
                                            <i class="bx bx-group" style="font-size:12px;"></i>
                                            Max: {{ $room->max_occupancy ?? 'N/A' }}
                                        </span>

                                        @if ($filteredPeakDates->count() > 0)
                                            <span style="color: #DC2626;">• {{ $filteredPeakDates->count() }} peak
                                                period{{ $filteredPeakDates->count() > 1 ? 's' : '' }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- TOGGLE BUTTON -->
                                <button wire:click="toggleRoom({{ $room->room_categoris_id }})" class="btn btn-sm"
                                    style="background:#F3F4F6;color:#374151;border-radius:6px;padding:6px 12px;">
                                    <i
                                        class="bx bx-chevron-{{ $expandedRoom === $room->room_categoris_id ? 'up' : 'down' }}"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if ($filteredOccupancies->count() > 0)
                        <!-- Regular Rates -->
                        <div class="mt-2">
                            <small
                                style="font-size: 10px; color: #6B7280; font-weight: 600; display: block; margin-bottom: 4px;">REGULAR
                                RATES</small>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($filteredOccupancies as $occ)
                                    <div
                                        style="background: #F0FDF4; padding: 6px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;">
                                        <span
                                            style="font-size: 11px; color: #166534; font-weight: 500;">{{ $occ->occupancy->title ?? 'N/A' }}</span>
                                        <div class="d-flex flex-column" style="line-height:1.2;">
                                            <span style="font-size:11px;color:#166534;font-weight:600;">
                                                Weekday: ₹{{ number_format($occ->rate ?? 0, 0) }}
                                            </span>

                                            <span style="font-size:11px;color:#166534;">
                                                Weekend: ₹{{ number_format($occ->weekend_rate ?? 0, 0) }}
                                            </span>
                                        </div>

                                        {{-- <span class="crm-success"
                                            style="font-size: 13px; font-weight: 700;">₹{{ number_format($occ->rate ?? 0, 0) }}</span> --}}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Peak Date Rates -->
                        @if ($upcomingPeaks->count() > 0)
                            @foreach ($upcomingPeaks as $peak)
                                @php
                                    $peakOccupancies = $this->getPeakOccupancies($peak);
                                    $dateRange = $this->getPeakDateRange($peak);
                                @endphp
                                @if ($peakOccupancies->count() > 0)
                                    <div class="mt-3">
                                        <small
                                            style="font-size: 10px; color: #DC2626; font-weight: 600; display: block; margin-bottom: 4px;">
                                            <i class="bx bx-calendar-star" style="font-size: 11px;"></i>
                                            {{ strtoupper($peak->title) }}
                                            ({{ $dateRange['start_date'] ? \Carbon\Carbon::parse($dateRange['start_date'])->format('d M') : '' }}
                                            -
                                            {{ $dateRange['end_date'] ? \Carbon\Carbon::parse($dateRange['end_date'])->format('d M Y') : '' }})
                                        </small>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($peakOccupancies as $occ)
                                                <div
                                                    style="background: #FEF2F2; padding: 6px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #FECACA;">
                                                    <span
                                                        style="font-size: 11px; color: #DC2626; font-weight: 500;">{{ $occ->occupancy->title ?? 'N/A' }}</span>
                                                    {{-- <span
                                                        style="font-size: 13px; font-weight: 700; color: #DC2626;">₹{{ number_format($occ->rate ?? 0, 0) }}</span> --}}
                                                    <div class="d-flex flex-column" style="line-height:1.2;">
                                                        <span style="font-size:11px;color:#DC2626;font-weight:600;">
                                                            Weekday: ₹{{ number_format($occ->rate ?? 0, 0) }}
                                                        </span>

                                                        <span style="font-size:11px;color:#DC2626;">
                                                            Weekend: ₹{{ number_format($occ->weekend_rate ?? 0, 0) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <p class="mb-0 mt-2" style="color: #9CA3AF; font-size: 12px;">No pricing configured</p>
                    @endif
                </div>

                @if ($expandedRoom === $room->room_categoris_id)
                    <div style="padding: 16px; border-top: 1px solid #F3F4F6; background: #FAFBFC;">
                        @if ($room->childPolicies && $room->childPolicies->count() > 0)
                            <div class="mb-3">
                                <h6 class="mb-2" style="font-size: 13px; font-weight: 600; color: #374151;">
                                    <i class="bx bx-child me-1" style="font-size: 14px;"></i>Child Policies
                                </h6>
                                <div class="row g-2">
                                    @php
                                        $groupedPolicies = $room->childPolicies->groupBy('free_child_age');
                                    @endphp
                                    @foreach ($groupedPolicies as $ageGroup => $policies)
                                        <div class="col-md-6">
                                            <div style="background: #FEF3C7; padding: 10px; border-radius: 6px;">
                                                <div
                                                    style="font-size: 11px; color: #92400E; font-weight: 600; margin-bottom: 6px;">
                                                    Age: {{ $ageGroup }}</div>
                                                <div class="d-flex gap-2">
                                                    <div class="flex-fill"
                                                        style="background: white; padding: 6px; border-radius: 4px; text-align: center;">
                                                        <small
                                                            style="font-size: 10px; color: #6B7280; display: block;">With
                                                            Bed</small>
                                                        <strong
                                                            style="font-size: 13px; color: #F59E0B;">₹{{ number_format($policies->first()->child_with_bed_rate ?? 0, 0) }}</strong>
                                                    </div>
                                                    <div class="flex-fill"
                                                        style="background: white; padding: 6px; border-radius: 4px; text-align: center;">
                                                        <small
                                                            style="font-size: 10px; color: #6B7280; display: block;">Without</small>
                                                        <strong
                                                            style="font-size: 13px; color: #F59E0B;">₹{{ number_format($policies->first()->child_without_bed_rate ?? 0, 0) }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($filteredPeakDates->count() > 0)
                            <div>
                                <h6 class="mb-2" style="font-size: 13px; font-weight: 600; color: #374151;">
                                    <i class="bx bx-calendar-star me-1"
                                        style="font-size: 14px; color: #DC2626;"></i>Peak Periods
                                </h6>
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($filteredPeakDates as $peak)
                                        @php
                                            $peakOccupancies = $this->getPeakOccupancies($peak);
                                            $dateRange = $this->getPeakDateRange($peak);
                                        @endphp
                                        <div
                                            style="background: #FEF2F2; padding: 12px; border-radius: 8px; border-left: 3px solid #DC2626;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span
                                                    style="font-size: 12px; color: #DC2626; font-weight: 600;">{{ $peak->title }}</span>
                                                @if ($peak->is_new_year)
                                                    <i class="bx bx-party" style="font-size: 13px; color: #DC2626;"></i>
                                                @endif
                                                <span style="font-size: 10px; color: #991B1B;">
                                                    {{ $dateRange['start_date'] ? \Carbon\Carbon::parse($dateRange['start_date'])->format('d M') : '' }}
                                                    -
                                                    {{ $dateRange['end_date'] ? \Carbon\Carbon::parse($dateRange['end_date'])->format('d M Y') : '' }}
                                                </span>
                                            </div>
                                            @if ($peakOccupancies->count() > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($peakOccupancies as $occ)
                                                        <div
                                                            style="background: white; padding: 6px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #FECACA;">
                                                            <span
                                                                style="font-size: 11px; color: #DC2626; font-weight: 500;">{{ $occ->occupancy->title ?? 'N/A' }}</span>
                                                            {{-- <span style="font-size: 13px; font-weight: 700; color: #DC2626;">₹{{ number_format($occ->rate ?? 0, 0) }}</span> --}}
                                                            <div class="d-flex flex-column" style="line-height:1.2;">
                                                                <span
                                                                    style="font-size:11px;color:#DC2626;font-weight:600;">
                                                                    Weekday: ₹{{ number_format($occ->rate ?? 0, 0) }}
                                                                </span>

                                                                <span style="font-size:11px;color:#DC2626;">
                                                                    Weekend:
                                                                    ₹{{ number_format($occ->weekend_rate ?? 0, 0) }}
                                                                </span>
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <small style="font-size: 10px; color: #991B1B;">No rates
                                                    configured</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="crm-card" style="padding: 40px 20px; text-align: center;">
            <i class="bx bx-door-open" style="font-size: 48px; color: #E5E7EB;"></i>
            <h6 class="mt-2 mb-1" style="color: #6B7280; font-size: 14px; font-weight: 600;">No Room Categories</h6>
            <p class="mb-0" style="color: #9CA3AF; font-size: 12px;">No rooms configured</p>
        </div>
    @endif
</div>
