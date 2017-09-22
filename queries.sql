-- добавление пользователей
INSERT INTO `users`(`id`, `email`, `name`, `password`) VALUES 
(1, 'ignat.v@gmail.com','Игнат','$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
(2, 'kitty_93@li.ru','Леночка','$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'), 
(3, 'warrior07@mail.ru','Руслан','$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

-- добавление проектов для одного пользователя
INSERT INTO `projects`(`id`,`name`, `user_id`) VALUES (1,'Входящие',1), 
						(2,'Учеба',1), 
						(3,'Работа',1), 
						(4,'Домашние дела',1), 
						(5,'Авто',1);
						
-- добавление задач для одного проекта
INSERT INTO `tasks`(`name`, `project_id`, `date_perfomance` ) VALUES
				('Собеседование в IT компании',3, '2018-06-01'), 
				('Выполнить тестовое задание',3, '2018-05-25'), 
				('Сделать задание первого раздела',2, '2018-04-21'), 
				('Встреча с другом',1, '2018-04-22'), 
				('Купить корм для кота',4, '2018-06-01'), 
				('Заказать пиццу',4, '2018-06-01');
				
-- получить список из всех проектов для одного пользователя;
SELECT `id`, `name`, `user_id` FROM `projects` WHERE `user_id` = 1;

-- получить список из всех задач для одного проекта;
SELECT * FROM `tasks` WHERE `project_id` = 3;

-- пометить задачу как выполненную;
UPDATE `tasks` SET `date_done`= NOW() WHERE `id` = 5;

-- получить все задачи для завтрашнего дня;
SELECT * FROM `tasks` WHERE TO_DAYS(date_perfomance) - TO_DAYS(NOW()) = 1;

-- обновить название задачи по её идентификатору.
UPDATE `tasks` SET `name`= 'Новое название' WHERE id = 2;