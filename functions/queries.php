<?php

/**
 * Функция обработки запроса к базе на получение проектов с подсчетом задач в каждом проекте для пользователя с user_id
 * @param int $user_id - идентификатор пользователя
 * @param $link результат выполнения функции подключения к базе
 * @param return - возвращает неформатированный результат обращения к базе  
 * */
function getProjectsCountTasks(mysqli $link, $user_id) {
    $sql = 'SELECT p.id, p.name, count(t.id) AS task_count 
    FROM projects p JOIN tasks t ON p.id = t.project_id 
    WHERE t.user_id = ' . $user_id . ' GROUP BY id'; 
    
    return mysqli_query($link, $sql);
};


/* 
Функция запроса к базе - все записи таблицы tasks по конкретному проекту и для пользователя с id=1
 

    $sql = 'SELECT * FROM tasks WHERE project_id='.$project_id.' AND user_id=1';            //запрос на получение из БД данных из таблицы задач - tasks для project_id = $id

    if ($result = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    }



 Функция запроса к базе - все записи таблицы tasks для пользователя с id=1
 

$sql = 'SELECT * FROM tasks WHERE user_id=1';                           //запрос на получение из БД данных из таблицы задач - tasks для пользователя = 1/2/3

if ($result = mysqli_query($link, $sql)) {
    $tasks = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    }



 Функция запроса к базе - все записи таблицы projects
 
$sql = 'SELECT * FROM projects';                                         //запрос на получение из БД списка проектов  
        
if ($result = mysqli_query($link, $sql)) {                               //запрос выполнен успешно
    $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);           //обрабатываем результат и форматируем его в виде двумерного массива
}
   


 Функция запроса к базе - внести новую задачу - подготовленное выражение 
 

    $sql = 'INSERT INTO tasks (name, project_id, date_end, file, user_id) 
        VALUES (?,?,?,?,1)';                                                 //подготовленное выражение запроса на внесение в БД задачи 
    
    $stmt = db_get_prepare_stmt($link, $sql, $task);                  //функция - создает подготовленное выражение на основе готового SQL запроса и переданных данных
    $result = mysqli_stmt_execute($stmt);                            //Выполняет подготовленное утверждение
*/