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
        // Clear active role session when visiting login page
        session()->forget('active_role');
        
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
            $user = Auth::user();
            
            // Filter roles: Only show management roles for selection in back-office
            $managementRolesList = [
                'learning_administrator',
                'learning_coordinator',
                'admin_coordinator',
                'sme',
                'helpdesk_admin'
            ];

            $availableManagementRoles = array_intersect($user->roles ?? [], $managementRolesList);

            if (count($availableManagementRoles) > 1) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'multiple_roles' => true,
                        'roles' => array_values($availableManagementRoles),
                        'message' => 'Silakan pilih peran Anda.'
                    ]);
                }
                return redirect()->route('auth.select-role');
            }

            $role = $user->role;
            
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

            $redirectUrl = $redirectMap[$role] ?? '/dashboard';
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'errors' => ['email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.']]
            ], 422);
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    /**
     * Show the role selection form.
     */
    public function showRoleSelection()
    {
        $user = Auth::user();
        $managementRolesList = [
            'learning_administrator',
            'learning_coordinator',
            'admin_coordinator',
            'sme',
            'helpdesk_admin'
        ];

        $availableManagementRoles = array_intersect($user->roles ?? [], $managementRolesList);

        if (!$user || count($availableManagementRoles) <= 1) {
            return redirect('/');
        }

        return view('pages.auth.management.select-role', [
            'roles' => array_values($availableManagementRoles)
        ]);
    }

    /**
     * Handle the role selection submission.
     */
    public function selectRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $user = Auth::user();
        if (!in_array($request->role, $user->roles)) {
            return back()->withErrors(['role' => 'Role tidak valid.']);
        }

        // Store active role in session
        $request->session()->put('active_role', $request->role);
        session()->save();

        // Redirect to appropriate dashboard
        $redirectMap = [
            'learning_administrator' => '/learning-admin/dashboard',
            'learning_coordinator' => '/learning-coordinator/dashboard',
            'admin_coordinator' => '/admin-coordinator/dashboard',
            'sme' => '/sme/dashboard',
            'helpdesk_admin' => '/helpdesk/dashboard',
        ];

        $redirectUrl = $redirectMap[$request->role] ?? '/dashboard';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);
        }

        return redirect($redirectUrl);
    }
}
