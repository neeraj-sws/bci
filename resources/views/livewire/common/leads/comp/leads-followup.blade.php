<div>
    <div class="card p-3">
       
        @forelse ($followUps as $followup)
            <div class="border-start border-2 ps-3 position-relative" style="margin-left: 15px;">

                @php
                    $stage = $followup->stage ?? null;
                    $status = $followup->status ?? null;
                    $followupDate = \Carbon\Carbon::parse($followup->followup_date ?? now())->format('F d, Y');
                    $updatedAt = \Carbon\Carbon::parse($followup->updated_at ?? now())->format('F d, Y h:iA');
                @endphp

                <div class="position-absolute top-0 start-0 translate-middle rounded-circle"
                    style="width: 12px; height: 12px; 
                        color: {{ $stage->btn_text ?? '#fff' }}; 
                        background: {{ $followup->mark ?? $stage->btn_bg ?? '#ccc' }};">
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span class="badge"
                        style="color: {{ $stage->btn_text ?? '#fff' }}; 
                               background: {{ $stage->btn_bg ?? '#ccc' }};">
                        {{ $stage->name ?? 'N/A' }}
                    </span>
                    <span class="badge"
                        style="color: {{ $status->btn_text ?? '#fff' }}; 
                               background: {{ $status->btn_bg ?? '#ccc' }};">
                        {{ $status->name ?? 'N/A' }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-calendar3"></i>
                        {{ $followupDate }}
                    </span>
                </div>

                <p class="mb-2">{{ $followup->comments ?? '' }}</p>

              <div class="d-flex align-items-center mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold me-2"
                        style="width: 40px; height: 40px;">
                       {{ isset($followup?->marketingperson) ? strtoupper(substr($followup?->marketingperson->name, 0, 1)) : ($followup?->user_id ? strtoupper(substr($followup?->user?->name, 0, 1)) : 'A') }}
                    </div>

                    <div>
                        <strong>{{ ucwords($followup?->marketingperson->name ?? $followup?->user?->name ?? 'Admin')}}</strong>
                        <div>
                            Updated on {{ $updatedAt }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No follow-ups found.</p>
        @endforelse
    </div>
</div>
