<?php

namespace App\Livewire\Common\Leads\Comp;

use App\Helpers\SettingHelper;
use App\Models\UploadImages;
use App\Models\Leads;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithFileUploads};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class LeadsUploads extends Component
{
    use WithFileUploads;


    public array $files = [];


    public $leadId;

    public $view = 'livewire.common.leads.comp.leads-uploads';


    public $existingImages = [];
    public $coloum, $guard, $stage;

    public $pendingFile = null;

    public function mount($id, $coloum = null, $guard = null)
    {
        $this->leadId = $id;
        $this->coloum = $coloum;
        $this->guard = $guard;
        $this->existingImages = UploadImages::where('lead_id', $this->leadId)->get();
        $lead = Leads::find($this->leadId);
        $this->stage = $lead->stage_id;
    }

    public function render()
    {
        return view($this->view);
    }

    public function save()
    {
        $userId = $this->coloum ? Auth::guard($this->guard)->user()->id : null;

        $this->validate([
            'files.*' => 'image|max:5120'
        ]);
        
        
        // NEW DEV 

        // $path = 'assets/images';

        // foreach ($this->files as $file) {
        //     $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        //     $file->storeAs($path, $fileName, 'public_root');

        //     UploadImages::create([
        //         'file' => $fileName,
        //         'ext' => $file->getClientOriginalExtension(),
        //         'lead_id' => $this->leadId,
        //     ]);
        // }
        
        $path = "uploads/leads/{$this->leadId}";
        if (count($this->files) > 0) {
            $path = "uploads/leads/{$this->leadId}";
            if (!Storage::disk('public_root')->exists($path)) {
                Storage::disk('public_root')->makeDirectory($path);
            }
            foreach ($this->files as $file) {
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $fileName = "$name.$ext";
                $files = collect(Storage::disk('public_root')->files($path));
                $matching = $files->filter(function ($f) use ($name, $ext) {
                    return preg_match("/^" . preg_quote($name, '/') . "(\(\d+\))?\.$ext$/", basename($f));
                });
                if ($matching->isNotEmpty()) {
                    $fileName = "{$name}(" . $matching->count() . ").{$ext}";
                }
                $file->storeAs($path, $fileName, 'public_root');
                UploadImages::create([
                    'file' => $fileName,
                    'ext' => $ext,
                    'lead_id' => $this->leadId,
                ]);
            }
        }
        
        // 
        $this->existingImages = UploadImages::where('lead_id', $this->leadId)->get();

        SettingHelper::leadActivityLog(5, $this->leadId, $userId, $this->coloum);
        $this->dispatch('history-status-updated');


        $this->files = [];

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Files uploaded successfully.',
        ]);
    }

    public function deleteImage($id)
    {
        $image = UploadImages::find($id);

        if ($image) {
            // NEW DEv 
            // @unlink(public_path('assets/images/' . $image->file));
            if ($this->leadId) {
                @unlink(public_path('uploads/leads/' . $this->leadId . '/' . $image->file));
            }
            // 
            $image->delete();

            $this->existingImages = UploadImages::where('lead_id', $this->leadId)->get();
        }
    }
    
        public function updatedFiles($data)
    {
        if ($this->leadId) {
            foreach ($this->files as $file) {
                $fileName = $file->getClientOriginalName();
                $path = "uploads/leads/{$this->leadId}/" . $fileName;
                if (Storage::disk('public_root')->exists($path)) {
                    $this->pendingFile = $file;
                    $this->dispatch('swal:confirm', [
                        'title' => 'File already exists!',
                        'text' => "The file '{$fileName}' already exists. Do you want to replace it?",
                        'icon' => 'warning',
                        'showCancelButton' => true,
                        'confirmButtonText' => 'Yes, replace it',
                        'cancelButtonText' => 'Cancel',
                        'action' => 'confirmReplace',
                        'cancelAction' => 'cancelReplace',
                    ]);
                    return;
                }
            }
        }
    }

    #[On('confirmReplace')]
    public function confirmReplace()
    {
        if ($this->pendingFile) {
            $this->pendingFile = null;

            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => '',
                'message' => "File was added with (1)."
            ]);
        }
    }

    #[On('cancelReplace')]
    public function cancelReplace()
    {
        if ($this->pendingFile) {
            $removeName = $this->pendingFile->getClientOriginalName();
            $this->files = array_filter($this->files, fn($file) => $file->getClientOriginalName() !== $removeName);
            $this->pendingFile = null;
            $this->dispatch('swal:toast', [
                'type' => 'info',
                'title' => '',
                'message' => "File '{$removeName}' was not added."
            ]);
        }
    }
}
