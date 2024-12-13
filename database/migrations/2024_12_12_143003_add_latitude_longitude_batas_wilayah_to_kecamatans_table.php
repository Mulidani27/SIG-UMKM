<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeBatasWilayahToKecamatansTable extends Migration
{
    public function up()
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();  // Menambahkan kolom latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Menambahkan kolom longitude
            $table->text('batas_wilayah')->nullable();  // Menambahkan kolom batas wilayah
        });
    }

    public function down()
    {
        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'batas_wilayah']);
        });
    }
}
