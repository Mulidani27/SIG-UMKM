<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UMKMExport;
use App\Models\Kategori;

class UMKMExportController extends Controller
{
    public function exportAll()
    {
        return Excel::download(new UMKMExport(), 'umkm_all.xlsx');
    }

    public function exportByCategory(Request $request)
    {
        $kategoriId = $request->input('kategori_id');
        
        // Ambil nama kategori berdasarkan ID
        $kategori = Kategori::find($kategoriId);
        $kategoriNama = $kategori ? $kategori->nama_kategori : 'unknown';
        
        // Bentuk nama file ekspor
        $fileName = 'umkm_' . str_replace(' ', '_', strtolower($kategoriNama)) . '.xlsx';
        
        return Excel::download(new UMKMExport($kategoriId), $fileName);
    }
}
