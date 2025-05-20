<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('pabrik.jadwalpanen');
    }

    public function rencanaGiling()
    {
        return view('pabrik.rencanagiling');
    }

    public function permintaanTerima()
    {
        return view('pabrik.permintaan');
    }

    public function riwayatTerima()
    {
        return view('pabrik.riwayatterima');
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

    public function rencanaPanen()
    {
        return view('petani.rencanapanen');
    }

    public function permintaanSetor()
    {
        return view('petani.permintaan');
    }

    public function jadwalGiling()
    {
        return view('petani.jadwalgiling');
    }

    public function riwayatSetor()
    {
        return view('petani.riwayatsetor');
    }

    public function profilPetani()
    {
        $petani = Auth::user();
        return view('petani.profil', compact('petani'));
    }
}
