# Cara Pasang File ke Proyek Laravel

Salin isi folder ini ke proyek Laravel Anda (struktur folder sama, timpa jika diminta).

## 1. Daftarkan alias middleware

Laravel 11/12: buka `bootstrap/app.php`, lalu isi bagian `withMiddleware`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

## 2. Jalankan migrasi dan seeder

```bash
php artisan migrate:fresh --seed
```

Akun awal:
- admin@brilink.test / admin12345 (admin)
- operator@brilink.test / operator12345 (operator)

Ganti kedua password setelah login pertama.

## 3. Pakai di route

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('jenis-transaksi', JenisTransaksiController::class);
});

Route::middleware(['auth', 'role:admin,operator'])->group(function () {
    Route::resource('transaksi', TransaksiController::class);
});
```

## Catatan

- `app/Models/User.php` di sini menggantikan bawaan Laravel (menambah kolom `role` dan relasi). Kolom nama memakai `name` agar cocok dengan Breeze.
- Migration `add_role_to_users_table` harus berjalan setelah migration bawaan `users`; timestamp-nya (2026_10_25) sudah lebih baru.
- Nama tabel memakai bahasa Indonesia, jadi setiap model punya `$table` eksplisit. Jangan dihapus.
