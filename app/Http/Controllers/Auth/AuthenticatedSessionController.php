<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login / masuk akun.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Silakan masukkan alamat email Anda.',
            'email.email'       => 'Format alamat email tidak valid.',
            'password.required' => 'Silakan masukkan kata sandi Anda.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan masuk yang salah. Silakan coba kembali dalam {$seconds} detik demi keamanan akun Anda.",
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        /** @var User $user */
        $user = Auth::user();

        // Pemeriksaan status akun aktif/nonaktif (NFR-01 Security & FR-15)
        if ($user->status_akun === 'nonaktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda dinonaktifkan oleh administrator. Silakan hubungi layanan bantuan Konsulaw.',
            ]);
        }

        $request->session()->regenerate();

        $pesanPeran = match ($user->role) {
            'advokat'    => 'Selamat datang, Advokat ' . ($user->nama ?? $user->name) . '.',
            'admin'      => 'Selamat datang, Administrator Konsulaw.',
            default      => 'Selamat datang di Konsulaw, ' . ($user->nama ?? $user->name) . '.',
        };

        // Advokat diarahkan ke Profil Saya (bukan dasbor) sesuai README 2.1.1
        if ($user->role === 'advokat') {
            return redirect()->route('advokat.profil.edit')->with('success', $pesanPeran);
        }

        return redirect()->intended('/')->with('success', $pesanPeran);
    }

    /**
     * Memproses keluar / logout pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar dari akun Konsulaw.');
    }
}
