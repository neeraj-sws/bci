<div class="container mt-sm-0 mt-3">
    <h3 class="mb-4 position-relative d-inline-block fw-bold mainheadingtext">
        Email Templates
        <span class="gradient-border-bottom"></span>
    </h3>
    <ul class="nav nav-tabs mt-4 d-flex gap-4 pb-2 navtabsbigborder overflow-auto w-100 flex-nowrap" id="tabsContainer">
        <li class="nav-item">
            <a wire:click="$set('tab', 1)" @class([
                'p-2 cursor-pointer text-nowrap',
                'activetabcolor' => $tab === 1,
                'inactivetabcolor' => $tab !== 1,
            ]) x-data x-init="$watch('$wire.tab', value => {
                if (value === 1) $el.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            })">
                Send Invoice
            </a>
        </li>
        <li class="nav-item">
            <a wire:click="$set('tab', 2)" @class([
                'p-2 cursor-pointer text-nowrap',
                'activetabcolor' => $tab === 2,
                'inactivetabcolor' => $tab !== 2,
            ]) x-data x-init="$watch('$wire.tab', value => {
                if (value === 2) $el.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            })">
                Send Estimate
            </a>
        </li>
    </ul>


    @if ($tab === 1)
        <livewire:common.preferences.email-settings.email-settings :type="1" :key="'Invoice'" />
    @endif

    @if ($tab === 2)
        <livewire:common.preferences.email-settings.email-settings :type="2" :key="'Estimate'" />
    @endif
    
</div>
