<div class="mx-5 calculatorpage mt-sm-0 mt-3 mb-lg-0 mb-4">
      <div class="row">
        <div class="col-lg-7">
            <button wire:click="resetCalculator" class="btn bluegradientbtn mb-2">RESET COST SUMMARY</button>

            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="save">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Parks</label>
                                <select id='park_id' class="form-select select2" wire:model.live="park_id">
                                    <option value="">Select Country</option>
                                    @foreach ($parks as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Person <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.live="number_of_person"
                                    placeholder="Number of Person">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tour Length <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.live="tour_length"
                                    placeholder="Tour Length">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rooms Required <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model.live="rooms_required"
                                    placeholder="Rooms required">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Room Cost Per Night</label>
                                <input type="number" class="form-control" wire:model.live="room_cost_per_night"
                                    placeholder="Room cost per night" @disabled(!is_numeric($this->rooms_required) || $this->rooms_required <= 0)>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Extra Person <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model.live="extra_person"
                                    placeholder="Extra Person">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cost Extra Person</label>
                                <input type="number" class="form-control" wire:model.live="cost_extra_person"
                                    placeholder="Cost Extra Person" @disabled(!is_numeric($this->extra_person) || $this->extra_person <= 0)>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Extra Child <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model.live="extra_child"
                                    placeholder="Extra Child">
                                <span>(6 - 12 Years)</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cost Extra Child</label>
                                <input type="number" class="form-control" wire:model.live="cost_extra_child"
                                    placeholder="Cost Extra Child" @disabled(!is_numeric($this->extra_child) || $this->extra_child <= 0)>
                            </div>
                        </div>

                        <hr>

                        <div class="@if ($park_id == 2) d-none @endif">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekend Core Safari</label>
                                    <input type="number" class="form-control" wire:model.live="weekend_core_safari"
                                        placeholder="Weekend Core Safari">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekend Cost</label>
                                    <input type="number" class="form-control" wire:model.live="weekend_cost"
                                        placeholder="Weekend Cost" @disabled(!is_numeric($this->weekend_core_safari) || $this->weekend_core_safari <= 0)>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekday Core Safari</label>
                                    <input type="number" class="form-control" wire:model.live="weekday_core_safari"
                                        placeholder="Weekday Core Safari">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekday Cost</label>
                                    <input type="number" class="form-control" wire:model.live="weekday_cost"
                                        placeholder="Weekday Cost" @disabled(!is_numeric($this->weekday_core_safari) || $this->weekday_core_safari <= 0)>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Buffer Safari</label>
                                    <input type="number" class="form-control" wire:model.live="buffer_safari"
                                        placeholder="Buffer Safari">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bfr Sf Cost</label>
                                    <input type="number" class="form-control" wire:model.live="buffer_safari_cost"
                                        placeholder="Weekday Cost" @disabled(!is_numeric($this->buffer_safari) || $this->buffer_safari <= 0)>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Night Safari</label>
                                    <input type="number" class="form-control" wire:model.live="night_safari"
                                        placeholder="Night Safari">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ng Sf Cost</label>
                                    <input type="number" class="form-control" wire:model.live="night_safari_cost"
                                        placeholder="Ng Sf Cost" @disabled(!is_numeric($this->night_safari) || $this->night_safari <= 0)>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gypsy and Guide</label>
                                    <input type="number" class="form-control" wire:model.live="gypsy_guide"
                                        placeholder="Gypsy and Guide">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gypsy, Guide Cost</label>
                                    <input type="number" class="form-control" wire:model.live="gypsy_guide_cost"
                                        placeholder="Gypsy, Guide Cost" @disabled(!is_numeric($this->gypsy_guide) || $this->gypsy_guide <= 0)>
                                </div>
                            </div>
                        </div>

                        {{-- On Selected Park  --}}
                        <div class="@if ($park_id == 2) d-block @else d-none @endif">
                            <div class="row">
                                <!-- Weekend Core Reg -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekend Core Reg</label>
                                    <input type="number" class="form-control" wire:model.live="weekend_core_reg"
                                        placeholder="Weekend Core Reg">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Weekend Core Reg</label>
                                    <input type="number" class="form-control"
                                        wire:model.live="cost_weekend_core_reg" placeholder="Wkend Cost Reg"
                                        @disabled(!is_numeric($this->weekend_core_reg) || $this->weekend_core_reg <= 0)>
                                </div>

                                <!-- Weekday Core Reg -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekday Core Reg</label>
                                    <input type="number" class="form-control" wire:model.live="weekday_core_reg"
                                        placeholder="Weekday Core Reg">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Weekday Core Reg</label>
                                    <input type="number" class="form-control"
                                        wire:model.live="cost_weekday_core_reg" placeholder="Weekday Cost Reg"
                                        @disabled(!is_numeric($this->weekday_core_reg) || $this->weekday_core_reg <= 0)>
                                </div>

                                <!-- Weekend Core Adv -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekend Core Adv</label>
                                    <input type="number" class="form-control" wire:model.live="weekend_core_adv"
                                        placeholder="Weekend Core Adv">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Weekend Core Adv</label>
                                    <input type="number" class="form-control"
                                        wire:model.live="cost_weekend_core_adv" placeholder="Wkend Cost Adv"
                                        @disabled(!is_numeric($this->weekend_core_adv) || $this->weekend_core_adv <= 0)>
                                </div>

                                <!-- Weekday Core Adv -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weekday Core Adv</label>
                                    <input type="number" class="form-control" wire:model.live="weekday_core_adv"
                                        placeholder="Weekday Core Adv">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Weekday Core Adv</label>
                                    <input type="number" class="form-control"
                                        wire:model.live="cost_weekday_core_adv" placeholder="Wkday Cost Adv"
                                        @disabled(!is_numeric($this->weekday_core_adv) || $this->weekday_core_adv <= 0)>
                                </div>

                                <!-- Buffer Weekend -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Buffer WeekEnd</label>
                                    <input type="number" class="form-control" wire:model.live="buffer_weekend"
                                        placeholder="Weekend Core Buffer">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Buffer WeekEnd</label>
                                    <input type="number" class="form-control" wire:model.live="cost_buffer_weekend"
                                        placeholder="Weekend Cost Buffer" @disabled(!is_numeric($this->buffer_weekend) || $this->buffer_weekend <= 0)>
                                </div>

                                <!-- Buffer Weekday -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Buffer WeekDay</label>
                                    <input type="number" class="form-control" wire:model.live="buffer_weekday"
                                        placeholder="Weekday Core Buffer">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Buffer WeekDay</label>
                                    <input type="number" class="form-control" wire:model.live="cost_buffer_weekday"
                                        placeholder="Weekday Cost Buffer" @disabled(!is_numeric($this->buffer_weekday) || $this->buffer_weekday <= 0)>
                                </div>
                            </div>
                        </div>


                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cab Pick and Drop</label>
                                <input type="number" class="form-control" wire:model.live="cab_pick_and_drop"
                                    placeholder="Cab Pick and Drop">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">GCab Pk Dp Cost</label>
                                <input type="number" class="form-control" wire:model.live="cab_pick_and_drop_cost"
                                    placeholder="Cab Pk Dp Cost" @disabled(!is_numeric($this->cab_pick_and_drop) || $this->cab_pick_and_drop <= 0)>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cab Retained</label>
                                <input type="number" class="form-control" wire:model.live="cab_retained"
                                    placeholder="Cab Retained">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cab Rt Cost</label>
                                <input type="number" class="form-control" wire:model.live="cab_retained_cost"
                                    placeholder="Cab Rt Cost" @disabled(!is_numeric($this->cab_retained) || $this->cab_retained <= 0)>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gate to Gate</label>
                                <input type="number" class="form-control" wire:model.live="gate_to_gate"
                                    placeholder="Gate to Gate">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gate2Gate Cost</label>
                                <input type="number" class="form-control" wire:model.live="gate_to_gate_cost"
                                    placeholder="Gate2Gate Cost" @disabled(!is_numeric($this->gate_to_gate) || $this->gate_to_gate <= 0)>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Long Dist. Gate</label>
                                <input type="number" class="form-control" wire:model.live="long_distance_gate"
                                    placeholder="Long Dist. Gate">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cost</label>
                                <input type="number" class="form-control" wire:model.live="long_distance_gate_cost"
                                    placeholder="Cost" @disabled(!is_numeric($this->long_distance_gate) || $this->long_distance_gate <= 0)>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax (%)</label>
                                <input type="number" class="form-control" wire:model.live="tax_percent"
                                    placeholder="Tax (%)">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Surcharge (%)</label>
                                <input type="number" class="form-control" wire:model.live="surcharge_percent"
                                    placeholder="Surcharge (%)">
                            </div>

                        </div>


                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mx-auto">
            <div class="receipt-container">
                <!-- Watermark Logo -->
                <div class="watermark">
                    @php
                        $organization = 'logo.png';
                    @endphp
                    <img src="{{ asset('assets/images/' . $organization) }}" alt="company_logo">
                </div>

                <!-- Toggle Button -->
                <button class="toggle-btn" id="toggleItems">
                    <i class="fas fa-eye-slash"></i>
                </button>
                <!-- Receipt Header -->
                <div class="receipt-header">
                    <div class="receipt-title">COST SUMMARY</div>
                </div>

                <!-- Receipt Body -->
                <div class="receipt-body">
                    <!-- Transaction Info -->
                    <div class="transaction-info">
                        <div><strong>DATE:</strong> <span id="currentDate">{{ date('d/m/Y') }}</span></div>
                    </div>

                    <!-- Items Header -->
                    <div class="items-header">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>

                    <div id="itemsSection">

                        @if ($number_of_person)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Number of Person</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $number_of_person }}</div>
                            </div>
                        @endif

                        @if ($tour_length)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Tour Length</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $tour_length }}</div>
                            </div>
                        @endif

                        @if ($rooms_required)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Rooms Required</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $rooms_required }}</div>
                            </div>
                        @endif

                        @if ($room_cost_per_night)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Room Cost Per Night</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $room_cost_per_night }}</div>
                            </div>
                        @endif

                        @if ($extra_person)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Extra Person</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $extra_person }}</div>
                            </div>
                        @endif

                        @if ($cost_extra_person)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost of Extra Person</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_extra_person }}</div>
                            </div>
                        @endif

                        @if ($extra_child)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Extra Child</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $extra_child }}</div>
                            </div>
                        @endif

                        @if ($cost_extra_child)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost of Extra Child</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_extra_child }}</div>
                            </div>
                        @endif

                        <!-- Core Safari Weekend -->
                        @if ($weekend_core_safari)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekend Core Safari</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekend_core_safari }}</div>
                            </div>
                        @endif

                        @if ($weekend_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekend Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekend_cost }}</div>
                            </div>
                        @endif

                        <!-- Core Safari Weekday -->
                        @if ($weekday_core_safari)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekday Core Safari</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekday_core_safari }}</div>
                            </div>
                        @endif

                        @if ($weekday_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekday Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekday_cost }}</div>
                            </div>
                        @endif

                        <!-- Buffer Safari -->
                        @if ($buffer_safari)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Buffer Safari</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $buffer_safari }}</div>
                            </div>
                        @endif

                        @if ($buffer_safari_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Buffer Safari Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $buffer_safari_cost }}</div>
                            </div>
                        @endif

                        <!-- Night Safari -->
                        @if ($night_safari)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Night Safari</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $night_safari }}</div>
                            </div>
                        @endif

                        @if ($night_safari_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Night Safari Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $night_safari_cost }}</div>
                            </div>
                        @endif

                        <!-- Gypsy Guide -->
                        @if ($gypsy_guide)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Gypsy Guide</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $gypsy_guide }}</div>
                            </div>
                        @endif

                        @if ($gypsy_guide_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Gypsy Guide Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $gypsy_guide_cost }}</div>
                            </div>
                        @endif

                        <!-- Cab Services -->
                        @if ($cab_pick_and_drop)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cab Pick and Drop</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cab_pick_and_drop }}</div>
                            </div>
                        @endif

                        @if ($cab_pick_and_drop_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cab Pick and Drop Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cab_pick_and_drop_cost }}</div>
                            </div>
                        @endif

                        @if ($cab_retained)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cab Retained</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cab_retained }}</div>
                            </div>
                        @endif

                        @if ($cab_retained_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cab Retained Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cab_retained_cost }}</div>
                            </div>
                        @endif

                        @if ($gate_to_gate)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Gate to Gate</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $gate_to_gate }}</div>
                            </div>
                        @endif

                        @if ($gate_to_gate_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Gate to Gate Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $gate_to_gate_cost }}</div>
                            </div>
                        @endif

                        @if ($long_distance_gate)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Long Distance Gate</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $long_distance_gate }}</div>
                            </div>
                        @endif

                        @if ($long_distance_gate_cost)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Long Distance Gate Cost</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $long_distance_gate_cost }}</div>
                            </div>
                        @endif

                        @if ($tax_percent)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Tax Percent</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $tax_percent }}</div>
                            </div>
                        @endif

                        @if ($surcharge_percent)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Surcharge Percent</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $surcharge_percent }}</div>
                            </div>
                        @endif

                        <!-- Core Safari Regular Weekend -->
                        @if ($weekend_core_reg)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekend Core Regular</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekend_core_reg }}</div>
                            </div>
                        @endif

                        @if ($cost_weekend_core_reg)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost Weekend Core Regular</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_weekend_core_reg }}</div>
                            </div>
                        @endif

                        <!-- Core Safari Regular Weekday -->
                        @if ($weekday_core_reg)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekday Core Regular</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekday_core_reg }}</div>
                            </div>
                        @endif

                        @if ($cost_weekday_core_reg)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost Weekday Core Regular</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_weekday_core_reg }}</div>
                            </div>
                        @endif

                        <!-- Core Safari Advance Weekend -->
                        @if ($weekend_core_adv)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekend Core Advance</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekend_core_adv }}</div>
                            </div>
                        @endif

                        @if ($cost_weekend_core_adv)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost Weekend Core Advance</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_weekend_core_adv }}</div>
                            </div>
                        @endif

                        <!-- Core Safari Advance Weekday -->
                        @if ($weekday_core_adv)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Weekday Core Advance</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $weekday_core_adv }}</div>
                            </div>
                        @endif

                        @if ($cost_weekday_core_adv)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost Weekday Core Advance</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_weekday_core_adv }}</div>
                            </div>
                        @endif

                        <!-- Buffer Safari Weekend -->
                        @if ($buffer_weekend)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Buffer Weekend</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $buffer_weekend }}</div>
                            </div>
                        @endif

                        @if ($cost_buffer_weekend)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost Buffer Weekend</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_buffer_weekend }}</div>
                            </div>
                        @endif

                        <!-- Buffer Safari Weekday -->
                        @if ($buffer_weekday)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Buffer Weekday</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $buffer_weekday }}</div>
                            </div>
                        @endif

                        @if ($cost_buffer_weekday)
                            <div class="receipt-item receiptitem-dashed">
                                <div>Cost Buffer Weekday</div>
                                <div  class="position-relative dashed-border"></div>
                                <div class="number-item text-end">{{ $cost_buffer_weekday }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Totals Section -->
                    <div class="totals-section">
                        <div class="total-line total-row">
                        </div>

                        <div id="itemsSection">
                            <div class="receipt-item receiptitem-dashed">
                                <div>Room Total:</div>
                                <div class="number-item text-end">{{ number_format($RoomTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Extra Person Total:</div>
                                <div class="number-item text-end">{{ number_format($ExtraPersonTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Extra Child Total:</div>
                                <div class="number-item text-end">{{ number_format($ExtraChildTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekend SF Total:</div>
                                <div class="number-item text-end">{{ number_format($WkendSfTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekday SF Total:</div>
                                <div class="number-item text-end">{{ number_format($WkdaySfTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekend Reg Total:</div>
                                <div class="number-item text-end">{{ number_format($WkendRegTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekday Reg Total:</div>
                                <div class="number-item text-end">{{ number_format($WkdayRegTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekend Adv Total:</div>
                                <div class="number-item text-end">{{ number_format($WkendAdvTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekday Adv Total:</div>
                                <div class="number-item text-end">{{ number_format($WkdayAdvTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekend Buffer Total:</div>
                                <div class="number-item text-end">{{ number_format($WkendBufferTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Weekday Buffer Total:</div>
                                <div class="number-item text-end">{{ number_format($WkdayBufferTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Buffer SF Total:</div>
                                <div class="number-item text-end">{{ number_format($BufferSfTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Night SF Total:</div>
                                <div class="number-item text-end">{{ number_format($NightSfTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Gypsy Guide Total:</div>
                                <div class="number-item text-end">{{ number_format($GypsyGuideTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Cab Pick/Drop Total:</div>
                                <div class="number-item text-end">{{ number_format($CabPkDpTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Cab Retained Total:</div>
                                <div class="number-item text-end">{{ number_format($CabRetainedTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Gate to Gate Total:</div>
                                <div class="number-item text-end">{{ number_format($Gate2GateTotal, 2) }}</div>
                            </div>
                            <div class="receipt-item">
                                <div>Long Distance Pick/Drop Total:</div>
                                <div class="number-item text-end">{{ number_format($LongDistPkDpTotal, 2) }}</div>
                            </div>
                        </div>
                        <div class="totals-section">
                            <div class="total-line">
                                <div>Net Total:</div>
                                <div class="number-item text-end">{{ number_format($NetTotal, 2) }}</div>
                            </div>
                            <div class="total-line">
                                <div>Total With Tax:</div>
                                <div class="number-item text-end">{{ number_format($TotalWithTax, 2) }}</div>
                            </div>
                            <div class="total-line total-row">
                                <div>Grand Total:</div>
                                <div class="number-item text-end">{{ number_format($GrandTotal, 2) }}</div>
                            </div>
                            <div class="total-line">
                                <div>Cost Per Person:</div>
                                <div class="number-item text-end">{{ number_format($CostPerPerson, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipt Footer -->
                <div class="receipt-footer">
                    <div class="thank-you">THANK YOU</div>
                    <small>www.bigcatindia.com</small>
                </div>
            </div>

        </div>


    </div>


</div>
<script>
    const toggleBtn = document.getElementById('toggleItems');
    const itemsSection = document.getElementById('itemsSection');
    toggleBtn.addEventListener('click', function() {
        if (itemsSection.style.display === 'none') {
            itemsSection.style.display = 'block';
            toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            itemsSection.style.display = 'none';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
</script>
</div>
