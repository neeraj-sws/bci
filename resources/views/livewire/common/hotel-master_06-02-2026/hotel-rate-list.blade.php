<div class="container mt-3">

    <div class="d-flex justify-content-between mb-3">
        <h5>{{ $pageTitle }}</h5>

        <a href="{{ route('common.hotel-rates.create') }}" class="btn bluegradientbtn">
            + Add Hotel Rate
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-end">
            <input type="text" class="form-control w-50" placeholder="Search by hotel..."
                wire:model.debounce.300ms="search">
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hotel</th>
                            <th>Room</th>
                            <th>Season</th>
                            <th>Rates</th>
                            <th>Status</th>
                            <th width="90">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->hotel->name }}</td>
                                <td>{{ $item->roomCategory->title }}</td>
                                <td>{{ $item->season->name ?? "Na" }}</td>
                                <td>
                                    WD: ₹{{ $item->weekday_rate }} <br>
                                    WE: ₹{{ $item->weekend_rate }}
                                </td>
                                <td>
                                    <input type="checkbox" wire:change="toggleStatus({{ $item->id }})"
                                        @checked($item->status)>
                                </td>
                                <td>
                                    <a href="{{ route('common.hotel-rates.edit', $item->id) }}">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a wire:click="confirmDelete({{ $item->id }})">
                                        <i class="bx bx-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Rates Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $items->links() }}
        </div>
    </div>
</div>
