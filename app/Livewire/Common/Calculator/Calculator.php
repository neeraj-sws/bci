<?php

namespace App\Livewire\Common\Calculator;

use App\Models\Tourists as Model;
use App\Models\Parks;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

#[Layout('components.layouts.common-app')]
class Calculator extends Component
{
    use WithPagination;

    public $pageTitle = 'Clients';

    public $model = Model::class;

    public $number_of_person = 0,

        //b 
        $tour_length = 0;

    // c 
    public $rooms_required = 0,
        $room_cost_per_night = 0;
    public $extra_person = 0, $cost_extra_person = 0;
    public $extra_child = 0, $cost_extra_child = 0;
    public $weekend_core_safari = 0, $weekend_cost = 0;

    public $weekday_core_safari = 0, $weekday_cost = 0;


    public $buffer_safari = 0, $buffer_safari_cost = 0;
    public $night_safari = 0, $night_safari_cost = 0;
    public $gypsy_guide = 0, $gypsy_guide_cost = 0;

    public $cab_pick_and_drop = 0, $cab_pick_and_drop_cost = 0;
    public $cab_retained = 0, $cab_retained_cost = 0;
    public $gate_to_gate = 0, $gate_to_gate_cost = 0;
    public $long_distance_gate = 0, $long_distance_gate_cost = 0;
    public $tax_percent = 0, $surcharge_percent = 0;

    public $RoomTotal = 0;
    public $ExtraPersonTotal = 0;
    public $ExtraChildTotal = 0;
    public $WkendSfTotal = 0;
    public $WkdaySfTotal = 0;
    public $WkendRegTotal = 0;
    public $WkdayRegTotal = 0;
    public $WkendAdvTotal = 0;
    public $WkdayAdvTotal = 0;
    public $WkendBufferTotal = 0;
    public $WkdayBufferTotal = 0;
    public $BufferSfTotal = 0;
    public $NightSfTotal = 0;
    public $GypsyGuideTotal = 0;
    public $CabPkDpTotal = 0;
    public $CabRetainedTotal = 0;
    public $Gate2GateTotal = 0;
    public $LongDistPkDpTotal = 0;
    public $NetTotal = 0;
    public $TotalWithTax = 0;
    public $GrandTotal = 0;
    public $CostPerPerson = 0;

    ##Parks
    public $parks, $park_id;

    ##Custome
    // Core Safari Regular
    public $weekend_core_reg = 0;
    public $cost_weekend_core_reg = 0;

    public $weekday_core_reg = 0;
    public $cost_weekday_core_reg = 0;

    // Core Safari Advance
    public $weekend_core_adv = 0;
    public $cost_weekend_core_adv = 0;

    public $weekday_core_adv = 0;
    public $cost_weekday_core_adv = 0;

    // Buffer Safari
    public $buffer_weekend = 0;
    public $cost_buffer_weekend = 0;

    public $buffer_weekday = 0;
    public $cost_buffer_weekday = 0;



    public $view = 'livewire.common.calculator.calculator';


