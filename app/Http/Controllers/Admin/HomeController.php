<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $role;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->role = 'admin';
    }

    public function index()
    {
        // get the authenticated user
        $admin = Auth::user();

        return view('admin.home')
            ->with([$this->role => $admin]);
    }
}
