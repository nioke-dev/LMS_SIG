<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('home'));
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            $activeRole = session('active_role', $user?->role);
            
            $redirectMap = [
                'learning_administrator' => '/learning-admin/dashboard',
                'learning_coordinator' => '/learning-coordinator/dashboard',
                'admin_coordinator' => '/admin-coordinator/dashboard',
                'sme' => '/sme/dashboard',
                'helpdesk_admin' => '/helpdesk/dashboard',
            ];

            return $redirectMap[$activeRole] ?? '/dashboard';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
