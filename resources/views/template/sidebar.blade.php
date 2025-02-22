<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>

                    @if (Auth::user()->role == 'admin')
                        <div class="sb-sidenav-menu-heading">Data Master</div>
                        <a class="nav-link" href="{{ route('akun') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-user-gear"></i></div>
                            Akun
                        </a>
                        <a class="nav-link" href="{{ route('barang.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang
                        </a>
                    @endif


                    <div class="sb-sidenav-menu-heading">Transaksi</div>
                    <a class="nav-link" href="{{ route('listBarang') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        List Barang
                    </a>
                    <a class="nav-link" href="{{ route('keranjang.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Keranjang
                    </a>
                    <a class="nav-link" href="{{ route('transaksiSaya.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                        Transaksi Saya
                    </a>
                    @if (Auth::user()->role == 'admin')
                        <a class="nav-link" href="{{ route('daftarTransaksi') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Daftar Transaksi
                        </a>
                        <div class="sb-sidenav-menu-heading">Laporan</div>
                        <a class="nav-link" href="{{ route('laporan') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Laporan Transaksi
                        </a>
                    @endif

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                {{ Auth::user()->name }}
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">

        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">{{ $title }}</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
