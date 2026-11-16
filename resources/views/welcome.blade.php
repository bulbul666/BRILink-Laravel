<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
<div class="flex min-h-screen flex-col">

    <header class="mx-auto flex h-20 w-full max-w-6xl items-center justify-between px-6">
        <div class="flex items-center gap-3">
            <div class="grid h-9 w-9 place-items-center rounded-xl bg-blue-600 text-sm font-bold text-white">B</div>
            <div class="leading-tight">
                <div class="text-sm font-semibold text-slate-900">BRILink</div>
                <div class="text-xs text-slate-500">Warung Abah</div>
            </div>
        </div>

        @auth
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-700 hover:underline">Ke Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-sm font-medium text-blue-700 hover:underline">Masuk</a>
        @endauth
    </header>

    <main class="flex flex-1 items-center">
        <div class="mx-auto w-full max-w-6xl px-6 py-12">
            <div class="max-w-2xl">
                <span class="inline-block rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-100">
                    Sistem Informasi Keuangan Agen
                </span>
                <h1 class="mt-5 text-4xl font-semibold leading-tight tracking-tight text-slate-900 sm:text-5xl">
                    Catat transaksi harian dengan rapi, laporan siap kapan saja.
                </h1>
                <p class="mt-5 text-lg text-slate-600">
                    Kelola transaksi, pengeluaran, dan saldo kas BRILink Warung Abah dalam satu tempat.
                </p>
                <div class="mt-8">
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                       class="inline-block rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        {{ auth()->check() ? 'Buka Dashboard' : 'Masuk ke Sistem' }}
                    </a>
                </div>
            </div>

            <div class="mt-16 grid gap-4 sm:grid-cols-3">
                @foreach ([
                    ['Pencatatan Transaksi', 'Setiap transaksi tercatat lengkap dengan biaya admin dan operator.'],
                    ['Saldo Kas Harian', 'Saldo awal dan akhir dibandingkan otomatis dengan hitungan sistem.'],
                    ['Laporan Otomatis', 'Rekap harian dan bulanan, dapat dicetak ke PDF.'],
                ] as [$judul, $isi])
                    <div class="rounded-2xl bg-white p-6 ring-1 ring-slate-200">
                        <div class="h-1.5 w-8 rounded-full bg-blue-600"></div>
                        <h3 class="mt-4 font-semibold text-slate-900">{{ $judul }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $isi }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} BRILink Warung Abah
    </footer>
</div>
</body>
</html>