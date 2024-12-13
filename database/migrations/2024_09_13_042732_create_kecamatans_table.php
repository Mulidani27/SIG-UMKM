<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKecamatansTable extends Migration
{
    public function up()
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan');
            $table->string('geojson_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kecamatans');
    }
}