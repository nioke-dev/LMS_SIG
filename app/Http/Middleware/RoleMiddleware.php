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
        
        // Check active role from session first (for multi-role users), 
        // fallback to database role if not set.
        $activeRole = trim(session('active_role', $user?->role));

        // Critical logging to catch the mismatch
        \Illuminate\Support\Facades\Log::info('Role Middleware Handle:', [
            'url' => $request->fullUrl(),
            'user' => $user?->email,
            'session_active_role' => session('active_role'),
            'effective_role' => $activeRole,
            'allowed_roles' => $roles,
            'is_authorized' => in_array($activeRole, $roles)
        ]);

        if (! $user || ! in_array($activeRole, $roles)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Akses Ditolak.'], 403);
            }
            return redirect()
                ->route('home')
                ->with('access_denied', 'Maaf, Anda tidak memiliki otorisasi untuk mengakses halaman tersebut. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.');
        }

        return $next($request);
    }
}
