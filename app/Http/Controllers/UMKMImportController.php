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
        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // Maksimal 2MB
        ]);

        $file = $request->file('file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        // Membuat nama file sementara
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Tentukan direktori penyimpanan
        $destinationPath = public_path('DataUMKM');

        // Periksa apakah direktori ada, jika tidak buat direktori
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);  // Membuat folder dengan permission yang sesuai
        }

        // Simpan file di public/DataUMKM
        $file->move($destinationPath, $fileName);

        try {
            // Mengimpor data dari file Excel
            Excel::import(new UMKMImport, $destinationPath . '/' . $fileName);
        } catch (SheetNotFoundException $e) {
            return redirect()->route('umkm')->with('error', 'Sheet tidak ditemukan dalam file Excel');
        } catch (NoTypeDetectedException $e) {
            return redirect()->route('umkm')->with('error', 'Tipe data tidak terdeteksi dalam file Excel');
        } catch (\Throwable $th) {
            return redirect()->route('umkm')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $th->getMessage());
        } finally {
            // Hapus file setelah pemrosesan, jika perlu
            unlink($destinationPath . '/' . $fileName);
        }

        return redirect()->route('umkm')->with('success', 'Data berhasil diimpor');
    }
}
