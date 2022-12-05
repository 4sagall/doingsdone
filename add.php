<?php
require_once ('init.php');                                                   //подключаем файл с данными для соединения с БД

if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
}
else {
    $sql = 'SELECT * FROM projects';                                         //запрос на получение из БД списка проектов  
        
    if ($result = mysqli_query($link, $sql)) {                               //запрос выполнен успешно
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);           //обрабатываем результат и форматируем его в виде двумерного массива
    }
    else {
        $error = mysqli_error($link);                                        //получить текст последней ошибки 
        $page_content = include_template('error.php', ['error' => $error]);
    }
        $page_content = include_template('add-form-task.php', [ 'projects' => $projects ]);             //передаем в шаблон формы результат запроса - массив проектов
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST;
    $filename = uniqid() . '.pdf';
    $task['file'] = $filename;
    
    move_uploaded_file ($_FILES['file']['tmp_name'], 'uploads/' . $filename); 

    $sql = 'INSERT INTO tasks (date_add, name, project_id, date_end, file, user_id) 
        VALUES (NOW(), ?,?,?,?, 1)';                                                               //подготовленное выражение запроса на внесение в БД задачи 
    
    $stmt = db_get_prepare_stmt($link, $sql, $task);                                           //функция - создает подготовленное выражение на основе готового SQL запроса и переданных данных
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $task_id = mysqli_insert_id($link);                                                    //определяем id новой задачи
        header(header: 'Location: index.php?id=');                                             //используется для отправки HTTP-заголовка
    }
    else {
        $error = mysqli_connect_error();
        $page_content = include_template('error.php', ['error' => $error]);
        print_r($task);
    }

}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);

?>