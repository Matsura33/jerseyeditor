<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Si l'utilisateur est admin, on le laisse accéder aux routes admin
                if ($request->is('admin*') && Auth::user()->admin) {
                    return $next($request);
                }
                // Sinon, on le redirige vers le dashboard
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
} 