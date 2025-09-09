<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->dateTime('tanggal_transaksi');
            $table->unsignedBigInteger('user_id')->nullable(); // admin/operator yang melakukan transaksi
            $table->text('deskripsi')->nullable();
            $table->decimal('profit', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
