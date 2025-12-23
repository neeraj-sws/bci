<?php

namespace App\Livewire\Admin\User;

use App\Models\User as Model;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('components.layouts.common-app')]
class User extends Component
{
    use WithPagination;

    public $userId;
    public $status = 1;
    public $name, $email, $mail_sent, $password, $password_confirmation, $search = '';
    public $isEditing = false;
    public $pageTitle = 'User';
    public $roles = [];
    public $selectedRole = '';
    public $userPermissions = [];
    public $groupedPermissions = [];

    public $model = Model::class;
    public $view = 'livewire.admin.user.user';

    public function mount()
    {
        $this->roles = Role::where('guard_name', 'web')->pluck('name', 'name')->toArray();
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $allPermissions = Permission::where('guard_name', 'web')->orderBy('name')->get();

        $this->groupedPermissions = [];
        foreach ($allPermissions as $permission) {
            $parts = explode(' ', $permission->name);
            $module = $parts[0];

            if (!isset($this->groupedPermissions[$module])) {
                $this->groupedPermissions[$module] = [];
            }

            $this->groupedPermissions[$module][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'display_name' => $this->formatPermissionName($permission->name),
                'checked' => in_array($permission->name, $this->userPermissions)
            ];
        }
    }

    protected function formatPermissionName($permissionName)
    {
        // Convert "module action" to "Module - Action"
        $parts = explode(' ', $permissionName);
        $module = ucfirst($parts[0]);
        $action = ucfirst(str_replace('_', ' ', $parts[1] ?? ''));

        return $module . ($action ? ' - ' . $action : '');
    }

    public function updatedSelectedRole($role)
    {
        if ($role) {
            $roleModel = Role::where('name', $role)->where('guard_name', 'web')->first();
            if ($roleModel) {
                $this->userPermissions = $roleModel->permissions->pluck('name')->toArray();
            }
        } else {
            $this->userPermissions = [];
        }
        $this->loadPermissions();
    }

    public function rules()
    {
        $table = (new $this->model)->getTable();
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:' . $table . ',email' . ($this->isEditing ? ',' . $this->userId : ''),
            'status'   => 'required',
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

    public function render()
    {
        $items = $this->model::where('name', 'like', "%{$this->search}%")->orderBy('updated_at', 'desc')
            ->latest()->paginate(10);

        return view($this->view, compact('items'));
    }

    public function store()
    {
        $this->validate($this->rules());

        $user = $this->model::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'mail_sent' => $this->mail_sent,
            'status' => $this->status,
        ]);

        // Assign role to user
        $user->syncRoles([$this->selectedRole]);

        // Assign selected permissions
        $user->syncPermissions($this->userPermissions);

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' Added Successfully'
        ]);
    }

    public function edit($id)
    {
        $this->resetForm();
        $item = $this->model::findOrFail($id);

        $this->userId = $item->id;
        $this->name = $item->name;
        $this->email = $item->email;
        $this->mail_sent = $item->mail_sent;
        $this->status = $item->status;
        $this->selectedRole = $item->roles->first()->name ?? '';
        $this->userPermissions = $item->getPermissionNames()->toArray();
        $this->isEditing = true;

        $this->loadPermissions();
    }

    public function update()
    {
        $this->validate($this->rules());

        $user = $this->model::findOrFail($this->userId);
        $data = [
            'name'      => $this->name,
            'email'     => $this->email,
            'mail_sent' => $this->mail_sent,
            'status'    => $this->status,
        ];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        $user->update($data);

        $user->syncRoles([$this->selectedRole]);
        $user->syncPermissions($this->userPermissions);

        $this->resetForm();

        $this->dispatch('swal:toast', [
            'type'    => 'success',
            'message' => $this->pageTitle . ' Updated Successfully'
        ]);
    }



    public function confirmDelete($id)
    {
        $this->userId = $id;

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
        $this->model::destroy($this->userId);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => $this->pageTitle . ' deleted successfully!'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'userId', 'isEditing', 'status', 'email', 'mail_sent', 'password', 'password_confirmation', 'selectedRole', 'userPermissions']);
        $this->userPermissions = [];
        $this->loadPermissions();
        $this->resetValidation();
    }

    public function toggleStatus($id)
    {
        $user = $this->model::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Status Changed Successfully']);
    }

    public function togglePermission($permissionName)
    {
        if (in_array($permissionName, $this->userPermissions)) {
            $this->userPermissions = array_diff($this->userPermissions, [$permissionName]);
        } else {
            $this->userPermissions[] = $permissionName;
        }

        $this->loadPermissions();
    }
}
