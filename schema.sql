CREATE DATABASE IF NOT EXISTS `projects_base` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `projects_base`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `contacts` text,
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
   FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `project_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `date_perfomance` datetime NOT NULL,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_done` datetime DEFAULT NULL,
  FOREIGN KEY (`project_id`)
        REFERENCES `projects` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX `email` ON `users`(`email`);

CREATE INDEX `name` ON `projects`(`name`);
CREATE INDEX `name` ON `tasks`(`name`);
CREATE INDEX `date_perfomance` ON `tasks`(`date_perfomance`);

