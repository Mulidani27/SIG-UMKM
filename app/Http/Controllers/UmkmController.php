<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Umkm;
class UmkmController extends Controller
{
    public function index()
    {
        $umkm = Umkm::all();
        $kategori = Kategori::all();
        return view('umkm.umkm', compact('umkm', 'kategori'));
    }
    public function create()
    {
        $kategori = Kategori::all();
        return view('umkm.create', compact('kategori'));
    }
    public function store(Request $request)
    {
        $validasiData = $request->validate([
            'nama_usaha'   => 'string|required',
            'nama_pemilik' => 'string|required',
            'kategori_id'  => 'required|exists:kategoris,id',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'phone'        => 'string|required|min:10|max:15',
            'alamat'       => 'string|required',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('latitude')) {
            $validasiData['latitude'] = $request->latitude;
        }

        if ($request->filled('longitude')) {
            $validasiData['longitude'] = $request->longitude;
        }

        if ($request->hasFile('foto')) {
            $validasiData['foto'] = $request->file('foto')->store('umkm_photos');
        }

        $umkm = Umkm::create($validasiData);

        if ($umkm) {
            return redirect()->route('umkm')->with('success', 'UMKM berhasil ditambahkan');
        } else {
            return back()->withInput()->with('failed', 'Gagal menambahkan UMKM. Silakan coba lagi');
        }
    }
    public function edit(Umkm $umkm)
    {
        $kategori = Kategori::all();
        return view('umkm.edit', compact('umkm', 'kategori'));
    }
    public function update(Request $request, Umkm $umkm)
    {
        $validasiData = $request->validate([
            'nama_usaha'   => 'string|required',
            'nama_pemilik' => 'string|required',
            'kategori_id'  => 'required|exists:kategoris,id',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'phone'        => 'string|required|min:10|max:15',
            'alamat'       => 'string|required',
            'foto'         => 'nullable|image',
        ]);

        if ($request->filled('latitude')) {
            $validasiData['latitude'] = $request->latitude;
        }

        if ($request->filled('longitude')) {
            $validasiData['longitude'] = $request->longitude;
        }

        if ($request->hasFile('foto')) {
            $validasiData['foto'] = $request->file('foto')->store('umkm_photos');
        }

        $updated = $umkm->update($validasiData);

        if ($updated) {
            return redirect()->route('umkm')->with('success', 'UMKM berhasil diperbarui');
        } else {
            return back()->withInput()->with('failed', 'Gagal memperbarui UMKM. Silakan coba lagi');
        }
    }
    public function destroy(Umkm $umkm)
    {
        $deleted = $umkm->delete();

        if ($deleted) {
            return redirect()->route('umkm')->with('success', 'UMKM berhasil dihapus');
        } else {
            return back()->withInput()->with('failed', 'Gagal menghapus UMKM. Silakan coba lagi');
        }
    }
    public function show(Umkm $umkm)
    {
        $kategori = Kategori::all();
        return view('umkm.detail', compact('umkm', 'kategori'));
    }

}
