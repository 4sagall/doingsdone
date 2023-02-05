<?php
require_once('init.php'); //подключаем файл с данными для соединения с БД
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {   //проверяем суперглобальную переменную SERVER на то какой метод отправки был использован, в данном случае 'POST', согласно форме
    $form = $_POST;
    $required = ['email', 'password', 'name']; //создаем массив с значениями соответствующими name полям формы, обязательным для заполнения
    $errors = [];                              //создаем пустой массив, в который будут записаны ошибки заполнения формы

    foreach ($form as $key => $value) { //обходим массив и проверяем поля на наличие правил и при установлении правил, валидируем введенное значение
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле должно быть заполнено";
        }
    }

    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Введен не корректный адрес email";
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email= '$email'";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = "Пользователь с указанным email уже зарегистрирован";
        } else {
            $hash = password_hash($form['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (date_add, email, password, name) VALUES (now(),?,?,?)';     //подготовленное выражение запроса на внесение в БД пользователя
            $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $hash, $form['name']]);
            $result = mysqli_stmt_execute($stmt);
        }

        if ($result && empty($errors)) {
            header(header: 'Location: /auth.php');       //используется для отправки HTTP-заголовка
            exit();
        }
    }
}

$page_content = include_template('register-form.php', ['errors' => $errors]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке | Регистрация'
]);

print($layout_content);