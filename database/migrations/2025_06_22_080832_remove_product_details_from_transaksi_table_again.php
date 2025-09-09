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
            // Hapus kolom-kolom yang seharusnya ada di detail_transaksi
            // Pastikan kolom-kolom ini ada di tabel Anda sebelum menjalankan migrasi ini.
            // Jika tidak ada, Anda akan mendapatkan error.
            if (Schema::hasColumn('transaksi', 'nama_produk')) {
                $table->dropColumn('nama_produk');
            }
            if (Schema::hasColumn('transaksi', 'jumlah_transaksi')) {
                $table->dropColumn('jumlah_transaksi');
            }
            if (Schema::hasColumn('transaksi', 'harga_satuan')) {
                $table->dropColumn('harga_satuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Jika Anda perlu rollback, tambahkan kembali kolom-kolom ini.
            // Sesuaikan tipe data dan properti (nullable/not null) sesuai dengan kebutuhan Anda.
            // Saya membuatnya nullable di sini untuk menghindari error jika tidak ada data saat rollback.
            $table->string('nama_produk')->nullable();
            $table->integer('jumlah_transaksi')->nullable();
            $table->decimal('harga_satuan', 10, 2)->nullable();
        });
    }
};
