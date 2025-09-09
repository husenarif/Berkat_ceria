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
        Schema::table('transaksi', function (Blueprint $table) {
            // Tambahkan kolom user_id
            // bigInteger untuk menyimpan ID dari tabel users
            // unsigned karena ID biasanya positif
            // nullable() jika transaksi bisa tanpa user (misal: transaksi tamu),
            // atau hapus nullable() jika user_id selalu wajib (dan tambahkan default value atau pastikan selalu diisi)
            $table->bigInteger('user_id')->unsigned()->after('kode_transaksi');

            // Tambahkan foreign key constraint
            // Ini akan memastikan user_id yang dimasukkan ada di tabel users
            // onDelete('cascade') berarti jika user dihapus, semua transaksinya juga akan dihapus
            // onDelete('restrict') atau onDelete('set null') bisa jadi pilihan lain tergantung kebutuhan
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['user_id']);

            // Kemudian hapus kolom user_id
            $table->dropColumn('user_id');
        });
    }
};
