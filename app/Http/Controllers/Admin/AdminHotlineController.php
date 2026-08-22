<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminHotlineController extends Controller
{
    public function index()
    {
        return view('admin.hotlines.index');
    }
}
