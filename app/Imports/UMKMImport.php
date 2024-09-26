<?php
namespace App\Imports;

use App\Models\Umkm;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UMKMImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari kecamatan berdasarkan nama
        $kecamatan = Kecamatan::where('nama_kecamatan', $row['kecamatan'])->first();
        if (!$kecamatan) {
            throw new \Exception('Kecamatan ' . $row['kecamatan'] . ' tidak ditemukan.');
        }
        // Cari kelurahan berdasarkan nama
        $kelurahan = Kelurahan::where('nama_kelurahan', $row['kelurahan'])
            ->where('kecamatan_id', $kecamatan->id)
            ->first();
        if (!$kelurahan) {
            throw new \Exception('Kelurahan ' . $row['kelurahan'] . ' tidak ditemukan di kecamatan ' . $row['kecamatan']);
        }
        $validator = Validator::make($row, [
            'nama'        => 'string|required',
            'nik'         => 'nullable|string',
            'nama_usaha'  => 'nullable|string',
            'jenis_usaha' => 'nullable|string',
            'alamat'      => 'string|required',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'phone'       => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator, response()->json($validator->errors(), 422));
        }

        try {
            $umkm = new Umkm([
                'nama'       => $row['nama'],
                'nik'        => $row['nik'],
                'nama_usaha' => $row['nama_usaha'],
                'jenis_usaha'=> $row['jenis_usaha'],
                'kecamatan_id'=> $kecamatan->id,
                'kelurahan_id'=> $kelurahan->id,
                'alamat'     => $row['alamat'],
                'latitude'   => $row['latitude'] ?? null,
                'longitude'  => $row['longitude'] ?? null,
                'phone'      => $row['phone'],
            ]);
            $umkm->save();
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengimpor data UMKM: ' . $e->getMessage());
        }
        return $umkm;
    }
}
