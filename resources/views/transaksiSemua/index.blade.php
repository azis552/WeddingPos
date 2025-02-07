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
    <!-- Modal -->
    {{-- end modal --}}
    <div class="card border-0 shadow">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-responsive display nowrap " style="width: 100%"
                id="myTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th>Pembeli</th>
                        <th scope="col">Tanggal Transaksi</th>
                        <th scope="col">Jumlah Barang</th>
                        <th scope="col">Total Harga</th>
                        <th>Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $transaksi->user->name }}</td>
                            <td>{{ $transaksi->tanggal }}</td>
                            <td>{{ $transaksi->detailTransaksi->count() }}</td>
                            <td>
                                {{ formatRupiah($transaksi->total) }}
                            </td>
                            <td>{{ $transaksi->status }}</td>
                            <td>
                                <button class="btn btn-success detailTransaksi" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-id="{{ $transaksi->id }}">Detail Barang</button>
                                @if ($transaksi->status == 'proses')
                                    <button class="btn btn-primary bayar" data-bs-toggle="modal" data-bs-target="#Bayar"
                                        data-id="{{ $transaksi->id }}">Bayar</button>
                                    <button class="btn btn-danger batal"
                                        {{ $transaksi->status == 'batal' ? 'disabled' : '' }}
                                        data-id="{{ $transaksi->id }}">Batal</button>
                                @else
                                    <button class="btn btn-primary btnInvoice" data-bs-toggle="modal"
                                        data-bs-target="#invoice" data-id="{{ $transaksi->id }}">Invoice</button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStatus"
                                        data-id="{{ $transaksi->id }}">
                                        Update Status
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="detailTransaksi">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="updateStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Progress </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-hover table-responsive display nowrap " style="width: 100%">
                            <thead>
                                <tr>
                                    <th scope="col">Status</th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                          </div>
                                    </td>
                                    <td>
                                        Pembayaran
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                          </div>
                                    </td>
                                    <td>
                                        Pemasangan
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                          </div>
                                    </td>
                                    <td>
                                        Pelepasan
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                          </div>
                                    </td>
                                    <td>
                                        Selesai
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Invoice</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="">Wedding Pos</label>
                            <hr>
                        </div>
                        <div id="htmlinvoice">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="cetakInvoice">Cetak</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="Bayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Bayar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <label for=""">Bukti Pembayaran</label>
                                    <input type="file" name="bukti_pembayaran" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal Pemasangan</label>
                                    <input type="date" name="tanggal_pemasangan" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal Pelepasan</label>
                                    <input type="date" name="tanggal_pelepasan" class="form-control">
                                </div>
                                <div>
                                    <label for="">Matode Pembayaran</label>
                                    <select name="metode_pembayaran" class="form-control">
                                        <option value="transfer">Transfer</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Catatan</label>
                                    <textarea class="form-control" name="catatan" id="" cols="30" rows="10"></textarea>
                                </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
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
                $('.batal').on('click', function() {
                    var id_transaksi = $(this).data('id');
                    $.ajax({
                        url: "{{ route('batalTransaksi', ':id') }}".replace(':id', id_transaksi),
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            window.location.href = "{{ route('transaksiSaya.index') }}";
                        }
                    });
                });

                $('.detailTransaksi').on('click', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('detailTransaksi', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            var table =
                                '<table class="table table-bordered table-striped table-hover table-responsive display nowrap " style="width: 100%">';
                            table += '<thead>';
                            table += '<tr>';
                            table += '<th scope="col">No</th>';
                            table += '<th scope="col">Barang</th>';
                            table += '<th scope="col">Harga</th>';
                            table += '<th scope="col">Jumlah</th>';
                            table += '<th scope="col">Total</th>';
                            table += '</tr>';
                            table += '</thead>';
                            table += '<tbody>';
                            var no = 1;
                            for (var i = 0; i < response.length; i++) {
                                var detail_transaksi = response[i];
                                table += '<tr>';
                                table += '<th scope="row">' + no + '</th>';
                                table += '<td>' + detail_transaksi.barang.name + '</td>';
                                table += '<td>' + formatRupiah(detail_transaksi.harga) + '</td>';
                                table += '<td>' + detail_transaksi.jumlah + '</td>';
                                table += '<td>' + formatRupiah(detail_transaksi.total) + '</td>';
                                table += '</tr>';
                                no++;
                            }
                            table += '</tbody>';
                            table += '</table>';
                            $('#detailTransaksi').html(table);
                        }
                    });
                });

                $('.bayar').on('click', function() {
                    var id = $(this).data('id');
                    $('#id').val(id);
                });

                $('.btnInvoice').on('click', function() {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ route('detailTransaksi', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            console.log(response);
                            var table = 'Nama Pembeli : ' + response[0].transaksi.user.name +
                            '<br>';
                            table +=
                                '<table class="table table-bordered table-striped table-hover table-responsive display nowrap " style="width: 100%">';
                            table += '<thead>';
                            table += '<tr>';
                            table += '<th scope="col">No</th>';
                            table += '<th scope="col">Barang</th>';
                            table += '<th scope="col">Harga</th>';
                            table += '<th scope="col">Jumlah</th>';
                            table += '<th scope="col">Total</th>';
                            table += '</tr>';
                            table += '</thead>';
                            table += '<tbody>';
                            var no = 1;
                            for (var i = 0; i < response.length; i++) {
                                var detail_transaksi = response[i];
                                table += '<tr>';
                                table += '<th scope="row">' + no + '</th>';
                                table += '<td>' + detail_transaksi.barang.name + '</td>';
                                table += '<td>' + formatRupiah(detail_transaksi.harga) + '</td>';
                                table += '<td>' + detail_transaksi.jumlah + '</td>';
                                table += '<td>' + formatRupiah(detail_transaksi.total) + '</td>';
                                table += '</tr>';
                                no++;
                            }
                            table += '</tbody>';
                            table += '<tfoot>';
                            table += '<tr>';
                            table += '<td colspan="4">Total</td>';
                            table += '<td colspan="2">' + formatRupiah(response[0].transaksi
                                .total) + '</td>';
                            table += '</tr>';
                            table += '<tr>';
                            table += '<td colspan="4">Tanggal Pemasangan</td>';
                            table += '<td colspan="2">' + response[0].transaksi.tanggal_pemasangan +
                                '</td>';
                            table += '</tr>';
                            table += '<tr>';
                            table += '<td colspan="4">Tanggal Pelepasan</td>';
                            table += '<td colspan="2">' + response[0].transaksi.tanggal_pelepasan +
                                '</td>';
                            table += '</tr>';
                            table += '<tr>';
                            table += '<td colspan="4">Metode Pembayaran</td>';
                            table += '<td colspan="2">' + response[0].transaksi.metode_pembayaran +
                                '</td>';
                            table += '</tr>';
                            table += '</tfoot>';
                            table += '</table>';
                            $('#htmlinvoice').html(table);
                        }
                    });
                });
                $("#cetakInvoice").on('click', function() {
                    var printContents = $("#htmlinvoice").html(); // Ambil isi elemen
                    var originalContents = $("body").html(); // Simpan isi asli halaman

                    $("body").html(printContents); // Ganti dengan elemen yang mau dicetak

                    window.print(); // Cetak halaman
                    window.location.href = "{{ route('daftarTransaksi') }}";
                    
                });
            });
        </script>
    @endsection
