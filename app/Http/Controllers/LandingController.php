<?php

namespace App\Http\Controllers;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('landing.landing', compact('kategori'));
    }
}
