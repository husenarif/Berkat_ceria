<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSatuanIdForeignToProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            // Pastikan kolom satuan_id sudah ada dan bertipe unsignedBigInteger
            // Jika belum ada, Anda harus menambahkannya terlebih dahulu
            // $table->unsignedBigInteger('satuan_id')->nullable()->after('kategori_id'); // Contoh jika belum ada

            // Tambahkan foreign key constraint
            $table->foreign('satuan_id')
                  ->references('id')
                  ->on('satuan_produk')
                  ->onDelete('restrict'); // Atau 'set null' jika Anda ingin mengizinkan null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            // Hapus foreign key constraint saat rollback
            $table->dropForeign(['satuan_id']);
        });
    }
}

