<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGambarToProdukTable extends Migration
{// database/migrations/xxxx_xx_xx_xxxxxx_add_gambar_to_produk_table.php

public function up()
{
    Schema::table('produk', function (Blueprint $table) {
        // Tambahkan kolom 'gambar' setelah kolom 'nama_produk'
        // Kolom ini bisa NULL karena mungkin ada produk lama yang belum punya gambar
        $table->string('gambar')->nullable()->after('nama_produk');
    });
}

public function down()
{
    Schema::table('produk', function (Blueprint $table) {
        // Logika untuk menghapus kolom jika migration di-rollback
        $table->dropColumn('gambar');
    });
}

}
