<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Umkm;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $umkms = Umkm::query();
        if ($request->has('kecamatan')) {
            $umkms->where('kecamatan_id', $request->kecamatan);
        }
        $umkms = $umkms->get();
        $kecamatan = Kecamatan::withCount('umkms')->get();
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
        return view('dashboard.dashboard', compact('umkms', 'kecamatan', 'selectedKecamatan',
         'geojsonFile', 'centerCoordinates', 'zoomLevel', 'kelurahan'));
    }
}
