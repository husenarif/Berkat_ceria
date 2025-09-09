<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDetailTransaksiProdukIdOnDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: Hapus foreign key yang sudah ada
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
        });

        // Step 2: Ubah kolom produk_id menjadi nullable dan PASTIKAN UNSIGNED
        // PENTING: Pastikan Anda sudah menginstal doctrine/dbal: composer require doctrine/dbal
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('produk_id')->nullable()->change(); // <--- PERBAIKAN DI SINI
        });

        // Step 3: Tambahkan kembali foreign key dengan ON DELETE SET NULL
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Step 1: Hapus foreign key dengan ON DELETE SET NULL
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
        });

        // Step 2: Ubah kolom produk_id kembali menjadi NOT NULL dan PASTIKAN UNSIGNED
        // PENTING: Pastikan Anda sudah menginstal doctrine/dbal
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('produk_id')->nullable(false)->change(); // <--- PERBAIKAN DI SINI
        });

        // Step 3: Tambahkan kembali foreign key dengan ON DELETE CASCADE (perilaku awal)
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
        });
    }
}
