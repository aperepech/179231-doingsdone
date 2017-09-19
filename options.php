<?php
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

$primary_menu = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date_of_perfomans' => '01.06.2018',
        'category' => 'Работа',
        'readiness' => 'Нет'
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date_of_perfomans' => '25.05.2018',
        'category' => 'Работа',
        'readiness' => 'Нет'
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date_of_perfomans' => '21.04.2018',
        'category' => 'Учеба',
        'readiness' => 'Да'
    ],
    [
        'task' => 'Встреча с другом',
        'date_of_perfomans' => '22.04.2018',
        'category' => 'Входящие',
        'readiness' => 'Нет'
    ],
    [
        'task' => 'Купить корм для кота',
        'date_of_perfomans' => '01.06.2018',
        'category' => 'Домашние дела',
        'readiness' => 'Нет'
    ],
    [
        'task' => 'Заказать пиццу',
        'date_of_perfomans' => '01.06.2018',
        'category' => 'Домашние дела',
        'readiness' => 'Нет'
    ]
];

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