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
            'nama_kecamatan' => 'required|string',
            'geojson'        => 'nullable|file|mimes:json',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'batas_wilayah'  => 'nullable|string',
        ]);

        // Periksa apakah kecamatan sudah ada dalam database
        $existingCategory = Kecamatan::where('nama_kecamatan', $validatedData['nama_kecamatan'])->first();

        // Jika kecamatan sudah ada, kembalikan pemberitahuan
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'kecamatan ini sudah ada.');
        }

        // Jika kecamatan belum ada, buat kecamatan baru
        $kecamatan = new Kecamatan();
        $kecamatan->nama_kecamatan = $validatedData['nama_kecamatan'];
        $kecamatan->latitude = $validatedData['latitude'];
        $kecamatan->longitude = $validatedData['longitude'];
        $kecamatan->batas_wilayah = $validatedData['batas_wilayah'];

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
        $kecamatan = Kecamatan::all();
        $kec = Kecamatan::findOrFail($id);
        return view('kecamatan.edit', compact('kecamatan', 'kec')); // Mengirim $kec ke view
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_kecamatan' => 'string|required',
            'geojson'        => 'nullable|file|mimes:json',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
            'batas_wilayah'  => 'nullable|string',
        ]);

        // Cari kecamatan berdasarkan ID yang dikirimkan
        $kecamatan = Kecamatan::findOrFail($id);

        // Cek apakah ada kecamatan lain dengan nama yang sama, kecuali kecamatan saat ini
        $existingCategory = Kecamatan::where('nama_kecamatan', $validatedData['nama_kecamatan'])
                                    ->where('id', '!=', $kecamatan->id)
                                    ->first();

        // Jika kecamatan dengan nama yang sama sudah ada, tampilkan pesan error
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'kecamatan ini sudah ada.');
        }

        // Update nama_kecamatan
        $kecamatan->nama_kecamatan = $validatedData['nama_kecamatan'];
        $kecamatan->latitude = $validatedData['latitude'];
        $kecamatan->longitude = $validatedData['longitude'];
        $kecamatan->batas_wilayah = $validatedData['batas_wilayah'];

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

    public function show(Kecamatan $kecamatan)
    {
        return view('kecamatan.detail', compact('kecamatan'));
    }

    public function destroy(Kecamatan $kecamatan)
    {
        // Daftar kecamatan default yang tidak boleh dihapus
        $defaultCategories = [
            'Kota Banjarmasin'
        ];

        // Periksa apakah kecamatan ini adalah kecamatan default
        if (in_array($kecamatan->nama_kecamatan, $defaultCategories)) {
            return redirect()->route('kecamatan')->with('failed', 'kecamatan default tidak dapat dihapus.');
        }

        // Periksa apakah ada UMKM yang menggunakan kecamatan ini
        $umkmCount = Umkm::where('kecamatan_id', $kecamatan->id)->count();

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