<div class="crm-card mb-4" style="padding: 24px;">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Left Side - Hotel Info -->
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-3 mb-2">
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
                            <i class="bx bxs-star" style="color: #F59E0B; font-size: 16px;"></i>
                        @else
                            <i class="bx bx-star" style="color: #E5E7EB; font-size: 16px;"></i>
                        @endif
                    @endfor
                </div>

                <!-- Hotel Category -->
                @if ($hotel->hotelCategory)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-category" style="color: #6B7280; font-size: 16px;"></i>
                        <span style="color: #6B7280; font-size: 14px;">{{ $hotel->hotelCategory->title }}</span>
                    </div>
                @endif

                <!-- Meal Plan -->
                @if ($hotel->hotelMealType && $hotel->hotelMealType->count() > 0)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-dish" style="color: #6B7280; font-size: 16px;"></i>
                        <span
                            style="color: #6B7280; font-size: 14px;">{{ $hotel->hotelMealType->first()->mealType->title ?? '' }}</span>
                    </div>
                @endif

                <!-- Location -->
                @if ($hotel->location)
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-map" style="color: #6B7280; font-size: 16px;"></i>
                        <span style="color: #6B7280; font-size: 14px;">{{ $hotel->location }}</span>
                    </div>
                @endif
            </div>
        </div>
        <!-- Right Side - Season Filter & Actions -->
        <div class="d-flex gap-3 align-items-center ms-4">
            <!-- Season Selector -->
            <div style="min-width: 200px;">
                <label
                    style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 6px;">Select
                    Season</label>
                <select class="form-select" wire:model.live="selectedSeason"
                    style="font-size: 14px;">
                    <option value="">All Seasons</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->seasons_id }}">
                            {{ $season->name }} ({{ \Carbon\Carbon::parse($season->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($season->end_date)->format('M d') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2" style="padding-top: 20px;">
                <a href="{{ route('common.update-hotel', $hotel->id) }}" class="btn btn-sm d-flex align-items-center"
                    style="background: #0F172A; color: white; border-radius: 6px; padding: 8px 16px; font-weight: 500; font-size: 13px; white-space: nowrap;">
                    <i class="bx bx-edit" style="font-size: 16px; margin-right: 4px;"></i> Edit Hotel
                </a>
                <a href="{{ route('common.hotel-list') }}" class="btn btn-sm d-flex align-items-center"
                    style="background: #F3F4F6; color: #374151; border-radius: 6px; padding: 8px 16px; font-weight: 500; font-size: 13px; white-space: nowrap; border: 1px solid #E5E7EB;">
                    <i class="bx bx-arrow-back" style="font-size: 16px; margin-right: 4px;"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
