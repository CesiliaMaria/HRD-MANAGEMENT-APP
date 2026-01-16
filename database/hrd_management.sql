-- ============================================
-- SQL FILE UNTUK HRD MANAGEMENT APP
-- Database: hrd_management
-- ============================================

-- Gunakan database
USE hrd_management;

-- ============================================
-- DROP TABLES (jika sudah ada)
-- ============================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `salaries`;
DROP TABLE IF EXISTS `attendances`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `personal_access_tokens`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- CREATE TABLE: migrations
-- ============================================
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: roles
-- ============================================
CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: users
-- ============================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: attendances
-- ============================================
CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `location_address` varchar(255) DEFAULT NULL,
  `status` enum('present','late','absent') NOT NULL DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_user_id_foreign` (`user_id`),
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: salaries
-- ============================================
CREATE TABLE `salaries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `allowance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `overtime_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_salary` decimal(10,2) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salaries_user_id_foreign` (`user_id`),
  CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: password_reset_tokens
-- ============================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: sessions
-- ============================================
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: cache
-- ============================================
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: cache_locks
-- ============================================
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: jobs
-- ============================================
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: job_batches
-- ============================================
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: failed_jobs
-- ============================================
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CREATE TABLE: personal_access_tokens
-- ============================================
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT DATA: migrations
-- ============================================
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2023_01_01_000001_create_roles_table', 1),
('2023_01_01_000002_add_role_id_to_users_table', 1),
('2023_01_01_000003_create_attendances_table', 1),
('2023_01_01_000004_create_salaries_table', 1),
('2025_09_30_065806_create_personal_access_tokens_table', 1),
('2025_09_30_085857_update_latitude_longitude_precision_in_attendances_table', 1);

-- ============================================
-- INSERT DATA: roles
-- ============================================
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator dengan akses penuh', NOW(), NOW()),
(2, 'manager', 'Manager dengan akses terbatas', NOW(), NOW()),
(3, 'employee', 'Karyawan dengan akses dasar', NOW(), NOW());

-- ============================================
-- INSERT DATA: users
-- Password untuk semua user: password
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- ============================================
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'Admin HRD', 'admin@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 1),
(2, 'Manager Operasional', 'manager@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 2),
(3, 'Budi Santoso', 'budi@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 3),
(4, 'Siti Rahayu', 'siti@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 3),
(5, 'Ahmad Wijaya', 'ahmad@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 3),
(6, 'Dewi Lestari', 'dewi@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 3),
(7, 'Rudi Hartono', 'rudi@hrd.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NOW(), NOW(), 3);

-- ============================================
-- INSERT DATA: attendances (Sample Data)
-- ============================================
INSERT INTO `attendances` (`user_id`, `date`, `check_in`, `check_out`, `latitude`, `longitude`, `location_address`, `status`, `notes`, `created_at`, `updated_at`) VALUES
-- Data untuk Budi Santoso (user_id 3)
(3, '2026-01-13', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(3, '2026-01-14', '08:05:00', '17:05:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'late', 'Terlambat 5 menit', NOW(), NOW()),
(3, '2026-01-15', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(3, '2026-01-16', '08:00:00', NULL, -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', 'Masih bekerja', NOW(), NOW()),

-- Data untuk Siti Rahayu (user_id 4)
(4, '2026-01-13', '07:55:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(4, '2026-01-14', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(4, '2026-01-15', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(4, '2026-01-16', '08:00:00', NULL, -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', 'Masih bekerja', NOW(), NOW()),

-- Data untuk Ahmad Wijaya (user_id 5)
(5, '2026-01-13', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(5, '2026-01-14', NULL, NULL, NULL, NULL, NULL, 'absent', 'Izin sakit', NOW(), NOW()),
(5, '2026-01-15', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(5, '2026-01-16', '08:10:00', NULL, -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'late', 'Terlambat, masih bekerja', NOW(), NOW()),

-- Data untuk Dewi Lestari (user_id 6)
(6, '2026-01-13', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(6, '2026-01-14', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(6, '2026-01-15', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(6, '2026-01-16', '08:00:00', NULL, -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', 'Masih bekerja', NOW(), NOW()),

-- Data untuk Rudi Hartono (user_id 7)
(7, '2026-01-13', '08:15:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'late', 'Terlambat 15 menit', NOW(), NOW()),
(7, '2026-01-14', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(7, '2026-01-15', '08:00:00', '17:00:00', -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', NULL, NOW(), NOW()),
(7, '2026-01-16', '08:00:00', NULL, -6.20000000, 106.81650000, 'Kantor Pusat Jakarta', 'present', 'Masih bekerja', NOW(), NOW());

-- ============================================
-- INSERT DATA: salaries (Sample Data)
-- ============================================
INSERT INTO `salaries` (`user_id`, `basic_salary`, `allowance`, `overtime_pay`, `tax`, `total_salary`, `payment_status`, `payment_method`, `transaction_id`, `payment_date`, `notes`, `created_at`, `updated_at`) VALUES
-- Gaji Admin
(1, 15000000.00, 2000000.00, 0.00, 850000.00, 16150000.00, 'paid', 'bank_transfer', 'TRX-2026-01-001', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(1, 15000000.00, 2000000.00, 0.00, 850000.00, 16150000.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00'),

-- Gaji Manager
(2, 12000000.00, 1500000.00, 0.00, 675000.00, 12825000.00, 'paid', 'bank_transfer', 'TRX-2026-01-002', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(2, 12000000.00, 1500000.00, 0.00, 675000.00, 12825000.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00'),

-- Gaji Budi Santoso
(3, 5000000.00, 500000.00, 200000.00, 285000.00, 5415000.00, 'paid', 'bank_transfer', 'TRX-2026-01-003', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(3, 5000000.00, 500000.00, 0.00, 275000.00, 5225000.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00'),

-- Gaji Siti Rahayu
(4, 5500000.00, 600000.00, 150000.00, 312500.00, 5937500.00, 'paid', 'bank_transfer', 'TRX-2026-01-004', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(4, 5500000.00, 600000.00, 0.00, 305000.00, 5795000.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00'),

-- Gaji Ahmad Wijaya
(5, 4800000.00, 480000.00, 100000.00, 269000.00, 5111000.00, 'paid', 'bank_transfer', 'TRX-2026-01-005', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(5, 4800000.00, 480000.00, 0.00, 264000.00, 5016000.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00'),

-- Gaji Dewi Lestari
(6, 5200000.00, 520000.00, 250000.00, 298500.00, 5671500.00, 'paid', 'bank_transfer', 'TRX-2026-01-006', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(6, 5200000.00, 520000.00, 0.00, 286000.00, 5434000.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00'),

-- Gaji Rudi Hartono
(7, 4900000.00, 490000.00, 180000.00, 278500.00, 5291500.00, 'paid', 'bank_transfer', 'TRX-2026-01-007', '2026-01-05', 'Gaji Desember 2025', '2025-12-31 16:00:00', '2026-01-05 14:30:00'),
(7, 4900000.00, 490000.00, 0.00, 269500.00, 5120500.00, 'pending', NULL, NULL, NULL, 'Gaji Januari 2026', '2026-01-31 16:00:00', '2026-01-31 16:00:00');

-- ============================================
-- SELESAI
-- ============================================
