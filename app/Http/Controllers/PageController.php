<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RencanaGiling;
use App\Models\RencanaPanen;
use Illuminate\Support\Facades\DB;



class PageController extends Controller
{
    // Halaman login

    // ========================
    // PABRIK
    // ========================

    public function dashboardPabrik()
    {
        $pabrik = Auth::user();
        $riwayat = [
            ['petani' => 'Petani A', 'jumlah' => '10 ton', 'tanggal' => '2025-05-01'],
            ['petani' => 'Petani B', 'jumlah' => '8 ton', 'tanggal' => '2025-05-02'],
        ];

        return view('pabrik.dashboard', compact('pabrik', 'riwayat'));
    }

    public function jadwalPanen()
    {
    // Ambil semua user dengan role pabrik
    $petanis = \App\Models\User::where('role', 'petani')->get();

    return view('pabrik.jadwalpanen', compact('petanis'));
    }

    public function rencanaPanenByPabrik(Request $request, $userId)
    {
    $tahun = $request->get('tahun', date('Y'));
    $petani = \App\Models\User::findOrFail($userId);

    $rencana = RencanaPanen::whereYear('tanggal', $tahun)
        ->where('user_id', $userId)
        ->get();

    $dataPerBulan = [];

    foreach ($rencana as $item) {
        $bulanNama = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('F');
        $dataPerBulan[$bulanNama][] = $item;
    }

    return view('pabrik.rencanapanen', [
        'tahunDipilih' => $tahun,
        'dataPerBulan' => $dataPerBulan,
        'petani' => $petani,
    ]);
    }

    public function rencanaGiling(Request $request)
    {
    $tahun = $request->get('tahun', date('Y'));
    $userId = Auth::user()->id;

    $rencana = RencanaGiling::whereYear('tanggal', $tahun)
        ->where('user_id', $userId)
        ->get();

    $dataPerBulan = [];

    foreach ($rencana as $item) {
        $bulanNama = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('F'); // Januari, Februari, ...
        $dataPerBulan[$bulanNama][] = $item;
    }

    return view('pabrik.rencanagiling', [
        'tahunDipilih' => $tahun,
        'dataPerBulan' => $dataPerBulan,
    ]);
    }

