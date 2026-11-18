<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Konsulaw — Platform Bantuan Hukum dan Keterbukaan Layanan Publik Kota dan Desa.">
    <title>{{ $title ?? 'Konsulaw — Platform Bantuan Hukum & Keterbukaan Layanan Publik Kota dan Desa' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-neutral-50 text-neutral-900 antialiased selection:bg-red-700 selection:text-white">
    {{-- Tautan Aksesibilitas Lewati ke Konten Utama (NFR-04) --}}
    <a
        href="#konten-utama"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-red-800 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white focus:shadow-lg"
    >
        Lewati ke konten utama
    </a>

    {{-- Navbar Utama (Desktop & Mobile Header) --}}
    @include('components.navbar')

    {{-- Konten Utama Halaman --}}
    <main id="konten-utama" class="flex-1">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    {{-- Footer Utama --}}
    @include('components.footer')

    {{-- Navigasi Bawah Khusus Tampilan Mobile (NFR-04) --}}
    @include('components.bottom-nav')
</body>
</html>
