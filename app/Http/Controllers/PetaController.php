<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Umkm;
use Illuminate\Support\Facades\Log;

class PetaController extends Controller
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
        // Initialize $selectedKecamatan as null
        $selectedKecamatan = null;
        $geojsonFile = '';
        $centerCoordinates = [-3.315031756474848, 114.5925235326802];
        $zoomLevel = 13;
        if ($request->has('kecamatan')) {
            $selectedKecamatan = Kecamatan::find($request->kecamatan);
            if ($selectedKecamatan && $selectedKecamatan->geojson_path) {
                // Pastikan path hanya mengandung nama file
                $geojsonFile = $selectedKecamatan->geojson_path;
                $fullPath = public_path('geospasial/' . $geojsonFile);
            }
        }
        return view('peta.peta', compact('umkms', 'kecamatan', 'selectedKecamatan',
         'geojsonFile', 'centerCoordinates', 'zoomLevel', 'kelurahan'));
    }
    public function showUmkmDetail($id)
    {
        $kecamatan = Kecamatan::all();
        $umkm = Umkm::find($id);
        
        if (!$umkm) {
            return redirect()->route('peta')->with('error', 'UMKM tidak ditemukan');
        }
        
        return view('peta.peta', compact('umkm', 'kecamatan'));
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
    
        return view('peta.peta', compact('selectedKecamatan', 'kelurahan', 'centerCoordinates', 'zoomLevel'));
    }
}
