<?php

namespace App\Imports;

use App\Models\Umkm;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UMKMImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'nama_usaha'   => 'required',
            'nama_pemilik' => 'required',
            'kategori'     => 'required',
            'phone'        => 'required',
            'alamat'       => 'required',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        try {
            // Simpan data ke dalam database
            $umkm = new Umkm([
                'nama_usaha'   => $row['nama_usaha'],
                'nama_pemilik' => $row['nama_pemilik'],
                'kategori'     => $row['kategori'],
                'latitude'     => $row['latitude']??null,
                'longitude'    => $row['longitude']??null, 
                'phone'        => $row['phone'],
                'alamat'       => $row['alamat'],
            ]);
            $umkm->save();

        } catch (\Exception $e) {
            throw new \Exception('Gagal mengimpor data UMKM. Silakan cek log untuk detail kesalahan.');
        }

        return $umkm;
    }
}
