# Setup Environment Variables

## Lokasi File

Tambahkan konfigurasi berikut di file **`.env`** yang berada di **root project Laravel** (bukan di folder frontend).

```
C:\Users\USER\projects\siyas\.env
```

## Konfigurasi yang Perlu Ditambahkan

Buka file `.env` di root project dan tambahkan atau pastikan ada konfigurasi berikut:

```env
# Laravel Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
SESSION_DOMAIN=localhost
```

## Cara Menambahkan

1. Buka file `.env` di root project (satu level dengan `composer.json`, `app/`, `routes/`, dll)
2. Tambahkan atau edit baris berikut:
   ```
   SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
   SESSION_DOMAIN=localhost
   ```
3. Simpan file
4. Restart server Laravel jika sedang berjalan

## Catatan

- **JANGAN** menambahkan di `frontend/.env.local` (itu untuk Next.js)
- **TAMBAHKAN** di root project Laravel (`.env` di level yang sama dengan `composer.json`)
- Jika file `.env` tidak ada, copy dari `.env.example` dan tambahkan konfigurasi di atas

## Struktur Folder

```
siyas/
├── .env                    ← TAMBAHKAN DI SINI
├── .env.example
├── composer.json
├── app/
├── routes/
├── config/
└── frontend/
    └── .env.local         ← Bukan di sini (ini untuk Next.js)
```

## Setelah Menambahkan

1. Restart server Laravel:
   ```bash
   php artisan serve
   ```

2. Clear config cache (opsional):
   ```bash
   php artisan config:clear
   ```

3. Test API dari frontend Next.js

