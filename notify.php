<?php

require_once 'vendor/autoload.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'email_init.php';
require_once 'mysql_helper.php';

$sql = "SELECT tasks.name as task_name, DATE_FORMAT(tasks.date_perfomance,'%d.%m.%y %H:%i') as date_perfomance, users.id, users.email, users.name as user_name ".
       'FROM tasks, projects, users WHERE tasks.project_id = projects.id '.
       'AND projects.user_id = users.id AND tasks.date_done IS NULL '.
       'AND date_perfomance <= DATE_ADD(NOW(), INTERVAL 1 HOUR) '.
       'AND date_perfomance >= NOW() ORDER BY users.id, tasks.date_perfomance';

$tasks = select_data($con, $sql);

if(!count($tasks)) {
    exit();
}

$user_id = $tasks[0]['id'];
$user_email = $tasks[0]['email'];
$msg = 'Уважаемый(-ая), '.$tasks[0]['user_name'].'. У вас запланированы задачи: ';
$msg .= '«'.$tasks[0]['task_name'].'» на '.$tasks[0]['date_perfomance'].'; ';

for($i=1; $i<count($tasks); $i++) {

    if($tasks[$i]['id'] != $user_id)
    {
        sendMessage($mailer, $msg, $email['from'], $user_email);
        $user_id = $tasks[$i]['id'];
        $user_email = $tasks[$i]['email'];

        $msg = 'Уважаемый(-ая), '.$tasks[$i]['user_name'].'. У вас запланированы задачи: ';
        $msg .= '«'.$tasks[$i]['task_name'].'» на '.$tasks[$i]['date_perfomance'].'; ';
    }
    else
    {
        $msg .= '«'.$tasks[$i]['task_name'].'» на '.$tasks[$i]['date_perfomance'].'; ';
    }
}

sendMessage($mailer,$msg,$email['from'],$user_email);