<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles)) {
            return redirect()
                ->route('home')
                ->with('access_denied', 'Maaf, Anda tidak memiliki otorisasi untuk mengakses halaman tersebut. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.');
        }

        return $next($request);
    }
}
