<?php

require_once('init.php'); //подключаем файл с данными для соединения с БД
require_once('helpers.php');
// require_once('data.php');
require_once('functions.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

if($link) { 
    //запрос на получение из БД списка проектов для пользователя = 2
    $sql = 'SELECT * FROM projects WHERE user_id=1';
}
    //запрос выполнен успешно
    if($result = mysqli_query($link, $sql)) {
        //обрабатываем результат и форматируем его в виде двумерного массива
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    }

if($link) {
    //запрос на получение из БД данных из таблицы задач - tasks
    $sql = 'SELECT id, date_add, status, name, file, date_end, project_id FROM tasks WHERE user_id=1';
}
    if($result = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
        
//передаем в шаблон результат запроса - массив задач
$page_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks 
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'projects' => $projects,
    'title' => 'Дела в порядке'
]);

    }

print($layout_content);

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}
 */
