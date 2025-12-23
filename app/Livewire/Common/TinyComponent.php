<?php

namespace App\Livewire\Common;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class TinyComponent extends Component
{
    #[Modelable]
    public $value = '';
    public $model;
    public $editorId;

    public function mount($model, $value = '', $editorId = null)
    {
        $this->value = $value;
        $this->model = $model;
        $this->editorId = $editorId ?? 'editor-' . uniqid();
        $this->dispatch('init-tinymce');
    }

    public function render()
    {
        return view('livewire.common.tiny-component');
    }
}
