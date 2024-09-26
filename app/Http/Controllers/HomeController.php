<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::all();
        return view('kecamatan.kecamatan', compact('kecamatan'));
    }
    
    public function create()
    {
        $kecamatan = Kecamatan::all();
        return view('kecamatan.create', compact('kecamatan'));
    }
   
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string',
            'geojson' => 'nullable|file|mimes:json', // Validasi file GeoJSON (opsional)
        ]);

        // Periksa apakah kecamatan sudah ada dalam database
        $existingCategory = kecamatan::where('nama_kategori', $validatedData['nama_kategori'])->first();

        // Jika kecamatan sudah ada, kembalikan pemberitahuan
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'kecamatan ini sudah ada.');
        }

        // Jika kecamatan belum ada, buat kecamatan baru
        $kecamatan = new kecamatan();
        $kecamatan->nama_kategori = $validatedData['nama_kategori'];

        // Cek apakah ada file GeoJSON baru yang di-upload
        if ($request->hasFile('geojson')) {
            // Hapus file GeoJSON lama jika ada
            if ($kecamatan->geojson_path && $kecamatan->geojson_path != 'geospasial/kota_banjarmasin.geojson') {
                Storage::disk('public')->delete($kecamatan->geojson_path);
            }

            // Dapatkan file yang di-upload
            $file = $request->file('geojson');

            // Tentukan path tujuan, dalam hal ini folder public/geospasial
            $destinationPath = public_path('geospasial');
            
            // Tentukan nama file (opsional, Anda bisa menggunakan nama asli atau mengubahnya)
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Pindahkan file ke folder tujuan
            $file->move($destinationPath, $fileName);

            // Simpan path relatif ke database
            $kecamatan->geojson_path = 'geospasial/' . $fileName;
        }

        // Simpan kecamatan ke database
        if ($kecamatan->save()) {
            return redirect()->route('kecamatan')->with('success', 'kecamatan berhasil ditambahkan');
        } else {
            return back()->withInput()->with('failed', 'Gagal menambahkan kecamatan. Silakan coba lagi');
        }
    }

    public function edit($id)
    {
        $kecamatan = kecamatan::all();
        $kat = kecamatan::findOrFail($id);
        return view('kecamatan.edit', compact('kecamatan', 'kat')); // Mengirim $kat ke view
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_kategori' => 'string|required',
        ]);

        // Cari kecamatan berdasarkan ID yang dikirimkan
        $kecamatan = kecamatan::findOrFail($id);

        // Cek apakah ada kecamatan lain dengan nama yang sama, kecuali kecamatan saat ini
        $existingCategory = kecamatan::where('nama_kategori', $validatedData['nama_kategori'])
                                    ->where('id', '!=', $kecamatan->id)
                                    ->first();

        // Jika kecamatan dengan nama yang sama sudah ada, tampilkan pesan error
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'kecamatan ini sudah ada.');
        }

        // Update nama_kategori
        $kecamatan->nama_kategori = $validatedData['nama_kategori'];

        // Cek apakah ada file GeoJSON baru yang di-upload
        if ($request->hasFile('geojson')) {
            // Hapus file GeoJSON lama jika ada
            if ($kecamatan->geojson_path && $kecamatan->geojson_path != 'geospasial/kota_banjarmasin.geojson') {
                Storage::disk('public')->delete($kecamatan->geojson_path);
            }

            // Dapatkan file yang di-upload
            $file = $request->file('geojson');

            // Tentukan path tujuan, dalam hal ini folder public/geospasial
            $destinationPath = public_path('geospasial');
            
            // Tentukan nama file (opsional, Anda bisa menggunakan nama asli atau mengubahnya)
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Pindahkan file ke folder tujuan
            $file->move($destinationPath, $fileName);

            // Simpan path relatif ke database
            $kecamatan->geojson_path = 'geospasial/' . $fileName;
        }

        // Simpan perubahan (update data di database)
        $updated = $kecamatan->save();

        // Cek apakah pembaruan berhasil
        if ($updated) {
            return redirect()->route('kecamatan')->with('success', 'kecamatan berhasil diperbarui');
        } else {
            return back()->withInput()->with('failed', 'Gagal memperbarui kecamatan. Silakan coba lagi.');
        }
    }

    public function destroy(kecamatan $kecamatan)
    {
        // Daftar kecamatan default yang tidak boleh dihapus
        $defaultCategories = [
            'Kota Banjarmasin'
        ];

        // Periksa apakah kecamatan ini adalah kecamatan default
        if (in_array($kecamatan->nama_kategori, $defaultCategories)) {
            return redirect()->route('kecamatan')->with('failed', 'kecamatan default tidak dapat dihapus.');
        }

        // Periksa apakah ada UMKM yang menggunakan kecamatan ini
        $umkmCount = Umkm::where('kategori_id', $kecamatan->id)->count();

        if ($umkmCount > 0) {
            return redirect()->route('kecamatan')->with('failed', 'kecamatan ini sedang digunakan, Anda tidak bisa menghapusnya.');
        }

        $deleted = $kecamatan->delete();

        if ($deleted) {
            return redirect()->route('kecamatan')->with('success', 'kecamatan berhasil dihapus');
        } else {
            return back()->withInput()->with('failed', 'Gagal menghapus kecamatan. Silakan coba lagi');
        }
    }
}
