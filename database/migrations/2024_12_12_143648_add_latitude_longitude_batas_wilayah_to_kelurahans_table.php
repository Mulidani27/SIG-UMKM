<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();  // Menambahkan kolom latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Menambahkan kolom longitude
            $table->text('batas_wilayah')->nullable();  // Menambahkan kolom batas wilayah
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelurahans', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'batas_wilayah']);
        });
    }
};
