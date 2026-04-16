<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Mengecek apakah user terautentikasi melalui guard yang sesuai dengan role
        if (!auth()->guard($role)->check()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk halaman ini.');
        }

        return $next($request);
    }
}
