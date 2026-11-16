<x-guest-layout>
    {{-- Merek untuk layar kecil --}}
    <div class="mb-10 flex items-center gap-3 lg:hidden">
        <div class="grid h-10 w-10 place-items-center rounded-xl bg-blue-600 text-base font-bold text-white">B</div>
        <div class="leading-tight">
            <div class="font-semibold text-slate-900">BRILink</div>
            <div class="text-sm text-slate-500">Warung Abah</div>
        </div>
    </div>

    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Selamat datang</h1>
    <p class="mt-1 text-sm text-slate-500">Masuk untuk mengelola transaksi agen.</p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Kata sandi</label>
            <input id="password" type="password" name="password"
                   required autocomplete="current-password"
                   class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember"
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-600">
            Ingat saya
        </label>

        <button type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            Masuk
        </button>
    </form>
</x-guest-layout>