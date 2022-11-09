<?php

require_once('init.php'); //подключаем файл с данными для соединения с БД
require_once('helpers.php');
// require_once('data.php');     //данные загружаются из БД
//require_once('functions.php'); //функции подключаются в init.php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

if(!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
}
else {
    //запрос на получение из БД списка проектов для пользователя = 1/2/3
    $sql = 'SELECT * FROM projects WHERE user_id=2';
    //выполняем запрос из БД
    $result = mysqli_query($link, $sql);
    //запрос выполнен успешно
    if($result) {
        //обрабатываем результат и форматируем его в виде двумерного массива
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    }
    else {
        //получить текст последней ошибки 
        $error = mysqli_error($link);
        $page_content = include_template('error.php', ['error' => $error]);
    }
    //запрос на получение из БД данных из таблицы задач - tasks для пользователя = 1/2/3
    $sql = 'SELECT id, date_add, status, name, file, date_end, project_id FROM tasks WHERE user_id=2';

    if($result = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
        
        //передаем в шаблон результат запроса - массив задач
        $page_content = include_template('main.php', [
        'projects' => $projects,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks
        ]);
    }
    else {
        //получить текст последней ошибки
        $page_content = include_template('error.php', ['error' => mysqli_error($link)]);
    }
}
 
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'projects' => $projects,
    'title' => 'Дела в порядке'
]);

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
