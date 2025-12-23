<div>
        <div class="card p-3">
    @can('leads edit')
        @if(!in_array($stage, [2,6,7]))
    <input type="file" wire:model="files" accept="image/*">
                @endif
 @endcan
    <div wire:loading wire:target="files" class="mt-2 text-blue-600">
        Uploading images, please wait...
    </div>

    @if ($files)
        <div class="mt-3 flex flex-wrap gap-3">
            @foreach ($files as $file)
                <div>
                    <img src="{{ $file->temporaryUrl() }}" alt="Preview"
                        style="max-height: 160px; object-fit: contain;" />
                </div>
            @endforeach
        </div>
        <div class="mt-4">
         
            <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">
                Upload Images
            </button>

        </div>
        @else
          <p>No data found</p>
    @endif
    @if (count($existingImages) > 0)
        <div class="mt-5">
            <h4>Uploaded Images:</h4>
            <div class="flex flex-wrap gap-3 mt-2">
                @foreach ($existingImages as $image)
                    <div class="relative">
                        <img src="{{ asset('uploads/leads/' . $this->leadId . '/' . $image->file) }}"
                            style="max-height: 160px; object-fit: contain;" />

                        <button wire:click="deleteImage({{ $image->id }})"
                            class="absolute top-1 right-1 btn btn-danger">
                            Delete
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
</div>
