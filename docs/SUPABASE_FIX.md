# Fix Supabase Storage Upload Issue

## Masalah
Error: "new row violates row-level security policy" saat upload gambar ke Supabase Storage.

## Penyebab
1. Row-Level Security (RLS) policy di bucket Supabase membatasi upload
2. Service Role Key di `.env` salah/sama dengan Anon Key

## Solusi

### 1. Update Service Role Key di .env

Buka Supabase Dashboard:
1. Pergi ke **Project Settings** → **API**
2. Cari bagian **Project API keys**
3. Copy **service_role** key (bukan anon key)
4. Update file `.env`:

```env
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inpyb3R5Z3RiY3djc2NxYmpldnF0Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2MDQyNjY4NiwiZXhwIjoyMDc2MDAyNjg2fQ.YOUR_ACTUAL_SERVICE_ROLE_KEY
```

**PENTING**: Service role key harus berbeda dengan anon key!

### 2. Buat/Update RLS Policy di Supabase Storage

Buka Supabase Dashboard:

#### Option A: Disable RLS untuk bucket uploads (Lebih Mudah)
1. Pergi ke **Storage** → Pilih bucket `uploads`
2. Klik **Policies**
3. Klik **Disable RLS** atau **New Policy**
4. Pilih template: **Allow public access**
5. Apply policy

#### Option B: Buat Custom Policy (Lebih Aman)
1. Pergi ke **Storage** → Pilih bucket `uploads`
2. Klik **Policies** → **New Policy**
3. Gunakan SQL berikut:

```sql
-- Policy untuk INSERT (Upload)
CREATE POLICY "Allow authenticated uploads"
ON storage.objects FOR INSERT
TO authenticated
WITH CHECK (bucket_id = 'uploads');

-- Policy untuk SELECT (Download/View)
CREATE POLICY "Allow public downloads"
ON storage.objects FOR SELECT
TO public
USING (bucket_id = 'uploads');

-- Policy untuk UPDATE
CREATE POLICY "Allow authenticated updates"
ON storage.objects FOR UPDATE
TO authenticated
USING (bucket_id = 'uploads');

-- Policy untuk DELETE
CREATE POLICY "Allow authenticated deletes"
ON storage.objects FOR DELETE
TO authenticated
USING (bucket_id = 'uploads');
```

#### Option C: Buat Bucket Baru (Public)
1. Pergi ke **Storage** → **New Bucket**
2. Nama: `uploads`
3. **Centang**: "Public bucket"
4. Create bucket

### 3. Test Upload

Setelah salah satu solusi di atas diterapkan:

1. Clear cache Laravel:
```bash
php artisan config:clear
php artisan cache:clear
```

2. Refresh halaman profil: http://127.0.0.1:8000/profil

3. Test upload:
   - Upload foto profil
   - Upload/gambar tanda tangan

4. Cek log jika masih error:
```bash
Get-Content storage\logs\laravel.log -Tail 50
```

## Verifikasi

Upload berhasil jika:
- ✅ Gambar langsung tampil di halaman profil
- ✅ Muncul notifikasi hijau "Foto/Tanda tangan berhasil diupload!"
- ✅ Tidak ada error di log Laravel
- ✅ File ada di Supabase Storage dashboard

## Troubleshooting

### Masih Error 403 Unauthorized
- Pastikan Service Role Key sudah benar
- Pastikan RLS policy sudah diterapkan
- Coba restart Laravel server: `php artisan serve`

### Upload Berhasil tapi Gambar Tidak Tampil
- Cek URL gambar di database (foto_profil/tanda_tangan column)
- Pastikan bucket adalah public atau ada read policy
- Clear browser cache (Ctrl + F5)

### Error "Bucket not found"
- Pastikan bucket `uploads` sudah dibuat di Supabase
- Cek SUPABASE_STORAGE_BUCKET di .env: `SUPABASE_STORAGE_BUCKET=uploads`
