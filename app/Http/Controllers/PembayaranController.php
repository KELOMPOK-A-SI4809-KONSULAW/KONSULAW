<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Pembayaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    /**
     * Inisialisasi Snap Token untuk pembayaran Midtrans (B3.1 s.d. B3.4).
     */
    public function inisialisasi(Request $request, int $konsultasiId): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::with(['advokat.profilAdvokat', 'pembayaran'])->findOrFail($konsultasiId);

        if ($user->id !== $konsultasi->masyarakat_id) {
            abort(403, 'Akses ditolak.');
        }

        if ($konsultasi->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Konsultasi ini tidak dalam status menunggu pembayaran.');
        }

        $tarif = $konsultasi->advokat->profilAdvokat->tarif_konsultasi ?? 0;
        if ($tarif <= 0) {
            // Jika tarif 0, langsung ubah menjadi berlangsung
            $konsultasi->update(['status' => 'berlangsung']);
            return redirect()->route('konsultasi.chat', $konsultasi->id)
                ->with('success', 'Konsultasi gratis berhasil diaktifkan. Ruang chat telah terbuka.');
        }

        $pembayaran = $konsultasi->pembayaran;
        if (! $pembayaran) {
            $orderId = 'KSL-' . $konsultasi->id . '-' . time();
            $pembayaran = Pembayaran::create([
                'konsultasi_id' => $konsultasi->id,
                'order_id'      => $orderId,
                'jumlah'        => $tarif,
                'status'        => 'pending',
            ]);
        }

        // Jika sudah ada token yang valid, gunakan token tersebut
        if ($pembayaran->snap_token) {
            if ($request->wantsJson()) {
                return response()->json(['snap_token' => $pembayaran->snap_token]);
            }
            return back();
        }

        try {
            Config::$serverKey    = config('midtrans.server_key');
            Config::$isProduction = (bool) config('midtrans.is_production', false);
            Config::$isSanitized  = (bool) config('midtrans.is_sanitized', true);
            Config::$is3ds        = (bool) config('midtrans.is_3ds', true);

            $params = [
                'transaction_details' => [
                    'order_id'     => $pembayaran->order_id,
                    'gross_amount' => (int) $pembayaran->jumlah,
                ],
                'customer_details' => [
                    'first_name' => $user->nama ?? $user->name,
                    'email'      => $user->email,
                    'phone'      => $user->no_hp ?? '081234567890',
                ],
                'item_details' => [[
                    'id'       => 'KONSUL-' . $konsultasi->id,
                    'price'    => (int) $pembayaran->jumlah,
                    'quantity' => 1,
                    'name'     => 'Konsultasi: ' . mb_substr($konsultasi->judul, 0, 45),
                ]],
                'expiry' => [
                    'unit'     => 'hours',
                    'duration' => 24,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $pembayaran->update(['snap_token' => $snapToken]);

            if ($request->wantsJson()) {
                return response()->json(['snap_token' => $snapToken]);
            }
            return back();
        } catch (\Throwable $e) {
            Log::warning('Midtrans Snap Exception: ' . $e->getMessage());

            // Token dummy fallback untuk sandbox lokal jika kredensial demo belum diset
            $dummyToken = 'DEMO_SNAP_' . md5($pembayaran->order_id);
            $pembayaran->update(['snap_token' => $dummyToken]);

            if ($request->wantsJson()) {
                return response()->json(['snap_token' => $dummyToken]);
            }
            return back();
        }
    }

    /**
     * Simulasi Pembayaran Sandbox Sukses (B3.4 Cadangan Demo).
     * Sangat berguna untuk pengujian lokal saat webhook belum tersambung ke publik.
     */
    public function simulasiSukses(Request $request, int $konsultasiId): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::with('pembayaran', 'advokat')->findOrFail($konsultasiId);

        if ($user->id !== $konsultasi->masyarakat_id && $user->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($konsultasi->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Konsultasi ini tidak dalam antrean pembayaran.');
        }

        $pembayaran = $konsultasi->pembayaran ?? Pembayaran::create([
            'konsultasi_id' => $konsultasi->id,
            'order_id'      => 'KSL-' . $konsultasi->id . '-' . time(),
            'jumlah'        => $konsultasi->advokat->profilAdvokat->tarif_konsultasi ?? 0,
            'status'        => 'pending',
        ]);

        $pembayaran->update([
            'status'             => 'paid',
            'metode'             => 'qris_sandbox',
            'transaction_id'     => 'TRX-SIM-' . strtoupper(bin2hex(random_bytes(6))),
            'dibayar_pada'       => now(),
            'payload_notifikasi' => json_encode(['simulasi' => true, 'timestamp' => now()->toIso8601String()]),
        ]);

        // Aktifkan konsultasi -> status 'berlangsung', chat terbuka otomatis (B2.5)
        $konsultasi->update(['status' => 'berlangsung']);

        return redirect()->route('konsultasi.chat', $konsultasi->id)
            ->with('success', 'Pembayaran sandbox sukses terkonfirmasi! Ruang chat konsultasi kini telah resmi aktif.');
    }

    /**
     * Webhook Notifikasi Midtrans Server-to-Server (B3.3, B3.4).
     * Endpoint: POST /midtrans/notification (Exempt CSRF).
     */
    public function webhook(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId     = $payload['order_id'] ?? null;
        $statusCode  = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signature   = $payload['signature_key'] ?? null;

        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signature) {
            return response()->json(['message' => 'Payload tidak lengkap.'], 400);
        }

        // Verifikasi Signature Key (B3.3)
        $serverKey = config('midtrans.server_key');
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signature !== $validSignature) {
            Log::warning("Midtrans Webhook: Signature tidak cocok untuk order {$orderId}");
            return response()->json(['message' => 'Invalid signature key.'], 403);
        }

        $pembayaran = Pembayaran::with('konsultasi')->where('order_id', $orderId)->first();
        if (! $pembayaran) {
            return response()->json(['message' => 'Order pembayaran tidak ditemukan.'], 404);
        }

        // Cek idempotent: Jika sudah 'paid', jangan proses ulang (B3.3)
        if ($pembayaran->status === 'paid') {
            return response()->json(['message' => 'Transaksi sudah diproses sebelumnya.'], 200);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? 'accept';
        $paymentType       = $payload['payment_type'] ?? null;

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept') {
                $pembayaran->update([
                    'status'             => 'paid',
                    'metode'             => $paymentType,
                    'transaction_id'     => $payload['transaction_id'] ?? null,
                    'dibayar_pada'       => now(),
                    'payload_notifikasi' => json_encode($payload),
                ]);

                // Buka chat otomatis (B2.5)
                $pembayaran->konsultasi->update(['status' => 'berlangsung']);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pembayaran->update([
                'status'             => ($transactionStatus === 'expire') ? 'expired' : 'cancelled',
                'payload_notifikasi' => json_encode($payload),
            ]);

            $pembayaran->konsultasi->update(['status' => 'kedaluwarsa']);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
