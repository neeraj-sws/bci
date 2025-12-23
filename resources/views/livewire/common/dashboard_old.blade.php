<div>
    <div class="container  mt-sm-0 mt-3 pb-4 dashboardpage">
        <div class="dashboard-header pb-0 mb-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
            <h6 class="breadcrumb-title pe-2 fs-24  border-0 text-black fw-600 mb-0">Dashboard</h6>
            <div class="text-end text-muted small">
                <p class="mb-0">Last updated: <span id="current-date"></span></p>
            </div>
        </div>

        {{-- <div class="row g-3">
            <!-- Park Card -->
            <a href="{{ route('admin.park') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        
                        <div class="card-value">{{$parks}}</div>
                        <h2 class="card-title mb-0">Park</h2>
                    </div>
                </div>
            </a>

            <!-- Zone Card -->
            <a href="{{ route('admin.zone') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="card-value">{{$zones}}</div>
                        <h2 class="card-title mb-0">Zone</h2>
                    </div>
                </div>
            </a>

            <!-- Taxi Card -->
            <a href="{{ route('admin.taxi') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="card-value">{{$taxis}}</div>
                        <h2 class="card-title mb-0">Taxi</h2>
                    </div>
                </div>
            </a>

            <!-- Gates Card -->
            <a href="{{ route('admin.gates') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="card-value">{{$gates}}</div>
                        <h2 class="card-title mb-0">Gates</h2>
                    </div>
                </div>
            </a>

            <!-- Resort Card -->
            <a href="{{ route('admin.resort') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="card-value">{{$resorts}}</div>
                        <h2 class="card-title mb-0">Resort</h2>
                    </div>
                </div>
            </a>

            <!-- Items Card -->
            <a href="{{ route('admin.item') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="card-value">{{$items}}</div>
                        <h2 class="card-title mb-0">Items</h2>
                    </div>
                </div>
            </a>

            <!-- Project Card -->
            <a href="{{ route('admin.tour') }}" class="col-md-6 col-lg-3">
                <div class="data-card card shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="card-value">{{$projects}}</div>
                        <h2 class="card-title mb-0">Project</h2>
                    </div>
                </div>
            </a>
        </div> --}}

        <div class="row g-3">
            @foreach ($dashboardData as $item)
                <a href="{{ $item['route'] }}" class="col-md-6 col-lg-3">
                    <div class="data-card card shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="card-value">{{ $item['value'] }}</div>
                            <h2 class="card-title mb-0">{{ $item['title'] }}</h2>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>


    </div>

    <script>
        // Display current date
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', options);
    </script>
</div>
