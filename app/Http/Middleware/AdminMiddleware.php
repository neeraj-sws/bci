<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('web')->user(); 

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.login');
        }else{
            return redirect()->route('user.login');
        }

        return $next($request);
    }
}
