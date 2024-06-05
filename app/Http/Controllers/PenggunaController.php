<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Kategori;
class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = Pengguna::all();
        $kategori = Kategori::all();
        return view('pengguna.pengguna', compact('pengguna', 'kategori'));
    }
    public function create()
    {
        $kategori = Kategori::all();
        return view('pengguna.create', compact('kategori'));
    }
    public function store(Request $request, Pengguna $pengguna){
        $validasiData = $request->validate([
            'nama'      => 'string|required',
            'email'     => 'string|required|unique:penggunas,email,' . $pengguna->id,
            'password'  => 'string|required|min:7',
            'phone'     => 'string|required|min:10|max:15',
            'alamat'    => 'string|required'
        ]);
        $pengguna = Pengguna::create($validasiData);
        if ($pengguna) {
            return redirect()->route('pengguna')->with('success', 'Pengguna berhasil ditambahkan');
        } else {
            return back()->withInput()->with('failed', 'Gagal menambahkan pengguna. Silakan coba lagi');
        }
    }
    public function edit(Pengguna $pengguna)
    {
        $kategori = Kategori::all();
        return view('pengguna.edit', compact('pengguna', 'kategori'));
    }
    public function update(Request $request, Pengguna $pengguna)
    {
        $validasiData = $request->validate([
            'nama'      => 'string|required',
            'email'     => 'string|required|unique:penggunas,email,' . $pengguna->id,
            'password'  => 'string|required|min:7',
            'phone'     => 'string|required|min:10|max:15',
            'alamat'    => 'string|required'
        ]);
        $updated = $pengguna->update($validasiData);
        if ($updated) {
            return redirect()->route('pengguna')->with('success', 'Pengguna berhasil diperbarui');
        } else {
            return back()->withInput()->with('failed', 'Gagal memperbarui pengguna. Silakan coba lagi');
        }
    }
    public function destroy(Pengguna $pengguna)
    {
        $deleted = $pengguna->delete();
        if ($deleted) {
            return redirect()->route('pengguna')->with('success', 'Pengguna berhasil dihapus');
        } else {
            return back()->withInput()->with('failed', 'Gagal menghapus pengguna. Silakan coba lagi');
        }
    }
}