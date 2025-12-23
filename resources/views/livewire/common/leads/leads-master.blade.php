   <div class="page-breadcrumb flex-wrap d-flex align-items-center mb-3">
        <div>
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600">Lead Setting </h6>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lead Setting</li>
                </ol>
            </nav>

        </div>

    </div>
    
<div class="col-12">
                     <ul class="nav nav-tabs nav-primary " role="tablist">
									<li class="nav-item me-3">
								<a href="{{ route('common.leads-pipeline') }}" class="nav-link p-2 @if($pageTitle == 'Leads Type') active @endif">
									<i class="ti ti-settings-cog me-2"></i>Leads Type
								</a>
							</li>
							<li class="nav-item me-3 d-none">
								<a href="{{ route('common.leads-status') }}" class="nav-link p-2 @if($pageTitle == 'Leads Status') active @endif">
									<i class="ti ti-world-cog me-2"></i>Leads Status
								</a>
							</li>
							<li class="nav-item me-3">
								<a href="{{ route('common.leads-stages') }}" class="nav-link p-2 @if($pageTitle == 'Leads Stage') active @endif">
									<i class="ti ti-apps me-2"></i>Leads Stage
								</a>
							</li>
							<li class="nav-item me-3">
								<a href="{{ route('common.leads-source') }}" class="nav-link p-2 @if($pageTitle == 'Leads Source') active @endif">
									<i class="ti ti-device-laptop me-2"></i>Leads Source
								</a>
								</ul>
								

</div>						