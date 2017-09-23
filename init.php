<?php

$con = mysqli_connect($db['host'],$db['user'],$db['password'],$db['db_name']);

if (!$con)
{
	$error = renderTemplate('error', ['error' => 'Текст ошибки: '.mysqli_connect_error()]);
	print($error);
	
	exit();
}
