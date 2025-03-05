<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksis_id');
    }
    public function status()
    {
        return $this->hasMany(Status::class, 'id_transaksi');
    }

    public function statusTerakhir()
    {
        return $this->hasOne(Status::class, 'id_transaksi')->latestOfMany();
    }
}
