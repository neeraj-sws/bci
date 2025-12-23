<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCorrectGuard
{
    protected $guard;

    public function __construct($guard = null)
    {
        $this->guard = $guard;
    }

    public function handle(Request $request, Closure $next, $guard = null)
    {
        $guard = $guard ?? $this->guard;
        
        if (!auth()->guard($guard)->check()) {
            if ($guard === 'web') {
                return redirect()->route('user.login')->with('error', 'Access Denied.');
            }
            return redirect("/$guard/login")->with('error', 'Access Denied.');
        }

        return $next($request);
    }
}
