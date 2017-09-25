<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'mysql_helper.php';

if(isset($_GET['reg']))
{
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $new_user['name']['value'] = trim($_POST['name'] ?? '');
        $new_user['email']['value'] = trim($_POST['email'] ?? '');
        $new_user['password']['value'] = $_POST['password'] ?? '';

        $is_error = false;

        if ($new_user['name']['value'] === '') {
            $new_user['name']['error'] = 'Укажите имя';
            $is_error = true;
        }

        if ($new_user['email']['value'] === '') {
            $new_user['email']['error'] = 'Укажите email';
            $is_error = true;
        } elseif (!filter_var($new_user['email']['value'], FILTER_VALIDATE_EMAIL)) {
            $new_user['email']['error'] = 'Недопустимый email';
            $is_error = true;
        } elseif (count(select_data($con,'SELECT * FROM users WHERE email = ?',[$new_user['email']['value']]))>0)
        {
            $new_user['email']['error'] = 'Такой email уже зарегистрирован';
            $is_error = true;
        }

        if ($new_user['password']['value'] === '') {
            $new_user['password']['error'] = 'Укажите пароль';
            $is_error = true;
        }

        if(!$is_error) //данные корректны
        {
            $ins_user = [
                'name' => $new_user['name']['value'],
                'email' => $new_user['email']['value'],
                'password' => password_hash($new_user['password']['value'],PASSWORD_DEFAULT),
            ];
            insert_data($con, 'users', $ins_user);

            header('Location: index.php?login');
        }
    }

    $reg = renderTemplate('register', compact('new_user'));

    print($reg);

    die();
}

if(isset($_GET['show_completed']))
{
    $value = $_GET['show_completed'] == 1 ? 1 : 0;
    setcookie('show_complete_tasks', $value, strtotime("Mon, 25-Jan-2027 10:00:00 GMT"), '/');
    header('Location: index.php');
}

session_start();

