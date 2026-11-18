@extends('layouts.app')

@section('content')
<div class="min-h-[60vh] bg-neutral-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl text-center">
        <div class="rounded-3xl border border-neutral-300 bg-white p-8 sm:p-12 shadow-xs">
            <span class="inline-block rounded-md bg-neutral-100 px-3 py-1 text-xs font-bold text-neutral-700">
                Fitur Cari Advokat
            </span>
            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold text-neutral-950">
                Pencarian &amp; Direktori Lengkap Advokat
            </h1>
            <p class="mt-2 text-sm text-neutral-600 max-w-md mx-auto">
                Halaman pencarian lanjutan dan filter spesialisasi advokat akan dikembangkan pada jadwal Jira berikutnya (LAW-14 &amp; LAW-15).
            </p>
            <div class="mt-6">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center rounded-xl bg-red-800 px-5 py-2.5 text-xs font-bold text-white transition hover:bg-neutral-950"
                >
                    &larr; Kembali ke Beranda Advokat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
