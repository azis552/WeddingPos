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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow-lg border-0 rounded-lg ">
                        <div class="card-body">

                            <form action="{{ route('barang.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="form-floating mb-3  ">
                                    <input class="form-control" id="inputFirstName" name="name" type="text"
                                        placeholder="Masukkan Nama Barang" />
                                    <label for="inputFirstName" class="pl-3">Nama Barang</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="harga" id="harga" type="text"
                                        placeholder="Masukkan Harga" />
                                    <label for="inputEmail">Harga Barang</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="stok" id="stok" type="text"
                                        placeholder="Stok Harga" />
                                    <label for="inputEmail">Stok Barang</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="deskripsi" id="deskripsi" type="text"
                                        placeholder="Deskripsi Barang" />
                                    <label for="inputEmail">Deskripsi Barang</label>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Foto Barang</label>
                                    <input class="form-control" name="foto" id="foto" type="file"
                                        placeholder="Foto Barang" />
                                </div>
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

    <div class="modal fade" id="updateForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Akun</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body">

                            <form action="{{ route('barang.update', ':id') }}" enctype="multipart/form-data" id="update-form" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="" id="idEdit">
                                <div class="form-floating mb-3  ">
                                    <input class="form-control" id="nameEdit" name="name" type="text"
                                        placeholder="Masukkan Nama Barang" />
                                    <label for="inputFirstName" class="pl-3">Nama Barang</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="harga" id="hargaEdit" type="text"
                                        placeholder="Masukkan Harga" />
                                    <label for="inputEmail">Harga Barang</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="deskripsi" id="deskripsiEdit" type="text"
                                        placeholder="Deskripsi Barang" />
                                    <label for="inputEmail">Deskripsi Barang</label>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Foto Barang</label>
                                    <input class="form-control" name="foto" id="foto" type="file"
                                        placeholder="Foto Barang" />
                                </div>
                                <div class="form-group mb-3">
                                    <img src="" id="fotoEdit" alt="Foto Barang" style="width: 100px; height: 100px; object-fit: cover">
                                </div>
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
    {{-- end modal --}}
    <div class="card border-0 shadow">
        <div class="card-body">
            <div class="input-group mb-3">
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                    data-bs-target="#staticBackdrop">
                    Tambah Barang
                </button>
            </div>
            <table class="table table-bordered table-striped table-hover table-responsive display nowrap "
                style="width: 100%" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Barang</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $barang->name }}</td>
                            <td>{{ formatRupiah($barang->harga)}}</td>
                            <td>{{ $barang->deskripsi }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>
                                <img src="{{ asset('storage/images/' . $barang->foto) }}" alt="Foto Barang"
                                    style="width: 100px; height: 100px; object-fit: cover">
                            </td>
                            <td>
                                {{-- <a href="{{ route('user.edit', $barang->id) }}" class="btn btn-warning">Edit</a> --}}
                                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal"
                                        data-bs-target="#updateForm" data-id="{{ $barang->id }}"
                                        data-foto="{{ $barang->foto }}" data-name="{{ $barang->name }}"
                                        data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}"
                                        >
                                        Edit
                                    </button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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

                $('.roleSwitch').on('change', function() {
                    var id = $(this).data('id');
                    var isChecked = $(this).is(':checked');
                    var role = isChecked ? 'admin' : 'user';
                    var badge = $(this).next('.badge');
                    $.ajax({
                        url: '{{ route('user.updateRole', ':id') }}/'.replace(':id', id),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        type: 'PUT',
                        data: {
                            role: role
                        },
                        success: function(response) {
                            if (response.success) {
                                badge.text(role);
                                badge.removeClass('bg-danger bg-success');
                                badge.addClass('bg-' + (role == 'admin' ? 'success' : 'danger'));
                            }
                        }
                    });
                })
            });
        </script>
    @endsection
