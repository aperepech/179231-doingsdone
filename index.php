<?php

require_once 'functions.php';
require_once 'options.php';

$sid = $_GET['id'] ?? 0;
$id = (int)$sid;

if($id != $sid || $id<0 || $id>count($tasks)-1) //6 id 0 1 2 3 4 5
{
    http_response_code(404);
    die();
}

$add = $_GET['add'] ?? '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    /// работа с формой

    //print_r($_POST);
    // получаем данные

    $task['name'] = $_POST['name'] ?? '';
    $task['project_index'] = $_POST['project'] ?? '';
    $task['date_of_perfomans'] = $_POST['date'] ?? '';

    $task['task'] = trim($task['name']);
    $task['project_index'] = trim($task['project_index']);
    $task['date_of_perfomans'] = trim($task['date_of_perfomans']);

    // проверка

    if($task['name'] === '')
    {
        $errors[0] = 'Укажите название проекта';
    }

    if ($task['date_of_perfomans'] === '')
    {
        $errors[1] = 'Укажите дату';
    }

    if(!isValidDate($task['date_of_perfomans']))
    {
        $errors[2] = 'Недопустимая дата';
    }

    //проверку прошли

    if (count($errors)>0) {

        $add = 'task';

    }
    else {

        //есть ли файл
        if(isset($_FILES['preview']))
        {
            $file_name = $_FILES['preview']['name'];
            $file_path = __DIR__.'/';
            move_uploaded_file($_FILES['preview']['tmp_name'], $file_path.$file_name);
        }

        $new_task = [
            'task' => $task['name'],
            'date_of_perfomans' => $task['date_of_perfomans'],
            'category' => $primary_menu[$task['project_index']],
            'readiness' => 'Нет'
        ];

        array_unshift($tasks, $new_task);
    }

}
///

if ($add == 'task')
{
    $projects = $primary_menu;
    $task_form = renderTemplate('task_form', compact('projects','errors','task'));
    $body_classes = "overlay";
}

$project = $primary_menu[$id];
$content = renderTemplate('index', compact('tasks', 'project'));
$layout = renderTemplate('layout', compact('title', 'user_name', 'content', 'primary_menu', 'tasks', 'id', 'task_form', 'body_classes'));

print($layout);
