<?php

namespace App\Livewire\User\Auth;

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

        if (Auth::guard('web')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $user = Auth::guard('web')->user();
            if ($user->is_admin == 1) {
                Auth::guard('web')->logout();
                $this->dispatch('swal:toast', [
                    'type' => 'error',
                    'title' => '',
                    'message' => 'Access denied. You do not have user privileges.',
                ]);
                return;
            }
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => '',
                'message' => 'Login successful!',
            ]);
            return redirect()->route('common.dashboard');
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
        return redirect()->route('user.login');
    }

    public function render()
    {

        return view('livewire.user.auth.login-component');
    }
}
