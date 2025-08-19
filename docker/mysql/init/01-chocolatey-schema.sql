-- Chocolatey CMS Database Schema
-- Initialisation de base pour le développement local

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =======================
-- Tables utilisateurs
-- =======================

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_ticket` varchar(255) DEFAULT NULL,
  `look` varchar(255) DEFAULT 'hr-115-42.hd-195-19.ch-3030-82.lg-275-1408.fa-1201.ca-1804-64',
  `motto` varchar(100) DEFAULT 'Je suis nouveau sur Habbo !',
  `account_created` int(11) NOT NULL,
  `last_login` int(11) DEFAULT NULL,
  `last_online` int(11) DEFAULT NULL,
  `online` tinyint(1) DEFAULT 0,
  `rank` int(11) DEFAULT 1,
  `credits` int(11) DEFAULT 25000,
  `pixels` int(11) DEFAULT 10000,
  `points` int(11) DEFAULT 0,
  `gender` enum('M','F') DEFAULT 'M',
  `real_name` varchar(100) DEFAULT NULL,
  `mail_verified` tinyint(1) DEFAULT 0,
  `account_day_of_birth` date DEFAULT NULL,
  `ip_register` varchar(45) DEFAULT NULL,
  `ip_current` varchar(45) DEFAULT NULL,
  `home_room` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `mail` (`mail`),
  KEY `idx_online` (`online`),
  KEY `idx_rank` (`rank`),
  KEY `idx_last_online` (`last_online`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tables des articles
-- =======================

CREATE TABLE IF NOT EXISTS `chocolatey_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `content` longtext NOT NULL,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `timestamp` int(11) NOT NULL,
  `published` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_author` (`author_id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_published` (`published`),
  KEY `idx_timestamp` (`timestamp`),
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chocolatey_article_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `translate` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tables des photos
-- =======================

CREATE TABLE IF NOT EXISTS `camera_web` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `room_id` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_timestamp` (`timestamp`),
  KEY `idx_room` (`room_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `camera_web_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_like` (`photo_id`, `user_id`),
  KEY `idx_photo` (`photo_id`),
  KEY `idx_user` (`user_id`),
  FOREIGN KEY (`photo_id`) REFERENCES `camera_web`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `camera_web_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `timestamp` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_photo` (`photo_id`),
  KEY `idx_reporter` (`reporter_id`),
  KEY `idx_status` (`status`),
  FOREIGN KEY (`photo_id`) REFERENCES `camera_web`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reporter_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tables des salles
-- =======================

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `owner_name` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `model` varchar(50) DEFAULT 'model_a',
  `password` varchar(100) DEFAULT NULL,
  `state` int(11) DEFAULT 0,
  `users` int(11) DEFAULT 0,
  `users_max` int(11) DEFAULT 25,
  `score` int(11) DEFAULT 0,
  `category` int(11) DEFAULT 1,
  `paper_floor` int(11) DEFAULT 0,
  `paper_wall` int(11) DEFAULT 0,
  `paper_landscape` decimal(10,2) DEFAULT 0.00,
  `thickness_wall` int(11) DEFAULT 0,
  `wall_height` int(11) DEFAULT -1,
  `thickness_floor` int(11) DEFAULT 0,
  `is_public` tinyint(1) DEFAULT 0,
  `guild_id` int(11) DEFAULT 0,
  `tags` text,
  PRIMARY KEY (`id`),
  KEY `idx_owner` (`owner_id`),
  KEY `idx_category` (`category`),
  KEY `idx_public` (`is_public`),
  KEY `idx_score` (`score`),
  KEY `idx_users` (`users`),
  FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tables de modération
-- =======================

CREATE TABLE IF NOT EXISTS `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `expires` int(11) DEFAULT NULL,
  `banned_by` int(11) NOT NULL,
  `type` enum('user','ip','machine') DEFAULT 'user',
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_banned_by` (`banned_by`),
  KEY `idx_expires` (`expires`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`banned_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tables système
-- =======================

CREATE TABLE IF NOT EXISTS `chocolatey_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `volume` int(11) DEFAULT 100,
  `old_chat` tinyint(1) DEFAULT 0,
  `block_following` tinyint(1) DEFAULT 0,
  `block_friendrequests` tinyint(1) DEFAULT 0,
  `block_roominvites` tinyint(1) DEFAULT 0,
  `block_camera_follow` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_security` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trusted_devices` json DEFAULT NULL,
  `two_factor_enabled` tinyint(1) DEFAULT 0,
  `recovery_codes` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Données de test
-- =======================

-- Catégories d'articles par défaut
INSERT INTO `chocolatey_article_categories` (`name`, `translate`, `description`) VALUES
('news', 'Actualités', 'Les dernières nouvelles du jeu'),
('events', 'Événements', 'Événements spéciaux et concours'),
('updates', 'Mises à jour', 'Nouvelles fonctionnalités et améliorations'),
('community', 'Communauté', 'Actualités de la communauté'),
('tips', 'Conseils', 'Conseils et astuces de jeu')
ON DUPLICATE KEY UPDATE translate=VALUES(translate);

-- Utilisateur administrateur par défaut
INSERT INTO `users` (`username`, `mail`, `password`, `look`, `motto`, `account_created`, `rank`, `credits`, `pixels`, `mail_verified`) VALUES
('admin', 'admin@chocolatey.local', SHA2('admin123', 256), 'hr-115-42.hd-195-19.ch-3030-82.lg-275-1408.fa-1201.ca-1804-64', 'Administrateur du système', UNIX_TIMESTAMP(), 7, 100000, 50000, 1),
('demo', 'demo@chocolatey.local', SHA2('demo123', 256), 'hr-100-61.hd-180-2.ch-210-66.lg-270-82.sh-305-62', 'Utilisateur de démonstration', UNIX_TIMESTAMP(), 1, 25000, 10000, 1)
ON DUPLICATE KEY UPDATE password=VALUES(password);

-- Création des préférences pour les utilisateurs par défaut
INSERT INTO `user_preferences` (`user_id`) 
SELECT `id` FROM `users` WHERE `username` IN ('admin', 'demo')
ON DUPLICATE KEY UPDATE user_id=VALUES(user_id);

-- Articles de démonstration
INSERT INTO `chocolatey_articles` (`title`, `description`, `content`, `author_id`, `category_id`, `image`, `timestamp`, `published`) VALUES
('Bienvenue sur Chocolatey CMS', 'Découvrez les nouvelles fonctionnalités de notre CMS optimisé', '<h2>Bienvenue dans la nouvelle version !</h2><p>Cette version de Chocolatey CMS a été entièrement optimisée pour les performances, l\'accessibilité et l\'éco-conception.</p><ul><li>Interface WCAG 2.1 AA compliant</li><li>Performances améliorées de 60%</li><li>Cache intelligent</li><li>Design responsive</li></ul>', 1, 1, 'https://via.placeholder.com/800x400/1e7cf7/ffffff?text=Chocolatey+CMS', UNIX_TIMESTAMP(), 1),
('Guide de démarrage rapide', 'Tout ce que vous devez savoir pour bien commencer', '<h2>Premiers pas</h2><p>Ce guide vous aidera à prendre en main rapidement toutes les fonctionnalités du CMS.</p><h3>Navigation</h3><p>Utilisez le menu principal pour accéder aux différentes sections.</p><h3>Personnalisation</h3><p>Vous pouvez personnaliser votre profil dans les paramètres.</p>', 1, 5, 'https://via.placeholder.com/800x400/28a745/ffffff?text=Guide+de+démarrage', UNIX_TIMESTAMP() - 3600, 1)
ON DUPLICATE KEY UPDATE title=VALUES(title);

SET FOREIGN_KEY_CHECKS = 1;