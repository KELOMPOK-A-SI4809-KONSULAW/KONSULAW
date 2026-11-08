<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerifikasiEkonomi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProBonoApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_pro_bono_form(): void
    {
        $response = $this->get('/pro-bono');

        $response->assertOk();
        $response->assertSee('Pengajuan Status Pro Bono');
    }

    public function test_guest_cannot_submit_pro_bono_application(): void
    {
        $response = $this->post('/pro-bono', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors('auth');
    }

    public function test_authenticated_user_can_submit_document(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'bukti.pdf',
            100,
            'application/pdf'
        );

        $response = $this->actingAs($user)
            ->from('/pro-bono')
            ->post('/pro-bono', [
                'dokumen' => $file,
            ]);

        $response->assertRedirect(route('pro-bono.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('verifikasi_ekonomi', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $verifikasi = VerifikasiEkonomi::where(
            'user_id',
            $user->id
        )->firstOrFail();

        Storage::disk('local')->assertExists(
            $verifikasi->dokumen
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status_ekonomi' => 'pending',
        ]);
    }

    public function test_unsupported_document_type_is_rejected(): void
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create(
            'dokumen.txt',
            10,
            'text/plain'
        );

        $response = $this->actingAs($user)
            ->from('/pro-bono')
            ->post('/pro-bono', [
                'dokumen' => $file,
            ]);

        $response->assertRedirect('/pro-bono');
        $response->assertSessionHasErrors('dokumen');

        $this->assertDatabaseCount('verifikasi_ekonomi', 0);
    }
}