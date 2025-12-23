<?php

namespace App\Livewire\Common\Preferences;

use App\Models\CommonColumnSettings as ModelsGeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.common-app')]
class ColumnSettings extends Component
{
    public $GeneralSetting;

    public $items, $items_other;
    public $units, $units_other;
    public $amount, $amount_other;

    public $date = false, $time = false, $custom = '';
    public $hide_quantity = false, $hide_rate = false, $hide_amount = false;

    public function mount()
    {
        $this->GeneralSetting = ModelsGeneralSettings::first();

        if ($this->GeneralSetting) {
            $data = $this->GeneralSetting->toArray();

            // Handle items
            if (in_array($data['items'], ['items', 'products', 'services'])) {
                $this->items = $data['items'];
                $this->items_other = '';
            } else {
                $this->items = 'other';
                $this->items_other = $data['items'];
            }

            // Handle units
            if (in_array($data['units'], ['qty', 'hours'])) {
                $this->units = $data['units'];
                $this->units_other = '';
            } else {
                $this->units = 'other';
                $this->units_other = $data['units'];
            }

            // Handle amount
            if ($data['amount'] === 'amount') {
                $this->amount = 'amount';
                $this->amount_other = '';
            } else {
                $this->amount = 'other';
                $this->amount_other = $data['amount'];
            }

            $this->date = (bool) $data['date'];
            $this->time = (bool) $data['time'];
            $this->custom = $data['custom'];
            $this->hide_quantity = (bool) $data['hide_quantity'];
            $this->hide_rate = (bool) $data['hide_rate'];
            $this->hide_amount = (bool) $data['hide_amount'];
        }
    }

    public function rules()
    {
        return [
            'items' => 'required',
            'units' => 'required',
            'amount' => 'required',
        ];
    }

    public function save()
    {
        $this->validate();

        $itemsValue = $this->items === 'other' ? $this->items_other : $this->items;
        $unitsValue = $this->units === 'other' ? $this->units_other : $this->units;
        $amountValue = $this->amount === 'other' ? $this->amount_other : $this->amount;

        $data = [
            'items' => $itemsValue,
            'units' => $unitsValue,
            'amount' => $amountValue,
            'date' => $this->date,
            'time' => $this->time,
            'custom' => $this->custom,
            'hide_quantity' => $this->hide_quantity,
            'hide_rate' => $this->hide_rate,
            'hide_amount' => $this->hide_amount,
        ];

        if ($this->GeneralSetting) {
            $this->GeneralSetting->update($data);
        } else {
            $this->GeneralSetting = ModelsGeneralSettings::create($data);
        }

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Saved',
            'message' => 'Invoice/Quotation Column Settings updated successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.common.preferences.columnsettings');
    }
}
