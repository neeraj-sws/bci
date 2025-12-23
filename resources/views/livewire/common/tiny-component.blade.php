 
<div class="mb-3" wire:ignore>
    <label for="{{ $editorId }}">{{ ucfirst($model) }}  <span class="text-danger">*</span></label>
    <textarea id="{{ $editorId }}" data-editor  data-editor-id="{{ $editorId }}" data-model="{{ $model }}"
        class="form-control">{{ $value }}</textarea>
</div>