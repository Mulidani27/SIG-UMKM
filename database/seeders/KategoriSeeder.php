<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    public function run()
    {
        $kecamatans = [
            [
                'id' => 1, 
                'nama_kecamatan' => 'Kota Banjarmasin', 
                'geojson_path' => 'geospasial/kota_banjarmasin'
            ]
        ];

        foreach ($kecamatans as $kecamatan) {
            if (!DB::table('kecamatans')->where('id', $kecamatan['id'])->exists()) {
                DB::table('kecamatans')->insert([
                    'id' => $kecamatan['id'],
                    'nama_kecamatan' => $kecamatan['nama_kecamatan'],
                    'geojson_path' => $kecamatan['geojson_path'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
