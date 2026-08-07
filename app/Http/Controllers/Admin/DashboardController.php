<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'complaints' => Complaint::all(),
        ]);
    }
}
