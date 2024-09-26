<?php
namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::all();
        // Ambil kecamatan dan jumlah UMKM per kecamatan
        $dataUmkmPerKecamatan = Kecamatan::leftJoin('umkms', 'kecamatans.id', '=', 'umkms.kecamatan_id')
                            ->selectRaw('kecamatans.nama_kecamatan, count(umkms.id) as jumlah')
                            ->groupBy('kecamatans.nama_kecamatan')
                            ->orderBy('kecamatans.nama_kecamatan')
                            ->get();

        // Hitung total UMKM untuk menghitung persentase
        $totalUmkm = $dataUmkmPerKecamatan->sum('jumlah');

        // Kirim data ke view
        return view('statistik.statistik', compact('dataUmkmPerKecamatan', 'kecamatan', 'totalUmkm'));
    }
}
