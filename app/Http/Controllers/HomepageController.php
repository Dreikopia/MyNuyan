<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function index()
    {
        // $totalComplaints = Auth::user()->complaints()->count();
        // $resolvedComplaints = Auth::user()->complaints()->where('status', 'resolved')->count();
        // $activeComplaints = Auth::user()->complaints()->where('status', 'active')->count();
        // $pendingComplaints = Auth::user()->complaints()->where('status', 'pending')->count();

        return view('resident.homepage');
    }
}
