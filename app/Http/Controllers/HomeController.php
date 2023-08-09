<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('home');
    }

    /**
     * Show the access denied page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function accessDenied(){
        return view('access-denied');
    }
}
