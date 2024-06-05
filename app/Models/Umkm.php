<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Umkm extends Model
{
    protected $fillable = [
        'nama_usaha',
        'nama_pemilik',
        'kategori_id',
        'latitude',
        'longitude',
        'phone',
        'alamat',
        'foto',
    ];
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}
