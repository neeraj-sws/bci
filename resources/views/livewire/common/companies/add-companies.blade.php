<div class="container  mt-sm-0 mt-3">
    <div class="page-header d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24 border-0 text-black fw-600">
                {{ $organization ? 'Edit' : 'Add' }} {{ $pageTitle }}
            </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('common.companies') }}"><i
                                class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item">{{ $pageTitle }}</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $organization ? 'Edit' : 'Add' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body pb-0 pt-0 px-2">
            <!-- Progress bar -->
            <div class="mb-4">
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $tab * 25 }}%" aria-valuenow="{{ $tab * 25 }}"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <ul class="nav nav-pills nav-fill mb-4">
                @php $tabs = [1 => 'Company Profile', 2 => 'Preferences', 3 => 'Email Templates', 4 => 'Taxes']; @endphp

                @foreach ($tabs as $key => $label)
                    @php
                        $isDisabled = !$organization && $key > 1; // Disable 2,3,4 if no org
                    @endphp

                    <li class="nav-item me-3">
                        <a href="javascript:void(0)"
                            @unless ($isDisabled) wire:click="handleTabChange({{ $key }})" @endunless
                            class="nav-link p-2
               {{ $tab === $key ? 'active' : '' }}
               {{ $isDisabled ? 'disabled opacity-50 cursor-not-allowed' : '' }}">
                            @switch($key)
                                @case(1)
                                    <i class="ti ti-settings-cog me-2"></i>
                                @break

                                @case(2)
                                    <i class="ti ti-info-circle me-2"></i>
                                @break

                                @case(3)
                                    <i class="ti ti-users me-2"></i>
                                @break

                                @case(4)
                                    <i class="ti ti-file-tax me-2"></i>
                                @break
                            @endswitch

                            {{ $label }}

                            <div wire:loading wire:target="handleTabChange({{ $key }})"
                                class="ms-2 spinner-border spinner-border-sm text-dark"></div>
                        </a>
                    </li>
                @endforeach

            </ul>

        </div>
    </div>



    <div class="tab-content mt-3">
        @if ($tab === 1)
            <livewire:common.companies.compani-profile :id="$organization ? $organization->id : null" wire:key="company-profile" />
        @elseif ($tab === 2)
            <livewire:common.companies.preferences.compani-preferences :id="$organization ? $organization->id : null"
                wire:key="company-preferences" />
        @elseif ($tab === 3)
            <livewire:common.companies.email-settings.compani-email :id="$organization ? $organization->id : null" wire:key="company-emailsettings" />
        @elseif ($tab === 4)
            <livewire:common.companies.taxes.compani-tax :id="$organization ? $organization->id : null" wire:key="company-taxes" />
        @endif

    </div>


</div>
