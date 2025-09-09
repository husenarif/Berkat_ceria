<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuanProdukTable extends Migration
{
    public function up()
    {
        Schema::create('satuan_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan', 100)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('satuan_produk');
    }
}
