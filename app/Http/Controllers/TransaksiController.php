<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Bayar;
use App\Models\DetailTransaksi;
use App\Models\Status;
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

        $transaksiLama = DB::select(
            "SELECT `transaksis`.id AS id_transaksi, `detail_transaksis`.`barangs_id`, 
            (
            SELECT STATUS FROM statuses 
            WHERE id_transaksi = `transaksis`.`id`
            ORDER BY created_at DESC
            LIMIT 1
            ) as status_terakhir
            FROM transaksis,`detail_transaksis`
            WHERE `transaksis`.`id` = `detail_transaksis`.`transaksis_id`
            and `detail_transaksis`.`barangs_id` = $request->id_barang
            HAVING status_terakhir = 'keranjang'"
        );
        if (count($transaksiLama) > 0) {
            $detailTransaksi = DetailTransaksi::where('transaksis_id', $transaksiLama[0]->id_transaksi)
                ->where('barangs_id', $request->id_barang)
                ->first();
            $detailTransaksi->update([
                'jumlah' =>  $request->qty
            ]);
            return response()->json(['message' => 'Quantity Berhasil dirubah']);
        } else {
            $dataTransaksi = Transaksi::where('users_id', Auth::user()->id)
                ->whereHas('statusTerakhir', function ($query) {
                    $query->where('status', 'keranjang');
                })
                ->first();
            if ($dataTransaksi) {
                $transaksi = $dataTransaksi;
            } else {
                $transaksi = Transaksi::create([
                    'users_id' => Auth::user()->id,
                ]);
                $status = Status::create([
                    'id_transaksi' => $transaksi->id,
                    'status' => 'keranjang'
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
                ->whereHas('statusTerakhir', function ($statusQuery) {
                    $statusQuery->where('status', 'keranjang');
                });
        })->get();
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
        $transaksis = Transaksi::where('users_id', Auth::user()->id)
            ->whereHas('statusTerakhir', function ($query) {
                $query->where('status', '!=', 'keranjang');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($transaksis);

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
            'tanggal_pemasangan' => $request->tanggalPemasangan,
            'tanggal_pelepasan' => $request->tanggalPelepasan,
            'jenis_pembayaran' => $request->metodePembayaran
        ]);
        $status = Status::create([
            'id_transaksi' => $transaksi->id,
            'status' => 'checkout'
        ]);
        return response()->json(['message' => 'Checkout Berhasil']);
    }

    public function batalTransaksi($id)
    {
        $status = Status::create([
            'id_transaksi' => $id,
            'status' => 'batal'
        ]);
        $detailTransaksi = DetailTransaksi::where('transaksis_id', $id)->get();

        foreach ($detailTransaksi as $detail) {
            $detail->barang->update([
                'stok' => $detail->barang->stok + $detail->jumlah,
            ]);
        }

        return response()->json(['message' => 'Transaksi Berhasil Dibatal']);
    }

    public function daftarTransaksi()
    {
        $title = 'Daftar Transaksi';
        $transaksis = Transaksi::whereHas('statusTerakhir', function ($query) {
            $query->where('status', '!=', 'keranjang');
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaksiSemua.index', compact('transaksis', 'title'));
    }

    public function detailTransaksi($id)
    {
        $detailTransaksi = DetailTransaksi::with('barang', 'transaksi.user')->where('transaksis_id', $id)->get();
        return response()->json($detailTransaksi);
    }

    public function bayarTransaksi(Request $request)
    {

        if($request->bukti != null){
            $bukti = $request->file('bukti');
            $nama_bukti = time() . '.' . $bukti->getClientOriginalExtension();
            $upload = $bukti->move(public_path('storage/images'), $nama_bukti);
        }

        $bayar = Bayar::create([
            'transaksis_id' => $request->id,
            'metode_pembayaran' => $request->jenis_pembayaran,
            'bukti_pembayaran' => $nama_bukti,
            'tanggal' => date('Y-m-d')
        ]);

        $status = Status::create([
            'id_transaksi' => $request->id,
            'status' => $request->jenis_pembayaran
        ]);

        return redirect()->route('daftarTransaksi')->with('success', 'Transaksi Berhasil Dibayar');
    }

    public function updateStatus($id)
    {
        $transaksi = Transaksi::find($id);
        return response()->json(['message' => 'Status Berhasil Diupdate', 'transaksi' => $transaksi]);
    }

    public function update(Request $request, $id)
    {
        $status = Status::create([
            'id_transaksi' => $id,
            'status' => $request->status
        ]);
        if($request->status == 'pelepasan'){
            $transaksi = Transaksi::find($id);
            $detailTransaksi = DetailTransaksi::where('transaksis_id', $transaksi->id)->get();
            foreach ($detailTransaksi as $detail) {
                $barang = Barang::find($detail->barangs_id);
                
                $barang->update([
                    'stok' => $barang->stok + $detail->jumlah,
                ]);
            }
        }
        
        return response()->json(['message' => 'Status Berhasil Diupdate']);
    }

    public function laporan()
    {
        $title = 'Laporan';
        return view('laporan.laporanTransaksi', compact('title'));
    }

    public function cariLaporan(Request $request)
    {
        $tanggal_awal = $request->tanggalMulai;
        $tanggal_akhir = $request->tanggalSelesai;
        $transaksis = Transaksi::with('user', 'detailTransaksi')->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])->get();
        return response()->json($transaksis);
    }

    public function cekPembayaran($id)
    {
        $bayars = Bayar::where('transaksis_id', $id)->get();
        return response()->json($bayars);
    }
}
