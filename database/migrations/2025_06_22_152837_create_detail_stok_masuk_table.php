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
        Schema::create('detail_stok_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_masuk_id')->constrained('stok_masuk')->onDelete('cascade'); // FK ke tabel stok_masuk
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade'); // FK ke tabel produk
            $table->integer('jumlah');
            $table->string('satuan_id')->constrained('satuan')->onDelete('cascade'); // Satuan produk (misal: Botol, Pcs, Dus)
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_stok_masuk');
    }
};
