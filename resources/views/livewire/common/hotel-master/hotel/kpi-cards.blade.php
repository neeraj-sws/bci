<div class="row mb-4">
    <!-- KPI Card 1 - Total Room Categories -->
    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #DBEAFE; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-door-open" style="font-size: 24px; color: #0F172A;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Room Categories</p>
                    <h3 class="mb-0 crm-primary" style="font-size: 28px; font-weight: 700;">{{ $totalRoomCategories }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Card 2 - Total Peak Date Ranges -->
    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-calendar-star" style="font-size: 24px; color: #F59E0B;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Peak Dates</p>
                    <h3 class="mb-0 crm-primary" style="font-size: 28px; font-weight: 700;">{{ $totalPeakDates }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Card 3 - Total Child Policies -->
    <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #DCFCE7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user" style="font-size: 24px; color: #16A34A;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Child Policies</p>
                    <h3 class="mb-0 crm-primary" style="font-size: 28px; font-weight: 700;">{{ $totalChildPolicies }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Card 4 - Starting Price -->
    <div class="col-lg-3 col-md-6">
        <div class="crm-card" style="padding: 20px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 48px; height: 48px; background: #E0F2FE; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="bx bx-rupee" style="font-size: 24px; color: #0F172A;"></i>
                </div>
                <div>
                    <p class="mb-0" style="font-size: 12px; color: #6B7280; font-weight: 500;">Starting Price</p>
                    <h3 class="mb-0 crm-success" style="font-size: 28px; font-weight: 700;">₹{{ number_format($startingPrice, 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
