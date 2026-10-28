<?php

namespace App\Http\Controllers;

use App\Models\ProBonoApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ProBonoApplicationController extends Controller
{
    public function create(): View
    {
        $application = Auth::check()
            ? ProBonoApplication::with('documents')
                ->where('user_id', Auth::id())
                ->latest('id')
                ->first()
            : null;

        return view('pro_bono.create', compact('application'));
    }

    public function store(Request $request)
    {
        if (! Auth::check()) {
            return back()->withErrors([
                'auth' => 'Silakan login sebagai masyarakat sebelum mengirim pengajuan.',
            ]);
        }

        $userId = (int) Auth::id();

        $previousApplication = ProBonoApplication::where('user_id', $userId)
            ->latest('id')
            ->first();

        if (
            $previousApplication &&
            in_array(
                $previousApplication->status,
                ['pending', 'pro_bono'],
                true
            )
        ) {
            return back()->with(
                'error',
                'Pengajuan baru belum dapat dibuat karena status sebelumnya masih Pending atau sudah Pro Bono.'
            );
        }

        $validated = $request->validate([
            'documents' => ['required', 'array', 'min:1', 'max:3'],
            'documents.*' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $validated,
                $userId,
                &$storedPaths
            ) {
                $application = ProBonoApplication::create([
                    'user_id' => $userId,
                    'status' => 'pending',
                ]);

                foreach ($validated['documents'] as $file) {
                    $path = $file->store(
                        "pro-bono/{$userId}",
                        'local'
                    );

                    $storedPaths[] = $path;

                    $application->documents()->create([
                        'original_name' => mb_substr(
                            $file->getClientOriginalName(),
                            0,
                            255
                        ),
                        'file_path' => $path,
                        'mime_type' => $file->getMimeType()
                            ?? 'application/octet-stream',
                        'size_bytes' => $file->getSize(),
                    ]);
                }
            });
        } catch (Throwable $exception) {
            if ($storedPaths !== []) {
                Storage::disk('local')->delete($storedPaths);
            }

            throw $exception;
        }

        return redirect()
            ->route('pro-bono.create')
            ->with(
                'success',
                'Pengajuan berhasil disimpan dan menunggu pemeriksaan admin.'
            );
    }
}