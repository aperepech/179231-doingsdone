<?php

// база данных
$db = [
	'host' => 'localhost',
	'user' => 'root',
	'password' => '',
	'db_name' => 'projects_base'
];

//email
$email = [
    'from' => 'doingsdone@mail.ru',
    'username' => 'doingsdone@mail.ru',
    'password' => 'rds7BgcL',
    'host' => 'smtp.mail.ru',
    'port' => 465,
    'encrypt' => 'ssl'
];

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

$days = rand(-3, 3);
$task_deadline_ts = strtotime("+" . $days . " day midnight"); // метка времени даты выполнения задачи
$current_ts = strtotime('now midnight'); // текущая метка времени

// запишите сюда дату выполнения задачи в формате дд.мм.гггг
$date_deadline = date("d.m.Y", $task_deadline_ts);

// в эту переменную запишите кол-во дней до даты задачи
$days_until_deadline = floor(($task_deadline_ts - $current_ts)/86400);

$title = 'Дела в порядке!';

$task_form = '';
$body_classes='';

$errors = [];
$auth = ['email' => ['value' => '', 'error' => ''],
    'password' => ['value' => '', 'error' => ''],
    'error' => ''];
$task = ['name' => ['value' => '', 'error' => ''],
        'project_index' => ['value' => '', 'error' => ''],
        'date_of_perfomans' => ['value' => '', 'error' => ''],
        ];
$user = [];
$show_complete_tasks = 0;
$filter = 0;