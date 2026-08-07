<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class HomepageController extends Controller
{
    public function index()
    {
        return view('resident.homepage');
    }
}
