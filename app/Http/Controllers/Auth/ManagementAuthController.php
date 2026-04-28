<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementAuthController extends Controller
{
    /**
     * Show the management back-office login form.
     */
    public function showLoginForm()
    {
        return view('pages.auth.management.login'); // Wireframe view
    }

    /**
     * Handle management login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            
            // Re-verify that the user has a management role
            $managementRoles = [
                'learning_administrator',
                'learning_coordinator',
                'admin_coordinator',
                'sme',
                'helpdesk_admin'
            ];

            if (!in_array($role, $managementRoles)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses ditolak. Anda tidak memiliki otoritas manajemen back-office.',
                ]);
            }

            // Redirect to appropriate dashboard
            $redirectMap = [
                'learning_administrator' => '/learning-admin/dashboard',
                'learning_coordinator' => '/learning-coordinator/dashboard',
                'admin_coordinator' => '/admin-coordinator/dashboard',
                'sme' => '/sme/dashboard',
                'helpdesk_admin' => '/helpdesk/dashboard',
            ];

            return redirect()->intended($redirectMap[$role] ?? '/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }
}
