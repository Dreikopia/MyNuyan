<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboard)
    {

        return view('admin.dashboard', [
            'monthlyComplaints' => $dashboard->monthlyComplaints(),
            'categoryComplaints' => $dashboard->complaintsByCategory(),
        ]);
    }
}
