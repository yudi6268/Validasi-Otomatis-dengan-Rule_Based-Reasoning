# Fitur Pemilihan Tahun Perjanjian

## Overview
Fitur ini memungkinkan user untuk memilih tahun saat membuat perjanjian kinerja, dan admin dapat mengatur tahun mana saja yang tersedia untuk dipilih (maksimal 2 tahun).

## Komponen yang Dibuat

### 1. Database
- **Migration**: `2026_01_31_160454_create_settings_table.php`
  - Tabel: `settings` dengan kolom:
    - `id` (primary key)
    - `key` (unique) - identifier untuk setting
    - `value` (text) - nilai setting
    - `description` (nullable) - deskripsi setting
    - `timestamps`
  - Data default:
    - `tahun_perjanjian_1` = `2025`
    - `tahun_perjanjian_2` = `2026`

- **Migration**: `2026_01_31_160419_add_tahun_to_perjanjians_if_not_exists.php`
  - Menambahkan kolom `tahun` ke tabel `perjanjians` (jika belum ada)
  - Type: `string`, nullable

### 2. Model
- **File**: `app/Models/Setting.php`
- **Methods**:
  ```php
  // Mengambil nilai setting
  Setting::get($key, $default = null)
  
  // Menyimpan/update setting
  Setting::set($key, $value, $description = null)
  
  // Mengambil array tahun yang tersedia
  Setting::getAvailableYears() // Returns: ['2025', '2026']
  ```

### 3. Controller Admin
- **File**: `app/Http/Controllers/Admin/SettingController.php`
- **Routes**:
  - `GET /admin/settings` - Tampilkan form pengaturan tahun
  - `PUT /admin/settings` - Update pengaturan tahun
- **Validasi**:
  - Tahun harus integer antara 2020-2050
  - Tahun kedua harus berbeda dengan tahun pertama

### 4. View Admin
- **File**: `resources/views/admin/settings/index.blade.php`
- **Fitur**:
  - Form untuk mengatur 2 tahun yang tersedia
  - Preview tahun yang akan ditampilkan ke user
  - Validasi client-side dan server-side

### 5. Integration dengan Form Perjanjian

#### Create Form
- **File**: `resources/views/perjanjian/create.blade.php`
- **Perubahan**:
  - Tambah dropdown pemilihan tahun sebelum form perjanjian
  - Header "PERJANJIAN KINERJA TAHUN" update realtime saat tahun dipilih
  - JavaScript untuk update header otomatis

#### Edit Form
- **File**: `resources/views/perjanjian/edit.blade.php`
- **Perubahan**:
  - Dropdown tahun dengan value selected dari database
  - Header menampilkan tahun dari perjanjian yang sedang diedit
  - JavaScript untuk update header otomatis

#### Controller
- **File**: `app/Http/Controllers/PerjanjianController.php`
- **Perubahan**:
  ```php
  // Method create() dan edit()
  $availableYears = Setting::getAvailableYears();
  return view('...', compact('...', 'availableYears'));
  ```

### 6. Sidebar Admin
- **File**: `resources/views/admin/layout.blade.php`
- **Perubahan**:
  - Tambah menu "Pengaturan Tahun" dengan icon `fa-cog`
  - Route: `admin.settings.index`

## Cara Penggunaan

### Admin
1. Login sebagai admin
2. Klik menu "Pengaturan Tahun" di sidebar
3. Atur 2 tahun yang akan ditampilkan ke user (misal: 2025 dan 2026)
4. Klik "Simpan Pengaturan"

### User
1. Buat perjanjian baru via menu "Form Perjanjian"
2. Pilih tahun dari dropdown (hanya 2 pilihan sesuai pengaturan admin)
3. Header "PERJANJIAN KINERJA TAHUN XXXX" akan update otomatis
4. Lanjutkan mengisi form dan simpan

### Edit Perjanjian
1. Buka perjanjian yang sudah dibuat
2. Tahun akan terisi otomatis sesuai data di database
3. Bisa diubah jika diperlukan (oleh Direktur)

## Database Schema

```sql
-- Tabel settings
CREATE TABLE settings (
    id BIGSERIAL PRIMARY KEY,
    key VARCHAR(255) UNIQUE NOT NULL,
    value TEXT NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Data default
INSERT INTO settings (key, value, description) VALUES
('tahun_perjanjian_1', '2025', 'Tahun Perjanjian Pertama'),
('tahun_perjanjian_2', '2026', 'Tahun Perjanjian Kedua');

-- Kolom tahun di tabel perjanjians
ALTER TABLE perjanjians ADD COLUMN tahun VARCHAR(255) NULL;
```

## Routes

```php
// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])
        ->name('settings.update');
});
```

## Testing

### Manual Testing
1. Akses `/admin/settings`
2. Ubah tahun menjadi 2026 dan 2027
3. Buka form create perjanjian
4. Verifikasi dropdown hanya menampilkan 2026 dan 2027
5. Pilih tahun, verifikasi header berubah
6. Simpan perjanjian, verifikasi tahun tersimpan

### Validation Testing
1. Coba set tahun yang sama untuk kedua field (harus error)
2. Coba set tahun di luar range 2020-2050 (harus error)
3. Coba submit form create perjanjian tanpa pilih tahun (harus error)

## Maintenance

### Menambah Range Tahun di Masa Depan
Edit validasi di `SettingController`:
```php
'tahun_perjanjian_1' => 'required|integer|min:2020|max:2060', // ubah max
```

### Menambah Lebih dari 2 Tahun
1. Tambah kolom baru di settings: `tahun_perjanjian_3`, `tahun_perjanjian_4`, dll
2. Update method `getAvailableYears()` di `Setting.php`
3. Tambah field di form admin settings

## Notes
- Tahun disimpan sebagai string untuk fleksibilitas
- Dropdown tahun wajib diisi (required)
- Header PDF akan menggunakan tahun yang dipilih
- Admin bisa mengatur tahun kapan saja tanpa restart aplikasi
