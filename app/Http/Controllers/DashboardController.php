<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return view('dashboards.admin');
            case 'guru':
                return view('dashboards.guru');
            case 'siswa':
                return view('dashboards.siswa');
            default:
                abort(403, 'Role tidak terdefinisi');
        }
    }
}