    public function render()
    {
        $this->parks = Parks::where('status', 1)->pluck('name', 'park_id');
        return view($this->view);
    }
public function updatedRoomCostPerNight($val)
{
    $this->RoomTotal = (float)$this->tour_length * (float)$this->rooms_required * (float)$val;
    $this->getCalculate();
}

public function updatedCostExtraPerson($val)
{
    $this->ExtraPersonTotal = (float)$this->tour_length * (float)$this->extra_person * (float)$val;
    $this->getCalculate();
}

public function updatedCostExtraChild($val)
{
    $this->ExtraChildTotal = (float)$this->tour_length * (float)$this->extra_child * (float)$val;
    $this->getCalculate();
}

public function updatedWeekendCost($val)
{
    $this->WkendSfTotal = (float)$this->weekend_core_safari * (float)$val;
    $this->getCalculate();
}

public function updatedWeekdayCost($val)
{
    $this->WkdaySfTotal = (float)$this->weekday_core_safari * (float)$val;
    $this->getCalculate();
}

public function updatedCostWeekendCoreReg($val)
{
    $this->WkendRegTotal = (float)$this->weekend_core_reg * (float)$val;
    $this->getCalculate();
}

public function updatedCostWeekdayCoreReg($val)
{
    $this->WkdayRegTotal = (float)$this->weekday_core_reg * (float)$val;
    $this->getCalculate();
}

public function updatedCostWeekendCoreAdv($val)
{
    $this->WkendAdvTotal = (float)$this->weekend_core_adv * (float)$val;
    $this->getCalculate();
}

public function updatedCostWeekdayCoreAdv($val)
{
    $this->WkdayAdvTotal = (float)$this->weekday_core_adv * (float)$val;
    $this->getCalculate();
}

public function updatedCostBufferWeekend($val)
{
    $this->WkendBufferTotal = (float)$this->buffer_weekend * (float)$val;
    $this->getCalculate();
}

public function updatedCostBufferWeekday($val)
{
    $this->WkdayBufferTotal = (float)$this->buffer_weekday * (float)$val;
    $this->getCalculate();
}

public function updatedBufferSafariCost($val)
{
    $this->BufferSfTotal = (float)$this->buffer_safari * (float)$val;
    $this->getCalculate();
}

public function updatedNightSafariCost($val)
{
    $this->NightSfTotal = (float)$this->night_safari * (float)$val;
    $this->getCalculate();
}

public function updatedGypsyGuideCost($val)
{
    $this->GypsyGuideTotal = (float)$this->gypsy_guide * (float)$val;
    $this->getCalculate();
}

public function updatedCabPickAndDropCost($val)
{
    $this->CabPkDpTotal = (float)$this->cab_pick_and_drop * (float)$val;
    $this->getCalculate();
}

public function updatedCabRetainedCost($val)
{
    $this->CabRetainedTotal = (float)$this->cab_retained * (float)$val;
    $this->getCalculate();
}

public function updatedGateToGateCost($val)
{
    $this->Gate2GateTotal = (float)$this->gate_to_gate * (float)$val;
    $this->getCalculate();
}

public function updatedLongDistanceGateCost($val)
{
    $this->LongDistPkDpTotal = (float)$this->long_distance_gate * (float)$val;
    $this->getCalculate();
}


    public function updatedTaxPercent($val)
    {
        $this->getCalculate();
    }
    public function updatedSurchargePercent($val)
    {
        $this->getCalculate();
    }

    public function updatedNumberOfPerson($val)
    {
        $this->getCalculate();
    }
    public function updatedTourLength($val)
    {
        $this->getCalculate();
    }

public function getCalculate()
{
    $tour_length = (float) $this->tour_length;
    $rooms_required = (float) $this->rooms_required;
    $room_cost_per_night = (float) $this->room_cost_per_night;
    $extra_person = (float) $this->extra_person;
    $cost_extra_person = (float) $this->cost_extra_person;
    $extra_child = (float) $this->extra_child;
    $cost_extra_child = (float) $this->cost_extra_child;
    $cab_pick_and_drop = (float) $this->cab_pick_and_drop;
    $cab_pick_and_drop_cost = (float) $this->cab_pick_and_drop_cost;
    $cab_retained = (float) $this->cab_retained;
    $cab_retained_cost = (float) $this->cab_retained_cost;
    $gate_to_gate = (float) $this->gate_to_gate;
    $gate_to_gate_cost = (float) $this->gate_to_gate_cost;
    $long_distance_gate = (float) $this->long_distance_gate;
    $long_distance_gate_cost = (float) $this->long_distance_gate_cost;

    $weekend_core_safari = (float) $this->weekend_core_safari;
    $weekday_core_safari = (float) $this->weekday_core_safari;
    $weekend_core_reg = (float) $this->weekend_core_reg;
    $weekday_core_reg = (float) $this->weekday_core_reg;
    $weekend_core_adv = (float) $this->weekend_core_adv;
    $weekday_core_adv = (float) $this->weekday_core_adv;
    $buffer_weekend = (float) $this->buffer_weekend;
    $buffer_weekday = (float) $this->buffer_weekday;
    $buffer_safari = (float) $this->buffer_safari;
    $night_safari = (float) $this->night_safari;

    $gypsy_guide = (float) $this->gypsy_guide;
    $gypsy_guide_cost = (float) $this->gypsy_guide_cost;

    $tax_percent = (float) $this->tax_percent;
    $surcharge_percent = (float) $this->surcharge_percent;
    $number_of_person = (float) $this->number_of_person;

    $count = $tour_length * $rooms_required * $room_cost_per_night;
    $get = $tour_length * $extra_person * $cost_extra_person;
    $c_child = $tour_length * $extra_child * $cost_extra_child;
    $drop = $cab_pick_and_drop * $cab_pick_and_drop_cost;
    $cab_rt = $cab_retained * $cab_retained_cost;
    $gate_2 = $gate_to_gate * $gate_to_gate_cost;
    $allcost = $long_distance_gate * $long_distance_gate_cost;

    $sum1 = $weekend_core_safari * $this->weekend_cost;
    $sum2 = $weekday_core_safari * $this->weekday_cost;
    $sum3 = $weekend_core_reg * $this->cost_weekend_core_reg;
    $sum4 = $weekday_core_reg * $this->cost_weekday_core_reg;
    $sum5 = $weekend_core_adv * $this->cost_weekend_core_adv;
    $sum6 = $weekday_core_adv * $this->cost_weekday_core_adv;
    $sum7 = $buffer_weekend * $this->cost_buffer_weekend;
    $sum8 = $buffer_weekday * $this->cost_buffer_weekday;
    $sum9 = $buffer_safari * $this->buffer_safari_cost;
    $sum10 = $night_safari * $this->night_safari_cost;

    $guide_c = $gypsy_guide * $gypsy_guide_cost;

    // Net Total
    $this->NetTotal = $count + $get + $c_child + $drop + $cab_rt + $gate_2 + $allcost +
        $sum1 + $sum2 + $sum3 + $sum4 + $sum5 + $sum6 + $sum7 + $sum8 + $sum9 + $sum10 +
        $guide_c;

    // Tax
    $net_tax = $this->NetTotal * ($tax_percent / 100);
    $this->TotalWithTax = $this->NetTotal + $net_tax;

    // Surcharge
    $grand_total_tax = $this->TotalWithTax * ($surcharge_percent / 100);
    $this->GrandTotal = $this->TotalWithTax + $grand_total_tax;

    // Per Person
    $this->CostPerPerson = $number_of_person > 0 ? $this->GrandTotal / $number_of_person : 0;
}


