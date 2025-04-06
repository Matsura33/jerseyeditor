<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur n'est pas connecté, on le laisse passer
        // (le middleware auth s'occupera de le rediriger)
        if (!auth()->check()) {
            return $next($request);
        }

        // Si l'utilisateur est connecté mais n'est pas admin, on le redirige
        if (!auth()->user()->admin) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
