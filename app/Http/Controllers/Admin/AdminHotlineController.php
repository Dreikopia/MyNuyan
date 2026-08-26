<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminHotlineController extends Controller
{
    public function index()
    {
        return view('admin.hotlines.index');
    }
}
