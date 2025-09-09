<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBayarKembalianToTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("transaksi", function (Blueprint $table) {
            $table->decimal("bayar", 15, 2)->after("total_harga")->nullable();
            $table->decimal("kembalian", 15, 2)->after("bayar")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("transaksi", function (Blueprint $table) {
            $table->dropColumn([ "bayar", "kembalian"]);
        });
    }
}