if(!isset($_SESSION['user']))
{
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $auth['email']['value'] = trim($_POST['email'] ?? '');
        $auth['password']['value'] = $_POST['password'] ?? '';
        $is_error = false;

        if ($auth['email']['value'] == '') {
            $auth['email']['error'] = 'Не указан email';
            $is_error = true;
        } elseif (!filter_var($auth['email']['value'], FILTER_VALIDATE_EMAIL)) {
            $auth['email']['error'] = 'Недопустимый email';
            $is_error = true;
        }

        if ($auth['password']['value'] == '') {
            $auth['password']['error'] = 'Не указан пароль';
            $is_error = true;
        }

        $users = [];
        if (!$is_error) {
            $users = select_data($con, 'SELECT * FROM users WHERE email = ?', [$auth['email']['value']]);

            if(count($users) != 1 || !password_verify($auth['password']['value'], $users[0]['password']))
            {
                $auth['error'] = 'Неверные данные для входа';
                $is_error = true;
            }
        }

        if(!$is_error) //аутентификация успешна
        {
            $_SESSION['user'] = $users[0];
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

$primary_menu = select_data($con, 'SELECT * FROM projects WHERE user_id = ? ORDER BY name', [$user['id']]);
array_unshift($primary_menu, ['name' => 'Все', 'id' => 0, 'user_id' => $user['id']]);

//проверка проекта
$sid = $_GET['id'] ?? 0;
$id = (int)$sid;

// проверяем есть ли проект с таким id в базе
if($id != $sid || !in_array($id,array_column($primary_menu,'id')))
{
    http_response_code(404);
    die();
}

if(isset($_GET['del']))
{
    $del_id = (int)$_GET['del'];

    if(trim($_GET['del']) == $del_id && checkTaskId($con, $del_id, $user['id']))
    {
        exec_query($con,'DELETE FROM tasks WHERE id = ?', [$del_id]);
    }
    else
    {
        header('Location: index.php');
    }
}
elseif(isset($_GET['done']))
{
    $done_id = (int)$_GET['done'];

    if(trim($_GET['done']) == $done_id && checkTaskId($con, $done_id, $user['id']))
    {
        exec_query($con,'UPDATE tasks SET date_done = NOW() WHERE id = ?', [$done_id]);
    }
    else
    {
        header('Location: index.php');
    }
}

$add = $_GET['add'] ?? '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $task['name']['value'] = trim($_POST['name'] ?? '');
    $task['project_index']['value'] = trim($_POST['project'] ?? '');
    $task['date_of_perfomans']['value'] = trim($_POST['date'] ?? '');

    $is_error = false;

    if ($task['name']['value'] === '') {
        $task['name']['error'] = 'Укажите название проекта';
        $is_error = true;
    }

    if ($task['date_of_perfomans']['value'] === '') {
        $task['date_of_perfomans']['error'] = 'Укажите дату';
        $is_error = true;
    } elseif (!isValidDate($task['date_of_perfomans']['value'])) {
        $task['date_of_perfomans']['error'] = 'Недопустимая дата';
        $is_error = true;
    }

    if ($is_error) {
        $add = 'task';
    } else {

        $file = null;

        if (isset($_FILES['preview']) && is_uploaded_file($_FILES['preview']['tmp_name'])) {
            $file_name = $_FILES['preview']['name'];
            $file_path = __DIR__ . '/';
            $file = $file_path . $file_name;
            move_uploaded_file($_FILES['preview']['tmp_name'], $file);
        }

        $new_task = [
            'name' => $task['name']['value'],
            'file' => $file,
            'date_perfomance' => dateToBase($task['date_of_perfomans']['value']),
            'project_id' => $task['project_index']['value']
        ];

        insert_data($con, 'tasks', $new_task);
    }
}

if ($add == 'task')
{
    $projects = $primary_menu;
    $task_form = renderTemplate('task_form', compact('projects','task'));
    $body_classes = "overlay";
}

$filter = (int)$_GET['filter'] ?? 0;

$menu_tasks = select_data($con, 'SELECT tasks.id, tasks.project_id, tasks.name, tasks.file, tasks.date_perfomance, tasks.date_create, tasks.date_done FROM tasks, projects WHERE tasks.project_id = projects.id AND projects.user_id = ? ORDER BY tasks.date_create DESC, tasks.date_perfomance DESC', [$user['id']]);

switch($filter) {

    case 0: $tasks = $menu_tasks;
            break;
    case 1: $tasks = select_data($con, 'SELECT tasks.id, tasks.project_id, tasks.name, tasks.file, tasks.date_perfomance, tasks.date_create, tasks.date_done FROM tasks, projects WHERE tasks.project_id = projects.id AND projects.user_id = ? AND TO_DAYS(date_perfomance) - TO_DAYS(NOW()) = 0 ORDER BY tasks.date_create DESC, tasks.date_perfomance DESC', [$user['id']]);
            break;
    case 2: $tasks = select_data($con, 'SELECT tasks.id, tasks.project_id, tasks.name, tasks.file, tasks.date_perfomance, tasks.date_create, tasks.date_done FROM tasks, projects WHERE tasks.project_id = projects.id AND projects.user_id = ? AND TO_DAYS(date_perfomance) - TO_DAYS(NOW()) = 1 ORDER BY tasks.date_create DESC, tasks.date_perfomance DESC', [$user['id']]);
            break;
    case 3: $tasks = select_data($con, 'SELECT tasks.id, tasks.project_id, tasks.name, tasks.file, tasks.date_perfomance, tasks.date_create, tasks.date_done FROM tasks, projects WHERE tasks.project_id = projects.id AND projects.user_id = ? AND date_done IS NULL AND TO_DAYS(date_perfomance) - TO_DAYS(NOW()) < 0 ORDER BY tasks.date_create DESC, tasks.date_perfomance DESC', [$user['id']]);
            break;
}



if(isset($_COOKIE['show_complete_tasks']))
{
    $show_complete_tasks = (int)$_COOKIE['show_complete_tasks'];
}

$content = renderTemplate('index', compact('tasks', 'id', 'show_complete_tasks', 'filter'));
$tasks = $menu_tasks;
$layout = renderTemplate('layout', compact('title', 'user', 'content', 'primary_menu', 'tasks', 'id', 'task_form', 'body_classes'));

print($layout);
