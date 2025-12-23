<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\{Layout, On};

#[Layout('components.layouts.common-app')]
class Roles extends Component
{
    use WithPagination;

    public $roleId, $name, $isEditing = false;
    public $search = '';
    public $pageTitle = 'Roles';

public function rules()
{
    return [
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:roles,name' . ($this->isEditing ? ',' . $this->roleId : ''),
        ],
    ];
}

public function messages()
{
    return [
        'name.required' => 'The Role name field is required.',
        'name.string' => 'The Role name must be a string.',
        'name.max' => 'The Role name may not be greater than 255 characters.',
        'name.unique' => 'The Role name has already been taken.'
    ];
}


    public function store()
    {
        $this->validate();

        Role::create([
            'name' => ucwords($this->name),
            'guard_name' => 'web',
        ]);

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Role created successfully!'
        ]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $role = Role::findOrFail($this->roleId);
        $role->update(['name' => ucwords($this->name)]);

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Role updated successfully!'
        ]);
    }


    public function confirmDelete($id)
    {
        $this->roleId = $id;

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
        $role = Role::findOrFail($this->roleId);
        $role->syncPermissions([]);
        $role->delete();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Role and its permissions link deleted successfully!'
        ]);
    }


    public function resetForm()
    {
        $this->reset(['roleId', 'name', 'isEditing']);
    }

    public function render()
    {
        $roles = Role::where('name', 'like', "%{$this->search}%")->where('guard_name', 'web')->paginate(10);

        return view('livewire.admin.user.roles', compact('roles'));
    }
}
