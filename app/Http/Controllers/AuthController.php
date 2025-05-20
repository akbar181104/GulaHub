<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ✅ Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // ✅ Proses login
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits_between:10,15',
            'password' => 'required',
        ]);

        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return match ($user->role) {
                'petani' => redirect()->route('petani.dashboard'),
                'pabrik' => redirect()->route('pabrik.dashboard'),
                // 'admin'  => redirect()->route('admin.dashboard'),
                default => redirect()->route('login')->withErrors(['role' => 'Role tidak dikenali.']),
            };
        }

        return back()->withErrors(['phone' => 'Nomor HP atau password salahhhhh.'])->withInput();
    }

    // ✅ Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    // ✅ Tampilkan halaman register petani
    public function showRegisterPetani()
    {
        return view('auth.register_petani');
    }

    // ✅ Proses register petani
    public function registerPetani(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|unique:users|digits_between:10,15',
            'alamat'   => 'required|string|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'alamat'   => $request->alamat,
            'password' => Hash::make($request->password),
            'role'     => 'petani',
        ]);

        return redirect()->route('login')->with('success', 'Berhasil daftar sebagai petani.');
    }

    // ✅ Tampilkan halaman register pabrik
    public function showRegisterPabrik()
    {
        return view('auth.register_pabrik');
    }

    // ✅ Proses register pabrik
    public function registerPabrik(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|unique:users|digits_between:10,15',
            'alamat'   => 'required|string|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'alamat'   => $request->alamat,
            'password' => Hash::make($request->password),
            'role'     => 'pabrik',
        ]);

        return redirect()->route('login')->with('success', 'Berhasil daftar sebagai pabrik.');
    }
}
