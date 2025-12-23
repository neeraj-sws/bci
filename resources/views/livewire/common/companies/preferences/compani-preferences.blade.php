<div class="container mt-4">
  <div class="row">
    <div class="col-md-3 mb-3">
      <ul class="nav flex-column nav-pills gap-2" id="sidebarTabs" role="tablist">
        <li class="nav-item">
          <a href="javascript:void(0)" wire:click="handleTabChange(1)"
             @class([
                'nav-link text-start',
                'active text-white bg-danger' => $tab === 1,
                'text-dark bg-light' => $tab !== 1
             ]) 
             role="tab">
            General Settings
          </a>
          <div wire:loading wire:target="handleTabChange(1)" class="spinner-border spinner-border-sm text-dark mt-1"></div>
        </li>
        <li class="nav-item">
          <a href="javascript:void(0)" wire:click="handleTabChange(2)"
             @class([
                'nav-link text-start',
                'active text-white bg-danger' => $tab === 2,
                'text-dark bg-light' => $tab !== 2
             ]) 
             role="tab">
            Invoice Settings
          </a>
          <div wire:loading wire:target="handleTabChange(2)" class="spinner-border spinner-border-sm text-dark mt-1"></div>
        </li>
        <li class="nav-item">
          <a href="javascript:void(0)" wire:click="handleTabChange(3)"
             @class([
                'nav-link text-start',
                'active text-white bg-danger' => $tab === 3,
                'text-dark bg-light' => $tab !== 3
             ]) 
             role="tab">
            Quotation Settings
          </a>
          <div wire:loading wire:target="handleTabChange(3)" class="spinner-border spinner-border-sm text-dark mt-1"></div>
        </li>
        <li class="nav-item">
          <a href="javascript:void(0)" wire:click="handleTabChange(4)"
             @class([
                'nav-link text-start',
                'active text-white bg-danger' => $tab === 4,
                'text-dark bg-light' => $tab !== 4
             ]) 
             role="tab">
            Inv / Est Column Settings
          </a>
          <div wire:loading wire:target="handleTabChange(4)" class="spinner-border spinner-border-sm text-dark mt-1"></div>
        </li>
      </ul>
    </div>

    <!-- Right Content -->
    <div class="col-md-9">
      @if ($tab === 1)
        <livewire:common.companies.preferences.general-settings :company_id="$company_id" />
      @endif

      @if ($tab === 2)
        <livewire:common.companies.preferences.invoice-settings :company_id="$company_id" />
      @endif

      @if ($tab === 3)
        <livewire:common.companies.preferences.estimate-settings :company_id="$company_id" />
      @endif

      @if ($tab === 4)
        <livewire:common.companies.preferences.column-settings :company_id="$company_id" />
      @endif
    </div>
  </div>
</div>
