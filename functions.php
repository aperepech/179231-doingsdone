<?php

function select_data($con, string $sql, array $data = [])
{
    $stmt =  db_get_prepare_stmt($con, $sql, $data);

    if($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return [];
}

function insert_data($con, string $table, array $data)
{
    $keys = array_keys($data);
    $cols = implode(', ', $keys);
    $values = array_values($data);
    $vals = str_repeat('?, ', count($values));
    $vals = substr($vals, 0, -2);
 
    $sql = "INSERT INTO $table ($cols) VALUES ($vals)";
 
    $stmt = db_get_prepare_stmt($con, $sql, $values);
    
    if (mysqli_stmt_execute($stmt)) {
         return mysqli_insert_id($con);
    }
    
    return false;
 }

function exec_query($con, string $sql, array $data = []) {

    return mysqli_stmt_execute(db_get_prepare_stmt($con, $sql, $data));
}

function renderTemplate($dir, $data)
{
   $dir = 'templates/'.$dir.'.php';
  

    if (!file_exists($dir)) {
        return '';
    }
    ob_start();

    extract($data);

    require_once $dir;
    return ob_get_clean();
}

function countTasksByCategory($project_id, $tasks)
{
    $count = 0;
    if ($project_id === 0)
    {
        $count = count($tasks);
    }
    else
    {
        foreach($tasks as $t)
        {
            if ($t['project_id'] === $project_id)
            {
                $count++;
            }
        }
    }
    return $count;
}

function isValidDate(string $strDate)
{
    return preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $strDate);
}

function dateFromBase($date)
{
    if ($date=='' || $date==null)
        return '';

    return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y');
}

function dateToBase($date)
{
    return \DateTime::createFromFormat('d.m.Y',$date)->format('Y-m-d');
}

function checkTaskId($con, $task_id, $user_id)
{
    return count(select_data($con, 'SELECT tasks.id FROM tasks, projects WHERE tasks.id= ? AND date_done IS NULL AND tasks.project_id=projects.id AND projects.user_id = ?',[$task_id, $user_id]))==1;
}

 