<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProfilAdvokat;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran akun baru.
     */
    public function create(): View
    {
        $wilayahList = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('auth.register', compact('wilayahList'));
    }

    /**
     * Memproses penyimpanan pendaftaran akun baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp'      => ['required', 'string', 'max:20'],
            'role'       => ['required', 'in:masyarakat,advokat'],
            'wilayah_id' => ['nullable', 'exists:wilayah,id'],
            'password'   => ['required', 'confirmed', Password::min(8)],
        ], [
            'nama.required'       => 'Nama lengkap wajib diisi.',
            'email.required'      => 'Alamat email wajib diisi.',
            'email.email'         => 'Format alamat email tidak valid.',
            'email.unique'        => 'Alamat email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'no_hp.required'      => 'Nomor telepon/WhatsApp wajib diisi.',
            'role.required'       => 'Pilih peran akun (Masyarakat atau Advokat).',
            'role.in'             => 'Pilihan peran tidak valid.',
            'wilayah_id.exists'   => 'Wilayah yang dipilih tidak valid.',
            'password.required'   => 'Kata sandi wajib diisi.',
            'password.confirmed'  => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'        => 'Kata sandi minimal harus terdiri dari 8 karakter.',
        ]);

        $user = DB::transaction(function () use ($request) {
            $role = $request->input('role');

            $user = User::create([
                'nama'           => $request->input('nama'),
                'email'          => $request->input('email'),
                'password'       => Hash::make($request->input('password')),
                'no_hp'          => $request->input('no_hp'),
                'wilayah_id'     => $request->input('wilayah_id'),
                'role'           => $role,
                'status_akun'    => 'aktif',
                'status_ekonomi' => $role === 'masyarakat' ? 'reguler' : null,
            ]);

            // Jika mendaftar sebagai advokat, siapkan profil advokat awal (pending verifikasi dokumen)
            if ($role === 'advokat') {
                ProfilAdvokat::create([
                    'user_id'           => $user->id,
                    'status_verifikasi' => 'pending',
                    'ketersediaan'      => true,
                    'kuota_probono'     => 0,
                    'tarif_konsultasi'  => 0,
                ]);
            }

            return $user;
        });

        Auth::login($user);

        if ($user->role === 'advokat') {
            return redirect('/')->with(
                'success',
                'Pendaftaran berhasil! Akun Advokat Anda telah dibuat. Silakan lengkapi berkas kualifikasi (Ijazah SH, BAS, dan KTA) untuk ditinjau oleh Admin.'
            );
        }

        return redirect('/')->with(
            'success',
            'Pendaftaran berhasil! Selamat datang di platform bantuan hukum Konsulaw.'
        );
    }
}