    public function resetCalculator()
    {
        $this->number_of_person = 0;
        $this->tour_length = 0;

        $this->rooms_required = 0;
        $this->room_cost_per_night = 0;
        $this->extra_person = 0;
        $this->cost_extra_person = 0;
        $this->extra_child = 0;
        $this->cost_extra_child = 0;
        $this->weekend_core_safari = 0;
        $this->weekend_cost = 0;

        $this->weekday_core_safari = 0;
        $this->weekday_cost = 0;

        $this->buffer_safari = 0;
        $this->buffer_safari_cost = 0;
        $this->night_safari = 0;
        $this->night_safari_cost = 0;
        $this->gypsy_guide = 0;
        $this->gypsy_guide_cost = 0;

        $this->cab_pick_and_drop = 0;
        $this->cab_pick_and_drop_cost = 0;
        $this->cab_retained = 0;
        $this->cab_retained_cost = 0;
        $this->gate_to_gate = 0;
        $this->gate_to_gate_cost = 0;
        $this->long_distance_gate = 0;
        $this->long_distance_gate_cost = 0;
        $this->tax_percent = 0;
        $this->surcharge_percent = 0;

        $this->RoomTotal = 0;
        $this->ExtraPersonTotal = 0;
        $this->ExtraChildTotal = 0;
        $this->WkendSfTotal = 0;
        $this->WkdaySfTotal = 0;
        $this->WkendRegTotal = 0;
        $this->WkdayRegTotal = 0;
        $this->WkendAdvTotal = 0;
        $this->WkdayAdvTotal = 0;
        $this->WkendBufferTotal = 0;
        $this->WkdayBufferTotal = 0;
        $this->BufferSfTotal = 0;
        $this->NightSfTotal = 0;
        $this->GypsyGuideTotal = 0;
        $this->CabPkDpTotal = 0;
        $this->CabRetainedTotal = 0;
        $this->Gate2GateTotal = 0;
        $this->LongDistPkDpTotal = 0;
        $this->NetTotal = 0;
        $this->TotalWithTax = 0;
        $this->GrandTotal = 0;
        $this->CostPerPerson = 0;

        $this->weekend_core_reg = 0;
        $this->cost_weekend_core_reg = 0;

        $this->weekday_core_reg = 0;
        $this->cost_weekday_core_reg = 0;

        $this->weekend_core_adv = 0;
        $this->cost_weekend_core_adv = 0;

        $this->weekday_core_adv = 0;
        $this->cost_weekday_core_adv = 0;

        $this->buffer_weekend = 0;
        $this->cost_buffer_weekend = 0;

        $this->buffer_weekday = 0;
        $this->cost_buffer_weekday = 0;

        $this->park_id = null;
    }
}
