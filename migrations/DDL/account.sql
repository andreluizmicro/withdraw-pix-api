-- withdraw_pix.account definition

CREATE TABLE `account` (
    `id` char(36) NOT NULL,
    `name` varchar(255) NOT NULL,
    `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
    `is_active` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;