<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UMKMImport;
use Maatwebsite\Excel\Exceptions\SheetNotFoundException;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;
use Illuminate\Support\Facades\Storage;

class UMKMImportController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $fileName = $file->getClientOriginalName();

        // Change temporary file extension to .xlsx
        $tempFileName = $fileName . '.xlsx';
        $file->storeAs('uploads', $tempFileName);

        try {
            Excel::import(new UMKMImport, storage_path('app/uploads/' . $tempFileName));
        } catch (SheetNotFoundException $e) {
            return redirect()->route('umkm')->with('error', 'Sheet tidak ditemukan dalam file Excel.');
        } catch (NoTypeDetectedException $e) {
            return redirect()->route('umkm')->with('error', 'Tipe data tidak terdeteksi dalam file Excel.');
        } catch (\Throwable $th) {
            return redirect()->route('umkm')->with('error', 'Terjadi kesalahan saat mengimpor data.');
        }

        // Remove temporary file after import
        Storage::delete('uploads/' . $tempFileName);

        return redirect()->route('umkm')->with('success', 'Data berhasil diimpor.');
    }
}
