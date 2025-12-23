<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (($request->is('admin') || $request->is('admin/*')) && Auth::guard('web')->check()) {
            return redirect('/admin/dashboard');       }

        if (Auth::guard('web')->check() && !$this->isAdmin($request) && !$this->isMarketing($request)) {
            return redirect('/user/dashboard');
        }

        return $next($request);
    }

     /**
     * Check if the request is for admin or vendor routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function isAdmin(Request $request): bool
    {
        return $request->is('admin/*') || $request->is('admin');
    }
        private function isMarketing(Request $request): bool
    {
        return $request->is('marketing') || $request->is('marketing/*');
    }
}
