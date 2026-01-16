# Cara Install Laravel DomPDF | UNTUK SLIPGAJI

Jika mengalami error timeout saat `composer require barryvdh/laravel-dompdf`, gunakan salah satu cara berikut:

## Metode 1: Composer dengan Timeout Lebih Lama

```bash
# Set timeout menjadi 600 detik (10 menit)
composer config --global process-timeout 600

# Install dengan opsi retry
composer require barryvdh/laravel-dompdf --prefer-dist --no-interaction
```

## Metode 2: Install dari Cache Lokal (Jika Ada)

```bash
# Download manual dari GitHub
cd vendor/
git clone https://github.com/barryvdh/laravel-dompdf.git barryvdh/laravel-dompdf
cd barryvdh/laravel-dompdf
composer install --no-dev

# Install dependencies
cd ../../../
composer update barryvdh/laravel-dompdf
```

## Metode 3: Edit composer.json Manual

Tambahkan di `composer.json`:

```json
{
    "require": {
        "barryvdh/laravel-dompdf": "^2.0"
    }
}
```

Lalu jalankan:

```bash
composer update --prefer-dist --no-interaction
```

## Metode 4: Gunakan Mirror Packagist (Untuk Koneksi Lambat)

```bash
# Gunakan mirror Asia
composer config repo.packagist composer https://packagist.jp
composer require barryvdh/laravel-dompdf

# Atau gunakan mirror China
composer config repo.packagist composer https://mirrors.aliyun.com/composer/
composer require barryvdh/laravel-dompdf
```

## Verifikasi Instalasi

Setelah berhasil install, verifikasi dengan:

```bash
composer show barryvdh/laravel-dompdf
```

## Konfigurasi di Laravel

1. **Publish Config (Opsional)**:
   ```bash
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```

2. **Tambahkan Alias di `config/app.php`** (Jika belum auto-discover):
   ```php
   'aliases' => [
       'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
   ]
   ```

3. **Test PDF Generation**:
   ```bash
   php -S localhost:8000 -t public
   ```
   
   Buka browser: `http://localhost:8000/salaries` dan coba download slip gaji.

## Catatan

- Jika semua metode gagal, sistem tetap berfungsi normal KECUALI fitur download PDF
- Fitur lain (input lembur, approval, perhitungan gaji) tetap bisa digunakan
- PDF library hanya dipakai untuk fitur download slip gaji saja

## Troubleshooting

**Error: "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"**
```bash
composer dump-autoload
php artisan config:clear
```

**Error: "proc_open(): fork failed"**
```bash
# Increase PHP memory limit
php -d memory_limit=512M artisan vendor:publish
```
