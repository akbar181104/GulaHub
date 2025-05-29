@extends('layouts.petani')

@section('content')
<div class="container">
    <h3>Rencana Giling - {{ $pabrik->name }} ({{ $tahunDipilih }})</h3>

    <!-- Filter Tahun -->
    <form method="GET" action="{{ route('petani.rencanagiling', $pabrik->id) }}">
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
                        <!-- Item -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" class="text-dark text-decoration-none">
                            <div class="border p-2 mb-2 rounded bg-light d-flex justify-content-between align-items-center hover-shadow">
                                <div>
                                    <i class="bi bi-leaf"></i>
                                    Tanggal {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                </div>
                                <span class="badge bg-secondary">{{ $item->status }}</span>
                            </div>
                        </a>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">Detail Rencana Giling</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Kebutuhan Giling:</strong> {{ $item->kebutuhan_giling }} Ton</p>
                                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <div class="text-center mt-4 pb-3">
                                        <form method="POST" action="{{ route('petani.ajukan', $item->id) }}">
                                            @csrf
                                            <input type="hidden" name="tanggal" id="modalTanggalInput">
                                            <input type="hidden" name="pabrik_id" value="{{ $pabrik->id }}">
                                            <button type="submit" class="btn btn-primary">Ajukan ke Pabrik</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @else
                        <p class="text-muted">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
