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
                        <th>Jenis Pembayaran</th>
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
                            <td>
                                @if ($transaksi->jenis_pembayaran == '1')
                                    cash bayar depan
                                @elseif ($transaksi->jenis_pembayaran == '2')
                                    cash bayar belakang
                                @elseif ($transaksi->jenis_pembayaran == '3')
                                    transfer bayar depan
                                @elseif ($transaksi->jenis_pembayaran == '4')
                                    transfer bayar belakang
                                @elseif ($transaksi->jenis_pembayaran == '5')
                                    cash dp + pelunasan
                                @elseif ($transaksi->jenis_pembayaran == '6')
                                    transfer dp + pelunasan
                                @endif

                            </td>
                            <td>{{ $transaksi->statusTerakhir->status }}</td>
                            <td>
                                
                                <button class="btn btn-success detailTransaksi" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"  data-id="{{ $transaksi->id }}">Detail Barang</button>

                                <button class="btn btn-primary bayar" {{ $transaksi->statusTerakhir->status == 'batal' ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#Bayar"
                                    data-id="{{ $transaksi->id }}"
                                    data-jenis_pembayaran="{{ $transaksi->jenis_pembayaran }}">Bayar</button>
                                <button class="btn btn-danger batal"  {{ $transaksi->statusTerakhir->status == 'batal' ? 'disabled' : '' }}
                                    data-id="{{ $transaksi->id }}">Batal</button>
                                <button class="btn btn-warning update_status" {{ $transaksi->statusTerakhir->status == 'batal' ? 'disabled' : '' }} data-bs-toggle="modal"
                                    data-bs-target="#updateStatus" data-id="{{ $transaksi->id }}">
                                    Update Status
                                </button>

                                <button class="btn btn-primary btnInvoice" {{ $transaksi->statusTerakhir->status == 'batal' ? 'disabled' : '' }} data-bs-toggle="modal" data-bs-target="#invoice"
                                    data-id="{{ $transaksi->id }}">Invoice</button>


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
                        <table class="table table-bordered table-striped table-hover table-responsive display nowrap "
                            style="width: 100%">
                            <input type="hidden" id="idProgress" name="idProgress">
                            <thead>
                                <tr>
                                    <th scope="col">Status</th>
                                    <th scope="col">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="tblProgress">


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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Bayar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>

                            <input type="hidden" name="id" id="id">
                            <div>
                                <input type="hidden" name="id" id="id">
                                <div id="tblTagihan"></div>

                            </div>
                            <span style=" font-weight: bold ">NB : pembayaran cash dibuktikan dengan foto kwitansi dan
                                bayar belakang dibuktikan dengan surat pernyataan</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    if (confirm("Apakah Anda yakin ingin membatalkan transaksi?")) {
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
                    }
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
                    var jenis_pembayaran = $(this).data('jenis_pembayaran');
                    $('#id').val(id);
                    var role = "{{ Auth::user()->role }}"; // Ambil role dari Blade ke JavaScript
                    $.ajax({
                        url: "{{ route('cekPembayaran', ':id') }}".replace(':id', id),
                        type: 'GET',
                        success: function(response) {
                            var table = '';
                            var no = 1;

                            // Jika ada data pembayaran, tampilkan di tabel
                            if (response.length > 0) {
                                table += `
                                <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                    <thead>
                                        <tr>
                                            <th scope='col'>No</th>
                                            <th scope='col'>Jenis Bayar</th>
                                            <th scope='col'>Bukti</th>
                                        </tr>
                                    </thead>
                                <tbody>`;
                                if (jenis_pembayaran == '1') {
                                    response.forEach((item, index) => {
                                        table += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.metode_pembayaran}</td>
                                    <td><img src="/storage/images/${item.bukti_pembayaran}" alt="Bukti Pembayaran" width="100"></td>

                                </tr>`;
                                    });
                                } else if (jenis_pembayaran == '2') {
                                    if (response.length == 1) {
                                        table += ` <tr>
                                                        <td>2</td>
                                                        <td>Cash Bayar Belakang</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Cash Bayar Belakang">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>`
                                    }
                                    response.forEach((item, index) => {
                                        table += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.metode_pembayaran}</td>
                                    <td><img src="/storage/images/${item.bukti_pembayaran}" alt="Bukti Pembayaran" width="100"></td>

                                </tr>`;
                                    });
                                } else if (jenis_pembayaran == '3') {
                                    response.forEach((item, index) => {
                                        table += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.metode_pembayaran}</td>
                                    <td><img src="/storage/images/${item.bukti_pembayaran}" alt="Bukti Pembayaran" width="100"></td>

                                </tr>`;
                                    });
                                } else if (jenis_pembayaran == '4') {
                                    if (response.length == 1) {
                                        table += ` <tr>
                                                        <td>2</td>
                                                        <td>Transfer Bayar Belakang</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Transfer Bayar Belakang">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>`
                                    }
                                    response.forEach((item, index) => {
                                        table += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.metode_pembayaran}</td>
                                    <td><img src="/storage/images/${item.bukti_pembayaran}" alt="Bukti Pembayaran" width="100"></td>

                                </tr>`;
                                    });
                                } else if (jenis_pembayaran == '5') {
                                    if (response.length == 1) {
                                        table += ` <tr>
                                                        <td>2</td>
                                                        <td>Cash Pelunasan</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Cash Pelunasan">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>`
                                    }
                                    response.forEach((item, index) => {
                                        table += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.metode_pembayaran}</td>
                                    <td><img src="/storage/images/${item.bukti_pembayaran}" alt="Bukti Pembayaran" width="100"></td>

                                </tr>`;
                                    });
                                } else if (jenis_pembayaran == '6') {
                                    if (response.length == 1) {
                                        table += ` <tr>
                                                        <td>2</td>
                                                        <td>Transfer Pelunasan</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Transfer Pelunasan">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>`
                                    }
                                    response.forEach((item, index) => {
                                        table += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.metode_pembayaran}</td>
                                    <td><img src="/storage/images/${item.bukti_pembayaran}" alt="Bukti Pembayaran" width="100"></td>

                                </tr>`;
                                    });
                                }


                                table += `</tbody></table>`;

                                $("#tblTagihan").html(table);
                            } else {
                                // Jika pembayaran masih kosong, tampilkan form pembayaran
                                if (jenis_pembayaran == '1') {
                                    if (role === "admin") {
                                        let $html = `
                                            <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>No</th>
                                                        <th scope='col'>Jenis Bayar</th>
                                                        <th scope='col'>Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Cash Bayar Depan</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Cash Bayar Depan">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>`;

                                        $("#tblTagihan").html($html);
                                    } else {
                                        $("#tblTagihan").html("Menunggu approve admin");
                                    }
                                } else if (jenis_pembayaran == '2') {
                                    if (role === "admin") {
                                        let $html = `
                                            <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>No</th>
                                                        <th scope='col'>Jenis Bayar</th>
                                                        <th scope='col'>Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Surat Pernyataan</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Surat Pernyataan">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Cash Bayar Belakang</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Cash Bayar Belakang">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>`;

                                        $("#tblTagihan").html($html);
                                    } else {
                                        $("#tblTagihan").html("Menunggu approve admin");
                                    }

                                } else if (jenis_pembayaran == '3') {
                                    let $html = `
                                            <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>No</th>
                                                        <th scope='col'>Jenis Bayar</th>
                                                        <th scope='col'>Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Transfer Bayar Depan</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Transfer Bayar Depan">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Bayar</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>`;

                                    $("#tblTagihan").html($html);
                                } else if (jenis_pembayaran == '4') {
                                    let $html = `
                                            <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>No</th>
                                                        <th scope='col'>Jenis Bayar</th>
                                                        <th scope='col'>Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Surat Pernyataan</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Surat Pernyataan">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>`;

                                    $("#tblTagihan").html($html);
                                } else if (jenis_pembayaran == '5') {
                                    let $html = `
                                            <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>No</th>
                                                        <th scope='col'>Jenis Bayar</th>
                                                        <th scope='col'>Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Cash DP</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Cash DP">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                            </table>`;

                                    $("#tblTagihan").html($html);


                                } else if (jenis_pembayaran == '6') {
                                    let $html = `
                                            <table class='table table-bordered table-striped table-hover table-responsive display nowrap' style='width: 100%'>
                                                <thead>
                                                    <tr>
                                                        <th scope='col'>No</th>
                                                        <th scope='col'>Jenis Bayar</th>
                                                        <th scope='col'>Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Transfer DP</td>
                                                        <td>
                                                            <form action="{{ route('transaksi.bayar') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="id" value="${id}">
                                                                <input type="hidden" name="jenis_pembayaran" value="Transfer DP">
                                                                <div class="input-group mt-2">
                                                                    <input type="file" required class="form-control" name="bukti">
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                            </table>`;

                                    $("#tblTagihan").html($html);
                                }

                            }
                        },
                        error: function() {
                            $("#tblTagihan").html(
                                "<p class='text-danger'>Gagal mengambil data pembayaran</p>");
                        }
                    });
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
                            table += '<td colspan="2">';
                            if (response[0].transaksi.jenis_pembayaran == '1') {
                                table += "cash bayar depan";
                            } else if (response[0].transaksi.jenis_pembayaran == '2') {
                                table += "cash bayar belakang";
                            } else if (response[0].transaksi.jenis_pembayaran == '3') {
                                table += "transfer bayar depan";
                            } else if (response[0].transaksi.jenis_pembayaran == '4') {
                                table += "transfer bayar belakang";
                            } else if (response[0].transaksi.jenis_pembayaran == '5') {
                                table += "cash dp + pelunasan";
                            } else if (response[0].transaksi.jenis_pembayaran == '6') {
                                table += "transfer dp + pelunasan";
                            }
                            table += '</td>';
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

                $(".update_status").on('click', function() {
                    var id = $(this).data('id');
                    $('#idProgress').val(id);
                    $.ajax({
                        url: "{{ route('transaksi.updateStatus', ':id') }}".replace(':id', id),
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var transaksi = response.transaksi;
                            var table = '';
                            // Menampilkan status yang telah ada
                            transaksi.forEach(function(item) {
                                table += '<tr>';
                                table +=
                                    '<td><input class="form-check-input" disabled type="checkbox" role="switch" checked></td>';
                                table += `<td>${item.status}</td>`;
                                table += '</tr>';
                            });
                            table += '<tr> <td colspan="2"><hr></td></tr>';
                            table += '<tr> <td colspan="2">untuk update klik dibawah</td></tr>';

                               
                                table += '<tr>';
                                table += '<tr>';
                                table += '<td><input class="form-check-input btnPemasangan"type="checkbox" role="switch"></td>';
                                table += '<td>Pemasangan</td>';
                                table += '</tr>';

                                table += '<tr>';
                                table += '<td><input class="form-check-input btnPelepasan" type="checkbox" role="switch"></td>';
                                table += '<td>Pelepasan</td>';
                                table += '</tr>';

                                table += '<tr>';
                                table += '<td><input class="form-check-input btnSelesai" type="checkbox" role="switch"></td>';
                                table += '<td>Selesai</td>';
                                table += '</tr>';
                            

                            $('#tblProgress').html(table);
                        }
                    });
                });
                // $(".btnPemasangan").on('change', function() {
                //     var id = $('#idProgress').val();
                //     var status = 'pemasangan';
                //     $.ajax({
                //         url: "{{ route('transaksi.updateStatusPut', ':id') }}".replace(':id', id),
                //         type: 'PUT',
                //         data: {
                //             status: status
                //         },
                //         headers: {
                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //         },
                //         success: function(response) {
                //             window.location.href = "{{ route('daftarTransaksi') }}";
                //         }
                //     });
                // })
            });
        </script>
        <script>
            $(document).on('change', '.btnPemasangan', function() {
                var id = $('#idProgress').val();
                var status = 'pemasangan';

                $.ajax({
                    url: "{{ route('transaksi.updateStatusPut', ':id') }}".replace(':id', id),
                    type: 'PUT',
                    data: {
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                    }
                });
            });
        </script>
        <script>
            $(document).on('change', '.btnPelepasan', function() {
                var id = $('#idProgress').val();
                var status = 'pelepasan';

                $.ajax({
                    url: "{{ route('transaksi.updateStatusPut', ':id') }}".replace(':id', id),
                    type: 'PUT',
                    data: {
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                    }
                });
            });
        </script>
        <script>
            $(document).on('change', '.btnSelesai', function() {
                var id = $('#idProgress').val();
                var status = 'selesai';

                $.ajax({
                    url: "{{ route('transaksi.updateStatusPut', ':id') }}".replace(':id', id),
                    type: 'PUT',
                    data: {
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                    }
                });
            });
        </script>
    @endsection
