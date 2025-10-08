-- withdraw_pix.account_withdraw definition

CREATE TABLE `account_withdraw` (
    `id` char(36) NOT NULL,
    `account_id` char(36) NOT NULL,
    `method` varchar(50) NOT NULL,
    `amount` decimal(15,2) NOT NULL,
    `scheduled` tinyint(1) DEFAULT '0',
    `scheduled_for` datetime DEFAULT NULL,
    `done` tinyint(1) DEFAULT '0',
    `error` tinyint(1) DEFAULT '0',
    `error_reason` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     KEY `account_id` (`account_id`),
    CONSTRAINT `account_withdraw_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;