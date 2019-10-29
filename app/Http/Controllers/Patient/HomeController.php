<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        // any registered user can access patient area
        $this->middleware('role:admin,doctor,patient');
    }

    public function index()
    {
        return view('patient.home');
    }
}
