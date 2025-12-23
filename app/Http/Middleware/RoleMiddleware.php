<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // public function handle($request, Closure $next, ...$roles)
    // {
    //     if (!Auth::check()) {
    //         return redirect('/login');
    //     }
    //     $user = Auth::guard('web')->user();
    //     if ($user->hasRole($roles)) {
    //         return $next($request);
    //     }
    //     if (in_array('admin', $roles)) {
    //         return redirect('/dashboard');
    //     }
    //     return redirect('/dashboard');
    // }
     public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $user = Auth::guard('web')->user();
        if ($user->roles()->exists()) {
            return $next($request);
        }
        return redirect('/dashboard');
    }

}
