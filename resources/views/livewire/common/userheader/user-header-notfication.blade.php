                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                        id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span
                            class="alert-count badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle">
                            {{ count($notifications) }}
                        </span>
                        <i class="bx bx-bell fs-4"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg" aria-labelledby="notificationDropdown"
                        style="min-width: 350px; max-height: 450px; overflow-y: auto;">
                        <div class="px-3 py-2 border-bottom bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Notifications</h6>
                                <span class="badge bg-primary">{{ count($notifications) }} New</span>
                            </div>
                        </div>

                        <div class="list-group list-group-flush">
                            @forelse($notifications as $notification)
                                <a wire:click='open({{$notification->id}})'
                                    class="list-group-item list-group-item-action d-flex align-items-start gap-3">
                                    <div class="bg-info bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;background-color:{{ optional($notification->followup)->mark }}">
                                        {{ strtoupper(substr($notification->user->name ?? 'NT', 0, 2)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">{{ $notification->msg->message_type ?? 'No Title' }}</h6>
                                            <small
                                                class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                           <span class="badge"
    style="color:{{ optional(optional($notification->lead)->stage)->btn_text }};
           background:{{ optional(optional($notification->lead)->stage)->btn_bg }}">
    {{ optional(optional($notification->lead)->stage)->name ?? 'NA' }}
</span>

<span class="badge"
    style="color:{{ optional(optional($notification->lead)->status)->btn_text }};
           background:{{ optional(optional($notification->lead)->status)->btn_bg }}">
    {{ optional(optional($notification->lead)->status)->name ?? 'NA' }}
</span>

                                        </div>
                                        <p class="mb-0 text-muted">
                                            {{ $notification->followup->comments ?? 'No message available' }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="px-3 py-2 text-center text-muted">
                                    No new notifications.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </li>
