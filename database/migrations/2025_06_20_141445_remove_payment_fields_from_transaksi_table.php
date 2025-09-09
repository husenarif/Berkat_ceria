<?php
// database/migrations/xxxx_xx_xx_remove_payment_fields_from_transaksi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePaymentFieldsFromTransaksiTable extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['total_harga', 'bayar', 'kembalian']);
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->double('total_harga')->nullable();
            $table->double('bayar')->nullable();
            $table->double('kembalian')->nullable();
        });
    }
}
