<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Umkm;
use Illuminate\Support\Facades\Storage;

class KelurahanController extends Controller
{
    public function index(Request $request)
    {
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();

        // Jika ada kecamatan yang dipilih, filter kelurahan berdasarkan kecamatan
        if ($request->has('kecamatan_id')) {
            $kelurahan = Kelurahan::where('kecamatan_id', $request->input('kecamatan_id'))->get();
        }

        return view('kelurahan.kelurahan', compact('kelurahan', 'kecamatan'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::all(); // Tambahkan data kecamatan di view create
        return view('kelurahan.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelurahan' => 'required|string|unique:kelurahans,nama_kelurahan',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'geojson' => 'nullable|file|mimes:json|max:2048', // Maksimal ukuran file 2MB
        ]);

        // Buat kelurahan baru
        $kelurahan = new Kelurahan();
        $kelurahan->nama_kelurahan = $validatedData['nama_kelurahan'];
        $kelurahan->kecamatan_id = $validatedData['kecamatan_id'] ?? null;

        // Cek apakah ada file GeoJSON yang di-upload
        if ($request->hasFile('geojson')) {
            $fileName = $this->handleGeojsonUpload($request->file('geojson'));
            $kelurahan->geojson_path = 'geospasial/' . $fileName;
        }

        if ($kelurahan->save()) {
            return redirect()->route('kelurahan')->with('success', 'Kelurahan berhasil ditambahkan');
        }

        return back()->withInput()->with('failed', 'Gagal menambahkan kelurahan. Silakan coba lagi.');
    }

    public function edit($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kecamatan = Kecamatan::all(); // Mengambil data kecamatan untuk dropdown
        return view('kelurahan.edit', compact('kelurahan', 'kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_kelurahan' => 'required|string|unique:kelurahans,nama_kelurahan,' . $id,
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'geojson' => 'nullable|file|mimes:json|max:2048',
        ]);

        $kelurahan = Kelurahan::findOrFail($id);
        $kelurahan->nama_kelurahan = $validatedData['nama_kelurahan'];
        $kelurahan->kecamatan_id = $validatedData['kecamatan_id'] ?? null;

        // Cek apakah ada file GeoJSON baru yang di-upload
        if ($request->hasFile('geojson')) {
            // Hapus GeoJSON lama jika bukan file default
            if ($kelurahan->geojson_path && $kelurahan->geojson_path != 'geospasial/kota_banjarmasin.geojson') {
                Storage::disk('public')->delete($kelurahan->geojson_path);
            }
            $fileName = $this->handleGeojsonUpload($request->file('geojson'));
            $kelurahan->geojson_path = 'geospasial/' . $fileName;
        }

        if ($kelurahan->save()) {
            return redirect()->route('kelurahan')->with('success', 'Kelurahan berhasil diperbarui');
        }

        return back()->withInput()->with('failed', 'Gagal memperbarui kelurahan. Silakan coba lagi.');
    }

    public function destroy(Kelurahan $kelurahan)
    {
        $defaultCategories = ['Kota Banjarmasin'];

        if (in_array($kelurahan->nama_kelurahan, $defaultCategories)) {
            return redirect()->route('kelurahan')->with('failed', 'Kelurahan default tidak dapat dihapus.');
        }

        $umkmCount = Umkm::where('kelurahan_id', $kelurahan->id)->count();

        if ($umkmCount > 0) {
            return redirect()->route('kelurahan')->with('failed', 'Kelurahan ini sedang digunakan, Anda tidak bisa menghapusnya.');
        }

        if ($kelurahan->delete()) {
            return redirect()->route('kelurahan')->with('success', 'Kelurahan berhasil dihapus');
        }

        return back()->with('failed', 'Gagal menghapus kelurahan. Silakan coba lagi.');
    }

    private function handleGeojsonUpload($file)
    {
        $destinationPath = public_path('geospasial');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);
        return $fileName;
    }
}
