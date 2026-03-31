<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $role = $request->user()->role;

        $redirectMap = [
            'learning_administrator' => '/learning-admin/dashboard',
            'learning_coordinator' => '/learning-coordinator/dashboard',
            'admin_coordinator' => '/admin-coordinator/dashboard',
            'sme' => '/sme/dashboard',
            'employee' => '/employee/dashboard',
            'helpdesk_admin' => '/helpdesk/dashboard',
            'public' => '/dashboard',
        ];

        $redirect = $redirectMap[$role] ?? '/dashboard';

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($redirect);
    }
}
