-- withdraw_pix.account_withdraw_pix definition

CREATE TABLE `account_withdraw_pix` (
    `id` char(36) NOT NULL,
    `account_withdraw_id` char(36) NOT NULL,
    `type` enum('cpf','cnpj','email','phone','random') NOT NULL,
    `key` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `account_withdraw_id` (`account_withdraw_id`),
    CONSTRAINT `account_withdraw_pix_ibfk_1` FOREIGN KEY (`account_withdraw_id`) REFERENCES `account_withdraw` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;