<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        // only admin can access admin area
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('admin.home');
    }
}
