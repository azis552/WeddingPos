<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $barang = Barang::all()->count();
        $user = User::all()->count();
        $jumlah_transaksi = Transaksi::all()->count();
        return view('welcome', compact('title', 'barang', 'user', 'jumlah_transaksi'));
    }
}
