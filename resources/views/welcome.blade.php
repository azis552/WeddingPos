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
    @if (Auth::user()->role == 'admin')

    <div class="row justify-content-start d-flex">
        <div class="col">
            <div class="card bg-primary text-white mb-4 text-center">
                <div class="card-body">Jumlah Barang</div>
                <h2>
                    {{ $barang }}
                </h2>
            </div>
        </div>
        <div class="col">
            <div class="card bg-info text-white mb-4 text-center">
                <div class="card-body">Jumlah User</div>
                <h2>
                    {{ $user }}
                </h2>
            </div>
        </div>
        <div class="col">
            <div class="card bg-success text-white mb-4 text-center" >
                <div class="card-body">Jumlah Transaksi</div>
                <h2>
                    {{ $jumlah_transaksi }}
                </h2>
            </div>
        </div>
    </div>
            
    @endif
    <div class="row text-center m-3"  style="border: 1px solid black ; border-radius: 10px ; padding: 10px">
        <h2>Selamat Datang Di Aplikasi</h2>
        <h3>{{ $_ENV['APP_NAME'] }}</h3>
        <h4>{{ Auth::user()->name }}</h4>
    </div>

    
@endsection
