<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Si no está logueado, fuera.
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Obtenemos el usuario actual
        $user = Auth::user();

        // 3. Verificamos si su rol coincide con el requerido
        // Si el usuario es 'admin', lo dejamos pasar a todo por si acaso.
        if ($user->role !== $role && $user->role !== 'admin') {
            // Si no tiene permiso, error 403 (Prohibido) o redirigir al home
            abort(403, 'No tienes permiso para entrar a esta zona de la barbería.');
        }

        return $next($request);
    }
}
