<?php

use App\Livewire\Admin\Auth\{LoginComponent};
use App\Livewire\Admin\User\User;
use Illuminate\Support\Facades\{Route};
use App\Livewire\Admin\User\Roles;
use App\Livewire\Admin\User\Permission;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/', LoginComponent::class);
        Route::get('login', LoginComponent::class)->name('login');
    });
    Route::middleware(['auth.guard:web', 'web', 'role:admin'])->group(callback: function () {
        // Route::get('/users', User::class)->name('users');
        // Route::get('/roles', Roles::class)->name('roles');
        // Route::get('/role-permisions', Permission::class)->name('permisions');
        Route::get('logout', [LoginComponent::class, 'logout'])->name('logout');
    });
});


Route::middleware(['auth.guard:web', 'web', 'role:admin'])->group(callback: function () {
    Route::prefix('user-management')->name('admin.')->group(function () {
        Route::get('/users', User::class)->name('users');
        Route::get('/roles', Roles::class)->name('roles');
        Route::get('/permissions', Permission::class)->name('permisions');
    });
});
