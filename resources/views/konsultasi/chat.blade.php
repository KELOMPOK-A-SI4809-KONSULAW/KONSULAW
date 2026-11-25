@extends('layouts.app')

@section('title', 'Ruang Chat Konsultasi: ' . $konsultasi->judul . ' — Konsulaw')

@php
    $isMasyarakat = (auth()->id() === $konsultasi->masyarakat_id);
    $isAdvokat    = (auth()->id() === $konsultasi->advokat_id);

    $lawanBicara = $isMasyarakat ? $konsultasi->advokat : $konsultasi->masyarakat;
    $namaLawan   = $lawanBicara->nama ?? $lawanBicara->name ?? ($isMasyarakat ? 'Advokat' : 'Masyarakat');
    $parts       = preg_split('/\s+/', trim($namaLawan)) ?: ['L', 'B'];
    $inisialLawan= mb_strtoupper(mb_substr($parts[0] ?? 'L', 0, 1) . mb_substr($parts[1] ?? 'B', 0, 1));
    $pesanList   = $konsultasi->pesan ?? collect();
@endphp

@section('content')
<div class="min-h-[85vh] py-6 sm:py-8 bg-ivory">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        {{-- Chat Room Card Wrapper --}}
        <div class="rounded-[24px] border border-line bg-paper shadow-card overflow-hidden flex flex-col h-[75vh] min-h-[550px]">
            {{-- 1. Top Bar / Header Chat --}}
            <div class="border-b border-line bg-ivory/80 px-5 py-3.5 flex items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <a href="{{ route('konsultasi.show', $konsultasi->id) }}" class="p-1 rounded-full text-ink-soft hover:text-accent hover:bg-paper transition" title="Kembali ke Rincian Kasus">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </a>

                    {{-- Avatar Lawan Bicara --}}
                    @if ($lawanBicara->foto_profil)
                        <img src="{{ asset('storage/' . $lawanBicara->foto_profil) }}" alt="{{ $namaLawan }}" class="h-10 w-10 rounded-full object-cover shrink-0 ring-2 ring-accent-soft">
                    @else
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent text-white font-display font-bold text-sm ring-2 ring-accent-soft">
                            {{ $inisialLawan }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h2 class="font-display text-sm sm:text-base font-bold text-ink truncate leading-tight">
                                {{ $namaLawan }}
                            </h2>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $isMasyarakat ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                {{ $isMasyarakat ? 'Advokat' : 'Klien' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-ink-soft truncate mt-0.5">
                            {{ $konsultasi->judul }}
                        </p>
                    </div>
                </div>

                {{-- Action Header: Selesaikan Konsultasi / Status --}}
                <div class="flex items-center gap-2 shrink-0">
                    @if (! $isReadOnly)
                        <form action="{{ route('konsultasi.selesaikan', $konsultasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan sesi konsultasi hukum ini? Setelah selesai, ruang percakapan akan ditutup dalam mode arsip read-only.')">
                            @csrf
                            <button
                                type="submit"
                                class="rounded-full border border-line bg-paper hover:bg-red-50 hover:border-danger hover:text-danger px-3.5 py-1.5 text-xs font-bold text-ink-soft transition shadow-2xs"
                                title="Tandai konsultasi telah tuntas diselesaikan"
                            >
                                Selesaikan Konsultasi &check;
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 border border-neutral-300 px-3 py-1 text-xs font-semibold text-neutral-700">
                            Arsip (Read-Only)
                        </span>
                    @endif
                </div>
            </div>

            {{-- 2. Message History Box (Scrollable) --}}
            <div id="chat-box" class="flex-1 p-4 sm:p-6 overflow-y-auto space-y-4 bg-ivory/30">
                {{-- System Greeting Bubble --}}
                <div class="mx-auto max-w-lg rounded-[16px] bg-paper border border-line p-4 text-center shadow-2xs">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-accent">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Sesi Konsultasi Pra-Litigasi Resmi Dimulai
                    </span>
                    <p class="mt-1 text-xs text-ink-soft leading-relaxed">
                        Percakapan ini bersifat rahasia antara pemohon dan advokat. Sampaikan klarifikasi berkas atau pertanyaan hukum Anda di ruang ini.
                    </p>
                    <a href="{{ route('konsultasi.show', $konsultasi->id) }}" class="mt-2 inline-block text-[11px] font-bold text-accent hover:underline">
                        Lihat Pokok Kronologi & Berkas Awal &rarr;
                    </a>
                </div>

                {{-- Pesan-Pesan Percakapan --}}
                @foreach ($pesanList as $pesan)
                    @php
                        $isMe = ($pesan->pengirim_id === auth()->id());
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] sm:max-w-[70%] space-y-1">
                            {{-- Bubble Pesan --}}
                            <div class="rounded-[18px] p-4 text-xs sm:text-sm leading-relaxed {{ $isMe ? 'bg-accent text-white rounded-br-xs shadow-btn' : 'bg-paper text-ink border border-line rounded-bl-xs shadow-2xs' }}">
                                @if ($pesan->isi)
                                    <p class="whitespace-pre-line">{{ $pesan->isi }}</p>
                                @endif

                                {{-- Lampiran Berkas dalam Chat --}}
                                @if ($pesan->lampiran)
                                    <div class="mt-2.5 pt-2 border-t {{ $isMe ? 'border-white/20' : 'border-line' }} flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <svg class="h-4 w-4 shrink-0 {{ $isMe ? 'text-white' : 'text-accent' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                            </svg>
                                            <span class="text-xs truncate font-medium {{ $isMe ? 'text-white' : 'text-ink' }}">
                                                Lampiran Berkas
                                            </span>
                                        </div>
                                        <a
                                            href="{{ asset('storage/' . $pesan->lampiran) }}"
                                            target="_blank"
                                            class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase transition {{ $isMe ? 'bg-white/20 text-white hover:bg-white/30' : 'bg-ivory border border-line text-ink hover:text-accent' }}"
                                        >
                                            Buka Berkas
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Waktu Pesan --}}
                            <p class="text-[10px] text-ink-soft/70 px-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                                {{ $pesan->created_at->translatedFormat('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 3. Input Bar Pesan --}}
            @if (! $isReadOnly)
                <div class="border-t border-line bg-paper p-3 sm:p-4 shrink-0">
                    <form action="{{ route('konsultasi.chat.kirim', $konsultasi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <div class="flex items-end gap-2">
                            {{-- Lampirkan Dokumen Button --}}
                            <div class="relative shrink-0">
                                <label for="chat-file-input" class="flex h-10 w-10 items-center justify-center rounded-full bg-ivory border border-line text-ink-soft hover:text-accent hover:border-accent cursor-pointer transition" title="Lampirkan Dokumen (PDF/JPG/PNG, maks 5 MB)">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                    </svg>
                                </label>
                                <input
                                    type="file"
                                    id="chat-file-input"
                                    name="lampiran"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="hidden"
                                    onchange="tampilkanPreviewFile(this)"
                                >
                            </div>

                            {{-- Textarea Pesan --}}
                            <div class="flex-1 min-w-0">
                                <textarea
                                    name="isi"
                                    rows="1"
                                    placeholder="Tulis pesan konsultasi hukum Anda..."
                                    class="w-full resize-none rounded-[16px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                                    onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"
                                ></textarea>
                            </div>

                            {{-- Tombol Kirim Pesan --}}
                            <button
                                type="submit"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent hover:bg-accent-dark text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                                title="Kirim Pesan"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>

                        {{-- Preview Nama File Terpilih --}}
                        <div id="file-preview-strip" class="hidden text-xs text-ink-soft flex items-center justify-between bg-ivory px-3 py-1.5 rounded-[12px] border border-line">
                            <span id="file-preview-name" class="truncate font-medium text-ink"></span>
                            <button type="button" onclick="hapusFileTerpilih()" class="text-danger font-bold ml-2 hover:underline">
                                Hapus Berkas &times;
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Banner Read-Only saat Konsultasi Selesai (B2.5) --}}
                <div class="border-t border-line bg-neutral-50 p-4 text-center text-xs text-ink-soft shrink-0">
                    <p class="font-medium text-ink">
                        Sesi konsultasi ini telah resmi diselesaikan.
                    </p>
                    <p class="mt-0.5 text-[11px]">
                        Ruang pesan dikunci dalam mode baca (read-only). Seluruh riwayat telaah hukum dan berkas lampiran tetap dapat diakses untuk keperluan arsip Anda.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function scrollKeBawah() {
        const box = document.getElementById('chat-box');
        if (box) box.scrollTop = box.scrollHeight;
    }

    function tampilkanPreviewFile(input) {
        const strip = document.getElementById('file-preview-strip');
        const nameEl = document.getElementById('file-preview-name');
        if (input.files && input.files[0]) {
            nameEl.textContent = 'Berkas terlampir: ' + input.files[0].name + ' (' + Math.round(input.files[0].size / 1024) + ' KB)';
            strip.classList.remove('hidden');
        } else {
            strip.classList.add('hidden');
        }
    }

    function hapusFileTerpilih() {
        const input = document.getElementById('chat-file-input');
        input.value = '';
        document.getElementById('file-preview-strip').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', scrollKeBawah);
</script>
@endsection
