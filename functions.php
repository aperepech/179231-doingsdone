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

function isValidDate(string $strDate)
 {
    return preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $strDate);
 }

 