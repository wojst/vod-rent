<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Sprawda warunki, czy użytkownik ma uprawnienia administratora
        if (Auth::check() && Auth::user()->admin_role == true) {
            // Jeśli użytkownik jest zalogowany i ma uprawnienia administratora, kontynuuj żądanie
            return $next($request);
        }

        // Jeśli użytkownik nie ma uprawnień administratora
        abort(403, 'Brak autoryzacji');
    }
}
