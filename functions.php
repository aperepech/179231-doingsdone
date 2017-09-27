<?php

/**
 * Получение данныз из базы данных
 *
 * @param $con Ресурс соединения
 * @param string $sql SQL-запрос с плейсхолдерами на всех переменных значений
 * @param array $data Данные для запроса [необязательный аргумент]
 *
 * @return array Пустой массив, если данных нет или возникла ошибка, двумерный массив с данными из БД
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
/**
 * Добавляет новые данные в указанную таблицу, при этом самостоятельно формирует подготовленный запрос
 *
 * @param $con Ресурс соединения
 * @param string $table Имя таблицы, в которую добавляются данные
 * @param array $data Ключи - имена полей, а значения - значения полей таблицы
 *
 * @return bool|int При ошибке false, иначе идентификатор (первичный ключ) последней добавленной записи
 */
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

/**
 * Выполнение произвольного запроса
 *
 * @param $con Ресурс соединения
 * @param string $sql SQL-запрос с плейсхолдерами на всех переменных значений
 * @param array $data Данные для запроса [необязательный аргумент]
 *
 * @return bool false, если произошла ошибка во время выполнения запроса, иначе true
 */
function exec_query($con, string $sql, array $data = []) {

    return mysqli_stmt_execute(db_get_prepare_stmt($con, $sql, $data));

}

/**
 * Генерация шаблона
 *
 * @param $dir Название шаблона
 * @param $data Данные для шаблона
 *
 * @return string Html код шаблона
 */
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

/**
 * Определяет количество задач для данного проекта
 *
 * @param $project_id Индетификатор проекта
 * @param $tasks Массив всех задач
 *
 * @return int Количество задач
 */
function countTasksByProject($project_id, $tasks)
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

/**
 * Проверка даты на формат d.m.Y
 *
 * @param string $strDate Дата
 *
 * @return int Результат проверки
 */
function isValidDate(string $strDate)
{
    return preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $strDate);
}

/**
 * Конвертация даты из формата 'Y-m-d H:i:s' в 'd.m.Y'
 *
 * @param $date Дата в формате 'Y-m-d H:i:s'
 *
 * @return string Дата в формате 'd.m.Y'
 */
function dateFromBase($date)
{
    if ($date=='' || $date==null)
        return '';

    return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('d.m.Y');
}

/**
 * Конвертация даты из формата 'd.m.Y' в 'Y-m-d H:i:s'
 *
 * @param $date Дата в формате 'd.m.Y'
 *
 * @return string Дата в формате 'Y-m-d H:i:s'
 */
function dateToBase($date)
{
    return \DateTime::createFromFormat('d.m.Y',$date)->format('Y-m-d');
}

/**
 * Проверка на существование задачи у данного пользователя
 *
 * @param $con Ресурс соединения
 * @param int $task_id Идентификатор задачи
 * @param int $user_id Идентификатор пользователя
 *
 * @return bool Результат проверки
 */
function isExistsUserTask($con, int $task_id, int $user_id)
{
    return count(select_data($con, 'SELECT tasks.id FROM tasks, projects WHERE tasks.id= ? AND date_done IS NULL AND tasks.project_id=projects.id AND projects.user_id = ?',[$task_id, $user_id]))==1;
}

/**
 * @param $mailer Объект Swift_Mailer
 * @param $msg Текст письма
 * @param $email_from Email отправителя
 * @param $user_email Email пользователя
 * @return mixed
 */
function sendMessage($mailer, $msg, $email_from, $user_email)
{
    // Create a message
    $message = (new Swift_Message('Уведомление от сервиса «Дела в порядке»'))
        ->setFrom([$email_from => 'Дела в порядке!'])
        ->setTo([$user_email])
        ->setBody($msg);

    return $mailer->send($message);
}

 