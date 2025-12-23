<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest_login')]
class LoginComponent extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();
        if (Auth::guard('web')->attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            $user = Auth::guard('web')->user();
            if ($user->hasRole('admin')) {
                $this->dispatch('swal:toast', [
                    'type' => 'success',
                    'title' => '',
                    'message' => 'Login successful!',
                ]);
                return redirect()->route('common.dashboard');
            } else {
                Auth::guard('web')->logout();

                $this->dispatch('swal:toast', [
                    'type' => 'error',
                    'title' => '',
                    'message' => 'Access denied. You do not have admin privileges.',
                ]);
            }
        } else {
            $this->dispatch('swal:toast', [
                'type' => 'error',
                'title' => '',
                'message' => 'Invalid credentials.',
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('admin.login');
    }

    public function render()
    {
        return view('livewire.admin.auth.login-component');
    }
}
