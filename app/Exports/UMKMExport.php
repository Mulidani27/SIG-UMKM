<?php

namespace App\Exports;

use App\Models\UMKM;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UMKMExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kategoriId;

    public function __construct($kategoriId = null)
    {
        $this->kategoriId = $kategoriId;
    }

    public function collection()
    {
        if ($this->kategoriId) {
            return UMKM::where('kategori_id', $this->kategoriId)->get();
        }
        return UMKM::all();
    }

    public function headings(): array
    {
        return [
            'Nama Usaha',
            'Nama Pemilik',
            'Kategori ID',
            'Latitude',
            'Longitude',
            'Phone',
            'Alamat'
        ];
    }

    public function map($umkm): array
    {
        return [
            $umkm->nama_usaha,
            $umkm->nama_pemilik,
            $umkm->kategori_id,
            $umkm->latitude,
            $umkm->longitude,
            $umkm->phone,
            $umkm->alamat,
        ];
    }
}
