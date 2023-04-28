CREATE TABLE `wp_membership` (
            `id` int NOT NULL AUTO_INCREMENT,
            `email` varchar(250) NOT NULL,
            `number` int NOT NULL,
             PRIMARY KEY (`id`),
             UNIQUE KEY (`email`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;