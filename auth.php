<?php
require_once('init.php');

if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
} else {

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $form = $_POST;
        $required = ['email', 'password'];    //создаем массив с значениями соответствующими name полям формы, обязательным для заполнения
        $errors = [];                         //создаем пустой массив, в который будут записаны ошибки заполнения формы

        foreach ($required as $field) {
            if (empty($form[$field])) {
                $errors[$field] = "Поле " . $field . " должно быть заполнено";
            }
        }

        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);

        $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

        if (!count($errors) and $user) {
            if (password_verify($form['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }

        $errors = array_filter($errors);     //Обходит каждое значение массива, передавая его в callback-функцию. Если callback-функция возвращает true, данное значение из array возвращается в результирующий array

        if (count($errors)) {                //проверяем массив с ошибками на наличие ошибок
            $page_content = include_template('auth-form.php', ['form' => $form, 'errors' => $errors, 'title' => 'Дела в порядке']);
        } else {
                header(header: 'Location: index.php?id=');
                exit();
        }
    } else {
        $page_content = include_template('auth-form.php', []);

        if (isset($_SESSION['user'])) {
            header(header: 'Location: index.php?id=');
            exit();
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке'
]);
print($layout_content);
