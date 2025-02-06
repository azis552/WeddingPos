<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function keranjangStore(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'qty' => 'required',
        ]);

        $transaksiLama = DB::table('transaksis')
            ->join('detail_transaksis', 'transaksis.id', '=', 'detail_transaksis.transaksis_id')
            ->where('barangs_id', $request->id_barang)
            ->where('users_id', Auth::user()->id)
            ->where('status', 'keranjang')
            ->get();

        if (count($transaksiLama) > 0) {
            $detailTransaksi = DetailTransaksi::where('transaksis_id', $transaksiLama[0]->id)->first();

            $detailTransaksi->update([
                'jumlah' =>  $request->qty
            ]);
            return response()->json(['message' => 'Quantity Berhasil dirubah']);
        } else {

            $dataTransaksi = Transaksi::where('users_id', Auth::user()->id)->where('status', 'keranjang')->first();
            if ($dataTransaksi) {
                $transaksi = $dataTransaksi;
            } else {
                $transaksi = Transaksi::create([
                    'users_id' => Auth::user()->id,
                ]);
            }

            if ($transaksi) {
                $detailTransaksi = DetailTransaksi::create([
                    'transaksis_id' => $transaksi->id,
                    'barangs_id' => $request->id_barang,
                    'jumlah' => $request->qty
                ]);
                if ($detailTransaksi) {
                    return response()->json(['message' => 'Data Berhasil Ditambahkan']);
                } else {
                    return response()->json(['message' => 'Data Gagal Ditambahkan']);
                }
            } else {
                return response()->json(['message' => 'Data Gagal Ditambahkan']);
            }
        }
    }

    public function keranjangIndex()
    {
        $title = 'Keranjang Saya';
        $barangs = DetailTransaksi::whereHas('transaksi', function ($query) {
            $query->where('users_id', Auth::user()->id)
                ->where('status', 'keranjang');
        })
            ->get();
        return view('keranjang.index', compact('barangs', 'title'));
    }

    public function hapusKeranjang($id)
    {
        $detailTransaksi = DetailTransaksi::find($id);
        $detailTransaksi->delete();
        return redirect()->route('keranjang.index')->with('success', 'Data Berhasil Dihapus');
    }
    public function transaksiSaya()
    {
        $title = 'Transaksi Saya';
        $transaksis = Transaksi::where('users_id', Auth::user()->id)->where('status', '!=', 'keranjang')->orderBy('created_at', 'desc')->get();
        return view('transaksi.index', compact('transaksis', 'title'));
    }

    public function checkout(Request $request)
    {
        $detailTransaksi = DetailTransaksi::where('transaksis_id', $request->id_transaksi)->get();

        foreach ($detailTransaksi as $detail) {
            $harga = $detail->barang->harga;
            $total = $detail->jumlah * $harga;
            $detail->update([
                'total' => $total,
                'harga' => $harga,
            ]);
            $detail->barang->update([
                'stok' => $detail->barang->stok - $detail->jumlah,
            ]);
        }

        $detailTransaksi = DetailTransaksi::where('transaksis_id', $request->id_transaksi)->sum('total');
        $transaksi = Transaksi::find($request->id_transaksi);
        $transaksi->update([
            'total' => $detailTransaksi,
            'tanggal' => date('Y-m-d'),
            'status' => 'proses'
        ]);
        return response()->json(['message' => 'Checkout Berhasil']);


    }

    public function batalTransaksi($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 'batal'
        ]);
        return response()->json(['message' => 'Transaksi Berhasil Dibatal']);
    }

    public function daftarTransaksi()
    {
        $title = 'Daftar Transaksi';
        $transaksis = Transaksi::where('status', '!=', 'keranjang')->orderBy('created_at', 'desc')->get();
        return view('transaksiSemua.index', compact('transaksis', 'title'));
    }

    public function detailTransaksi($id)
    {
        $detailTransaksi = DetailTransaksi::with('barang')->where('transaksis_id', $id)->get();
        return response()->json($detailTransaksi);
    }
}
