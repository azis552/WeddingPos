<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $title = 'Data Barang';
        $barangs = Barang::all();
        return view('dataBarang.index', compact('barangs', 'title'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'name' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'deskripsi' => 'required',
        ]);

        $foto = $request->file('foto');
        $nama_foto = time() . '.' . $foto->getClientOriginalExtension();
        $upload = $foto->move(public_path('storage/images'), $nama_foto);

        if ($upload) 
        {
            Barang::create([
                'name' => $request->name,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'deskripsi' => $request->deskripsi,
                'foto' => $nama_foto,
                'users_id' => Auth::user()->id
            ]);
            return redirect()->route('barang.index')->with('success', 'Data Berhasil Ditambahkan');
        }
        else
        {
            return redirect()->route('barang.index')->with('error', 'Data Gagal Ditambahkan');
        }
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        // dd($request->all());
        if ($request->file('foto')) {
            $foto = $request->file('foto');
            $nama_foto = time() . '.' . $foto->getClientOriginalExtension();
            $upload = $foto->move(public_path('storage/images'), $nama_foto);
            if ($upload) 
            {
                $barang->update([
                    'name' => $request->name,
                    'harga' => $request->harga,
                    'deskripsi' => $request->deskripsi,
                    'foto' => $nama_foto,
                ]);
                return redirect()->route('barang.index')->with('success', 'Data Berhasil Diubah');
            }
        }
        else
        {
            $barang->update([
                'name' => $request->name,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
            ]);
            return redirect()->route('barang.index')->with('success', 'Data Berhasil Diubah');
        }

    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data Berhasil Dihapus');
    }
}
