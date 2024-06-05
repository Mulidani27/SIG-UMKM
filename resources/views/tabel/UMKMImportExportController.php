<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UMKMImport;
use App\Exports\UMKMExport;

class UMKMImportExportController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new UMKMImport, $file);

        return redirect()->route('umkm.umkm')->with('success', 'Data berhasil diimpor.');
    }

    public function exportAll()
    {
        return Excel::download(new UMKMExport(), 'umkm_all.xlsx');
    }

    public function exportByCategory(Request $request)
    {
        $kategoriId = $request->input('category_id');
        return Excel::download(new UMKMExport($kategoriId), 'umkm_by_category.xlsx');
    }
}
