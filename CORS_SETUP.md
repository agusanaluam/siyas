# Setup CORS untuk Laravel + Next.js

## Masalah CORS

Jika Anda mengalami error CORS saat mengakses API dari Next.js frontend, ikuti langkah-langkah berikut:

## 1. Pastikan File config/cors.php Ada

File `config/cors.php` sudah dibuat dengan konfigurasi berikut:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000', 'http://127.0.0.1:3000'],
'supports_credentials' => true,
```

## 2. Update File .env

Pastikan file `.env` memiliki konfigurasi berikut:

```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
SESSION_DOMAIN=localhost
FRONTEND_URL=http://localhost:3000
```

## 3. Pastikan CSRF Token Di-exclude untuk API

File `bootstrap/app.php` sudah dikonfigurasi untuk exclude CSRF pada API routes:

```php
$middleware->validateCsrfTokens(except: [
    'api/*',
]);
```

## 4. Restart Laravel Server

Setelah mengubah konfigurasi, restart server Laravel:

```bash
php artisan serve
```

## 5. Test CORS

Test dengan curl atau dari browser console:

```bash
curl -X OPTIONS http://localhost:8000/api/auth/login \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -v
```

Atau dari browser console di http://localhost:3000:

```javascript
fetch('http://localhost:8000/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  credentials: 'include',
  body: JSON.stringify({
    email: 'test@example.com',
    password: 'password'
  })
})
.then(r => r.json())
.then(console.log)
.catch(console.error)
```

## Troubleshooting

### Error: "Access-Control-Allow-Origin" header is missing

1. Pastikan file `config/cors.php` ada dan dikonfigurasi dengan benar
2. Pastikan origin frontend ada di `allowed_origins`
3. Restart Laravel server

### Error: "Credentials flag is true, but Access-Control-Allow-Credentials is not true"

1. Pastikan `supports_credentials` di `config/cors.php` adalah `true`
2. Pastikan frontend mengirim request dengan `credentials: 'include'`

### Error: CSRF token mismatch

1. Pastikan `api/*` ada di `validateCsrfTokens(except: [...])`
2. Pastikan request tidak mengirim CSRF token untuk API routes

## Catatan

- Di Laravel 11, CORS sudah dihandle secara otomatis jika file `config/cors.php` ada
- Tidak perlu install package tambahan untuk CORS
- Sanctum memerlukan `supports_credentials: true` untuk cookie-based authentication

