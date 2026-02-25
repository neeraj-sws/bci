<div> {{-- KPI Summary Card --}} @if ($peakDates->count() > 0)
        <div class="crm-card mb-3"
            style="padding: 16px; background: linear-gradient(135deg, #FEF2F2 0%, #FFFFFF 100%); border-left: 3px solid #DC2626;">
            <div class="d-flex justify-content-between align-items-center">
                <div> <small
                        style="font-size: 11px; color: #6B7280; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Highest
                        Peak Surcharge</small>
                    <h3 class="mb-0 mt-1" style="font-size: 28px; font-weight: 700; color: #DC2626;">
                        ₹{{ number_format($highestSurcharge, 0) }}</h3>
                    @if ($highestSurchargePeak)
                        <small style="font-size: 12px; color: #6B7280;">{{ $highestSurchargePeak->title }} •
                            {{ \Carbon\Carbon::parse($highestSurchargePeak->start_date)->format('d M') }} -
                            {{ \Carbon\Carbon::parse($highestSurchargePeak->end_date)->format('d M, Y') }}</small>
                    @endif
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; color: #6B7280; margin-bottom: 4px;">Total Peak Periods</div>
                    <div style="font-size: 24px; font-weight: 700; color: #0F172A;">{{ $peakDates->count() }}</div>
                </div>
            </div>
        </div> @endif {{-- Peak Date Master Cards --}} @if ($peakDates->count() > 0)
            @foreach ($peakDates as $peak)
                <div class="crm-card mb-3"
                    style="overflow: hidden; border-left: 3px solid {{ $peak->status ? '#DC2626' : '#9CA3AF' }};">
                    {{-- Collapsed Header --}} <div wire:click="togglePeak({{ $peak->peak_dates_id }})"
                        style="padding: 16px; cursor: pointer; {{ $expandedPeak === $peak->peak_dates_id ? 'background: #FAFBFC;' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h6 class="mb-0" style="font-size: 15px; font-weight: 600; color: #0F172A;">
                                        {{ \Carbon\Carbon::parse($peak->start_date)->format('d M') }} –
                                        {{ \Carbon\Carbon::parse($peak->end_date)->format('d M, Y') }} </h6>
                                    @if ($peak->is_new_year)
                                        <i class="bx bx-party" style="color: #DC2626; font-size: 16px;"></i>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-3" style="font-size: 11px; color: #6B7280;">
                                    <span>{{ \Carbon\Carbon::parse($peak->start_date)->diffInDays(\Carbon\Carbon::parse($peak->end_date)) + 1 }}
                                        Days</span> <span>•</span>
                                    <span>{{ $peak->roomCategory ? '1 Room' : '0 Rooms' }}</span> <span>•</span>
                                    @if ($peak->status)
                                        <span style="color: #16A34A; font-weight: 600;">Active</span>
                                    @else
                                        <span style="color: #9CA3AF; font-weight: 600;">Inactive</span>
                                    @endif
                                </div>
                                @if ($peak->extra_amount && $peak->extra_amount > 0)
                                    <div class="mt-2"> <span style="font-size: 11px; color: #6B7280;">Surcharge:
                                        </span> <span
                                            style="font-size: 14px; font-weight: 700; color: #DC2626;">+₹{{ number_format($peak->extra_amount, 0) }}</span>
                                    </div>
                                @endif
                            </div> <button class="btn btn-sm"
                                style="background: #F3F4F6; color: #374151; border-radius: 6px; padding: 6px 12px; font-size: 12px;">
                                <i
                                    class="bx bx-chevron-{{ $expandedPeak === $peak->peak_dates_id ? 'up' : 'down' }}"></i>
                            </button>
                        </div>
                    </div> {{-- Expanded View - Room-Wise Pricing --}} @if ($expandedPeak === $peak->peak_dates_id)
                        <div style="padding: 16px; border-top: 1px solid #E5E7EB; background: #FAFBFC;">
                            @if ($peak->roomCategory)
                                {{-- Single Room View --}}
                                <div class="mb-3">
                                    <h6 class="mb-2" style="font-size: 13px; font-weight: 600; color: #374151;"> <i
                                            class="bx bx-door-open me-1"
                                            style="font-size: 14px;"></i>{{ $peak->roomCategory->title }} </h6>
                                    @if ($peak->occupancies && $peak->occupancies->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($peak->occupancies as $occ)
                                                <div
                                                    style="background: #FEF2F2; padding: 6px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;">
                                                    <span
                                                        style="font-size: 11px; color: #DC2626; font-weight: 500;">{{ $occ->occupancy->title ?? 'N/A' }}</span>
                                                    <span class="crm-danger"
                                                        style="font-size: 13px; font-weight: 700;">₹{{ number_format($occ->rate ?? 0, 0) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="mb-0" style="color: #9CA3AF; font-size: 12px;">No pricing configured
                                        </p>
                                    @endif
                                </div>
                                @endif @if ($peak->notes)
                                    <div class="mt-3 p-2"
                                        style="background: white; border-radius: 6px; border-left: 2px solid #E5E7EB;">
                                        <small
                                            style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 4px;">Notes</small>
                                        <p class="mb-0" style="font-size: 12px; color: #374151;">{{ $peak->notes }}
                                        </p>
                                    </div>
                                @endif


                                @if ($peak->childPolicies && $peak->childPolicies->count() > 0)
                                    <div class="mt-4">
                                        <h6 class="mb-2" style="font-size:13px;font-weight:600;color:#374151;">
                                            <i class="bx bx-child me-1" style="font-size:14px;"></i>
                                            Child Policies
                                        </h6>

                                        @php
                                            $groupedPolicies = $peak->childPolicies->groupBy('free_child_age');
                                        @endphp

                                        <div class="row g-3">
                                            @foreach ($groupedPolicies as $age => $policies)
                                                @php
                                                    $policy = $policies->first();
                                                @endphp

                                                <div class="col-md-6">
                                                    <div
                                                        style="
                            background:#FEF3C7;
                            border-radius:8px;
                            padding:12px;
                        ">
                                                        <!-- Age Header -->
                                                        <div
                                                            style="font-size:12px;font-weight:600;color:#92400E;margin-bottom:8px;">
                                                            Age: {{ $age }}
                                                        </div>

                                                        <!-- Rate Boxes -->
                                                        <div class="d-flex gap-2">
                                                            <!-- With Bed -->
                                                            <div class="flex-fill"
                                                                style="
                                    background:#FFFFFF;
                                    border-radius:6px;
                                    padding:8px;
                                    text-align:center;
                                ">
                                                                <div
                                                                    style="font-size:10px;color:#6B7280;margin-bottom:4px;">
                                                                    With Bed
                                                                </div>
                                                                <div
                                                                    style="font-size:14px;font-weight:700;color:#F59E0B;">
                                                                    ₹{{ number_format($policy->child_with_bed_rate ?? 0, 0) }}
                                                                </div>
                                                            </div>

                                                            <!-- Without Bed -->
                                                            <div class="flex-fill"
                                                                style="
                                    background:#FFFFFF;
                                    border-radius:6px;
                                    padding:8px;
                                    text-align:center;
                                ">
                                                                <div
                                                                    style="font-size:10px;color:#6B7280;margin-bottom:4px;">
                                                                    Without
                                                                </div>
                                                                <div
                                                                    style="font-size:14px;font-weight:700;color:#F59E0B;">
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
        @else
            <div class="crm-card" style="padding: 40px 20px; text-align: center;"> <i class="bx bx-calendar-x"
                    style="font-size: 48px; color: #E5E7EB;"></i>
                <h6 class="mt-2 mb-1" style="color: #6B7280; font-size: 14px; font-weight: 600;">No Peak Dates</h6>
                <p class="mb-0" style="color: #9CA3AF; font-size: 12px;">No peak dates configured</p>
            </div>
        @endif
</div>
