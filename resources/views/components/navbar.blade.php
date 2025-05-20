@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand me-auto" href="#">
            <img src="{{ asset('img/favicon.png') }}" alt="Logo" width="70" height="70" style="margin-left: 3rem;">
        </a>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav" style="margin-left: 3rem; margin-right:3rem">
            <ul class="navbar-nav ms-auto">

                {{-- Dapatkan role user yang sedang login --}}
                @php
                    $role = Auth::user()->role;
                @endphp

                <li class="nav-item">
                    <a class="nav-link" href="{{ route($role . '.dashboard') }}">Dashboard</a>
                </li>

                @if ($role === 'pabrik')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pabrik.jadwalpanen') }}">Jadwal Panen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pabrik.permintaan') }}">Permintaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pabrik.rencanagiling') }}">Rencana Giling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pabrik.riwayatterima') }}">Riwayat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pabrik.profil') }}">Profil</a>
                    </li>
                @elseif ($role === 'petani')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('petani.jadwalgiling') }}">Jadwal Giling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('petani.permintaan') }}">Permintaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('petani.rencanapanen') }}">Rencana Panen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('petani.riwayatsetor') }}">Riwayat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('petani.profil') }}">Profil</a>
                    </li>
                @endif

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link" style="color: white;">Logout</button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>
@endauth
