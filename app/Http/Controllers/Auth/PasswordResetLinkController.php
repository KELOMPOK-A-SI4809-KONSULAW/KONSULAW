<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Menampilkan formulir permintaan tautan pemulihan kata sandi.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses pengiriman tautan pemulihan kata sandi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Silakan masukkan alamat email yang terdaftar.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            // Sesuai standar OWASP (NFR-01 Security): jangan membocorkan apakah email terdaftar atau tidak
            return back()->with('status', 'Jika alamat email tersebut terdaftar di sistem Konsulaw, tautan pemulihan kata sandi telah dikirimkan.');
        }

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan pemulihan kata sandi telah berhasil dikirimkan ke alamat email Anda.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
