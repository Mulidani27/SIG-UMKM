<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Umkm;

class UmkmController extends Controller
{
    public function index()
    {
        $umkm = Umkm::all();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        return view('umkm.umkm', compact('umkm', 'kecamatan', 'kelurahan'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        return view('umkm.create', compact('kecamatan', 'kelurahan'));
    }

    public function store(Request $request)
    {
        $validasiData = $request->validate([
            'nama'        => 'string|required',
            'nik'         => 'nullable|string',
            'nama_usaha'  => 'nullable|string',
            'jenis_usaha' => 'nullable|string',
            'kecamatan_id'=> 'required|exists:kecamatans,id',
            'kelurahan_id'=> 'required|exists:kelurahans,id', // Memastikan kelurahan valid
            'alamat'      => 'string|required',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'phone'       => 'nullable|string|min:10|max:15',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Pengecekan koordinat berdasarkan kecamatan dan kelurahan
        $kecamatan = Kecamatan::find($request->input('kecamatan_id'));
        $kelurahan = Kelurahan::find($request->input('kelurahan_id'));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ($kecamatan && !$this->checkCoordinates($kecamatan->nama_kecamatan, $latitude, $longitude)) {
            return back()->withInput()->with('failed', 'Koordinat di luar kecamatan.');
        }
        if ($kelurahan && !$this->checkCoordinates($kelurahan->nama_kelurahan, $latitude, $longitude)) {
            return back()->withInput()->with('failed', 'Koordinat di luar kelurahan.');
        }

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            // Tentukan lokasi penyimpanan relatif ke folder public
            $destinationPath = 'image'; // Ini akan merujuk ke folder public/image

            // Pindahkan file ke direktori yang ditentukan
            $fileName = time() . '_' . $file->getClientOriginalName(); // Nama unik untuk file
            $file->move(public_path($destinationPath), $fileName);

            // Simpan nama file ke database
            $validasiData['foto'] = $fileName;
        }

        // Buat UMKM baru
        $umkm = Umkm::create($validasiData);
        return $umkm ? redirect()->route('umkm')->with('success', 'UMKM berhasil ditambahkan') :
                       back()->withInput()->with('failed', 'Gagal menambahkan UMKM.');
    }

    public function edit(Umkm $umkm)
    {
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        return view('umkm.edit', compact('umkm', 'kecamatan', 'kelurahan'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $validasiData = $request->validate([
            'nama'        => 'string|required',
            'nik'         => 'nullable|string',
            'nama_usaha'  => 'nullable|string',
            'jenis_usaha' => 'nullable|string',
            'kecamatan_id'=> 'required|exists:kecamatans,id',
            'kelurahan_id'=> 'required|exists:kelurahans,id', // Memastikan kelurahan valid
            'alamat'      => 'string|required',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'phone'       => 'nullable|string|min:10|max:15',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Pengecekan koordinat
        $kecamatan = Kecamatan::find($request->input('kecamatan_id'));
        $kelurahan = Kelurahan::find($request->input('kelurahan_id'));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ($kecamatan && !$this->checkCoordinates($kecamatan->nama_kecamatan, $latitude, $longitude)) {
            return back()->withInput()->with('failed', 'Koordinat di luar kecamatan.');
        }
        if ($kelurahan && !$this->checkCoordinates($kelurahan->nama_kelurahan, $latitude, $longitude)) {
            return back()->withInput()->with('failed', 'Koordinat di luar kelurahan.');
        }

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($umkm->foto) {
                $oldImagePath = public_path('image/' . $umkm->foto);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('foto');
            $destinationPath = 'image'; // Folder public/image
            $fileName = time() . '_' . $file->getClientOriginalName(); // Nama unik untuk file
            $file->move(public_path($destinationPath), $fileName);

            // Simpan nama file baru ke database
            $validasiData['foto'] = $fileName;
        } else {
            $validasiData['foto'] = $umkm->foto; // Pertahankan foto lama jika tidak ada yang baru
        }

        // Perbarui UMKM
        $updated = $umkm->update($validasiData);
        return $updated ? redirect()->route('umkm')->with('success', 'UMKM berhasil diperbarui') :
                          back()->withInput()->with('failed', 'Gagal memperbarui UMKM.');
    }

    public function destroy(Umkm $umkm)
    {
        if ($umkm->foto) {
            Storage::delete('umkm_photos/' . $umkm->foto);
        }
        $deleted = $umkm->delete();
        return $deleted ? redirect()->route('umkm')->with('success', 'UMKM berhasil dihapus') :
                          back()->withInput()->with('failed', 'Gagal menghapus UMKM.');
    }

    public function show(Umkm $umkm)
    {
        $kecamatan = Kecamatan::all();
        return view('umkm.detail', compact('umkm', 'kecamatan'));
    }

    // Fungsi pengecekan koordinat
    private function checkCoordinates($nama_kecamatan, $latitude, $longitude)
    {
        $coordinates = [
            'Banjarmasin Utara' => [-3.3190513133673680, -3.2673603371098352, 114.5630938084325408, 114.6398500457430600],
            'Banjarmasin Tengah' => [-3.3328934462754769, -3.3039638433566552, 114.5711678848900874, 114.6069905844479990],
            'Banjarmasin Selatan' => [-3.3821400040578742, -3.3276707189285162, 114.5222812861525199, 114.6267710766239816],
            'Banjarmasin Barat' => [-3.3474377491527321, -3.2929059188730321, 114.5479693340175800, 114.5893565860376384],
            'Banjarmasin Timur' => [-3.3503296109227558, -3.3030794716402738, 114.5996158513904675, 114.6595890755754681],
            'Kota Banjarmasin' => [-3.3821391999999260, -3.2672272999999450, 114.5219479000001002, 114.6595898000000489],
        ];

        if (isset($coordinates[$nama_kecamatan])) {
            [$latMin, $latMax, $longMin, $longMax] = $coordinates[$nama_kecamatan];

            if ($latitude < $latMin || $latitude > $latMax || $longitude < $longMin || $longitude > $longMax) {
                return false;
            }
        }

        return true;
    }
    
    public function getKelurahan($kecamatan_id)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatan_id)->get();
        return response()->json($kelurahan);
    }

}
