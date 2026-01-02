-- NovaRadio CMS Database Schema
-- Version: 0.1.0
-- License: GPL-3.0

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Core Tables

CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(255) DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('super_admin','admin','moderator','editor') DEFAULT 'editor',
    `permissions` JSON DEFAULT NULL,
    `is_op` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `genre` VARCHAR(100) DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `azuracast_url` VARCHAR(500) DEFAULT NULL,
    `api_key` VARCHAR(255) DEFAULT NULL,
    `station_id` INT DEFAULT 1,
    `stream_url` VARCHAR(500) DEFAULT NULL,
    `is_default` TINYINT(1) DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `shows` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `genre` VARCHAR(100) DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `djs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `bio` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `password` VARCHAR(255) DEFAULT NULL,
    `social_links` JSON DEFAULT NULL,
    `azuracast_dj_id` INT DEFAULT NULL,
    `azuracast_username` VARCHAR(100) DEFAULT NULL,
    `azuracast_password` VARCHAR(255) DEFAULT NULL,
    `can_stream` TINYINT(1) DEFAULT 1,
    `can_upload` TINYINT(1) DEFAULT 1,
    `can_playlist` TINYINT(1) DEFAULT 1,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `schedule` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `show_id` INT UNSIGNED DEFAULT NULL,
    `dj_id` INT UNSIGNED DEFAULT NULL,
    `day_of_week` TINYINT NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `events` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `event_date` DATETIME NOT NULL,
    `location` VARCHAR(255) DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT DEFAULT NULL,
    `meta_description` VARCHAR(500) DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sliders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) DEFAULT NULL,
    `subtitle` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(500) NOT NULL,
    `link` VARCHAR(500) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `menu_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `location` ENUM('header','footer') DEFAULT 'header',
    `label` VARCHAR(100) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `target` VARCHAR(20) DEFAULT '_self',
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `widgets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `location` VARCHAR(50) DEFAULT 'footer_1',
    `title` VARCHAR(100) DEFAULT NULL,
    `type` ENUM('text','html','social','menu') DEFAULT 'text',
    `content` TEXT DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `requests` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `artist` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) DEFAULT NULL,
    `message` TEXT DEFAULT NULL,
    `status` ENUM('pending','approved','played','rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `analytics` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `page` VARCHAR(255) NOT NULL,
    `views` INT DEFAULT 1,
    `date` DATE NOT NULL,
    UNIQUE KEY `page_date` (`page`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Blog Tables

CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `excerpt` TEXT DEFAULT NULL,
    `content` LONGTEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `tags` VARCHAR(500) DEFAULT NULL,
    `author_id` INT UNSIGNED DEFAULT NULL,
    `featured` TINYINT(1) DEFAULT 0,
    `views` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `published_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` INT UNSIGNED NOT NULL,
    `parent_id` INT UNSIGNED DEFAULT NULL,
    `author_name` VARCHAR(100) NOT NULL,
    `author_email` VARCHAR(255) DEFAULT NULL,
    `content` TEXT NOT NULL,
    `approved` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Podcast Tables

CREATE TABLE IF NOT EXISTS `podcasts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `author` VARCHAR(100) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `episodes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `podcast_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `audio_url` VARCHAR(500) DEFAULT NULL,
    `duration` INT DEFAULT 0,
    `downloads` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `published_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gallery Tables

CREATE TABLE IF NOT EXISTS `galleries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `cover_image` VARCHAR(500) DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery_images` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `gallery_id` INT UNSIGNED NOT NULL,
    `image` VARCHAR(500) NOT NULL,
    `caption` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Poll Tables

CREATE TABLE IF NOT EXISTS `polls` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `question` VARCHAR(500) NOT NULL,
    `multiple` TINYINT(1) DEFAULT 0,
    `ends_at` DATETIME DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `poll_options` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `poll_id` INT UNSIGNED NOT NULL,
    `option_text` VARCHAR(255) NOT NULL,
    `votes` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `poll_votes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `poll_id` INT UNSIGNED NOT NULL,
    `option_id` INT UNSIGNED NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat Tables

