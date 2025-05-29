@extends('layouts.petani')

@section('content')
<div class="container">
    <h3>Riwayat Permintaan Rencana Panen</h3>

    <!-- Filter Tahun -->
    <form method="GET" action="{{ route('petani.riwayatsetor') }}" class="mb-4">
        <label for="tahun">Filter Tahun:</label>
        <select name="tahun" id="tahun" onchange="this.form.submit()" class="form-select w-auto d-inline-block ms-2">
            <option value="">Semua Tahun</option>
            @foreach (range(date('Y'), 2024) as $year)
                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </form>

    <!-- Tabel Riwayat -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nama Pabrik</th>
                    <th>Tanggal Rencana</th>
                    <th>Status</th>
                    <th>Tanggal Diajukan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $item)
                    <tr>
                        <td>{{ $item->nama_pabrik }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_rencana)->translatedFormat('d F Y') }}</td>
                        <td>
                            <span class="badge {{ $item->status === 'Disetujui' ? 'bg-success' : 'bg-danger' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_diajukan)->translatedFormat('d F Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada riwayat ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
