<?php

require_once 'functions.php';
require_once 'options.php';
require_once 'userdata.php';

session_start();

if(!isset($_SESSION['user']))
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $auth['email'] = trim($_POST['email'] ?? '');
        $auth['password'] = $_POST['password'] ?? '';

        if($auth['email'] == '')
        {
            $errors[0] = 'Не указан email';
        }
        elseif (!filter_var($auth['email'], FILTER_VALIDATE_EMAIL))
        {
            $errors[0] = 'Недопустимый email';
        }

        if($auth['password'] == '')
        {
            $errors[1] = 'Не указан пароль';
        }

        $res = array_filter($users, function ($a) use ($auth) {
            return $a['email'] == $auth['email'] && password_verify($auth['password'], $a['password']);
        });

        if (count($errors) == 0 && count($res) != 1) {
            $errors[2] = 'Неверные данные для входа';
        }

        if(count($errors) == 0) //аутентификация успешна
        {
            $_SESSION['user'] = array_shift($res);
            header('Location: index.php');
        }
    }

    $guest_data = ['hidden' => 'hidden', 'body_classes' => '',
        'errors' => [], 'auth' => []];
    if (isset($_GET['login']))
    {
        $guest_data = ['hidden' => '', 'body_classes' => 'overlay',
            'errors' => $errors, 'auth' => $auth];
    }
    $guest = renderTemplate('guest', $guest_data);

    print($guest);

    die();

}
else
{
    $user = $_SESSION['user'];
}

$sid = $_GET['id'] ?? 0;
$id = (int)$sid;

if($id != $sid || !array_key_exists($id, $tasks)) 
{
    http_response_code(404);
    die();
}

$add = $_GET['add'] ?? '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $task['name'] = trim($_POST['name'] ?? '');
    $task['project_index'] = trim($_POST['project'] ?? '');
    $task['date_of_perfomans'] = trim($_POST['date'] ?? '');

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


    if (count($errors)>0) {

        $add = 'task';

    }
    else {

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


if ($add == 'task')
{
    $projects = $primary_menu;
    $task_form = renderTemplate('task_form', compact('projects','errors','task'));
    $body_classes = "overlay";
}


$project = $primary_menu[$id];
$content = renderTemplate('index', compact('tasks', 'project'));
$layout = renderTemplate('layout', compact('title', 'user', 'content', 'primary_menu', 'tasks', 'id', 'task_form', 'body_classes'));

print($layout);
