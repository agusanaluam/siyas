# Rencana Migrasi Halaman ke Next.js

## Status Migrasi

### ✅ Sudah Selesai
- [x] Setup Next.js dengan TypeScript & Tailwind
- [x] Setup Laravel Sanctum
- [x] Halaman Auth (login, register, forgot password, reset password, email verification)
- [x] Layout (Header, Sidebar, DashboardLayout)
- [x] Dashboard page
- [x] API Campaign & Campaign Category

### 🔄 Sedang Dikerjakan
- [ ] API endpoints untuk semua fitur
- [ ] Halaman Campaign di Next.js
- [ ] Halaman Blog di Next.js
- [ ] Halaman Donation di Next.js
- [ ] Halaman Volunteer di Next.js
- [ ] Halaman Settings di Next.js

### ⏳ Belum Dimulai
- [ ] Komponen UI reusable (tables, forms, modals)
- [ ] Image upload handling
- [ ] DataTables replacement dengan custom table
- [ ] File upload untuk campaign/blog images

## Prioritas Migrasi

1. **API Endpoints** (PENTING - harus selesai dulu)
   - Campaign ✅
   - Campaign Category ✅
   - Blog
   - Donation
   - Volunteer
   - Settings/Profile
   - Location (provinsi, kota, kecamatan, desa)

2. **Halaman Next.js** (setelah API selesai)
   - Campaign (list, create, edit, details)
   - Blog (list, create, edit, details)
   - Donation (list, create, history, transfer, mutation)
   - Volunteer (list, create, edit, group)
   - Settings (profile user, profile yayasan)

3. **Komponen Reusable**
   - DataTable component
   - Form components
   - Modal components
   - Image upload component

## Catatan

- Semua controller perlu diubah menjadi API (return JSON)
- Gunakan Laravel Sanctum untuk authentication
- Pastikan CORS sudah dikonfigurasi
- File upload perlu handle dengan FormData
- Image processing tetap di backend

