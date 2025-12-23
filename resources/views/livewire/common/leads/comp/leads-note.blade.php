<div>
        <div class="card p-3">
    <form wire:submit.prevent="updateNotes">
        <div class="mb-3">
            <label for="notes" class="form-label">Notes:</label>
            <textarea id="notes" class="form-control" wire:model.defer="notes"></textarea>
        </div>
 @can('leads edit')
 @if(!in_array($stage, [2,6,7]))
        <button type="submit" class="btn btn-primary">Save Notes</button>
        @endif
        @endcan
    </form>
</div>
</div>
