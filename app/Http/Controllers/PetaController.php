<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Umkm;

class PetaController extends Controller
{
    public function index(Request $request)
    {
        // Query awal untuk mendapatkan semua data UMKM
        $umkms = Umkm::query();

        // Jika kategori yang dipilih disertakan dalam request, filter data UMKM berdasarkan kategori
        if ($request->has('kategori')) {
            $umkms->where('kategori_id', $request->kategori);
        }

        $umkms = $umkms->get(); // Eksekusi query dan dapatkan data UMKM yang sudah difilter
        $kategori = Kategori::all(); // Mendapatkan semua data kategori

        // Jika kategori ada dalam request, ambil data kategori tersebut
        $selectedKategori = null;
        if ($request->has('kategori')) {
            $selectedKategori = Kategori::find($request->kategori);
        }

        return view('peta.peta', compact('umkms', 'kategori', 'selectedKategori'));
    }
}
