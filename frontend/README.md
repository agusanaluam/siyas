# SIYAS Frontend - Next.js

Frontend aplikasi SIYAS menggunakan Next.js 14 dengan TypeScript dan Tailwind CSS.

## Struktur Folder

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
│   └── utils/             # Utility functions
├── hooks/                 # Custom React hooks
├── types/                 # TypeScript type definitions
└── middleware.ts          # Next.js middleware untuk auth
```

## Setup

1. Install dependencies:
```bash
npm install
```

2. Buat file `.env.local`:
```
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

3. Jalankan development server:
```bash
npm run dev
```

Aplikasi akan berjalan di http://localhost:3000

## Teknologi

- **Next.js 14** - React framework dengan App Router
- **TypeScript** - Type safety
- **Tailwind CSS** - Utility-first CSS framework
- **Axios** - HTTP client
- **js-cookie** - Cookie management

## Catatan

- Pastikan backend Laravel sudah berjalan di port 8000
- Pastikan Laravel Sanctum sudah terkonfigurasi dengan benar
- CORS harus dikonfigurasi di Laravel untuk mengizinkan request dari frontend

