<?php

namespace App\Http\Controllers;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $umkms = Umkm::query();
        if ($request->has('kecamatan')) {
            $umkms->where('kecamatan_id', $request->kecamatan);
        }
        $umkms = $umkms->get();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        // Inisialisasi $selectedKecamatan sebagai null
        $selectedKecamatan = null;
        $geojsonFile = '';
        $centerCoordinates = [-3.315031756474848, 114.5925235326802];
        // Ambil path GeoJSON untuk Kota Banjarmasin
        $zoomLevel = 13;
        if ($request->has('kecamatan')) {
            $selectedKecamatan = Kecamatan::find($request->kecamatan);
            if ($selectedKecamatan && $selectedKecamatan->geojson_path) {
                // Pastikan path hanya mengandung nama file
                $geojsonFile = $selectedKecamatan->geojson_path;
                $fullPath = public_path('geospasial/' . $geojsonFile);
            }
        }
        return view('landing.landing', compact('umkms', 'kecamatan', 'selectedKecamatan',
         'geojsonFile', 'centerCoordinates', 'zoomLevel', 'kelurahan'));
    }
    
    public function showUmkmList()
    {
        $kecamatan = Kecamatan::all();
        $umkms = Umkm::all();
        
        $dataUmkmPerKecamatan = Kecamatan::leftJoin('umkms', 'kecamatans.id', '=', 'umkms.kecamatan_id')
                                ->selectRaw('kecamatans.nama_kecamatan, count(umkms.id) as jumlah')
                                ->groupBy('kecamatans.nama_kecamatan')
                                ->orderBy('kecamatans.nama_kecamatan')
                                ->get();
        
        // Hitung total UMKM untuk menghitung persentase
        $totalUmkm = $dataUmkmPerKecamatan->sum('jumlah');

        // Tentukan apakah ada data UMKM
        $isEmpty = $totalUmkm == 0;

        return view('landing.landingumkm', compact('umkms', 'dataUmkmPerKecamatan', 'kecamatan', 'totalUmkm', 'isEmpty'));
    }

    public function showUmkmDetail($id)
    {
        $kecamatan = Kecamatan::all();
        $umkm = Umkm::find($id);
        
        if (!$umkm) {
            return redirect()->route('landing')->with('error', 'UMKM tidak ditemukan');
        }
        
        return view('landing.landingdetail', compact('umkm', 'kecamatan'));
    }

    public function showMapByKecamatan($kecamatanId, Request $request)
    {
        // Dapatkan kecamatan yang dipilih
        $selectedKecamatan = Kecamatan::findOrFail($kecamatanId);
    
        // Filter kelurahan hanya yang sesuai dengan kecamatan yang dipilih
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatanId)->get();
    
        // Data lain untuk peta
        $centerCoordinates = [-3.315031756474848, 114.5925235326802];
        $zoomLevel = 13;
    
        return view('landing.landing', compact('selectedKecamatan', 'kelurahan', 'centerCoordinates', 'zoomLevel'));
    }
 }
