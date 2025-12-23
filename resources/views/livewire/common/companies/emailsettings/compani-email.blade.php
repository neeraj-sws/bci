<div class="container mt-4">
    <div class="row">
        <!-- Sidebar Tabs -->
        <div class="col-md-3 mb-3">
            <ul class="nav flex-column nav-pills gap-2" id="sidebarTabs" role="tablist">
                <li class="nav-item">
                    <a href="javascript:void(0)" wire:click="handleTabChange(1)" @class([
                        'nav-link text-start',
                        'active text-white bg-danger' => $tab === 1,
                        'text-dark bg-light' => $tab !== 1,
                    ])
                        role="tab">
                        Send Invoice
                    </a>
                    <div wire:loading wire:target="handleTabChange(1)"
                        class="spinner-border spinner-border-sm text-dark mt-1"></div>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" wire:click="handleTabChange(2)" @class([
                        'nav-link text-start',
                        'active text-white bg-danger' => $tab === 2,
                        'text-dark bg-light' => $tab !== 2,
                    ])
                        role="tab">
                        Send Quotation
                    </a>
                    <div wire:loading wire:target="handleTabChange(2)"
                        class="spinner-border spinner-border-sm text-dark mt-1"></div>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" wire:click="handleTabChange(3)" @class([
                        'nav-link text-start',
                        'active text-white bg-danger' => $tab === 3,
                        'text-dark bg-light' => $tab !== 3,
                    ])
                        role="tab">
                        Send PrInvoice
                    </a>
                    <div wire:loading wire:target="handleTabChange(3)"
                        class="spinner-border spinner-border-sm text-dark mt-1"></div>
                </li>
            </ul>
        </div>

        <!-- Right Content -->
        <div class="col-md-9">
            @if ($tab === 1)
                <livewire:common.companies.email-settings.email-settings :type="1" :key="'Invoice'"
                    :company_id="$company_id" />
            @endif

            @if ($tab === 2)
                <livewire:common.companies.email-settings.email-settings :type="2" :key="'Estimate'"
                    :company_id="$company_id" />
            @endif

            @if ($tab === 3)
                <livewire:common.companies.email-settings.email-settings :type="3" :key="'PrInvoice'"
                    :company_id="$company_id" />
            @endif
        </div>
    </div>
</div>
