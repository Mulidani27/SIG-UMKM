<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LupapwController extends Controller
{
    public function index(){
        return view('auth.lupapw');
    }
}