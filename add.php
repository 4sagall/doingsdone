<?php
require_once('init.php'); //подключаем файл с данными для соединения с БД
$user_id = $_SESSION['id'];
/** @var object $link в объекте хранятся данные соединения с базой данных */
if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $result = getAllProjects($link);                            //Функция обработки запроса к базе на получение всех записей таблицы projects
    if ($result) {                               //запросы выполнен успешно
        $projects = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);       //обрабатываем результат и форматируем его в виде двумерного массива

    } else {
        $error = mysqli_error($link);                                        //получить текст последней ошибки 
        $page_content = include_template('error.php', ['error' => $error]);
    }
    /** @var array $projects */
    $page_content = include_template('add-form-task.php', [
        'projects' => $projects
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {          //Какой метод был использован для запроса страницы; к примеру 'GET', 'HEAD', 'POST', 'PUT'
    $required = ['name', 'project', 'date'];
    $errors = [];

    /** @var array $projects */
    $rules = [
        'name' => function ($value) {
            return validateTaskName($value, 300);
        },
        'project' => function ($value) use ($projects) {
            return validateProjectId($value, $projects);
        },
        'date' => function ($value) {
            return validateDate($value);
        }
    ];
    $task = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT, 'project' => FILTER_DEFAULT, 'date' => FILTER_DEFAULT]); //передаем в переменную $task интересующие нас поля формы

    foreach ($task as $key => $value) { //обходим массив и проверяем поля на наличие правил и при установлении правил, валидируем введенное значение
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле должно быть заполнено";
        }
    }
    $errors = array_filter($errors);                //убираем из массива с ошибками все значения типа null

    if (isset($_FILES)) {                           //Валидация файла - проверяем загружен ли файл
        $finfo = finfo_open(FILEINFO_MIME_TYPE);    //если глобальный массив $_FILES не пустой, определяем интересующие нас значения файла
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];

        if ($file_size > 3000000) {
            $errors['file'] = "Максимальный размер файла: 3Mb";
        }
        if ($file_size > 0 && $file_size < 3000000) {
            move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $file_name);
            $task['file'] = $file_name;
            $task['file_path'] = 'uploads/' . $file_name;
        }
        if ($file_size == 0) {
            $task['file'] = null;
            $task['file_path'] = null;
        }
    }

    if (count($errors)) {                                            //проверяем массив с ошибками на наличие ошибок
        $page_content = include_template('add-form-task.php', [
            'task' => $task,
            'errors' => $errors,
            'projects' => $projects
        ]);
    } else {
        $result = addNewTask($link, $task, $user_id);      //Функция обработки запроса на добавление в таблицу tasks новой задачи

        if ($result) {                                       //проверяем успешно ли выполнен запрос на внесение задачи в базу данных
            $task_id = mysqli_insert_id($link);              //определяем id новой задачи
            header(header: 'Location: index.php?id=');       //используется для отправки HTTP-заголовка
        }
    }
} else {
    /** @var array $projects */
    $page_content = include_template('add-form-task.php', ['projects' => $projects]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);

print($layout_content);