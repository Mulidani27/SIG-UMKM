<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->nullable();
            $table->string('nama_usaha')->nullable();
            $table->string('jenis_usaha')->nullable();
            $table->unsignedBigInteger('kecamatan_id');
            $table->unsignedBigInteger('kelurahan_id');  // Menempatkan setelah kecamatan_id
            $table->string('alamat');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

            // Tambahkan index pada kolom kecamatan_id dan kelurahan_id
            $table->index('kecamatan_id');
            $table->index('kelurahan_id');  // Typo diperbaiki

            // Definisikan foreign key constraint
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('cascade');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('cascade');  // Pastikan nama tabel benar
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkms');
    }
};
