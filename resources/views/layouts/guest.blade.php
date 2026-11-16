<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800">
    <div class="grid min-h-screen lg:grid-cols-2">

        {{-- Panel kiri --}}
        <div class="relative hidden flex-col justify-between overflow-hidden bg-blue-700 p-12 text-white lg:flex">
            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-blue-600/50"></div>
            <div class="absolute -bottom-24 -left-16 h-80 w-80 rounded-full bg-blue-800/60"></div>

            <div class="relative flex items-center gap-3">
                <div class="grid h-10 w-10 place-items-center rounded-xl bg-white text-base font-bold text-blue-700">B</div>
                <div class="leading-tight">
                    <div class="font-semibold">BRILink</div>
                    <div class="text-sm text-blue-200">Warung Abah</div>
                </div>
            </div>

            <div class="relative max-w-md">
                <h2 class="text-4xl font-semibold leading-tight tracking-tight">
                    Transaksi harian tercatat rapi, laporan siap kapan saja.
                </h2>
                <p class="mt-4 text-blue-100">
                    Kelola transaksi, pengeluaran, dan saldo kas agen dalam satu tempat.
                </p>
            </div>

            <div class="relative text-sm text-blue-200">
                Sistem Informasi Pengelolaan Transaksi Keuangan Harian
            </div>
        </div>

        {{-- Panel kanan --}}
        <div class="flex items-center justify-center bg-white p-6 sm:p-12">
            <div class="w-full max-w-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>