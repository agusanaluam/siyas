# Panduan Setup Migrasi ke Next.js

Dokumen ini menjelaskan langkah-langkah untuk setup migrasi dari Laravel Blade ke Next.js.

## Backend Setup (Laravel)

### 1. Install Laravel Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Konfigurasi CORS

Pastikan file `config/cors.php` ada dan dikonfigurasi dengan benar. Jika belum ada, buat file tersebut:

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

### 3. Update .env

Tambahkan konfigurasi berikut di file `.env`:

```
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

### 4. Install Dependencies

```bash
composer install
```

## Frontend Setup (Next.js)

### 1. Install Dependencies

```bash
cd frontend
npm install
```

### 2. Setup Environment Variables

Buat file `.env.local` di folder `frontend`:

```
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### 3. Jalankan Development Server

```bash
npm run dev
```

Frontend akan berjalan di http://localhost:3000

## Struktur Folder Frontend

```
frontend/
├── app/                    # Next.js App Router
│   ├── login/             # Halaman login
│   ├── dashboard/         # Halaman dashboard
│   ├── layout.tsx         # Root layout
│   ├── page.tsx           # Home page
│   └── globals.css        # Global styles
├── components/            # Komponen UI yang dapat digunakan ulang
│   ├── ui/                # Komponen UI dasar (button, input, dll)
│   └── layout/            # Komponen layout (header, sidebar)
├── lib/                   # Logic dan utilities
│   ├── api/               # API client dan services
│   │   ├── client.ts      # Axios instance dengan interceptors
│   │   └── auth.ts        # Auth service
│   └── utils/             # Utility functions
├── hooks/                 # Custom React hooks
│   └── useAuth.ts         # Hook untuk authentication
├── types/                 # TypeScript type definitions
│   └── index.ts           # Type definitions
└── middleware.ts          # Next.js middleware untuk auth
```

## API Endpoints

### Authentication

- `POST /api/auth/login` - Login
- `POST /api/auth/register` - Register
- `POST /api/auth/logout` - Logout (requires auth)
- `GET /api/auth/user` - Get current user (requires auth)
- `POST /api/auth/forgot-password` - Request reset password
- `POST /api/auth/reset-password` - Reset password
- `GET /api/auth/verify-email` - Verify email
- `POST /api/auth/resend-verification` - Resend verification email

### Dashboard

- `GET /api/dashboard` - Get dashboard data (requires auth)
- `GET /api/dashboard/leaderboard` - Get volunteer leaderboard (requires auth)

## Testing

1. Pastikan backend Laravel berjalan di port 8000
2. Pastikan frontend Next.js berjalan di port 3000
3. Buka http://localhost:3000/login
4. Test login dengan kredensial yang valid

## Catatan Penting

1. **CORS Configuration**: Pastikan CORS dikonfigurasi dengan benar di Laravel untuk mengizinkan request dari frontend
2. **Sanctum Configuration**: Pastikan `SANCTUM_STATEFUL_DOMAINS` di `.env` sesuai dengan domain frontend
3. **Session Domain**: Pastikan `SESSION_DOMAIN` dikonfigurasi dengan benar
4. **Token Storage**: Token disimpan di cookie dengan nama `auth_token`
5. **Middleware**: Next.js middleware akan redirect ke `/login` jika user tidak authenticated

## Next Steps

1. Migrasi halaman register
2. Migrasi halaman forgot password
3. Migrasi halaman reset password
4. Migrasi halaman email verification
5. Setup layout dengan header dan sidebar
6. Migrasi halaman dashboard sesuai role
7. Migrasi fitur lainnya secara bertahap

