<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('stok_barang', function (Blueprint $table) {
    $table->id();
    $table->string('nama_produk', 100);
    $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
    $table->foreignId('supplier_id')->nullable()->constrained('supplier')->onDelete('set null');
    $table->string('satuan', 50)->nullable(); // misal: pcs, box, liter
    $table->integer('stok')->default(0);
    $table->text('deskripsi')->nullable();
    $table->integer('jumlah')->default(0);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
