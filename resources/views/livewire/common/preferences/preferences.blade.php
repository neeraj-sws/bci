<div class="container mt-sm-0 mt-3">
   <div class="d-flex justify-content-between align-items-center">
        <h3 class="mb-4 position-relative d-inline-block fw-bold mainheadingtext">
        Preferences
        <span class="gradient-border-bottom"></span>
    </h3>
            
                    <div class="d-flex gap-3">
                <a href="javascript:void(0)" wire:click='confirmDelete(1)' class="btn bluegradientbtn"  wire:loading.attr="disabled"> <i class="fadeIn animated bx bx-layer-minus"></i> Clear Leads </a>
                 <a href="javascript:void(0)"  wire:click='confirmDelete(2)' class="btn bluegradientbtn"  wire:loading.attr="disabled"> <i class="fadeIn animated bx bx-memory-card"></i> Clear Quotations</a>
            </div>
   </div>
            
<ul class="nav nav-tabs mt-4 d-flex gap-4 pb-2 navtabsbigborder overflow-auto w-100 flex-nowrap" id="tabsContainer">
    <li class="nav-item">
        <a wire:click="$set('tab', 1)"
           @class([
               'p-2 cursor-pointer text-nowrap',
               'activetabcolor' => $tab === 1,
               'inactivetabcolor' => $tab !== 1,
           ])
           x-data
           x-init="$watch('$wire.tab', value => {
               if (value === 1) $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
           })"
        >
            General Settings
        </a>
    </li>
    <li class="nav-item">
        <a wire:click="$set('tab', 2)"
           @class([
               'p-2 cursor-pointer text-nowrap',
               'activetabcolor' => $tab === 2,
               'inactivetabcolor' => $tab !== 2,
           ])
           x-data
           x-init="$watch('$wire.tab', value => {
               if (value === 2) $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
           })"
        >
            Invoice Settings
        </a>
    </li>
    <li class="nav-item">
        <a wire:click="$set('tab', 3)"
           @class([
               'p-2 cursor-pointer text-nowrap',
               'activetabcolor' => $tab === 3,
               'inactivetabcolor' => $tab !== 3,
           ])
           x-data
           x-init="$watch('$wire.tab', value => {
               if (value === 3) $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
           })"
        >
            Quotation Settings
        </a>
    </li>
    <li class="nav-item">
        <a wire:click="$set('tab', 4)"
           @class([
               'p-2 cursor-pointer text-nowrap',
               'activetabcolor' => $tab === 4,
               'inactivetabcolor' => $tab !== 4,
           ])
           x-data
           x-init="$watch('$wire.tab', value => {
               if (value === 4) $el.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
           })"
        >
            Inv / Est Column Settings
        </a>
    </li>
</ul>


    @if ($tab === 1)
        <livewire:common.preferences.general-settings />
    @endif

    @if ($tab === 2)
        <livewire:common.preferences.invoice-settings />
    @endif

    @if ($tab === 3)
        <livewire:common.preferences.estimate-settings />
    @endif

    @if ($tab === 4)
        <livewire:common.preferences.column-settings />
    @endif
</div>
