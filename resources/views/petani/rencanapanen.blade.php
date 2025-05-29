@extends('layouts.pabrik')

@section('content')
<div class="container">
    <h3>Rencana Panen: {{ $tahunDipilih }}</h3>

    <!-- Filter Tahun -->
    <form method="GET" action="{{ route('petani.rencanapanen') }}">
        <select class="form-select" name="tahun" onchange="this.form.submit()" style="width: 150px;">
            @for ($year = now()->year; $year <= now()->year + 5; $year++)
                <option value="{{ $year }}" {{ $year == $tahunDipilih ? 'selected' : '' }}>{{ $year }}</option>
            @endfor
        </select>
    </form>

    <!-- Accordion per Bulan -->
    <div class="accordion mt-3" id="accordionRencanaGiling">
        @php
            $bulanList = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
        @endphp

        @foreach ($bulanList as $i => $bulan)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $i }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                    {{ $bulan }}
                </button>
            </h2>
            <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                <div class="accordion-body">
                    @if (isset($dataPerBulan[$bulan]) && count($dataPerBulan[$bulan]) > 0)
                        @foreach ($dataPerBulan[$bulan] as $item)
                            <!-- Data Item -->
                            <div
                                class="border p-2 mb-2 rounded bg-light d-flex justify-content-between align-items-center"
                                style="cursor: pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $item->id }}"
                            >
                                <div>
                                    <i class="bi bi-leaf"></i>
                                    Tanggal {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                </div>
                                <span class="badge bg-secondary">{{ $item->status }}</span>
                            </div>


                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Form Edit -->
                                        <form method="POST" action="{{ route('rencanapanen.update', $item->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data Panen</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Jenis Tebu</label>
                                                    <input type="text" class="form-control" name="jenis_tebu" value="{{ $item->jenis_tebu }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Total Panen</label>
                                                    <input type="text" class="form-control" name="total_panen" value="{{ $item->total_panen }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Tanggal</label>
                                                    <input type="date" class="form-control" name="tanggal" value="{{ $item->tanggal }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>

                                        <!-- Form Hapus -->
                                        <form method="POST" action="{{ route('rencanapanen.destroy', $item->id) }}" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada data</p>
                    @endif

                    <!-- Tombol Tambah -->
                    <button class="btn w-100 border border-dark text-center mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <strong>+</strong>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('rencanapanen.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rencana Panen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Jenis Tebu</label>
                        <input type="text" class="form-control" name="jenis_tebu" required>
                    </div>
                    <div class="mb-3">
                        <label>Total Panen</label>
                        <input type="text" class="form-control" name="total_panen" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
