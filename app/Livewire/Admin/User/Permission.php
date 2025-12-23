<?php

namespace App\Livewire\Admin\User;

use Livewire\Component;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.common-app')]
class Permission extends Component
{
    public $selectedRole = '';
    public $selectedPermissions = [];
    public $roles = [];
    public $groupedPermissions = [];
    public $pageTitle = 'Permissions';

    public function mount()
    {
        // Load all roles
        $this->roles = Role::where('guard_name', 'web')
            ->pluck('name', 'id')
            ->toArray();

        // Load all permissions grouped by module
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $allPermissions = ModelsPermission::where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        $this->groupedPermissions = [];

        foreach ($allPermissions as $permission) {
            $parts = explode(' ', $permission->name);
            $module = $parts[0];

            if (!isset($this->groupedPermissions[$module])) {
                $this->groupedPermissions[$module] = [];
            }

            $this->groupedPermissions[$module][] = [
                'id' => $permission->id,
                'name' => $permission->name, // ✅ store actual name
                'display_name' => ucfirst($parts[0]) . ' ' . ucfirst($parts[1] ?? ''),
            ];
        }
    }

    public function updatedSelectedRole($roleId)
    {
        if ($roleId) {
            $role = Role::find($roleId);
            // ✅ store permission names
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function save()
    {
        $role = Role::findOrFail($this->selectedRole);

        // ✅ syncPermissions expects names
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Permissions updated successfully!'
        ]);
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.user.permission');
    }
    
      public function resetForm()
    {
        $this->userId = null;
        $this->selectedPermissions = '';
        $this->selectedRole = '';
    }
}
