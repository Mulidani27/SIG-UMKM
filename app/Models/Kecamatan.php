<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kecamatan', 'geojson_path'];

    // Relasi ke Kelurahan
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
    }

    // Relasi ke UMKM
    public function umkms()
    {
        return $this->hasMany(Umkm::class, 'kecamatan_id');
    }
}
