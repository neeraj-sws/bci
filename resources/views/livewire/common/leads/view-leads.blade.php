<div class="container py-4">
<div class="card shadow-none border">
   
							<div class="card-body pb-2">
							   <a href="javascript:history.back()"><i class="lni lni-chevron-left"></i></a> 
								<div class="d-flex  justify-content-between flex-wrap">
									<div class="d-flex align-items-center mb-2">
										<div class="avatar avatar-xxl avatar-rounded border border-warning bg-soft-warning me-3 flex-shrink-0">
											<h6 class="mb-0 text-warning">{{ substr(trim($leadData->tourist->primary_contact ?? $leadData->tourist->name), 0, 1) }}</h6>
										
										</div>
											
										<div>
											<h5 class="mb-1 fw-bold"><span style="color:{{ $leadData->type->color }}"><i class="bx bxs-circle me-1"></i></span>{{ $leadData->tourist->primary_contact ?? $leadData->tourist->name }} 
											@can('leads manage')
            @if ($leadData->stage_id == 1)
                @if (!$leadData->invoice)
                 <a href="{{ route($route.'.lead-edit', $leadData->uuid) }}" class="editbtn"><i class="fadeIn animated bx bx-edit"></i></a>
                @endif
            @endif
     @endcan</h5>
											<p class="mb-1 d-flex align-items-center"><i class="lni lni-phone pe-1"></i>{{ $leadData->contact}}</p>
											<p class="mb-0 d-flex align-items-center"><i class="fadeIn animated bx bx-comment-detail pe-1"></i>{{ $leadData->email }}</p>
											 @if($leadData?->tourist?->address)
											<p class="mb-0 d-flex">
                                              <i class="fadeIn animated bx bx-map pe-1"></i>
                                            {{
                                                collect([
                                                    ucwords($leadData?->tourist?->stateRelation?->name),
                                                    ucwords($leadData?->tourist?->city?->name),
                                                    $leadData?->tourist?->country?->name,
                                                ])->filter()->implode(', ')
                                            }}
                                        </p>
                                        	<p class="mb-0 d-flex">
                                            <i class="fadeIn animated bx bx-map-alt pe-1"></i>
                                            @if($leadData?->tourist?->address)
                                                {{ $leadData->tourist->address }}
                                            @endif
                                        </p>

												@endif
								
										</div>
									</div>
									<div class="newboxside">
									 @if (optional($leadData->status)->name)
										<span class="py-1 px-2 fs-12  rounded text-white fw-medium" style="color:{{ optional($leadData->stage)->btn_text }};background:{{ optional($leadData->stage)->btn_bg }}">{{ $leadData->stage->name }} </span>
											 @endif
											 @can('leads manage')
            @if ($leadData->stage_id == 3)

                @if (!$leadData->invoice)
                    <div class="my-3">
                            <div class="dropdown-menu-end text-end">
                                @if (!$leadData->quotation)
                                    <a href="{{ route($route.'.add-quotation',  ['lead_id' => ($leadData->uuid ?? $leadId)] ) }}" class="btn btn-sm btn-success"
                                        href="javascript:void(0);">Genreate Quotation</a>
                                @endif
                            </div>
                            

                       
                    </div>
                @endif
            @endif
            
                                   
                       <div class="">
                @if (!$leadData->user_id)
    <div class="col-md-6 mb-3">
      <label class="form-label">User <span class="text-danger">*</span></label>
      <select id='user_id' class="form-select form-select-sm select2" wire:model="user_id">
        <option value="">Select User</option>
                            @foreach ($users as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
      @error('user_id')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <a wire:click='userAssign()' class="btn btn-primary">Assign</a>
    @endif


@if(!in_array($leadData->stage_id, [2,6,7]))
    <div class="row mt-2">

      <div class="col-md-12 mb-3">
        <label class="form-label">Stage</label>
        <select id='stage_id' class="form-select form-select-sm select2" wire:model="stage_id">
            <option>Select Stage</option>
                            @foreach ($stageselects as $id => $name)
          <option value="{{ $id }}">
          {{ $name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6 mb-3 d-none">
      <label class="form-label">Status <span class="text-danger">*</span></label>
      <select id='status_id' class="form-select select2" wire:model="status_id">
                            @foreach ($status as $id => $name)
        <option value="{{ $id }}" @if ($leadData->status->id == $id) selected @endif>
        {{ $name }}</option>
      @endforeach
    </select>
  </div>
                </div>
                @endif

            </div>
     @endcan
									
									</div>
								</div>
							</div>
						</div>
						<div class="row">
						    <div class="col-md-12">
						         <div class="step-progress justify-content-center mb-4 d-flex flex-nowrap gap-2">
						                        @foreach ($stages as $index => $name)
            								<div class="step bg-indigo nav-pills " @if (($leadData->stage_id ?? null) == $index) style="color:{{ optional($leadData->stage)->btn_text }};background:{{ optional($leadData->stage)->btn_bg }}" @endif>{{ $name }} </div>
            								@endforeach
            								<div class="step bg-transparent"></div>
						                  </div>
						    </div>
						    <div class="col-md-4">
						        <div class="card shadow-none">
						            <div class="card-body p-3">
						              <h6 class="mb-3 fw-bold">Lead Information</h6>
						              <div class="border-bottom mb-3 pb-2">
						              <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Date Created</p>
										<p class="mb-0 text-dark fw-normal"> 
									{{ $leadData?->created_at
    ? \Carbon\Carbon::parse($leadData->created_at)->format('d M Y h:i A')
    : 'N/A' }}
									</p>
									</div>
									@if($leadData->user?->name)
									 <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Assigned to</p>
										<p class="mb-0 text-dark fw-normal"> {{ $leadData->user?->name ?? 'N/A' }}</p>
									</div>
									@endif
									@if($leadData->source?->name)
									 <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Source</p>
										<p class="mb-0 text-dark fw-normal"> {{ $leadData?->source?->name ?? 'N/A' }}</p>
									</div>
									@endif
									
									
									    @if ($leadData?->quotation?->quotation_no)
                                            <div class="d-flex align-items-center justify-content-between mb-2">
        										<p class="mb-0 text-secondary">Quotation#</p>
        										<p class="mb-0 text-dark fw-normal"> <a href="{{ route($route.'.view-quotation', $leadData?->quotation->uuid) }}" class="fw-500 text-primary">
                                                    #{{ $leadData?->quotation->quotation_no ?? 'NA' }}
                                                </a></p>
        									</div>
                                        @endif
                                        
                                        @if ($leadData?->invoice?->invoice_no)
                                            <div class="d-flex align-items-center justify-content-between mb-2">
        										<p class="mb-0 text-secondary">Invoice#</p>
        										<p class="mb-0 text-dark fw-normal"> <a href="{{ route($route.'.view-invoice', $leadData?->invoice->uuid) }}" class="fw-500 text-primary">
                                                    #{{ $leadData?->invoice->invoice_no ?? 'NA' }}
                                                </a></p>
        									</div>
                                        @endif
									
						            </div>
						            <h6 class="mb-3 fw-bold">Travel Information</h6>
						            <div class="border-bottom mb-3 pb-2">
						                 @if($leadData?->travel_date)
						              <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Travel Date</p>
										<p class="mb-0 text-dark fw-normal"> {{ $leadData?->travel_date 
                                        ? \Carbon\Carbon::parse($leadData?->travel_date)->format('d M Y') 
                                        : 'N/A' }}</p>
									</div>
									@endif
									 @if($leadData?->travel_days)
						              <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Travel Days</p>
										<p class="mb-0 text-dark fw-normal"> {{ $leadData?->travel_days ?? 'N/A' }}</p>
									</div>
									@endif
									 @if($leadData?->budget)
						              <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Budget</p>
										<p class="mb-0 text-dark fw-normal"> {{ $leadData?->budget ?? 'N/A' }}</p>
									</div>
									@endif
									 @if($leadData?->follow_up_date)
						              <div class="d-flex align-items-center justify-content-between mb-2">
										<p class="mb-0 text-secondary">Follow Up</p>
										<p class="mb-0 text-dark fw-normal"> {{ $leadData?->follow_up_date && $leadData?->follow_up_time
        ? \Carbon\Carbon::parse($leadData?->follow_up_date.' '.$leadData?->follow_up_time)->format('d M Y h:i A')
        : 'N/A' }}</p>
									</div>
									@endif
						            </div>
						             <h6 class="mb-3 fw-bold">Remark</h6>
						            <div class="">
						                <p class="mb-0">{{ $leadData?->notes }}</p>
						            </div>
						            </div>
						        </div>
						    </div>
						    <div class="col-md-8">
						        <div class="card mb-3">
						            <div class="card-body">
            <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="leadfollowup-tab" data-bs-toggle="tab" data-bs-target="#leadfollowup"
                        href="javascript:void(0);" role="tab">Follow-ups</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="leadnotes-tab" data-bs-toggle="tab" data-bs-target="#leadnotes" href="javascript:void(0);"
                        role="tab">Notes</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="leaduploads-tab" data-bs-toggle="tab" data-bs-target="#leaduploads"
                        href="javascript:void(0);" role="tab">Uploads</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="leadhistory-tab" data-bs-toggle="tab" data-bs-target="#leadhistory"
                        href="javascript:void(0);" role="tab">History</a>
                </li>
            </ul>
            </div>
            </div>
            

    <div class="tab-content rounded">
        <div class="tab-pane fade show active" id="leadfollowup" role="tabpanel">
             @can('leads manage')
            @if(!in_array($leadData->stage_id, [2,6,7]))
                    <div class="mb-3 text-end">
         <a wire:click.prevent='addFollowup()' class="btn btn-sm btn-primary"
                                    href="javascript:void(0);"><i class="fadeIn animated bx bx-plus p-0"></i>Add
                                    Follow
                                    Up</a>
                                    </div>
            @endif
     @endcan
            <livewire:common.leads.comp.leads-followup :id="$leadId" :key="'leadfollowup'" />
        </div>
        <div class="tab-pane fade" id="leadnotes" role="tabpanel">
            <livewire:common.leads.comp.leads-note :id="$leadId" :key="'leadnotes'" :coloum="$coloum" :guard="$guard" />
        </div>
        <div class="tab-pane fade" id="leaduploads" role="tabpanel">
            <livewire:common.leads.comp.leads-uploads :id="$leadId" :key="'leaduploads'" :coloum="$coloum" :guard="$guard" />
        </div>
        <div class="tab-pane fade" id="leadhistory" role="tabpanel">
            <livewire:common.leads.comp.leads-history :id="$leadId" :key="'leadhistory'" />
        </div>
    </div>
						            </div>
						       
						</div>
    <div class="profile-header d-flex p-4 mb-4 card d-none">



        @if ($leadData?->type?->name)
            <div class="ribbon-wrapper2 ">
                <div class="ribbon2" style="background: {{ $leadData->type->color ?? '#fe7216' }}">
                    {{ $leadData->type->name }}</div>
            </div>
        @endif

        <div class="justify-content-end">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-6 fw-bold mb-3">{{ $leadData->client->company_name ?? 'N/A' }}</h1>

                    @if (optional($leadData->stage)->name)
                        <a href="#" class="btn"
                            style="color:{{ optional($leadData->stage)->btn_text }};background:{{ optional($leadData->stage)->btn_bg }}">
                            {{ $leadData->stage->name }}
                        </a>
                    @endif

                    @if (optional($leadData->status)->name)
                        <a href="#" class="btn"
                            style="color:{{ optional($leadData->status)->btn_text }};background:{{ optional($leadData->status)->btn_bg }}">
                            {{ $leadData->status->name }}
                        </a>
                    @endif

                    <span>{{ optional($leadData->created_at)->format('F d, Y \a\t h:ia') ?? 'Date not available' }}</span>
                </div>
            </div>

@can('leads manage')
            @if(!in_array($leadData->stage_id, [2,6,7]))
                @if (!$leadData->invoice)
                    <div class="my-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary">Action</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route($route.'.lead-edit', $leadId) }}" class="dropdown-item">Edit</a>
                                <a wire:click.prevent='addFollowup()' class="dropdown-item"
                                    href="javascript:void(0);">Add
                                    Follow
                                    Up</a>
                                @if (!$leadData->quotation)
                                    <a href="{{ route($route.'.add-quotation',  ['lead_id' => ($leadData->uuid ?? $leadId)] ) }}" class="dropdown-item"
                                        href="javascript:void(0);">Convert to Quotation</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif
     @endcan


            <div class="flex gap-3">
                @if ($leadData?->quotation?->quotation_no)
                    <a href="{{ route($route.'.view-quotation', $leadData?->quotation->id) }}" class="fw-500 text-primary">
                        #{{ $leadData?->quotation->quotation_no ?? 'NA' }}
                    </a>
                @endif

                @if ($leadData?->invoice?->invoice_no)
                    <a href="{{ route($route.'.view-invoice', $leadData?->invoice->id) }}" class="fw-500 text-primary">
                        #{{ $leadData?->invoice->invoice_no ?? 'NA' }}
                    </a>
                @endif
            </div>

        </div>
@if ($leadData?-> user?->name)
  <div class="my-2">Assigned to: <strong>{{ $leadData->user->name ?? 'N/A'}}</strong></div>
@endif

@can('leads manage')
@if(!in_array($leadData->stage_id, [2,6,7]))
  <div class="">
                @if (!$leadData->user_id)
    <div class="col-md-6 mb-3">
      <label class="form-label">User <span class="text-danger">*</span></label>
      <select id='user_id' class="form-select select2" wire:model="user_id">
        <option value="">Select User</option>
                            @foreach ($users as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
      @error('user_id')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <a wire:click='userAssign()' class="btn btn-primary">Assign</a>
    @endif


    <div class="row mt-2">

      <div class="col-md-6 mb-3">
        <label class="form-label">Stage</label>
        <select id='stage_id' class="form-select" wire:model="stage_id">
                            @foreach ($stages as $id => $name)
          <option value="{{ $id }}" @if ($leadData->stage->id == $id) selected @endif>
          {{ $name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Status <span class="text-danger">*</span></label>
      <select id='status_id' class="form-select select2" wire:model="status_id">
                            @foreach ($status as $id => $name)
        <option value="{{ $id }}" @if ($leadData->status->id == $id) selected @endif>
        {{ $name }}</option>
      @endforeach
    </select>
  </div>
                </div>

            </div>
  @endif
  @endcan

    </div >


  <div class="modal @if ($showFollowupModal) show @endif" tabindex="-1"
    style="opacity:1; background-color:#0606068c; display:@if ($showFollowupModal) block @else none @endif">
    <div class="modal-dialog modal-lg">
      <div class="modal-content p-4">
        <form wire:submit.prevent="storeFollowUp">
        <div class="row mb-3">
          <div class="col-md-6 mb-3">
            <label class="form-label">Follow up Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control datepicker @error('followup_date') is-invalid @enderror"
              wire:model="followup_date">
              @error('followup_date')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Follow up Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control timepicker @error('followup_time') is-invalid @enderror"
              wire:model="followup_time">
              @error('followup_time')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Mark</label>
            <input type="color" class="form-control"
              wire:model="mark">
          </div>

          <div class="mb-3">
            <label for="title" class="form-label">Comments <span
              class="text-danger">*</span></label>
            <textarea class="form-control @error('comments') is-invalid @enderror" wire:model="comments" placeholder="comments"></textarea>
            @error('comments')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-sm btn-primary px-5" wire:loading.attr="disabled">
          Save changes
          <i class="spinner-border spinner-border-sm" wire:loading.delay
                                wire:target="storeFollowUp"></i>
      </button>
      <button type="button" wire:click="resetForm" class="btn btn-sm btn-secondary">Close</button>
    </div>
  </form>
            </div >
        </div >
    </div >
</div >
  