CREATE TABLE IF NOT EXISTS `chat_rooms` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `topic` VARCHAR(255) DEFAULT NULL,
    `is_default` TINYINT(1) DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(255) DEFAULT NULL,
    `password` VARCHAR(255) DEFAULT NULL,
    `dj_id` INT UNSIGNED DEFAULT NULL,
    `is_guest` TINYINT(1) DEFAULT 0,
    `is_op` TINYINT(1) DEFAULT 0,
    `is_banned` TINYINT(1) DEFAULT 0,
    `last_seen` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `room_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat_private` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `from_user` INT UNSIGNED NOT NULL,
    `to_user` INT UNSIGNED NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Advertising & Newsletter Tables

CREATE TABLE IF NOT EXISTS `ads` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `position` ENUM('header','sidebar','footer','popup') DEFAULT 'sidebar',
    `image` VARCHAR(500) DEFAULT NULL,
    `link` VARCHAR(500) DEFAULT NULL,
    `content` TEXT DEFAULT NULL,
    `impressions` INT DEFAULT 0,
    `clicks` INT DEFAULT 0,
    `starts_at` DATETIME DEFAULT NULL,
    `ends_at` DATETIME DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `name` VARCHAR(100) DEFAULT NULL,
    `token` VARCHAR(64) DEFAULT NULL,
    `confirmed` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Radio Feature Tables

