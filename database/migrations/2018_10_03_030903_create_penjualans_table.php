<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Kode_Penjualan');
            $table->date('Tanggal_Jual');
            $table->string('Nama_Pelanggan');
            $table->integer('Barang_id')->unsigned();
            $table->integer('kat_id')->unsigned();
            $table->integer('sub_id')->unsigned();
            $table->integer('Jumlah');
            $table->integer('Total_Bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualans');
    }
}
