<?php
namespace App\Exports;

use App\Models\Umkm;
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
        return [
            $umkm->nama,
            $umkm->nik,
            $umkm->nama_usaha,
            $umkm->jenis_usaha,
            $umkm->kecamatan_id,
            $umkm->kelurahan_id,
            $umkm->alamat,
            $umkm->latitude,
            $umkm->longitude,
            $umkm->phone
        ];
    }
}
