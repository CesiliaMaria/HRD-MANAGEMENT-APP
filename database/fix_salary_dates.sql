-- ============================================
-- FIX TANGGAL SALARY - UPDATE DATA YANG SUDAH ADA
-- Jalankan di phpMyAdmin
-- ============================================

USE hrd_management;

-- Update gaji yang status PAID (Gaji Desember 2025)
-- Created: 2 Januari 2026, Updated: 5 Januari 2026 (saat dibayar)
UPDATE `salaries` 
SET 
    `created_at` = '2026-01-02 10:00:00',
    `updated_at` = '2026-01-05 14:30:00'
WHERE 
    `payment_status` = 'paid' 
    AND `payment_date` = '2026-01-05';

-- Update gaji yang status PENDING (Gaji Januari 2026)
-- Created: 10 Januari 2026, belum ada update
UPDATE `salaries` 
SET 
    `created_at` = '2026-01-10 09:00:00',
    `updated_at` = '2026-01-10 09:00:00'
WHERE 
    `payment_status` = 'pending' 
    AND `payment_date` IS NULL;

-- Verifikasi hasilnya
SELECT 
    id,
    user_id,
    total_salary,
    payment_status,
    payment_date,
    notes,
    created_at,
    updated_at
FROM salaries
ORDER BY user_id, created_at;
