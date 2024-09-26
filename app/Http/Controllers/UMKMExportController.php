<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UMKMExport;
use App\Models\Kecamatan;

class UMKMExportController extends Controller
{
    public function exportAll()
    {
        return Excel::download(new UMKMExport(), 'umkm_all.xlsx');
    }

    public function exportByCategory(Request $request)
    {
        $kategoriId = $request->input('kecamatan_id');
        
        // Ambil nama kecamatan berdasarkan ID
        $kecamatan = Kecamatan::find($kategoriId);
        $kategoriNama = $kecamatan ? $kecamatan->nama_kategori : 'unknown';
        
        // Bentuk nama file ekspor
        $fileName = 'umkm_' . str_replace(' ', '_', strtolower($kategoriNama)) . '.xlsx';
        
        return Excel::download(new UMKMExport($kategoriId), $fileName);
    }
}
