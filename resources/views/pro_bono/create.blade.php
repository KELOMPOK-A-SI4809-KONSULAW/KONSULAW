<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pro Bono - Konsulaw</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5">
            <a href="/" class="text-2xl font-bold text-indigo-700">
                Konsulaw
            </a>
            <span class="text-sm text-slate-500">Layanan bantuan hukum</span>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-5 py-10">
        <a href="/" class="text-sm text-indigo-700 hover:underline">
            &larr; Kembali ke Beranda
        </a>

        <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">
                Pengajuan Masyarakat
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight">
                Pengajuan Status Pro Bono
            </h1>

            <p class="mt-3 leading-7 text-slate-600">
                Unggah dokumen pendukung kondisi ekonomi untuk diajukan
                kepada admin. Dokumen akan disimpan secara privat.
            </p>

            @if (session('success'))
                <div class="mt-6 rounded-lg bg-green-50 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-6 rounded-lg bg-amber-50 p-4 text-amber-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-lg bg-red-50 p-4 text-red-700">
                    <p class="font-semibold">Ada masalah:</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @guest
                <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-900">
                    Untuk mengirim pengajuan, masyarakat harus login terlebih dahulu.
                </div>
            @endguest

            @if ($verifikasi && in_array($verifikasi->status, ['pending', 'disetujui'], true))

                <div class="mt-6 rounded-lg bg-slate-100 p-5">
                    <p class="text-sm text-slate-500">
                        Status pengajuan terakhir
                    </p>

                    <p class="mt-1 text-xl font-semibold">
                        {{ $verifikasi->status === 'pending' ? 'Pending' : 'Disetujui' }}
                    </p>

                    <p class="mt-2 text-sm text-slate-600">
                        {{ $verifikasi->status === 'pending'
                            ? 'Pengajuan sedang menunggu pemeriksaan admin.'
                            : 'Pengajuan telah disetujui oleh admin.' }}
                    </p>
                </div>

            @else

                <form
                    action="{{ route('pro-bono.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-8 space-y-6"
                >
                    @csrf

                    <div>
                        <label for="dokumen" class="block font-semibold">
                            Dokumen pendukung kondisi ekonomi
                        </label>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Format PDF, JPG, JPEG, atau PNG.
                            Maksimal 5 MB untuk satu dokumen.
                        </p>

                        <input
                            id="dokumen"
                            name="dokumen"
                            type="file"
                            accept=".pdf,.jpg,.jpeg,.png"
                            required
                            class="mt-3 block w-full rounded-lg border border-slate-300 bg-white p-3 text-sm"
                        >
                    </div>

                    <div class="rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                        Pastikan dokumen yang diunggah benar dan relevan.
                        Dokumen hanya boleh diakses oleh pihak yang berwenang.
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-indigo-700 px-5 py-3 font-semibold text-white hover:bg-indigo-800"
                    >
                        Ajukan Verifikasi
                    </button>
                </form>

            @endif
        </section>

        <p class="mt-6 text-center text-sm text-slate-500">
            Konsulaw — Layanan konsultasi dan bantuan hukum
        </p>
    </main>
</body>
</html>