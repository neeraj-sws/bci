<div>

    <div class="card p-3">
        @foreach ($historys as $history)
            <div class="border-start border-2 ps-3 position-relative" style="margin-left: 15px;">
                <!-- Timeline Dot -->
                <div class="position-absolute top-0 start-0 translate-middle bg-info rounded-circle"
                    style="width: 12px; height: 12px;"></div>

                <!-- User Info -->
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold me-2" style="width: 40px; height: 40px;">
                    {{ isset($history?->marketingperson) ? strtoupper(substr($history?->marketingperson->name, 0, 1)) : ($history?->user_id ? strtoupper(substr($history?->user?->name, 0, 1)) : 'A') }}
                </div>
            
                <div class="d-flex flex-column">
                    <strong>{{ $history?->msg?->message_type ?? 'NA' }} by {{ $history?->marketingperson?->name ?? $history?->user?->name ?? 'Admin' }}</strong>
                    <div class="d-flex justify-content-between">
                        <span>On {{ \Carbon\Carbon::parse($history->updated_at ?? now())->format(App\Helpers\SettingHelper::getGenrealSettings('date_format') ?? 'd M Y') }}</span>
                    </div>
                </div>
                </div>
                
                             <span>{{ \Carbon\Carbon::parse($history->updated_at)->format('h:iA') }}</span>
                
            </div>
            


            </div>
        @endforeach
    </div>

</div>
