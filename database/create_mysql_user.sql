-- ============================================
-- SQL UNTUK MEMBUAT USER BARU DI MySQL
-- Jalankan ini di phpMyAdmin | jika ada restriksi/privilege error
-- ============================================

-- Pilih tab SQL di phpMyAdmin, lalu jalankan query ini:

-- 1. Buat user baru untuk Laravel
CREATE USER IF NOT EXISTS 'hrd_user'@'localhost' IDENTIFIED BY 'hrd_password123';

-- 2. Berikan akses penuh ke database hrd_management
GRANT ALL PRIVILEGES ON hrd_management.* TO 'hrd_user'@'localhost';

-- 3. Refresh privileges
FLUSH PRIVILEGES;

-- 4. Verifikasi user sudah dibuat
SELECT user, host FROM mysql.user WHERE user='hrd_user';
