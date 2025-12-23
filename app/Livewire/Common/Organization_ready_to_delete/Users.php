<?php

namespace App\Livewire\Common\Organization;

use App\Models\User as Model;
use Illuminate\Support\Facades\{Hash, Auth};
use Livewire\Attributes\{Layout, On};
use Livewire\{Component, WithPagination};

class Users extends Component
{
    use WithPagination;

    public $userId, $password, $password_confirmation, $email;
    public $search = '', $showModal = false;
    public $isAdmin = false;
    public $pageTitle = 'Users';
    public $model = Model::class;
    public $view = 'livewire.common.organization.users';

    public function mount()
    {
        $this->userId = Auth::id();
        $user = $this->model::findOrFail($this->userId);
        $this->email = $user->email;
    }

    public function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'The retype password does not match.',
            'email.unique' => 'The email has already been taken.',
        ];
    }

    public function render()
    {
        return view($this->view);
    }

    public function updateEmail()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ]);

        $user = $this->model::findOrFail($this->userId);
        $user->update([
            'email' => $this->email,
        ]);
                $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Email updated successfully!'
        ]);
    }

    public function resetPassword()
    {
        $this->validate();

        $user = $this->model::findOrFail($this->userId);
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['password', 'password_confirmation', 'showModal']);
        $this->resetValidation();

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Password reset successfully!'
        ]);
    }

    // Toggle modal visibility
    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }
}
