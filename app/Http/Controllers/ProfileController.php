<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman profil sesuai peran akun masing-masing.
     */
    public function index(Request $request): RedirectResponse
    {
        $role = $request->user()->role;

        return match ($role) {
            'advokat'    => redirect()->route('advokat.dashboard'),
            'masyarakat' => redirect()->route('masyarakat.profil.edit'),
            default      => redirect('/'),
        };
    }
}
