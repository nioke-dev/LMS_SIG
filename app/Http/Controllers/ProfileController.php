<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile details.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Mock stats for LC (Learning Coordinator)
        $stats = [
            'total_submissions' => 12,
            'approved_tna' => 8,
            'pending_review' => 4,
            'training_completed' => 24, // Total training courses in their unit
            'avg_participation' => '85%',
        ];

        return view('pages.profile.show', compact('user', 'stats'));
    }

    /**
     * Show the change password page.
     */
    public function changePassword()
    {
        $user = Auth::user();
        return view('pages.profile.change-password', compact('user'));
    }
}
