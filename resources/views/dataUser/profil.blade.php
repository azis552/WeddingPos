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
        <div class="card-body">
            <form action="{{ route('profil.update', $profil->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="my-input">Username</label>
                        <input id="name" class="form-control" value="{{ $profil->user->name }}" type="text" name="name">
                    </div>
                    <div class="form-group">
                        <label for="my-input">Email</label>
                        <input id="email" class="form-control" value="{{ $profil->user->email }}" type="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <input id="role" class="form-control" value="{{ $profil->user->role }}" type="text" name="role">
                    </div>
                    <div class="form-group">
                        <label for="my-input">Nama Lengkap</label>
                        <input id="name" class="form-control" value="{{ $profil->name }}" type="text" name="nama_lengkap">
                    </div>
                    <div class="form-group">
                        <label for="my-input">Alamat</label>
                        <input id="alamat" class="form-control" value="{{ $profil->alamat }}" type="text" name="alamat">
                    </div>
                    <div class="form-group">
                        <label for="my-input">Tanggal Lahir</label>
                        <input id="tanggal_lahir" class="form-control" value="{{ $profil->tanggal_lahir }}" type="date" name="tanggal_lahir">
                    </div>
                    <div class="form-group">
                        <label for="my-input">No. Hp</label>
                        <input id="no_hp" class="form-control" value="{{ $profil->nomor_hp }}" type="text" name="nomor_hp">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="my-input">Foto Profil</label>
                        <input type="file" name="foto" class="form-control" id="foto">
                    </div>
                    @if ($profil->foto)
                        <img src="{{ asset('storage/images/' . $profil->foto) }}" class="img-fluid mt-2" style=" width: 13rem; height: 13rem; radius: 50%;" alt="Foto Belum Ada">
                    @endif
                </div>
            </div>
            
        </div>
        <div class="card-footer">
            <div class="float-end">
                <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#editProfilModal">Edit</button>
            </div>
        </div>
    </form>
    @endsection


    @section('scripts')
    @endsection
