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
        Schema::create('bayars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksis_id');
            $table->foreign('transaksis_id')->references('id')->on('transaksis')->onDelete('cascade');
            $table->string('metode_pembayaran')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayars');
    }
};
