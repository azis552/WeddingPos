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

    <div class="card border-0 shadow">
        <div class="card-body laporan">
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center">Laporan Transaksi</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('cetakLaporan') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="tanggalMulai">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="dariTanggal" name="dariTanggal">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="tanggalSelesai">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="sampaiTanggal" name="sampaiTanggal">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2 mb-2">
                            <button type="button" class="btn btn-secondary cari">Cari</button>
                            <button type="button" class="btn btn-primary cetakLaporan">Cetak Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <table id="myTable"
                    class="table table-bordered table-striped table-hover table-responsive display nowrap  ">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Pelanggan</th>
                            <th scope="col">Tanggal Transaksi</th>
                            <th scope="col">Jumlah Barang</th>
                            <th scope="col">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody id="tblLaporan">
                    </tbody>
                </table>
            </div>


        </div>
        </form>
    @endsection


    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.cari').on('click', function() {
                    var tanggalMulai = $('#dariTanggal').val();
                    var tanggalSelesai = $('#sampaiTanggal').val();
                    $.ajax({
                        url: '{{ route('cariLaporan') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            tanggalMulai: tanggalMulai,
                            tanggalSelesai: tanggalSelesai
                        },
                        success: function(response) {
                            var table = '';
                            for (var i = 0; i < response.length; i++) {
                                var detail_transaksi = response[i];
                                table += '<tr>';
                                table += '<th scope="row">' + (i + 1) + '</th>';
                                table += '<td>' + detail_transaksi.user.name + '</td>';
                                table += '<td>' + detail_transaksi.tanggal + '</td>';
                                table += '<td>' + detail_transaksi.detail_transaksi.length +
                                '</td>';
                                table += '<td>' + formatRupiah(detail_transaksi.total) + '</td>';
                                table += '</tr>';
                            }
                            $('#tblLaporan').html(table);
                        }
                    });
                });
                new DataTable('#myTable', {
                    scrollX: true,
                });
                $('.cetakLaporan').on('click', function() {
                    var printContents = $(".laporan").html(); // Ambil isi elemen
                    var originalContents = $("body").html(); // Simpan isi asli halaman

                    $("body").html(printContents); // Ganti dengan elemen yang mau dicetak

                    window.print(); // Cetak halaman
                    window.location.href = "{{ route('laporan') }}";
                })
            });
        </script>
    @endsection
