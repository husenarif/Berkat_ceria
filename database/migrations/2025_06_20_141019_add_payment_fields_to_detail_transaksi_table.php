<?php
// database/migrations/xxxx_xx_xx_add_payment_fields_to_detail_transaksi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToDetailTransaksiTable extends Migration
{
    public function up()
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->double('total_harga')->after('harga_satuan');
            $table->double('bayar')->after('total_harga');
            $table->double('kembalian')->after('bayar');
        });
    }

    public function down()
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropColumn(['total_harga', 'bayar', 'kembalian']);
        });
    }
}

