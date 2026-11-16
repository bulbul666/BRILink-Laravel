<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800">
@php
    $menu = [
        'Menu Utama' => [
            ['Dashboard', 'dashboard', 'dashboard'],
            ['Transaksi', 'transaksi.index', 'transaksi.*'],
            ['Pengeluaran', 'pengeluaran.index', 'pengeluaran.*'],
            ['Saldo Harian', 'saldo.index', 'saldo.*'],
        ],
        'Laporan' => [
            ['Laporan Harian', 'laporan.harian', 'laporan.harian*'],
            ['Laporan Bulanan', 'laporan.bulanan', 'laporan.bulanan*'],
        ],
    ];
@endphp

<div x-data="{ open: false }" class="min-h-screen">

    {{-- Lapisan gelap untuk sidebar di HP --}}
    <div x-show="open" x-cloak @click="open = false"
         class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform lg:translate-x-0">

        <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-6">
            <div class="grid h-9 w-9 place-items-center rounded-xl bg-blue-600 text-sm font-bold text-white">B</div>
            <div class="leading-tight">
                <div class="text-sm font-semibold text-slate-900">BRILink</div>
                <div class="text-xs text-slate-500">Warung Abah</div>
            </div>
        </div>

        <nav class="flex-1 space-y-6 overflow-y-auto px-4 py-5">
            @foreach ($menu as $grup => $items)
                <div>
                    <div class="mb-2 px-3 text-xs font-medium uppercase tracking-wider text-slate-400">{{ $grup }}</div>
                    <div class="space-y-1">
                        @foreach ($items as [$label, $rute, $pola])
                            @php $aktif = request()->routeIs($pola); @endphp
                            <a href="{{ route($rute) }}"
                               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                                      {{ $aktif ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $aktif ? 'bg-blue-600' : 'bg-slate-300' }}"></span>
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="border-t border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="truncate text-sm font-medium text-slate-900">{{ auth()->user()->name }}</div>
                    <div class="text-xs capitalize text-slate-500">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('profile.edit') }}"
                   class="flex-1 rounded-lg border border-slate-200 px-3 py-1.5 text-center text-xs font-medium text-slate-600 hover:bg-slate-50">
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button class="w-full rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Konten --}}
    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                <button @click="open = true" class="-ml-2 p-2 text-slate-500 lg:hidden" aria-label="Buka menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="min-w-0 flex-1">{{ $header ?? '' }}</div>
                <div class="hidden text-xs text-slate-500 sm:block">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>