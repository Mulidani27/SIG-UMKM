<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory; // Tambahkan jika menggunakan factory

    protected $fillable = ['nama_kelurahan', 'kecamatan_id', 'latitude', 'longitude', 'batas_wilayah'];

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
