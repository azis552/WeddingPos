@extends('template.master')
@section('notifikasi')
    @if (session('success'))
        <div class="alert alert-success ">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
@section('content')
    @yield('notifikasi')
    {{-- end modal --}}
    <div class="card border-0 shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-responsive display nowrap "
                style="width: 100%">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Barang</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                        <th scope="col" style="width: 10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $qty = 0;
                    @endphp
                    @foreach ($barangs as $barang)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $barang->barang->name }}</td>

                            <td>
                                <img src="{{ asset('storage/images/' . $barang->barang->foto) }}" class="card-img-top"
                                    alt="{{ $barang->foto }}" style=" width: 9rem; height: 9rem; align-self: center; ">
                            </td>
                            <td>{{ $barang->jumlah }}</td>
                            <td>
                                {{ formatRupiah($barang->barang->harga) }}

                                @php
                                    $totalHarga = $barang->jumlah * $barang->barang->harga;
                                @endphp
                            </td>
                            <td>{{ formatRupiah($totalHarga) }}</td>
                            <td>
                                {{-- <a href="{{ route('user.edit', $barang->id) }}" class="btn btn-warning">Edit</a> --}}
                                <form action="{{ route('keranjang.destroy', $barang->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <input type="hidden" id="id_transaksi" name="id_transaksi" value="{{ $barang->transaksis_id }}">
                        @php
                            $total += $totalHarga;
                            $qty += $barang->jumlah;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total Quantity</td>
                        <td>{{ $qty }}</td>
                        <td>Total Harga</td>
                        <td>{{ formatRupiah($total) }}</td>
                        <td></td>
                </tfoot>
            </table>
            <div class="card shadow-md p-2  ">
                <div class="row">
                    <div class="col">
                        <label for="">Tanggal Pemasangan</label>
                        <input type="date" name="tanggalPemasangan" required id="tanggalPemasangan" class="form-control">
                    </div>
                    <div class="col">
                        <label for="">Tanggal Pelepasan</label>
                        <input type="date" name="tanggalPelepasan" required id="tanggalPelepasan" class="form-control">
                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col">
                        <label for="">Jenis Pembayaran</label>
                        <select name="metodePembayaran" required id="metodePembayaran" class="form-control">
                            <option>Pilih Metode Pembayaran</option>
                            <option value="1">cash bayar depan</option>
                            <option value="2">cash bayar belakang</option>
                            <option value="3">transfer bayar depan</option>
                            <option value="4">transfer bayar belakang</option>
                            <option value="5">cash dp + pelunasan</option>
                            <option value="6">transfer dp + pelunasan</option>
                        </select>
                    </div>
                </div>
            </div>


        </div>
        <div class="card-footer">
            <div class="float-end">
                <button type="button" class="btn btn-primary checkout">Checkout</button>
            </div>

        </div>
    @endsection


    @section('scripts')
        <script>
            $(document).ready(function() {
                $('#button-search').on('click', function() {
                    var search = $('input[name="search"]').val();
                    alert(search);
                });
                new DataTable('#myTable', {
                    scrollX: true,
                });
                $('.checkout').on('click', function() {
                    var id_transaksi = $('#id_transaksi').val();
                    var tanggalPemasangan = $('#tanggalPemasangan').val();
                    var tanggalPelepasan = $('#tanggalPelepasan').val();
                    var metodePembayaran = $('#metodePembayaran').val();
                    // Validasi jika ada input yang kosong
                    if (!id_transaksi || !tanggalPemasangan || !tanggalPelepasan || !metodePembayaran) {
                        alert("Harap isi semua kolom sebelum melakukan checkout!");
                        return;
                    }
                    if (confirm("Apakah Anda yakin ingin melakukan checkout?")) {
                        $.ajax({
                            url: "{{ route('checkout') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            data: {
                                id_transaksi: id_transaksi,
                                tanggalPemasangan: tanggalPemasangan,
                                tanggalPelepasan: tanggalPelepasan,
                                metodePembayaran: metodePembayaran
                            },
                            success: function(response) {
                                window.location.href = "{{ route('transaksiSaya.index') }}";
                            }
                        });
                    }
                });
            });

            function confirmDelete(event) {
                event.preventDefault(); // Mencegah form terkirim langsung
                if (confirm("Apakah Anda yakin ingin menghapus barang ini?")) {
                    event.target.submit(); // Jika konfirmasi "OK", kirimkan form
                }
            }
        </script>
    @endsection
