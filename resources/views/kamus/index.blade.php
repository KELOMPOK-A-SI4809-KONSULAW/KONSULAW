@extends('layouts.app')

@section('content')
<div class="min-h-[65vh] py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl text-center">
        <div class="rounded-[20px] border border-line bg-paper p-8 sm:p-14 shadow-card">
            <span class="inline-flex items-center rounded-full bg-accent-soft px-3.5 py-1 text-xs font-bold text-accent-dark">
                Fitur Kamus Hukum
            </span>
            <h1 class="mt-4 font-display text-2xl sm:text-3xl font-bold text-ink">
                Kamus Istilah Hukum &amp; Peraturan
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-ink-soft max-w-md mx-auto leading-relaxed">
                Modul edukasi istilah hukum awam dan direktori perundang-undangan akan dikembangkan pada jadwal Jira berikutnya (PBI-07 / LAW-16).
            </p>
            <div class="mt-8">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center rounded-full bg-accent px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark hover:-translate-y-0.5 active:scale-95"
                >
                    &larr; Kembali ke Beranda Advokat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
