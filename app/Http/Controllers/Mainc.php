<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Mainc extends Controller
{
    
public function index()
    {
        return view('index');
    }

    public function success()
    {
        return view('success');
    }
}
