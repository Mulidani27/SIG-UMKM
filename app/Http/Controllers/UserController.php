<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $kecamatan = Kecamatan::all();
        $users = User::all(); // Mengambil semua pengguna
        return view('user.user', compact('users', 'kecamatan')); // Ubah sesuai dengan nama view Anda
    }

    // Menampilkan formulir untuk menambahkan pengguna
    public function create()
    {
        $kecamatan = Kecamatan::all();
        return view('user.create', compact('kecamatan')); // Ubah sesuai dengan nama view Anda
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('user')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Menampilkan formulir untuk mengedit pengguna
    public function edit($id)
    {
        $kecamatan = Kecamatan::all();
        $user = User::findOrFail($id); // Mencari pengguna berdasarkan ID
        return view('user.edit', compact('user', 'kecamatan')); // Ubah sesuai dengan nama view Anda
    }

    // Memperbarui pengguna
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|string|min:6',
            'phone' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // Hanya hash password jika diisi
        }

        $user->phone = $request->phone;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('user')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Cek apakah user yang dihapus adalah akun default
        if ($user->email === 'admin@gmail.com') {
            return redirect()->route('user')->with('failed', 'Akun pengguna default tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('user')->with('success', 'Pengguna berhasil dihapus.');
    }

}
