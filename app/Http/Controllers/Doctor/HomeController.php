<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        // only admin and doctors can access doctor area
        $this->middleware('role:admin,doctor');
    }

    public function index()
    {
        return view('doctor.home');
    }
}
