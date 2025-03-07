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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal')->nullable();
            $table->string('total')->nullable();
            $table->string('jenis_pembayaran')->nullable();
            // cash = 1
            // dp-pelunasan = 2
            // bayar-belakang = 3
            $table->date('tanggal_pemasangan')->nullable();
            $table->date('tanggal_pelepasan')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
