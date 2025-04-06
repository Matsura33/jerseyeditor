<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ornament;
use App\Models\Jersey;
use App\Models\User;
use App\Models\UserJersey;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'ornaments' => Ornament::count(),
            'jerseys' => Jersey::count(),
            'users' => User::count(),
            'userJerseys' => UserJersey::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 