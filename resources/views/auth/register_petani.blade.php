@extends('layouts.login')

@section('content')
<h2>Daftar Sebagai Petani</h2>

<form method="POST" action="{{ route('register.petani') }}">
    @csrf
    <input type="text" name="name" placeholder="Nama" required><br>
    <input type="text" name="phone" placeholder="Nomor HP" required><br>
    <textarea name="alamat" placeholder="Alamat lengkap" required></textarea><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required><br>
    <button type="submit">Daftar</button>
</form>

{{-- Tampilkan error validasi jika ada --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Link ke halaman register pabrik--}}
<p style="margin-top: 1rem;">
    Ingin daftar sebagai pabrik?
    <a href="{{ route('register.pabrik') }}">Klik di sini</a>
</p>
@endsection
