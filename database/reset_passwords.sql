--- yang di jalankan langsung file hrd_management.sql saja, 
--- karena sebelumnya ada masalah pada password bycript

-- ============================================
-- RESET PASSWORD UNTUK SEMUA USER
-- Jalankan di phpMyAdmin untuk reset password
-- Password baru untuk semua user: password
-- ============================================

USE hrd_management;

-- Update password untuk semua user
-- Hash ini adalah bcrypt untuk "password"
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'admin@hrd.com';
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'manager@hrd.com';
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'budi@hrd.com';
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'siti@hrd.com';
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'ahmad@hrd.com';
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'dewi@hrd.com';
UPDATE `users` SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE `email` = 'rudi@hrd.com';

-- Setelah menjalankan query di atas, semua user bisa login dengan password: password
