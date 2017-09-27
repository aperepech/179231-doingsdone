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

$title = 'Дела в порядке!';

$task_form = '';
$body_classes = '';

$errors = [];
$auth = ['email' => ['value' => '', 'error' => ''],
    'password' => ['value' => '', 'error' => ''],
    'error' => ''];
$task = [
        'name' => ['value' => '', 'error' => ''],
        'project_index' => ['value' => '', 'error' => ''],
        'date_of_perfomans' => ['value' => '', 'error' => ''],
    ];
$user = [];
$new_user = ['name' => ['value' => '', 'error' => ''],
    'email' => ['value' => '', 'error' => ''],
    'password' => ['value' => '', 'error' => ''],
];
$show_complete_tasks = 0;
$filter = 0;