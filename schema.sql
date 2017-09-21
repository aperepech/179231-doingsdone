CREATE DATABASE IF NOT EXISTS `projects_base` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `projects_base`;

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `contacts` text,
  `reg_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `projects` (
  `id_project` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `id_user` int(11) NOT NULL,
   FOREIGN KEY (`id_user`)
        REFERENCES `users` (`id_user`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id_task` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `date_perfomance` datetime NOT NULL,
  `date_create` datetime NOT NULL,
  `date_done` datetime DEFAULT NULL,
  FOREIGN KEY (`id_user`)
        REFERENCES `users` (`id_user`)
        ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (`id_project`)
        REFERENCES `projects` (`id_project`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX `email` ON `users`(`email`);

CREATE INDEX `name` ON `projects`(`name`);
CREATE INDEX `name` ON `tasks`(`name`);
CREATE INDEX `date_perfomance` ON `tasks`(`date_perfomance`);

