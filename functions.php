<?php

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

function isValidDate($str)
{
    // d.m.Y
    // 02.10.2007

    $ar = explode('.',$str);

    if (count($ar) !== 3) {
        return false;
    }

    if(strlen($ar[0])!== 2 || strlen($ar[1])!== 2 || strlen($ar[2])!== 4)
    {
        return false;
    }

    $d = (int)$ar[0];
    $m = (int)$ar[1];
    $y = (int)$ar[2];

    if(!checkdate($m,$d,$y) || !is_numeric($ar[0]) || !is_numeric($ar[1]) || !is_numeric($ar[2]))
        return false;

    return true;
}