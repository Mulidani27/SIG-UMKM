<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan jika menggunakan factory

class Umkm extends Model
{
    use HasFactory; // Tambahkan jika menggunakan factory

    protected $fillable = [
        'nama',
        'nik',
        'nama_usaha',
        'jenis_usaha',
        'kecamatan_id',
        'kelurahan_id',
        'alamat',
        'latitude',
        'longitude',
        'phone',
        'foto',
    ];

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    // Relasi ke Kelurahan
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id'); // Explicit foreign key
    }
}
