@if ($paginator->hasPages())
    <div class="card-footer">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of
                {{ $paginator->total() }} entries
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0 flex-wrap justify-content-center justify-content-md-end">
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="previousPage"
                            {{ $paginator->onFirstPage() ? 'disabled' : '' }}>Previous</button>
                    </li>

                    @php
                        $start = max(1, $paginator->currentPage() - 2);
                        $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
                    @endphp

                    @if ($start > 1)
                        <li class="page-item">
                            <button class="page-link" wire:click="gotoPage(1)">1</button>
                        </li>
                        @if ($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                            <button class="page-link" wire:click="gotoPage({{ $page }})">
                                {{ $page }}
                            </button>
                        </li>
                    @endfor

                    @if ($end < $paginator->lastPage())
                        @if ($end < $paginator->lastPage() - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <button class="page-link" wire:click="gotoPage({{ $paginator->lastPage() }})">
                                {{ $paginator->lastPage() }}
                            </button>
                        </li>
                    @endif

                    <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="nextPage"
                            {{ !$paginator->hasMorePages() ? 'disabled' : '' }}>Next</button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endif
