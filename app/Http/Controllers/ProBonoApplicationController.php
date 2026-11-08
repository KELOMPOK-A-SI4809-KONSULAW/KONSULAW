<?php

namespace App\Http\Controllers;

use App\Models\VerifikasiEkonomi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class ProBonoApplicationController extends Controller
{
    public function create(): View
    {
        $verifikasi = auth()->check()
            ? VerifikasiEkonomi::where('user_id', auth()->id())
                ->latest('id')
                ->first()
            : null;

        return view('pro_bono.create', compact('verifikasi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user) {
            return back()->withErrors([
                'auth' => 'Silakan login terlebih dahulu.',
            ]);
        }

        if ($user->role !== 'masyarakat') {
            return back()->withErrors([
                'auth' => 'Hanya masyarakat yang dapat mengajukan verifikasi ekonomi.',
            ]);
        }

        $existing = VerifikasiEkonomi::where('user_id', $user->id)
            ->latest('id')
            ->first();

        if ($existing && in_array(
            $existing->status,
            ['pending', 'disetujui'],
            true
        )) {
            return back()->with(
                'error',
                'Pengajuan sebelumnya masih diproses atau sudah disetujui.'
            );
        }

        $validated = $request->validate([
            'dokumen' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $path = $validated['dokumen']->store(
            "verifikasi-ekonomi/{$user->id}",
            'local'
        );

        try {
            DB::transaction(function () use ($user, $path) {
                VerifikasiEkonomi::create([
                    'user_id' => $user->id,
                    'dokumen' => $path,
                    'status' => 'pending',
                ]);

                $user->update([
                    'status_ekonomi' => 'pending',
                ]);
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return redirect()
            ->route('pro-bono.create')
            ->with('success', 'Pengajuan berhasil dikirim dan menunggu pemeriksaan admin.');
    }
}
