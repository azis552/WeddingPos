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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Akun</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card shadow-lg border-0 rounded-lg ">
                        <div class="card-body">

                            <form action="{{ route('register.addUser') }}" method="POST">
                                @csrf
                                <div class="form-floating mb-3  ">
                                    <input class="form-control" id="inputFirstName" name="name" type="text"
                                        placeholder="Enter your first name" />
                                    <label for="inputFirstName" class="pl-3">Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="email" id="inputEmail" type="email"
                                        placeholder="name@example.com" />
                                    <label for="inputEmail">Email address</label>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" name="password" id="inputPassword" type="password"
                                                placeholder="Create a password" />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" name="password_confirmation"
                                                id="inputPasswordConfirm" type="password" placeholder="Confirm password" />
                                            <label for="inputPasswordConfirm">Confirm Password</label>
                                        </div>
                                    </div>
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

                            <form action="{{ route('user.update', ':id') }}" id="update-form" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="idEdit">
                                <div class="form-floating mb-3  ">
                                    <input class="form-control" id="usernameEdit" name="name" type="text"
                                        placeholder="Enter your first name" />
                                    <label for="inputFirstName" class="pl-3">Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="email" id="emailEdit" type="email"
                                        placeholder="name@example.com" />
                                    <label for="inputEmail">Email address</label>
                                </div>
                                <div class="col-md">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input class="form-control" name="password" id="inputPassword" type="password"
                                            placeholder="Create a password" />
                                        <label for="inputPassword">Password</label>
                                    </div>
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
            <table class="table table-bordered table-striped table-hover table-responsive display nowrap "
                style="width: 100%" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Foto</th>
                        <th>Harga</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $transaksi->tanggal }}</td>
                            <td>{{ $transaksi->detailTransaksi->count() }}</td>
                            <td>
                                {{-- <img src="{{ asset('storage/images/' . $transaksi->barang->foto) }}" 
                                class="card-img-top" alt="{{ $transaksi->foto }}" style=" width: 9rem; height: 9rem; align-self: center; "> --}}
                            </td>
                            <td>
                                {{-- {{ formatRupiah($transaksi->barang->harga) }} --}}
                            </td>
                            <td>
                                {{-- <a href="{{ route('user.edit', $transaksi->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('keranjang.destroy', $transaksi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form> --}}
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
    @endsection
