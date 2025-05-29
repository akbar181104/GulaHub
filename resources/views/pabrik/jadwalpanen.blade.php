@extends('layouts.pabrik')

@section('content')
<div class="container">
    <h2 class="mb-4">Pilih Petani untuk Melihat Jadwal Panen</h2>
    <div class="row">
        @foreach($petanis as $petani)
        <div class="col-md-3 mb-4">
            <a href="{{ route('pabrik.rencanapanen', $petani->id) }}" style="text-decoration: none;">
                <div class="card text-center shadow-sm" style="cursor: pointer;">
                    <div class="card-body bg-light">
                        <h5 class="card-title">{{ $petani->name }}</h5>
                        <p class="text-muted">{{ $petani->email }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
