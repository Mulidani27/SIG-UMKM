<?php
namespace App\Exports;

use App\Models\Umkm;
use App\Models\Kecamatan; // Model Kecamatan
use App\Models\Kelurahan; // Model Kelurahan
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UMKMExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kecamatanId;

    public function __construct($kecamatanId = null)
    {
        $this->kecamatanId = $kecamatanId;
    }

    public function collection()
    {
        if ($this->kecamatanId) {
            return UMKM::where('kecamatan_id', $this->kecamatanId)->get();
        }
        return UMKM::all();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'Nama Usaha',
            'Jenis Usaha',
            'Kecamatan',
            'Kelurahan',
            'Alamat',
            'Latitude',
            'Longitude',
            'Phone'
        ];
    }

    public function map($umkm): array
    {
        // Mengambil nama kecamatan dan kelurahan berdasarkan id yang disimpan
        $kecamatanName = Kecamatan::find($umkm->kecamatan_id)->nama_kecamatan ?? 'Tidak Diketahui';
        $kelurahanName = Kelurahan::find($umkm->kelurahan_id)->nama_kelurahan ?? 'Tidak Diketahui';

        return [
            $umkm->nama,
            $umkm->nik,
            $umkm->nama_usaha,
            $umkm->jenis_usaha,
            $kecamatanName, // Menggunakan nama_kecamatan
            $kelurahanName, // Menggunakan nama_kelurahan
            $umkm->alamat,
            $umkm->latitude,
            $umkm->longitude,
            $umkm->phone
        ];
    }
}
