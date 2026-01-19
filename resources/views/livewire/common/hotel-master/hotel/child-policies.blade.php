<div>
    @if($childPolicies->count() > 0)
        <div class="row">
            @foreach($groupedPolicies as $ageGroup => $policies)
                <div class="col-lg-6 mb-4">
                    <div class="crm-card" style="border-left: 4px solid #16A34A;">
                        <!-- Card Header -->
                        <div style="padding: 20px; background: #F0FDF4; border-bottom: 1px solid #DCFCE7;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 crm-success" style="font-size: 18px; font-weight: 600;">
                                        <i class="bx bx-child me-2"></i>Age: Up to {{ $ageGroup }} years
                                    </h5>
                                    <small style="color: #166534; font-size: 12px;">
                                        {{ $policies->count() }} {{ $policies->count() > 1 ? 'policies' : 'policy' }}
                                    </small>
                                </div>

                                @if($policies->first()->status)
                                    <span class="crm-badge crm-badge-active">Active</span>
                                @else
                                    <span class="crm-badge crm-badge-inactive">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div style="padding: 20px;">
                            @foreach($policies as $policy)
                                <div class="mb-3 pb-3" style="border-bottom: 1px solid #F3F4F6;">
                                    @if($policy->roomCategory)
                                        <div class="mb-3">
                                            <label style="font-size: 11px; color: #6B7280; font-weight: 500; display: block; margin-bottom: 4px;">
                                                Room Category
                                            </label>
                                            <span style="background: #F3F4F6; color: #374151; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                                                {{ $policy->roomCategory->title }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-6">
                                            <div style="background: #FEF3C7; padding: 14px; border-radius: 8px; text-align: center;">
                                                <label style="font-size: 11px; color: #92400E; font-weight: 500; display: block; margin-bottom: 6px;">
                                                    <i class="bx bx-bed me-1"></i>With Bed
                                                </label>
                                                <h4 class="mb-0 crm-warning" style="font-size: 22px; font-weight: 700;">
                                                    ₹{{ number_format($policy->child_with_bed_rate ?? 0, 0) }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div style="background: #FEF3C7; padding: 14px; border-radius: 8px; text-align: center;">
                                                <label style="font-size: 11px; color: #92400E; font-weight: 500; display: block; margin-bottom: 6px;">
                                                    <i class="bx bx-x me-1"></i>Without Bed
                                                </label>
                                                <h4 class="mb-0 crm-warning" style="font-size: 22px; font-weight: 700;">
                                                    ₹{{ number_format($policy->child_without_bed_rate ?? 0, 0) }}
                                                </h4>
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

        <!-- Summary Info Box -->
        <div class="crm-card" style="padding: 20px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);">
            <div class="row text-white">
                <div class="col-md-4 text-center">
                    <i class="bx bx-group" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $groupedPolicies->count() }}</h3>
                    <small style="opacity: 0.9;">Age Groups</small>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bx bx-file" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $childPolicies->count() }}</h3>
                    <small style="opacity: 0.9;">Total Policies</small>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bx bx-check-circle" style="font-size: 32px; opacity: 0.9;"></i>
                    <h3 class="mt-2 mb-0" style="font-weight: 700;">{{ $childPolicies->where('status', 1)->count() }}</h3>
                    <small style="opacity: 0.9;">Active Policies</small>
                </div>
            </div>
        </div>
    @else
        <div class="crm-card" style="padding: 60px 20px; text-align: center;">
            <i class="bx bx-child" style="font-size: 64px; color: #E5E7EB;"></i>
            <h5 class="mt-3" style="color: #6B7280; font-size: 16px; font-weight: 600;">No Child Policies</h5>
            <p class="mb-0" style="color: #9CA3AF; font-size: 14px;">No child policies have been configured for this hotel</p>
        </div>
    @endif
</div>
