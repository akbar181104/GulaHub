@extends('layouts.petani')

@section('content')
<div class="container">
    <h2 class="mb-4">Pilih Pabrik untuk Melihat Jadwal Giling</h2>
    <div class="row">
        @foreach($pabriks as $pabrik)
        <div class="col-md-3 mb-4">
            <a href="{{ route('petani.rencanagiling', $pabrik->id) }}" style="text-decoration: none;">
                <div class="card text-center shadow-sm" style="cursor: pointer;">
                    <div class="card-body bg-light">
                        <h5 class="card-title">{{ $pabrik->name }}</h5>
                        <p class="text-muted">{{ $pabrik->email }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
