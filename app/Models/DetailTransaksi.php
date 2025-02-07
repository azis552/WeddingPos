<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $guarded = ['id'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barangs_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksis_id');
    }

    
}
