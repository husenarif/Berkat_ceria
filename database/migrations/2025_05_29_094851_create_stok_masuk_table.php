<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokMasukTable extends Migration
{
    public function up()
    {
      Schema::create('stok_masuk', function (Blueprint $table) {
    $table->id();
    $table->string('kode_stok_masuk')->unique();
    $table->foreignId('supplier_id')->constrained('supplier')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('user_id')->onDelete('cascade');
    $table->text('deskripsi');
    $table->date('tanggal_masuk');
    $table->timestamps();
});
}

    public function down()
    {
        Schema::dropIfExists('stok_masuk');
    }
}
