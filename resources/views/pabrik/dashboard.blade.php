@extends('layouts.pabrik')

@section('content')
<h2>Selamat datang, {{ Auth::user()->name }}!</h2>
<p>Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.</p>

@endsection
