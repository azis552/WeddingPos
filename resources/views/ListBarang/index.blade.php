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
            <div class="row d-flex justify-content-start m-3">
                @foreach ($barangs as $barang)
                    <div class="col-auto mt-2">
                        <div class="card p-3" style="width: 18rem; ">
                            <img src="{{ asset('storage/images/' . $barang->foto) }}" class="card-img-top"
                                alt="{{ $barang->foto }}" style=" width: 13rem; height: 13rem; align-self: center; ">
                            <div class="card-body ">
                                <h5 class="card-title">{{ $barang->name }}</h5>
                                <p class="card-text">{{ $barang->deskripsi }}</p>
                            </div>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Harga: {{ formatRupiah($barang->harga) }}</li>
                                <li class="list-group-item qty-stok" data-stok="{{ $barang->stok }}">Stok: {{ $barang->stok }}</li>
                            </ul>
                            <div class="input-group mt-2">
                                <button class="btn btn-warning btnMinus" id=""><i
                                        class="fa-solid fa-minus"></i></button>
                                <button class="btn btn-success btnPlus" id=""><i
                                        class="fa-solid fa-plus"></i></button>
                                <input type="text" readonly id="" class="form-control qty"
                                    @php
$detailTransaksi = $barang->detailTransaksi->filter(function($detail) {
                                    return $detail->transaksi 
                                        && $detail->transaksi->users_id == Auth::user()->id 
                                        && optional($detail->transaksi->statusTerakhir)->status == 'keranjang';
                                })->first(); @endphp
                                    @if ($detailTransaksi) value="{{ $detailTransaksi->jumlah }}"
                            @else
                                value="0" @endif>
                                <button class="btn btn-warning keranjang" id="" data-id="{{ $barang->id }}"><i
                                        class="fa-solid fa-cart-plus"></i></button>
                                {{-- <button class="btn btn-success" id="transaksi"><i class="fa-solid fa-cash-register"></i></button> --}}
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection


    @section('scripts')
        <script>
            $(document).ready(function() {
                $('.btnPlus').on('click', function() {
                    var qtyInput = $(this).closest(".input-group").find(".qty");
                    var stok = $(this).closest(".input-group").prev().find(".qty-stok").data("stok");
                    var currentVal = parseInt(qtyInput.val());

                    if (currentVal >= stok) {
                        alert("Stok tidak mencukupi!");
                        return false;
                    }

                    qtyInput.val(currentVal + 1);
                });
                $('.btnMinus').on('click', function() {
                    var qty = $(this).closest('.input-group').find('.qty');
                    var currentVal = parseInt(qty.val());
                    if (!isNaN(currentVal) && currentVal > 0) {
                        qty.val(currentVal - 1);
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.keranjang').on('click', function() {
                    var qty = $(this).closest('.input-group').find('.qty');
                    var id = $(this).data('id');
                    if (qty.val() == 0) {
                        alert('qty tidak boleh kosong');
                        return false;
                    }
                    $.ajax({
                        url: "{{ route('keranjang.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id_barang: id,
                            qty: qty.val()
                        },
                        success: function(response) {
                            alert(response.message);
                        }
                    })
                })
            });
        </script>
    @endsection
