<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir pengelolaan data diri masyarakat.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $wilayahList = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('masyarakat.profil', compact('user', 'wilayahList'));
    }

    /**
     * Memperbarui informasi data diri masyarakat.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'nik'        => ['nullable', 'numeric', 'digits:16'],
            'no_hp'      => ['required', 'string', 'max:20'],
            'wilayah_id' => ['nullable', 'exists:wilayah,id'],
            'alamat'     => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'nik.digits'        => 'NIK harus berjumlah tepat 16 digit angka.',
            'nik.numeric'       => 'NIK hanya boleh berupa karakter angka.',
            'no_hp.required'    => 'Nomor handphone / WhatsApp wajib diisi.',
            'wilayah_id.exists' => 'Wilayah yang dipilih tidak valid.',
            'alamat.max'        => 'Alamat maksimal terdiri dari 500 karakter.',
        ]);

        $user->update($validated);

        return redirect()->route('masyarakat.profil.edit')
            ->with('success', 'Data profil Anda berhasil diperbarui!');
    }
}
