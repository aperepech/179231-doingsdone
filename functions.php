<?php

/*
 * Read data from DB and return data array
 * @param (resource) $con
 * @param (string) $sql
 * @param (array) $data
 * @return array
 */

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

/*
 * Insert data to DB
 * @param (resource) $con
 * @param (string) $table
 * @param (array) $data
 * @return false or int
 */

function insert_data($con, string $table, array $data)
{
    $keys = [];
    $values = [];

    foreach ($data as $key => $value) {
        array_push($keys, $key);
        array_push($values, "'".$value."'");
    }

    $sql = 'INSERT INTO '.$table.' ('.implode(", ", $keys).') VALUES ('.implode(', ', $values).');';
    $stmt = db_get_prepare_stmt($con, $sql);

    if($stmt) {
        mysqli_stmt_execute($stmt);
        return mysqli_insert_id($con);
    }

    return false;
}

/*
 * Run SQL-query (for example, update or delete)
 * @param (resource) $con
 * @param (string) $sql
 * @param (array) $data
 * @return boolean
 */

function exec_query ($con, string $sql, array $data = []) {

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


function countTasksByCategory($category, $tasks)
{
    $count = 0;
    if ($category === "Все")
    {
        $count = count($tasks);
    }
    else
    {
        for($i=0; $i<count($tasks); $i++)
        {
            if ($tasks[$i]['category'] === $category)
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

 