CREATE TABLE IF NOT EXISTS `song_history` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `artist` VARCHAR(255) DEFAULT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `album` VARCHAR(255) DEFAULT NULL,
    `art` VARCHAR(500) DEFAULT NULL,
    `played_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `listener_stats` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `listeners` INT DEFAULT 0,
    `unique_listeners` INT DEFAULT 0,
    `recorded_at` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stream_mounts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `bitrate` INT DEFAULT 128,
    `format` VARCHAR(20) DEFAULT 'mp3',
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `live_status` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL UNIQUE,
    `is_live` TINYINT(1) DEFAULT 0,
    `show_id` INT UNSIGNED DEFAULT NULL,
    `dj_id` INT UNSIGNED DEFAULT NULL,
    `live_since` DATETIME DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `dedications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `from_name` VARCHAR(100) NOT NULL,
    `to_name` VARCHAR(100) NOT NULL,
    `message` TEXT DEFAULT NULL,
    `song_request` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('pending','approved','played','rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `special_broadcasts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `dj_id` INT UNSIGNED DEFAULT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `is_live` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `charts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `period` ENUM('weekly','monthly') DEFAULT 'weekly',
    `position` INT NOT NULL,
    `artist` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `movement` ENUM('up','down','same','new') DEFAULT 'new',
    `weeks_on_chart` INT DEFAULT 1,
    `chart_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `favorites` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `visitor_id` VARCHAR(64) NOT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `item_type` ENUM('show','dj','podcast','track') NOT NULL,
    `item_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `visitor_item` (`visitor_id`, `item_type`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `shoutbox` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `message` VARCHAR(255) NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `approved` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Contest & Artist Tables

CREATE TABLE IF NOT EXISTS `contests` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `prize` VARCHAR(255) DEFAULT NULL,
    `rules` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `start_date` DATETIME DEFAULT NULL,
    `end_date` DATETIME DEFAULT NULL,
    `winner_id` INT UNSIGNED DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contest_entries` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `contest_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `answer` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `artists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `bio` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `genre` VARCHAR(100) DEFAULT NULL,
    `website` VARCHAR(500) DEFAULT NULL,
    `social_links` JSON DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trivia & Interactive Tables

CREATE TABLE IF NOT EXISTS `trivia` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `question` TEXT NOT NULL,
    `correct_answer` VARCHAR(255) NOT NULL,
    `wrong_answers` JSON NOT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `points` INT DEFAULT 10,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `trivia_scores` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `visitor_id` VARCHAR(64) NOT NULL UNIQUE,
    `username` VARCHAR(100) DEFAULT NULL,
    `score` INT DEFAULT 0,
    `games_played` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `track_reactions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `artist` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `reaction` ENUM('like','dislike') NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `request_queue` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED NOT NULL,
    `artist` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `requested_by` VARCHAR(100) DEFAULT 'Anonymous',
    `position` INT DEFAULT 0,
    `status` ENUM('queued','playing','played','skipped') DEFAULT 'queued',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `show_reminders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `schedule_id` INT UNSIGNED NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `sent` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `schedule_email` (`schedule_id`, `email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Website Content Tables

CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `role` VARCHAR(100) DEFAULT NULL,
    `content` TEXT NOT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `rating` TINYINT DEFAULT 5,
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `team` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `role` VARCHAR(100) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `social_links` JSON DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sponsors` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `logo` VARCHAR(500) DEFAULT NULL,
    `website` VARCHAR(500) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `tier` ENUM('platinum','gold','silver','bronze','partner') DEFAULT 'partner',
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `faq` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `question` VARCHAR(500) NOT NULL,
    `answer` TEXT NOT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `downloads` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `file_url` VARCHAR(500) NOT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `dj_id` INT UNSIGNED DEFAULT NULL,
    `download_count` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Shop Tables

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `sale_price` DECIMAL(10,2) DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `sku` VARCHAR(100) DEFAULT NULL,
    `stock` INT DEFAULT 0,
    `featured` TINYINT(1) DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(255) NOT NULL,
    `customer_phone` VARCHAR(50) DEFAULT NULL,
    `shipping_address` TEXT DEFAULT NULL,
    `items` JSON NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `shipping` DECIMAL(10,2) DEFAULT 0,
    `total` DECIMAL(10,2) NOT NULL,
    `status` ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    `payment_status` ENUM('pending','paid','refunded') DEFAULT 'pending',
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `promo_codes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('percent','fixed') DEFAULT 'percent',
    `discount_value` DECIMAL(10,2) NOT NULL,
    `min_order` DECIMAL(10,2) DEFAULT 0,
    `max_uses` INT DEFAULT NULL,
    `used_count` INT DEFAULT 0,
    `expires_at` DATETIME DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Support Tables

CREATE TABLE IF NOT EXISTS `tickets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ticket_number` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `category` ENUM('general','technical','billing','feedback') DEFAULT 'general',
    `priority` ENUM('low','medium','high','urgent') DEFAULT 'medium',
    `status` ENUM('open','in_progress','waiting','resolved','closed') DEFAULT 'open',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ticket_replies` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT UNSIGNED NOT NULL,
    `admin_id` INT UNSIGNED DEFAULT NULL,
    `message` TEXT NOT NULL,
    `is_staff` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Tables

CREATE TABLE IF NOT EXISTS `redirects` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `source_url` VARCHAR(500) NOT NULL,
    `target_url` VARCHAR(500) NOT NULL,
    `redirect_type` ENUM('301','302') DEFAULT '301',
    `hits` INT DEFAULT 0,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `activity_log` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `username` VARCHAR(100) DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `entity_type` VARCHAR(50) DEFAULT NULL,
    `entity_id` INT UNSIGNED DEFAULT NULL,
    `details` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `email_templates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `subject` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Additional Tables

CREATE TABLE IF NOT EXISTS `app_tokens` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `token` VARCHAR(255) NOT NULL UNIQUE,
    `device_name` VARCHAR(100) DEFAULT NULL,
    `platform` VARCHAR(50) DEFAULT NULL,
    `last_used` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audio_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `from_name` VARCHAR(100) NOT NULL,
    `to_name` VARCHAR(100) DEFAULT NULL,
    `audio_url` VARCHAR(500) NOT NULL,
    `message` TEXT DEFAULT NULL,
    `duration` INT DEFAULT 0,
    `status` ENUM('pending','approved','played','rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `backups` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `size` BIGINT DEFAULT 0,
    `type` ENUM('database','files','full') DEFAULT 'database',
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cron_jobs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `command` VARCHAR(255) NOT NULL,
    `schedule` VARCHAR(100) NOT NULL,
    `last_run` DATETIME DEFAULT NULL,
    `next_run` DATETIME DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `email_log` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `to_email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `template_id` INT UNSIGNED DEFAULT NULL,
    `status` ENUM('sent','failed','pending') DEFAULT 'pending',
    `error` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `embed_widgets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `config` JSON DEFAULT NULL,
    `embed_code` TEXT DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `listening_history` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `visitor_id` VARCHAR(64) DEFAULT NULL,
    `station_id` INT UNSIGNED NOT NULL,
    `artist` VARCHAR(255) DEFAULT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `listened_at` DATETIME NOT NULL,
    `duration` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lyrics` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `artist` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `lyrics` TEXT NOT NULL,
    `source` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `artist_title` (`artist`, `title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT DEFAULT NULL,
    `link` VARCHAR(500) DEFAULT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `playlists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `type` VARCHAR(50) DEFAULT 'default',
    `is_enabled` TINYINT(1) DEFAULT 1,
    `play_per_songs` INT DEFAULT 0,
    `play_per_minutes` INT DEFAULT 0,
    `weight` INT DEFAULT 3,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `playlist_tracks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `playlist_id` INT UNSIGNED NOT NULL,
    `artist` VARCHAR(255) DEFAULT NULL,
    `title` VARCHAR(255) DEFAULT NULL,
    `file_path` VARCHAR(500) DEFAULT NULL,
    `duration` INT DEFAULT 0,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `push_subscriptions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `endpoint` TEXT NOT NULL,
    `p256dh` VARCHAR(255) DEFAULT NULL,
    `auth` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `social_feed` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `platform` VARCHAR(50) NOT NULL,
    `post_id` VARCHAR(255) NOT NULL,
    `content` TEXT DEFAULT NULL,
    `media_url` VARCHAR(500) DEFAULT NULL,
    `author` VARCHAR(100) DEFAULT NULL,
    `posted_at` DATETIME DEFAULT NULL,
    `cached_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `platform_post` (`platform`, `post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tips` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `dj_id` INT UNSIGNED DEFAULT NULL,
    `station_id` INT UNSIGNED DEFAULT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `donor_name` VARCHAR(100) DEFAULT 'Anonymous',
    `message` TEXT DEFAULT NULL,
    `payment_id` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('pending','completed','failed') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_playlists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `visitor_id` VARCHAR(64) DEFAULT NULL,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `is_public` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Data

INSERT INTO `settings` (`key`, `value`) VALUES
('site_name', 'NovaRadio'),
('site_description', 'Your Internet Radio Station'),
('site_url', ''),
('logo_url', ''),
('logo_text', 'NovaRadio'),
('favicon_url', ''),
('primary_color', '#6366f1'),
('copyright_text', '© 2025 NovaRadio. All rights reserved.'),
('google_analytics', ''),
('custom_head_code', ''),
('custom_footer_code', ''),
('chat_enabled', '1'),
('comments_enabled', '1'),
('comments_moderation', '1'),
('newsletter_enabled', '1'),
('polls_enabled', '1'),
('shoutbox_enabled', '1'),
('favorites_enabled', '1'),
('track_reactions', '1'),
('request_queue_enabled', '1'),
('trivia_enabled', '1'),
('maintenance_mode', '0'),
('maintenance_message', 'We are currently performing maintenance. Please check back soon.'),
('gdpr_enabled', '1'),
('gdpr_title', 'Cookie Consent'),
('gdpr_message', 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.'),
('gdpr_accept_text', 'Accept'),
('gdpr_decline_text', 'Decline'),
('gdpr_privacy_url', ''),
('gdpr_cookies_url', ''),
('gdpr_position', 'bottom'),
('gdpr_style', 'bar');

INSERT INTO `chat_rooms` (`name`, `slug`, `topic`, `is_default`, `sort_order`) VALUES
('Main Chat', 'main', 'Welcome to the main chat room!', 1, 0);

SET FOREIGN_KEY_CHECKS = 1;
