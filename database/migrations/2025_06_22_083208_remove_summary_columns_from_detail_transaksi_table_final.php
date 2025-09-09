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
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // Hapus kolom-kolom yang seharusnya hanya ada di tabel 'transaksi'
            // Tambahkan if (Schema::hasColumn(...)) untuk keamanan jika kolom sudah tidak ada
            if (Schema::hasColumn('detail_transaksi', 'total_harga')) {
                $table->dropColumn('total_harga');
            }
            if (Schema::hasColumn('detail_transaksi', 'bayar')) {
                $table->dropColumn('bayar');
            }
            if (Schema::hasColumn('detail_transaksi', 'kembalian')) {
                $table->dropColumn('kembalian');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // Jika Anda perlu rollback, tambahkan kembali kolom-kolom ini.
            // Sesuaikan tipe data dan properti (nullable/not null) sesuai dengan kebutuhan Anda.
            // Saya membuatnya nullable di sini untuk menghindari error jika tidak ada data saat rollback.
            $table->double('total_harga')->nullable(); // Sesuaikan dengan tipe data asli (double)
            $table->double('bayar')->nullable();      // Sesuaikan dengan tipe data asli (double)
            $table->double('kembalian')->nullable();  // Sesuaikan dengan tipe data asli (double)
        });
    }
};
