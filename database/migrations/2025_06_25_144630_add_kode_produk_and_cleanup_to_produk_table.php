<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeProdukAndCleanupToProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            // Tambahkan kolom 'kode_produk'
            // Dibuat nullable() agar tidak error jika ada data lama yang tidak memiliki kode ini.
            // unique() untuk memastikan setiap kode produk unik.
            $table->string('kode_produk')->unique()->nullable()->after('id');

            // Hapus kolom 'jumlah_prodint' jika ada, karena tidak digunakan
            if (Schema::hasColumn('produk', 'jumlah_prodint')) {
                $table->dropColumn('jumlah_prodint');
            }
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
            // Rollback: Hapus kolom 'kode_produk'
            $table->dropColumn('kode_produk');

            // Rollback: Tambahkan kembali kolom 'jumlah_prodint' jika sebelumnya dihapus
            // Ini penting agar rollback bisa berjalan, meskipun kolom ini tidak digunakan.
            $table->string('jumlah_prodint')->nullable();
        });
    }
}
