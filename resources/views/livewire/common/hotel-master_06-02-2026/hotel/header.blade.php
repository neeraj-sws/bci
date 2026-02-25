<div class="crm-card mb-4" style="padding: 24px;">
    <div class="d-flex justify-content-between align-items-start">
        <!-- Left Side - Hotel Info -->
        <div>
            <div class="d-flex align-items-center gap-3 mb-3">
                <h2 class="crm-primary mb-0" style="font-size: 24px; font-weight: 600;">{{ $hotel->name }}</h2>

                @if ($hotel->status == 1)
                    <span class="crm-badge crm-badge-active">Active</span>
                @elseif($hotel->status == 0)
                    <span class="crm-badge crm-badge-inactive">Inactive</span>
                @else
                    <span class="crm-badge crm-badge-draft">Draft</span>
                @endif
            </div>

            <div class="d-flex align-items-center gap-4 flex-wrap">
                <!-- Star Rating -->
                <div class="d-flex align-items-center gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= ($hotel->star_rating ?? 0))
                            <i class="bx bxs-star" style="color: #F59E0B; font-size: 18px;"></i>
                        @else
                            <i class="bx bx-star" style="color: #E5E7EB; font-size: 18px;"></i>
                        @endif
                    @endfor
                </div>

                <!-- Location -->
                @if ($hotel->location)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-map" style="color: #6B7280;"></i>
                        <span style="color: #6B7280; font-size: 14px;">{{ $hotel->location }}</span>
                    </div>
                @endif

                <!-- Hotel Category -->
                @if ($hotel->hotelCategory)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-category" style="color: #6B7280;"></i>
                        <span style="color: #6B7280; font-size: 14px;">{{ $hotel->hotelCategory->title }}</span>
                    </div>
                @endif

                <!-- Meal Plan -->
                @if ($hotel->hotelMealType)
                    <div class="d-flex align-items-center gap-2">
                        @foreach ($hotel->hotelMealType as $item)
                            <i class="bx bx-dish" style="color: #6B7280;"></i>
                            <span style="color: #6B7280; font-size: 14px;">{{ $item->mealType->title }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side - Actions -->
        <div class="d-flex gap-2">
            <a href="{{ route('common.update-hotel', $hotel->id) }}"
               class="btn btn-sm"
               style="background: #0F172A; color: white; border-radius: 8px; padding: 10px 20px; font-weight: 600;">
                <i class="bx bx-edit me-1"></i> Edit Hotel
            </a>
            <a href="{{ route('common.hotel-list') }}"
               class="btn btn-sm"
               style="background: #F3F4F6; color: #374151; border-radius: 8px; padding: 10px 20px; font-weight: 600;">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
    </div>
</div>
