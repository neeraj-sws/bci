<div class="row">
    <div class="col-lg-8">
        <!-- Available Occupancy Types -->
        <div class="crm-card mb-4" style="padding: 24px;">
            <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                <i class="bx bx-group me-2"></i>Available Occupancy Types
            </h5>

            @if ($occupancies->count() > 0)
                <div class="row">
                    @foreach ($occupancies as $occupancy)
                        <div class="col-md-3 mb-3">
                            <div
                                style="background: #F0FDF4; padding: 16px; border-radius: 8px; border-left: 3px solid #16A34A;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bx bx-user" style="font-size: 24px; color: #16A34A;"></i>
                                    <div>
                                        <p class="mb-0" style="font-size: 15px; font-weight: 600; color: #0F172A;">
                                            {{ $occupancy->title }}</p>
                                        <small style="font-size: 11px; color: #166534;">Active</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #9CA3AF; font-size: 14px;">No occupancy types configured</p>
            @endif
        </div>

        <!-- Hotel Configuration -->
        <div class="crm-card mb-4" style="padding: 24px;">
            <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                <i class="bx bx-cog me-2"></i>Hotel Configuration
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Hotel
                        Type</label>
                    <p class="mb-0" style="font-size: 15px;">
                        <span
                            style="background: #E0F2FE; color: #0369A1; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                            {{ $hotel->hotelType->title ?? 'N/A' }}
                        </span>
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Default
                        Meal Plan</label>
                    <p class="mb-0 gap-1 d-flex" style="font-size: 15px;">
                        @foreach ($hotel->hotelMealType as $item)
                            <span
                                style="background: #FEF3C7; color: #92400E; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                {{ $item->mealType->title ?? 'NA' }}
                            </span>
                        @endforeach
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Status</label>
                    <p class="mb-0">
                        @if ($hotel->status == 1)
                            <span class="crm-badge crm-badge-active">Active</span>
                        @elseif($hotel->status == 0)
                            <span class="crm-badge crm-badge-inactive">Inactive</span>
                        @else
                            <span class="crm-badge crm-badge-draft">Draft</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Policies & Terms -->
        <div class="crm-card mb-4" style="padding: 24px;">
            <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                <i class="bx bx-file-blank me-2"></i>Policies & Terms
            </h5>

            <!-- Cancellation Policy -->
            <div class="mb-4">
                <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 8px;">
                    <i class="bx bx-x-circle me-1"></i>Cancellation Policy
                </label>
                @if ($hotel->cancellation_policy)
                    <div
                        style="background: #F9FAFB; padding: 16px; border-radius: 8px; border-left: 3px solid #6B7280;">
                        <p class="mb-0" style="font-size: 14px; color: #374151; line-height: 1.6;">
                            {{ $hotel->cancellation_policy }}
                        </p>
                    </div>
                @else
                    <p style="color: #9CA3AF; font-size: 14px;">No cancellation policy defined</p>
                @endif
            </div>

            <!-- Notes -->
            <div>
                <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 8px;">
                    <i class="bx bx-note me-1"></i>Additional Notes
                </label>
                @if ($hotel->notes)
                    <div
                        style="background: #F9FAFB; padding: 16px; border-radius: 8px; border-left: 3px solid #6B7280;">
                        <p class="mb-0" style="font-size: 14px; color: #374151; line-height: 1.6;">
                            {{ $hotel->notes }}
                        </p>
                    </div>
                @else
                    <p style="color: #9CA3AF; font-size: 14px;">No additional notes</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column - Quick Info -->
    <div class="col-lg-4">
        <!-- Taxes Card (if applicable) -->
        <div class="crm-card mb-4" style="padding: 20px;">
            <h6 class="crm-primary mb-3" style="font-size: 16px; font-weight: 600;">
                <i class="bx bx-receipt me-2"></i>Taxes & Charges
            </h6>

            @php
                // You may need to adjust this based on your actual tax structure
                $gst = $hotel->gst_percentage ?? 0;
                $serviceTax = $hotel->service_tax ?? 0;
            @endphp

            @if ($gst > 0 || $serviceTax > 0)
                <div class="mb-3">
                    <label
                        style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">GST</label>
                    <h4 class="mb-0 crm-primary" style="font-size: 24px; font-weight: 700;">{{ $gst }}%</h4>
                </div>

                @if ($serviceTax > 0)
                    <div>
                        <label
                            style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Service
                            Tax</label>
                        <h4 class="mb-0 crm-primary" style="font-size: 24px; font-weight: 700;">{{ $serviceTax }}%
                        </h4>
                    </div>
                @endif
            @else
                <p class="mb-0" style="color: #9CA3AF; font-size: 13px; text-align: center; padding: 20px 0;">
                    No tax information available
                </p>
            @endif
        </div>

        <!-- Important Dates Card -->
        <div class="crm-card" style="padding: 20px;">
            <h6 class="crm-primary mb-3" style="font-size: 16px; font-weight: 600;">
                <i class="bx bx-calendar me-2"></i>Record Info
            </h6>

            <div class="mb-3">
                <label
                    style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Created
                    At</label>
                <p class="mb-0" style="font-size: 13px; color: #374151;">
                    {{ $hotel->created_at ? $hotel->created_at->format('d M, Y') : 'N/A' }}
                </p>
            </div>

            <div>
                <label
                    style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Last
                    Updated</label>
                <p class="mb-0" style="font-size: 13px; color: #374151;">
                    {{ $hotel->updated_at ? $hotel->updated_at->format('d M, Y h:i A') : 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>
