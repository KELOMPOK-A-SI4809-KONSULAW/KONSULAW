<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Konsulaw — Platform Bantuan Hukum dan Keterbukaan Layanan Publik Kota dan Desa.">
    <title>{{ $title ?? 'Konsulaw — Platform Bantuan Hukum & Keterbukaan Layanan Publik Kota dan Desa' }}</title>

    {{-- Tipografi: Fraunces (Display serif) & Plus Jakarta Sans (Body & UI) sesuai baselineui.md --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500..800;1,9..144,500..800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-ivory text-ink font-sans antialiased selection:bg-accent selection:text-white">
    {{-- Tautan Aksesibilitas Lewati ke Konten Utama (NFR-04) --}}
    <a
        href="#konten-utama"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-full focus:bg-accent focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white focus:shadow-btn focus:outline-none focus:ring-3 focus:ring-accent-soft"
    >
        Lewati ke konten utama
    </a>

    {{-- Navbar Utama (Top Bar) --}}
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
