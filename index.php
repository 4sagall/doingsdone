<?php

require_once ('init.php'); //подключаем файл с данными для соединения с БД
require_once ('helpers.php');
// require_once('data.php');     //данные загружаются из БД
require_once ('functions.php'); //функции подключаются в init.php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
}
else {
    //запрос на получение из БД списка проектов для пользователя = 1/2/3
    $sql = 'SELECT p.id, p.name, count(t.id) AS task_count 
    FROM projects p 
    JOIN tasks t ON p.id = t.project_id 
    WHERE t.user_id=1
    GROUP BY id';

    //выполняем запрос из БД
    $result = mysqli_query($link, $sql);
    
    //запрос выполнен успешно
    if ($result) {
        //обрабатываем результат и форматируем его в виде двумерного массива
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    }
    else {
        //получить текст последней ошибки 
        $error = mysqli_error($link);
        $page_content = include_template('error.php', ['error' => $error]);
    }

$id = $_GET['id'] ?? null;                              //проверка на существование параметра запроса с идентификатором проекта 
$project_id = getProjectById($id, $projects);           // функция проверяет $id на соответствие с id полученных ранее проектов -> 16-20

if ($id == null || !is_int($id)) {
    $error = http_response_code(404);
    $page_content = include_template('error.php', ['error' => $error]);
}
if ($project_id) {
        //запрос на получение из БД данных из таблицы задач - tasks для project_id = $id
        $sql = 'SELECT * FROM tasks WHERE project_id='.$project_id.' AND user_id=1';

        if ($result = mysqli_query($link, $sql)) {
            $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
            
            //передаем в шаблон результат запроса - массив задач
            $page_content = include_template('main.php', [
            'projects' => $projects,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
            'id' => $id
            ]);
        } 
}
if ($id === "") {
    //запрос на получение из БД данных из таблицы задач - tasks для пользователя = 1/2/3
    $sql = 'SELECT * FROM tasks WHERE user_id=1';

    if ($result = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
        
        //передаем в шаблон результат запроса - массив задач
        $page_content = include_template('main.php', [
        'projects' => $projects,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks,
        'id' => $id
        ]);
    }
}
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'projects' => $projects,
    'title' => 'Дела в порядке'
]);

print($layout_content);

?>