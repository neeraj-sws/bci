<div class="row">
    <!-- LEFT COLUMN - Information Cards -->
    <div class="col-lg-8">
        <!-- Hotel Information Card -->
        <div class="crm-card mb-4" style="padding: 24px;">
            <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                <i class="bx bx-building me-2"></i>Hotel Information
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Hotel
                        Name</label>
                    <p class="mb-0 crm-primary" style="font-size: 15px; font-weight: 600;">{{ $hotel->name }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Category</label>
                    <p class="mb-0" style="font-size: 15px;">
                        <span
                            style="background: #DCFCE7; color: #16A34A; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                            {{ $hotel->hotelCategory->title ?? 'N/A' }}
                        </span>
                    </p>
                </div>

                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Star
                        Rating</label>
                    <div class="d-flex gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= ($hotel->star_rating ?? 0))
                                <i class="bx bxs-star" style="color: #F59E0B; font-size: 16px;"></i>
                            @else
                                <i class="bx bx-star" style="color: #E5E7EB; font-size: 16px;"></i>
                            @endif
                        @endfor
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Location</label>
                    <p class="mb-0" style="font-size: 15px;">{{ $hotel->location ?? 'N/A' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <label
                        style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Meal
                        Plan</label>
                    <p class="mb-0 gap-1 d-flex" style="font-size: 15px;">
                        @foreach ($hotel->hotelMealType as $item)
                            <span
                                style="background: #E0F2FE; color: #0369A1; padding: 4px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                {{ $item->mealType->title ?? 'N/A' }}
                            </span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <!-- Association Card - Conditional Logic -->
        @if ($marketingCompany)
            <!-- Marketing Company Card -->
            <div class="crm-card mb-4" style="padding: 24px;">
                <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                    <i class="bx bx-buildings me-2"></i>Marketing Company
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Company Name</label>
                        <p class="mb-0 crm-primary" style="font-size: 15px; font-weight: 600;">{{ $marketingCompany->name }}</p>
                    </div>

                    @if ($marketingCompany->contact_person)
                        <div class="col-md-6 mb-3">
                            <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Contact Person</label>
                            <p class="mb-0" style="font-size: 15px;">{{ $marketingCompany->contact_person }}</p>
                        </div>
                    @endif

                    @if ($marketingCompany->email)
                        <div class="col-md-6 mb-3">
                            <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Email</label>
                            <p class="mb-0" style="font-size: 15px;">{{ $marketingCompany->email }}</p>
                        </div>
                    @endif

                    @if ($marketingCompany->phone)
                        <div class="col-md-6 mb-3">
                            <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Phone</label>
                            <p class="mb-0" style="font-size: 15px;">{{ $marketingCompany->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($parentChain)
            <!-- Parent Chain Card -->
            <div class="crm-card mb-4" style="padding: 24px;">
                <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                    <i class="bx bx-link me-2"></i>Parent Chain
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Chain Name</label>
                        <p class="mb-0 crm-primary" style="font-size: 15px; font-weight: 600;">{{ $parentChain->name }}</p>
                    </div>

                    @if ($parentChain->location)
                        <div class="col-md-6 mb-3">
                            <label style="font-size: 12px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Location</label>
                            <p class="mb-0" style="font-size: 15px;">{{ $parentChain->location }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Independent Hotel Card -->
            <div class="crm-card mb-4" style="padding: 24px;">
                <div style="text-align: center; padding: 40px 20px;">
                    <i class="bx bx-hotel" style="font-size: 48px; color: #E5E7EB;"></i>
                    <h5 class="mt-3" style="color: #6B7280; font-size: 16px; font-weight: 600;">Independent Hotel</h5>
                    <p class="mb-0" style="color: #9CA3AF; font-size: 14px;">Not associated with any marketing company or chain</p>
                </div>
            </div>
        @endif
    </div>

    <!-- RIGHT COLUMN - Pricing Snapshot -->
    <div class="col-lg-4">
        <div class="crm-card" style="padding: 24px; position: sticky; top: 24px;">
            <h5 class="crm-primary mb-4" style="font-size: 18px; font-weight: 600;">
                <i class="bx bx-money me-2"></i>Pricing Snapshot
            </h5>

            <!-- Lowest Single Price -->
            <div class="mb-4 p-3" style="background: #F0FDF4; border-radius: 10px; border-left: 4px solid #16A34A;">
                <label style="font-size: 12px; color: #166534; font-weight: 500; display: block; margin-bottom: 8px;">Lowest Single Price</label>
                <h3 class="mb-0 crm-success" style="font-size: 32px; font-weight: 700;">₹{{ number_format($lowestSinglePrice, 0) }}</h3>
                <small style="color: #16A34A; font-size: 12px;">per night</small>
            </div>

            <!-- Lowest Double Price -->
            <div class="mb-4 p-3" style="background: #F0FDF4; border-radius: 10px; border-left: 4px solid #16A34A;">
                <label style="font-size: 12px; color: #166534; font-weight: 500; display: block; margin-bottom: 8px;">Lowest Double Price</label>
                <h3 class="mb-0 crm-success" style="font-size: 32px; font-weight: 700;">₹{{ number_format($lowestDoublePrice, 0) }}</h3>
                <small style="color: #16A34A; font-size: 12px;">per night</small>
            </div>

            <!-- Peak Single Price -->
            @if ($peakSinglePrice > 0)
                <div class="mb-4 p-3" style="background: #FEF3C7; border-radius: 10px; border-left: 4px solid #F59E0B;">
                    <label style="font-size: 12px; color: #92400E; font-weight: 500; display: block; margin-bottom: 8px;">Peak Single Price</label>
                    <h3 class="mb-0 crm-warning" style="font-size: 32px; font-weight: 700;">₹{{ number_format($peakSinglePrice, 0) }}</h3>
                    <small style="color: #F59E0B; font-size: 12px;">during peak dates</small>
                </div>
            @endif

            <!-- Peak Double Price -->
            @if ($peakDoublePrice > 0)
                <div class="mb-4 p-3" style="background: #FEF3C7; border-radius: 10px; border-left: 4px solid #F59E0B;">
                    <label style="font-size: 12px; color: #92400E; font-weight: 500; display: block; margin-bottom: 8px;">Peak Double Price</label>
                    <h3 class="mb-0 crm-warning" style="font-size: 32px; font-weight: 700;">₹{{ number_format($peakDoublePrice, 0) }}</h3>
                    <small style="color: #F59E0B; font-size: 12px;">during peak dates</small>
                </div>
            @endif

            <!-- Highest Peak Surcharge -->
            @if ($highestPeakSurcharge > 0)
                <div class="p-3" style="background: #FEF2F2; border-radius: 10px; border-left: 4px solid #DC2626;">
                    <label style="font-size: 12px; color: #991B1B; font-weight: 500; display: block; margin-bottom: 8px;">Highest Peak Surcharge</label>
                    <h3 class="mb-0 crm-danger" style="font-size: 32px; font-weight: 700;">+₹{{ number_format($highestPeakSurcharge, 0) }}</h3>
                    <small style="color: #DC2626; font-size: 12px;">during peak dates</small>
                </div>
            @else
                <div class="p-3" style="background: #F9FAFB; border-radius: 10px; text-align: center;">
                    <i class="bx bx-info-circle" style="font-size: 24px; color: #9CA3AF;"></i>
                    <p class="mb-0 mt-2" style="color: #6B7280; font-size: 13px;">No peak surcharges configured</p>
                </div>
            @endif
        </div>
    </div>
</div>
