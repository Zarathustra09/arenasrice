<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToHome
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            $currentPath = $request->path();

            switch ($role) {
                case 0:
                    if ($currentPath !== '/') {
                        return redirect('/');
                    }
                    break;
                case 1:
                    if ($currentPath !== 'home') {
                        return redirect('/home');
                    }
                    break;
                case 2:
                    if ($currentPath !== 'staff/pos/index') {
                        return redirect('/staff/pos/index');
                    }
                    break;
                default:
                    if ($currentPath !== '/') {
                        return redirect('/');
                    }
                    break;
            }
        }

        return $next($request);
    }
}
