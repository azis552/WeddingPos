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
            <div class="row m-3">
                @foreach ($barangs as $barang)
                    <div class="col mt-2">
                        <div class="card p-3" style="width: 18rem; ">
                            <img src="{{ asset('storage/images/' . $barang->foto) }}" 
                                class="card-img-top" alt="{{ $barang->foto }}" style=" width: 13rem; height: 13rem; align-self: center; ">
                            <div class="card-body ">
                                <h5 class="card-title">{{ $barang->name }}</h5>
                                <p class="card-text">{{ $barang->deskripsi }}</p>
                            </div>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Harga: {{ formatRupiah($barang->harga) }}</li>
                                <li class="list-group-item">Stok: {{ $barang->stok }}</li>
                            </ul>
                            <div class="input-group mt-2">
                                <button class="btn btn-warning btnMinus" id=""><i class="fa-solid fa-minus"></i></button>
                                <button class="btn btn-success btnPlus" id=""><i class="fa-solid fa-plus"></i></button>
                                <input type="text" readonly id="" value="0" class="form-control qty" value="1">
                                <button class="btn btn-warning keranjang" id=""><i class="fa-solid fa-cart-plus"></i></button>
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
                    var qty = $(this).closest('.input-group').find('.qty');
                    var currentVal = parseInt(qty.val());
                    if (!isNaN(currentVal)) {
                        qty.val(currentVal + 1);
                    }
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
                $('#updateForm').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('id');
                    var name = button.data('name');
                    var harga = button.data('harga');
                    var deskripsi = button.data('deskripsi');
                    var foto = button.data('foto');
                    var modal = $(this);
                    var action = modal.find('#update-form').attr('action').replace(':id', id);
                    modal.find('#update-form').attr('action', action);
                    modal.find('#idEdit').val(id);
                    modal.find('#nameEdit').val(name);
                    modal.find('#hargaEdit').val(harga);
                    modal.find('#deskripsiEdit').val(deskripsi);
                    modal.find('#fotoEdit').attr('src', "{{ asset('storage/images/') }}/" + foto);

                });
            });
        </script>
    @endsection
