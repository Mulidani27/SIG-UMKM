<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Umkm;
use App\Models\Kategori;
use App\Models\Pengguna as pengguna;
class DashboardController extends Controller
{
    public function index()
    {
        $umkms = Umkm::all();
        $User = pengguna::first();
        $kategori = Kategori::all();
        return view('dashboard.dashboard', compact('umkms', 'User', 'kategori'));
    } 
}
