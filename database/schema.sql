CREATE TABLE `servicos` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(150) NOT NULL,
  `descricao` longtext,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT NULL,
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `prestadores` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nome` varchar(150) NOT NULL,
  `sobrenome` varchar(150) NOT NULL,
  `telefone` varchar(20),
  `email` varchar(150),
  `foto` varchar(255),
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime DEFAULT NULL,
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `prestadores_servicos` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `prestador_id` int NOT NULL,
  `servico_id` int NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `prestador_id` (`prestador_id`),
  KEY `servico_id` (`servico_id`),
  UNIQUE KEY `prestador_id_servico_id` (`prestador_id`, `servico_id`),
  CONSTRAINT `fk_prestadores_servicos_prestador` FOREIGN KEY (`prestador_id`) REFERENCES `prestadores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_prestadores_servicos_servico` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;