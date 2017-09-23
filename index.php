<?php
require_once 'functions.php';
require_once 'userdata.php';
require_once 'init.php';
require_once 'mysql_helper.php';

if(isset($_GET['show_completed']))
{
    $value = $_GET['show_completed'] == 1 ? 1 : 0;
    setcookie('show_complete_tasks', $value, strtotime("Mon, 25-Jan-2027 10:00:00 GMT"), '/');
    header('Location: index.php');
}

session_start();

if(!isset($_SESSION['user']))
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $auth['email']['value'] = trim($_POST['email'] ?? '');
        $auth['password']['value'] = $_POST['password'] ?? '';
        $is_error = false;

        if($auth['email']['value'] == '')
        {
            $auth['email']['error'] = 'Не указан email';
            $is_error = true;
        }
        elseif (!filter_var($auth['email']['value'], FILTER_VALIDATE_EMAIL))
        {
            $auth['email']['error']  = 'Недопустимый email';
            $is_error = true;
        }

        if($auth['password']['value'] == '')
        {
            $auth['password']['error']  = 'Не указан пароль';
            $is_error = true;
        }

        $res = array_filter($users, function ($a) use ($auth) {
            return $a['email'] == $auth['email']['value'] && password_verify($auth['password']['value'], $a['password']);
        });

        if (!$is_error && count($res) != 1) {
            $auth['error'] = 'Неверные данные для входа';
            $is_error = true;
        }

        if(!$is_error) //аутентификация успешна
        {
            $_SESSION['user'] = array_shift($res);
            header('Location: index.php');
        }
    }

    $guest_data = ['hidden' => 'hidden', 'body_classes' => '',
        'auth' => []];
    if (isset($_GET['login']))
    {
        $guest_data = ['hidden' => '', 'body_classes' => 'overlay',
            'auth' => $auth];
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

    $task['name']['value'] = trim($_POST['name'] ?? '');
    $task['project_index']['value'] = trim($_POST['project'] ?? '');
    $task['date_of_perfomans']['value'] = trim($_POST['date'] ?? '');

    $is_error = false;

    if($task['name']['value'] === '')
    {
        $task['name']['error'] = 'Укажите название проекта';
        $is_error = true;
    }

    if ($task['date_of_perfomans']['value'] === '')
    {
        $task['date_of_perfomans']['error'] = 'Укажите дату';
        $is_error = true;
    }
    elseif(!isValidDate($task['date_of_perfomans']['value']))
    {
        $task['date_of_perfomans']['error'] = 'Недопустимая дата';
        $is_error = true;
    }

    if ($is_error) {
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
            'task' => $task['name']['value'],
            'date_of_perfomans' => $task['date_of_perfomans']['value'],
            'category' => $primary_menu[$task['project_index']['value']],
            'readiness' => 'Нет'
        ];

        array_unshift($tasks, $new_task);
    }

}


if ($add == 'task')
{
    $projects = $primary_menu;
    $task_form = renderTemplate('task_form', compact('projects','task'));
    $body_classes = "overlay";
}

if(isset($_COOKIE['show_complete_tasks']))
{
    $show_complete_tasks = (int)$_COOKIE['show_complete_tasks'];
}

$project = $primary_menu[$id];
$content = renderTemplate('index', compact('tasks', 'project', 'show_complete_tasks'));
$layout = renderTemplate('layout', compact('title', 'user', 'content', 'primary_menu', 'tasks', 'id', 'task_form', 'body_classes'));

print($layout);
