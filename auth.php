<?php
require_once('init.php');
/**  подключаем файл с данными для соединения с БД */
/** @var object $link */
if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {
    $result = getAllUsers($link);
}
if ($result) {
    $users = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
} else {
    $error = mysqli_error($link);                                         //получить текст последней ошибки
    $page_content = include_template('error.php', ['error' => $error]);
}

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $required = ['email', 'password'];    //создаем массив с значениями соответствующими name полям формы, обязательным для заполнения
    $errors = [];                         //создаем пустой массив, в который будут записаны ошибки заполнения формы

    $rules = [
        'email' => function ($value) use ($users) {
            return auth_Email($value, $users);
        },
        'password' => function ($value) use ($users) {
            return auth_Pass($value, $users);
        }
    ];
    //передаем в переменную $user значения полей из формы
    $user = filter_input_array(INPUT_POST, options: ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT]);

    foreach ($user as $key => $value) {         //обходим массив и проверяем поля на наличие правил $rules и валидируем введенное значение
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле " . $key . " должно быть заполнено";
        }
    }
    $errors = array_filter($errors);     //Обходит каждое значение массива, передавая его в callback-функцию. Если callback-функция возвращает true, данное значение из array возвращается в результирующий array

    if (count($errors)) {                                            //проверяем массив с ошибками на наличие ошибок
        $page_content = include_template('auth-form.php', [
            'errors' => $errors,
            'user' => $user,
            'title' => 'Дела в порядке'
        ]);
    } else {
        $result = get_NameIdUser($link, $user);                       //Функция обработки запроса на получение из таблицы users  id, name пользователя

        if ($result) {
            $auth_user = mysqli_fetch_array($result, MYSQLI_ASSOC);  //обрабатываем результат и форматируем его в виде двумерного массива id, name
            $this_session = session_start();
            $_SESSION['id'] = $auth_user['id'];
            $_SESSION['name'] = $auth_user['name'];
            header(header: 'Location: index.php?id=');                   //используется для отправки HTTP-заголовка
        }
    }
} else {
    $errors = [];
    $page_content = include_template('auth-form.php', [
        'errors' => $errors,
        'title' => 'Дела в порядке'
    ]);
}
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);
print($layout_content);
