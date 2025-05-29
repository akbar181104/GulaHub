@extends('layouts.petani')

@section('content')
<div class="container">
    <h3>Permintaan Terima</h3>

    @foreach ($rencanaDenganPengajuan as $rencana)
        <div class="card mb-3">
            <div class="card-header">
                Rencana: {{ $rencana->tanggal }} | Status: {{ $rencana->status }}
            </div>
            <div class="card-body">
                @foreach ($rencana->pengaju as $pabrik)
                    <div class="mb-2">
                        <strong>{{ $pabrik->name }}</strong> - Status: <em>{{ $pabrik->pivot->status }}</em>
                        <form method="POST" action="{{ route('petani.konfirmasi', [$rencana->id, $pabrik->id]) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="Disetujui">
                            <button class="btn btn-success btn-sm">Setujui</button>
                        </form>
                        <form method="POST" action="{{ route('petani.konfirmasi', [$rencana->id, $pabrik->id]) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="Ditolak">
                            <button class="btn btn-danger btn-sm">Tolak</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
