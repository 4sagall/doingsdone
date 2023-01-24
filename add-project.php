<?php
require_once('init.php');           //подключаем файл с данными для соединения с БД

$user_id = $_SESSION['id'];

/** @var object $link в объекте хранятся данные соединения с базой данных */
if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $res1 = getProjects_CountTasks($link, $user_id);          //Функция на получение проектов с подсчетом задач в каждом проекте для пользователя с user_id
    $res2 = getAllProjects($link, $user_id);

    if ($res1 && $res2) {                               //запросы выполнен успешно
        $projects = mysqli_fetch_all($res1, mode: MYSQLI_ASSOC);       //обрабатываем результат и форматируем его в виде двумерного массива
        $projects_user = mysqli_fetch_all($res2, mode: MYSQLI_ASSOC);       //обрабатываем результат и форматируем его в виде двумерного массива

    } else {
        $error = mysqli_error($link);                                        //получить текст последней ошибки
        $page_content = include_template('error.php', ['error' => $error]);
    }
    /** @var array $projects */
    $page_content = include_template('add-form-project.php', [  'projects' => $projects ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {          //Какой метод был использован для запроса страницы; к примеру 'GET', 'HEAD', 'POST', 'PUT'
    $required = ['name'];
    $errors = [];

    $rules = [
        'name' => function ($value) use ($projects_user){
            return validateProject($value, 200, $projects_user);
        }
    ];
    $project = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT]);  //передаем в переменную $task интересующие нас поля формы

    foreach ($project as $key => $value) { //обходим массив и проверяем поля на наличие правил и при установлении правил, валидируем введенное значение
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле должно быть заполнено";
        }
    }
    $errors = array_filter($errors);                //убираем из массива с ошибками все значения типа null

    if (count($errors)) {                                            //проверяем массив с ошибками на наличие ошибок
        $page_content = include_template('add-form-project.php', [
            'project' => $project,
            'errors' => $errors,
            'projects' => $projects,
            'projects_user' => $projects_user
        ]);
    } else {
        $result = addNewProject($link, $project, $user_id);      //Функция обработки запроса на добавление в таблицу projects нового проекта

        if ($result) {                                       //проверяем успешно ли выполнен запрос на внесение задачи в базу данных
            header(header: 'Location: index.php?id=');       //используется для отправки HTTP-заголовка
        }
    }
} else {
    /** @var array $projects */
    $page_content = include_template('add-form-project.php', [ 'projects' => $projects, 'projects_user' => $projects_user ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);
