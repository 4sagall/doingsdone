<?php
require_once ('init.php');              //подключаем файл с данными для соединения с БД
$show_complete_tasks = rand(0, 1);      // показывать или нет выполненные задачи

if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
}
else {
    $result = getProjectsCountTasks($link, user_id: 1);                          //Функция запроса к базе - проекты с подсчетом задач в каждом проекте для пользователя с id=?
    if ($result) {                                                      //запрос выполнен успешно
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);      //обрабатываем результат и форматируем его в виде двумерного массива
    }
    else {
        $error = mysqli_error($link);                                         //получить текст последней ошибки 
        $page_content = include_template('error.php', ['error' => $error]);
    }
    
    $id = $_GET['id'] ?? null;                       //проверка на существование параметра запроса с идентификатором проекта 
    $project_id = getProjectById($id, $projects);   // функция проверяет $id на соответствие с id полученных ранее проектов -> 16-20
    
    if ($id == null || !is_int($id)) {
        $error = http_response_code(404);
        $page_content = include_template('error.php', ['error' => $error]);
    }
    
    if ($project_id) {
        $sql = 'SELECT * FROM tasks WHERE project_id='.$project_id.' AND user_id=1';            //запрос на получение из БД данных из таблицы задач - tasks для project_id = $id

        if ($result = mysqli_query($link, $sql)) {
            $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
            
            $page_content = include_template('main.php', [                         //передаем в шаблон результат запроса - массив задач
            'projects' => $projects,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
            'id' => $id
            ]);
        } 
    }
    
    if ($id === "") {
        $sql = 'SELECT * FROM tasks WHERE user_id=1';                           //запрос на получение из БД данных из таблицы задач - tasks для пользователя = 1/2/3
        
        if ($result = mysqli_query($link, $sql)) {
            $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
            
            $page_content = include_template('main.php', [                      //передаем в шаблон результат запроса - массив задач
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