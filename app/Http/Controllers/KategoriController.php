<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        return view('kategori.kategori', compact('kategori'));
    }
    
    public function create()
    {
        $kategori = Kategori::all();
        return view('kategori.create', compact('kategori'));
    }
   
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'required|string',
            'geojson' => 'nullable|file|mimes:json', // Validasi file GeoJSON (opsional)
        ]);

        // Periksa apakah kategori sudah ada dalam database
        $existingCategory = Kategori::where('nama_kategori', $validatedData['nama_kategori'])->first();

        // Jika kategori sudah ada, kembalikan pemberitahuan
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'Kategori ini sudah ada.');
        }

        // Jika kategori belum ada, buat kategori baru
        $kategori = new Kategori();
        $kategori->nama_kategori = $validatedData['nama_kategori'];

        // Cek apakah ada file GeoJSON baru yang di-upload
        if ($request->hasFile('geojson')) {
            // Hapus file GeoJSON lama jika ada
            if ($kategori->geojson_path && $kategori->geojson_path != 'geospasial/kota_banjarmasin.geojson') {
                Storage::disk('public')->delete($kategori->geojson_path);
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
            $kategori->geojson_path = 'geospasial/' . $fileName;
        }

        // Simpan kategori ke database
        if ($kategori->save()) {
            return redirect()->route('kategori')->with('success', 'Kategori berhasil ditambahkan');
        } else {
            return back()->withInput()->with('failed', 'Gagal menambahkan kategori. Silakan coba lagi');
        }
    }

    public function edit($id)
    {
        $kategori = Kategori::all();
        $kat = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori', 'kat')); // Mengirim $kat ke view
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_kategori' => 'string|required',
        ]);

        // Cari kategori berdasarkan ID yang dikirimkan
        $kategori = Kategori::findOrFail($id);

        // Cek apakah ada kategori lain dengan nama yang sama, kecuali kategori saat ini
        $existingCategory = Kategori::where('nama_kategori', $validatedData['nama_kategori'])
                                    ->where('id', '!=', $kategori->id)
                                    ->first();

        // Jika kategori dengan nama yang sama sudah ada, tampilkan pesan error
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'Kategori ini sudah ada.');
        }

        // Update nama_kategori
        $kategori->nama_kategori = $validatedData['nama_kategori'];

        // Cek apakah ada file GeoJSON baru yang di-upload
        if ($request->hasFile('geojson')) {
            // Hapus file GeoJSON lama jika ada
            if ($kategori->geojson_path && $kategori->geojson_path != 'geospasial/kota_banjarmasin.geojson') {
                Storage::disk('public')->delete($kategori->geojson_path);
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
            $kategori->geojson_path = 'geospasial/' . $fileName;
        }

        // Simpan perubahan (update data di database)
        $updated = $kategori->save();

        // Cek apakah pembaruan berhasil
        if ($updated) {
            return redirect()->route('kategori')->with('success', 'Kategori berhasil diperbarui');
        } else {
            return back()->withInput()->with('failed', 'Gagal memperbarui kategori. Silakan coba lagi.');
        }
    }

    public function destroy(Kategori $kategori)
    {
        // Daftar kategori default yang tidak boleh dihapus
        $defaultCategories = [
            'Kota Banjarmasin'
        ];

        // Periksa apakah kategori ini adalah kategori default
        if (in_array($kategori->nama_kategori, $defaultCategories)) {
            return redirect()->route('kategori')->with('failed', 'Kategori default tidak dapat dihapus.');
        }

        // Periksa apakah ada UMKM yang menggunakan kategori ini
        $umkmCount = Umkm::where('kategori_id', $kategori->id)->count();

        if ($umkmCount > 0) {
            return redirect()->route('kategori')->with('failed', 'Kategori ini sedang digunakan, Anda tidak bisa menghapusnya.');
        }

        $deleted = $kategori->delete();

        if ($deleted) {
            return redirect()->route('kategori')->with('success', 'Kategori berhasil dihapus');
        } else {
            return back()->withInput()->with('failed', 'Gagal menghapus kategori. Silakan coba lagi');
        }
    }
}
