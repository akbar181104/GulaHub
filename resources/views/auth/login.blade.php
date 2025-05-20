@extends('layouts.login')

@section('content')
<h2>Login</h2>

<form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <input type="text" name="phone" placeholder="Nomor HP" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>

{{-- Tampilkan error jika ada --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Tambahkan pilihan daftar --}}
<p style="margin-top: 1rem;">
    Belum punya akun? Daftar sebagai:
    <br>
    👉 <a href="{{ route('register.petani') }}">Petani</a> |
    <a href="{{ route('register.pabrik') }}">Pabrik</a>
</p>
@endsection