    public function storeRencanaGiling(Request $request)
    {
    $request->validate([
        'kebutuhan_giling' => 'required|string',
        'tanggal' => 'required|date',
    ]);

    RencanaGiling::create([
        'user_id' => Auth::user()->id,
        'kebutuhan_giling' => $request->kebutuhan_giling,
        'tanggal' => $request->tanggal,
        'status' => 'Menunggu',
    ]);

    return redirect()->route('pabrik.rencanagiling', ['tahun' => date('Y', strtotime($request->tanggal))])
    ->with('success', 'Data berhasil ditambahkan!');
    }
    public function destroyGiling($id)
    {
    $rencana = RencanaGiling::findOrFail($id);
    $rencana->delete();

    return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
    public function updateGiling(Request $request, $id)
    {
    $request->validate([
        'kebutuhan_giling' => 'required|string',
        'tanggal' => 'required|date',
    ]);

    $rencana = RencanaGiling::findOrFail($id);

    // Pastikan hanya user yang berhak yang bisa mengubah data
    if ($rencana->user_id !== Auth::user()->id) {
        abort(403, 'Akses ditolak');
    }

    $rencana->update([
        'kebutuhan_giling' => $request->kebutuhan_giling,
        'tanggal' => $request->tanggal,
    ]);

    return redirect()->route('pabrik.rencanagiling', ['tahun' => date('Y', strtotime($request->tanggal))])
        ->with('success', 'Data berhasil diperbarui!');

    }

    public function permintaanTerima()
    {
        $pabrikId = Auth::user()->id; // Ambil ID pabrik yang login

        // Ambil hanya rencana giling milik pabrik ini
        $rencanaDenganPengajuan = RencanaGiling::where('user_id', $pabrikId)
            ->whereHas('pengaju', function ($query) {
                $query->where('petani_rencana_giling.status', 'Menunggu Persetujuan');
            })
            ->with(['pengaju' => function ($query) {
                $query->where('petani_rencana_giling.status', 'Menunggu Persetujuan');
            }])
            ->get();

        return view('pabrik.permintaan', compact('rencanaDenganPengajuan'));
    }


    public function konfirmasiAjuan(Request $request, $rencanaGilingId, $petaniId)
    {
        $status = $request->input('status'); // "Disetujui" atau "Ditolak"

        DB::beginTransaction();
        try {
            // Update status di pivot untuk petani yg dikonfirmasi
            DB::table('petani_rencana_giling')
                ->where('rencana_giling_id', $rencanaGilingId)
                ->where('petani_id', $petaniId)
                ->update(['status' => $status]);

            if ($status === 'Disetujui') {
                // Tolak semua pengajuan lain di pivot yang berkaitan dengan rencana ini
                DB::table('petani_rencana_giling')
                    ->where('rencana_giling_id', $rencanaGilingId)
                    ->where('petani_id', '!=', $petaniId)
                    ->update(['status' => 'Ditolak']);

                // Ubah status di rencana giling menjadi "Disetujui"
                RencanaGiling::where('id', $rencanaGilingId)
                    ->update(['status' => 'Disetujui']);
            }

            DB::commit();
            return back()->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function riwayatTerima(Request $request)
    {
        $tahun = $request->input('tahun');
        $pabrikId = Auth::user()->id; // ID pabrik yang login

        $riwayat = DB::table('petani_rencana_giling')
            ->join('users', 'petani_rencana_giling.petani_id', '=', 'users.id')
            ->join('rencana_gilings', 'petani_rencana_giling.rencana_giling_id', '=', 'rencana_gilings.id')
            ->select(
                'users.name as nama_petani',
                'rencana_gilings.tanggal as tanggal_rencana',
                'petani_rencana_giling.status',
                'petani_rencana_giling.tanggal_diajukan'
            )
            ->where('rencana_gilings.user_id', $pabrikId) // Filter sesuai pabrik yang login
            ->whereIn('petani_rencana_giling.status', ['Disetujui', 'Ditolak'])
            ->when($tahun, function ($query, $tahun) {
                return $query->whereYear('rencana_gilings.tanggal', $tahun);
            })
            ->orderBy('rencana_gilings.tanggal', 'desc')
            ->get();

        return view('pabrik.riwayatterima', compact('riwayat', 'tahun'));
    }


    public function ajukanTerima($id)
    {
    $rencana = RencanaPanen::findOrFail($id);
    $pabrik = auth()->user();

    // Cek apakah sudah pernah mengajukan
    if ($rencana->pengaju()->where('pabrik_id', $pabrik->id)->exists()) {
        return back()->with('warning', 'Anda sudah mengajukan untuk tanggal ini.');
    }

    $rencana->pengaju()->attach($pabrik->id, [
        'status' => 'Menunggu Persetujuan',
        'tanggal_diajukan' => now(),
    ]);

    return back()->with('success', 'Pengajuan berhasil dikirim.');
    }



    public function profilPabrik()
    {
        $pabrik = Auth::user();
        return view('pabrik.profil', compact('pabrik'));
    }

    // ========================
    // PETANI
    // ========================

    public function dashboardPetani()
    {
        $petani = Auth::user();
        $riwayat = [
            ['pabrik' => 'Pabrik A', 'status' => 'Diterima', 'tanggal' => '2025-05-01'],
            ['pabrik' => 'Pabrik B', 'status' => 'Ditolak', 'tanggal' => '2025-05-03'],
        ];

        return view('petani.dashboard', compact('petani', 'riwayat'));
    }

    public function rencanaPanen(Request $request)
    {
    $tahun = $request->get('tahun', date('Y'));
    $userId = Auth::user()->id;

    $rencana = RencanaPanen::whereYear('tanggal', $tahun)
        ->where('user_id', $userId)
        ->get();

    $dataPerBulan = [];

    foreach ($rencana as $item) {
        $bulanNama = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('F'); // Januari, Februari, ...
        $dataPerBulan[$bulanNama][] = $item;
    }

    return view('petani.rencanapanen', [
        'tahunDipilih' => $tahun,
        'dataPerBulan' => $dataPerBulan,
    ]);
    }

    public function storeRencanaPanen(Request $request)
    {
    $request->validate([
        'jenis_tebu' => 'required|string',
        'total_panen' => 'required|string',
        'tanggal' => 'required|date',
    ]);

    RencanaPanen::create([
        'user_id' => Auth::user()->id,
        'jenis_tebu' => $request->jenis_tebu,
        'total_panen' => $request->total_panen,
        'tanggal' => $request->tanggal,
        'status' => 'Menunggu',
    ]);

    return redirect()->route('petani.rencanapanen', ['tahun' => date('Y', strtotime($request->tanggal))])
    ->with('success', 'Data berhasil ditambahkan!');
    }
    public function destroyPanen($id)
    {
    $rencana = RencanaPanen::findOrFail($id);
    $rencana->delete();

    return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
    public function updatePanen(Request $request, $id)
    {
    $request->validate([
        'jenis_tebu' => 'required|string',
        'total_panen' => 'required|string',
        'tanggal' => 'required|date',
    ]);

    $rencana = RencanaPanen::findOrFail($id);

    // Pastikan hanya user yang berhak yang bisa mengubah data
    if ($rencana->user_id !== Auth::user()->id) {
        abort(403, 'Akses ditolak');
    }

    $rencana->update([
        'jenis_tebu' => $request->jenis_tebu,
        'total_panen' => $request->total_panen,
        'tanggal' => $request->tanggal,
    ]);

    return redirect()->route('petani.rencanapanen', ['tahun' => date('Y', strtotime($request->tanggal))])
        ->with('success', 'Data berhasil diperbarui!');

    }

    public function permintaanSetor()
    {
    $petaniId = Auth::user()->id; // ambil ID user (petani) yang login

    $rencanaDenganPengajuan = RencanaPanen::where('user_id', $petaniId)
        ->whereHas('pengaju', function ($query) {
            $query->where('pabrik_rencana_panen.status', 'Menunggu Persetujuan');
        })
        ->with(['pengaju' => function ($query) {
            $query->where('pabrik_rencana_panen.status', 'Menunggu Persetujuan');
        }])
        ->get();

    return view('petani.permintaan', compact('rencanaDenganPengajuan'));
    }

    public function konfirmasiSetor(Request $request, $rencanaPanenId, $pabrikId)
    {
        $status = $request->input('status'); // "Disetujui" atau "Ditolak"

        DB::beginTransaction();
        try {
            // Update status di pivot untuk petani yg dikonfirmasi
            DB::table('pabrik_rencana_panen')
                ->where('rencana_panen_id', $rencanaPanenId)
                ->where('pabrik_id', $pabrikId)
                ->update(['status' => $status]);

            if ($status === 'Disetujui') {
                // Tolak semua pengajuan lain di pivot yang berkaitan dengan rencana ini
                DB::table('pabrik_rencana_panen')
                    ->where('rencana_panen_id', $rencanaPanenId)
                    ->where('pabrik_id', '!=', $pabrikId)
                    ->update(['status' => 'Ditolak']);

                // Ubah status di rencana giling menjadi "Disetujui"
                RencanaPanen::where('id', $rencanaPanenId)
                    ->update(['status' => 'Disetujui']);
            }

            DB::commit();
            return back()->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayatSetor(Request $request)
    {
        $tahun = $request->input('tahun');
        $petaniId = Auth::user()->id; // ambil id petani yang login

        $riwayat = DB::table('pabrik_rencana_panen')
            ->join('users', 'pabrik_rencana_panen.pabrik_id', '=', 'users.id')
            ->join('rencana_panens', 'pabrik_rencana_panen.rencana_panen_id', '=', 'rencana_panens.id')
            ->select(
                'users.name as nama_pabrik',
                'rencana_panens.tanggal as tanggal_rencana',
                'pabrik_rencana_panen.status',
                'pabrik_rencana_panen.tanggal_diajukan'
            )
            ->where('rencana_panens.user_id', $petaniId) // filter hanya data milik petani login
            ->whereIn('pabrik_rencana_panen.status', ['Disetujui', 'Ditolak'])
            ->when($tahun, function ($query, $tahun) {
                return $query->whereYear('rencana_panens.tanggal', $tahun);
            })
            ->orderBy('rencana_panens.tanggal', 'desc')
            ->get();

        return view('petani.riwayatsetor', compact('riwayat', 'tahun'));
    }


    public function jadwalGiling()
    {
    // Ambil semua user dengan role pabrik
    $pabriks = \App\Models\User::where('role', 'pabrik')->get();

    return view('petani.jadwalgiling', compact('pabriks'));
    }

    public function rencanaGilingByPetani(Request $request, $userId)
    {
    $tahun = $request->get('tahun', date('Y'));
    $pabrik = \App\Models\User::findOrFail($userId);

    $rencana = RencanaGiling::whereYear('tanggal', $tahun)
        ->where('user_id', $userId)
        ->get();

    $dataPerBulan = [];

    foreach ($rencana as $item) {
        $bulanNama = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('F');
        $dataPerBulan[$bulanNama][] = $item;
    }

    return view('petani.rencanagiling', [
        'tahunDipilih' => $tahun,
        'dataPerBulan' => $dataPerBulan,
        'pabrik' => $pabrik,
    ]);
    }


    public function ajukanSetoran($id)
    {
    $rencana = RencanaGiling::findOrFail($id);
    $petani = auth()->user();

    // Cek apakah sudah pernah mengajukan
    if ($rencana->pengaju()->where('petani_id', $petani->id)->exists()) {
        return back()->with('warning', 'Anda sudah mengajukan untuk tanggal ini.');
    }

    $rencana->pengaju()->attach($petani->id, [
        'status' => 'Menunggu Persetujuan',
        'tanggal_diajukan' => now(),
    ]);

    return back()->with('success', 'Pengajuan berhasil dikirim.');
    }





    public function profilPetani()
    {
        $petani = Auth::user();
        return view('petani.profil', compact('petani'));
    }
}
