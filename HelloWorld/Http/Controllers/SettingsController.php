<?php

namespace Modules\HelloWorld\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:admin');
    }
    
    /**
     * Show the Hello World settings page.
     */
    public function index()
    {
        return view('helloworld::settings');
    }
} 