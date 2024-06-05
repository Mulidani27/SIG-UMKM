<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Umkm;
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
        ]);

        // Periksa apakah kategori sudah ada dalam database
        $existingCategory = Kategori::where('nama_kategori', $validatedData['nama_kategori'])->first();

        // Jika kategori sudah ada, kembalikan pemberitahuan
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'Kategori ini sudah ada.');
        }

        // Jika kategori belum ada, tambahkan ke database
        $kategori = Kategori::create($validatedData);

        if ($kategori) {
            return redirect()->route('kategori')->with('success', 'Kategori berhasil ditambahkan');
        } else {
            return back()->withInput()->with('failed', 'Gagal menambahkan kategori. Silakan coba lagi');
        }
    }
    public function edit($id)
    {
        // dd($id);
        $kategori = Kategori::all();
        $kateg = Kategori::where('id', $id)->first();
        return view('kategori.edit', compact('kategori', 'kateg'));
    }
    public function update(Request $request, Kategori $kategori)
    {
        $validatedData = $request->validate([
            'nama_kategori' => 'string|required'
        ]);

        // Periksa apakah ada kategori lain dengan nama yang sama kecuali yang sedang diperbarui
        $existingCategory = Kategori::where('nama_kategori', $validatedData['nama_kategori'])
                                    ->where('id', '!=', $kategori->id)
                                    ->first();

        // Jika kategori sudah ada, kembalikan pemberitahuan
        if ($existingCategory) {
            return back()->withInput()->with('failed', 'Kategori ini sudah ada.');
        }

        // Jika tidak ada kategori yang sama, lanjutkan dengan pembaruan
        $updated = $kategori->update($validatedData);

        if ($updated) {
            return redirect()->route('kategori')->with('success', 'Kategori berhasil diperbarui');
        } else {
            return back()->withInput()->with('failed', 'Gagal memperbarui kategori. Silakan coba lagi');
        }
    }
    public function destroy(Kategori $kategori)
    {
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
