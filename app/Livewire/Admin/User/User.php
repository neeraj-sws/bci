<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use App\Models\User as Model;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\{Layout, On};
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.common-app')]
class User extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password,$password_confirmation,$mail_sent;
    public $status = true;
    public $selectedRole = '';
    public $roles = [];
    public $isEditing = false, $search = '';
    public $pageTitle = 'Users';
    public $itemId;

    public function mount()
    {
        $this->roles = Role::where('guard_name', 'web')->pluck('name', 'id')->toArray();
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->status = true;
        $this->selectedRole = '';
        $this->isEditing = false;
        $this->password_confirmation = '';
        $this->reset(['mail_sent']);
    }

 public function rules()
{
    $table = (new Model)->getTable();

    $rules = [
        'name' => [
            'required',
            'string',
            'max:255'
        ],
        'email' => 'required|email|unique:' . $table . ',email' . ($this->isEditing ? ',' . $this->userId . ',user_id' : ''),
        'status' => 'required',
        'selectedRole' => 'required|in:' . implode(',', array_keys($this->roles)),
    ];

    if ($this->isEditing && $this->password) {
        $rules['password'] = 'required|string|min:6|confirmed';
    }

    if (!$this->isEditing) {
        $rules['password'] = 'required|string|min:6|confirmed';
    }

    return $rules;
}

public function messages()
{
    return [
        'name.required' => 'The name field is required.',
        'email.required' => 'The email field is required.',
        'email.email' => 'Please provide a valid email address.',
        'email.unique' => 'This email has already been taken.',
        'password.required' => 'The password field is required.',
        'password.min' => 'The password must be at least 6 characters.',
        'password.confirmed' => 'The password confirmation does not match.',
        'status.required' => 'The status field is required.',
        'selectedRole.required' => 'Please select a role.',
        'selectedRole.in' => 'Invalid role selected.',
    ];
}


    public function store()
    {
        $this->validate($this->rules());
        if ($this->isEditing) {
            $user = Model::findOrFail($this->userId);
            $user->name = ucwords($this->name);
            $user->email = $this->email;
            $user->status = $this->status;
            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
        } else {
            $user = Model::create([
                'name' => ucwords($this->name),
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'status' => $this->status,
                'mail_sent' => $this->mail_sent,
            ]);
        }

        $role = Role::find($this->selectedRole);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => $this->isEditing ? 'User updated successfully!' : 'User created successfully!'
        ]);

        $this->resetForm();
    }

    public function edit($id)
    {
        $this->resetForm();
        $user = Model::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->status = $user->status;
        $this->selectedRole = $user->roles->first()?->id ?? '';
        $this->isEditing = true;
    }

    public function render()
    {
        $items = Model::where('is_admin','!=',1)
            ->where('name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

        return view('livewire.admin.user.user', compact('items'));
    }
    
        public function confirmDelete($id)
    {
        $this->itemId = $id;

        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => 'This action cannot be undone.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, delete it!',
            'cancelButtonText' => 'Cancel',
            'action' => 'delete'
        ]);
    }

    #[On('delete')]
    public function delete()
    {
        Model::destroy($this->itemId);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }
    
        public function toggleStatus($id)
    {
        $habitat = Model::findOrFail($id);
        $habitat->status = !$habitat->status;
        $habitat->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }
}
