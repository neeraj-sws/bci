<div class="crm-card" style="padding: 16px; position: sticky; top: 24px; max-height: 75vh; overflow-y: auto;">
    <h6 class="crm-primary mb-3" style="font-size: 14px; font-weight: 600;">
        <i class="bx bx-time me-1" style="font-size: 16px;"></i>Timeline
    </h6>

    @if($roomCategories->count() > 0)
        <div style="position: relative;">
            <div style="position: absolute; left: 6px; top: 0; bottom: 0; width: 1px; background: #E5E7EB;"></div>

            @foreach($roomCategories as $room)
                <div class="mb-3" style="position: relative; padding-left: 20px;">
                    @if($room->peakDates && $room->peakDates->count() > 0)
                        <div style="position: absolute; left: 3px; top: 2px; width: 8px; height: 8px; background: #DC2626; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 1px #FEE2E2;"></div>
                    @else
                        <div style="position: absolute; left: 3px; top: 2px; width: 8px; height: 8px; background: #16A34A; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 1px #DCFCE7;"></div>
                    @endif

                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="mb-0 crm-primary" style="font-size: 13px; font-weight: 600;">{{ $room->title }}</h6>
                            @if($room->peakDates && $room->peakDates->count() > 0)
                                <span style="background: #FEE2E2; color: #DC2626; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;">{{ $room->peakDates->count() }} peak{{ $room->peakDates->count() > 1 ? 's' : '' }}</span>
                            @endif
                        </div>

                        @if($room->peakDates && $room->peakDates->count() > 0)
                            <div style="font-size: 11px; color: #6B7280; line-height: 1.4;">
                                @foreach($room->peakDates as $peak)
                                    @php
                                        // Get season-filtered occupancies
                                        $filteredOccs = $peak->occupancies->filter(function ($occ) use ($selectedSeason) {
                                            return is_null($occ->season_id) || $occ->season_id == $selectedSeason;
                                        });
                                        
                                        // Get date range from first matching occupancy
                                        $firstOcc = $filteredOccs->first();
                                        $startDate = $firstOcc->start_date ?? null;
                                        $endDate = $firstOcc->end_date ?? null;
                                    @endphp
                                    <div style="margin-bottom: 2px;">
                                        @if($peak->title)
                                            {{ $peak->title }}
                                        @elseif($startDate && $endDate)
                                            {{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M') }}
                                        @else
                                            {{ $peak->title ?? 'Peak Date' }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <small style="color: #16A34A; font-size: 11px;">No peak dates</small>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 20px 10px;">
            <i class="bx bx-calendar-x" style="font-size: 32px; color: #E5E7EB;"></i>
            <p class="mb-0 mt-2" style="color: #6B7280; font-size: 12px;">No rooms</p>
        </div>
    @endif
</div>
