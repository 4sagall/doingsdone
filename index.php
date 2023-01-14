<?php
require_once ('init.php');              //подключаем файл с данными для соединения с БД
$show_complete_tasks = random_int(0, 1);      // показывать или нет выполненные задачи
session_start();

/** @var object $link в объекте хранятся данные соединения с базой данных */
if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
}
else {    
    $result = getProjects_CountTasks($link, user_id: 1);           //Функция запроса к базе - проекты с подсчетом задач в каждом проекте для пользователя user_id
    if ($result) {
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);        
    }
    else {
        $error = mysqli_error($link);                                         //получить текст последней ошибки 
        $page_content = include_template('error.php', ['error' => $error]);
    }

    $id = $_GET['id'] ?? null;                       //проверка на существование параметра запроса с идентификатором проекта 
    /** @var array $projects  функция проверяет $id на соответствие с id полученных ранее проектов -> 12 */
    $project_id = getProjectById($id, $projects);

    if ($id == null || !is_int($id)) {
        $error = http_response_code(404);
        $page_content = include_template('error.php', ['error' => $error]);
    }
  
    if ($project_id) {
        $tasks = getTasks_ProjectId_UserId($link, $project_id, user_id: 1);    //Функция обработки запроса на получение всех записей из tasks по проекту $project_id и для user_id
        $page_content = include_template('main.php', [
            'projects' => $projects,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
            'id' => $id
        ]);
    }

    if ($id === "") {
        $tasks = getTasks_UserId($link, user_id: 1);              //Функция обработки запроса на получение из БД данных из таблицы задач - tasks для user_id
        $page_content = include_template('main.php', [
            'projects' => $projects,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
            'id' => $id
        ]);  
    }
}

/** @var object $page_content  шаблон блока страницы */
/** @var array $projects массив проектов*/
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'projects' => $projects,
    'title' => 'Дела в порядке'
]);

print($layout_content);