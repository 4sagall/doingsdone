<?php
require_once ('init.php'); //подключаем файл с данными для соединения с БД

if (!$link) {
    $error = mysqli_connect_error();
    $layout_content = include_template('error.php', ['error' => $error]);
}
else {
    $result = getAllUsers($link);            //Функция обработки запроса к базе на получение всех записей таблицы users 
    
    if ($result) {                                                          //запрос выполнен успешно
        $users = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);       //обрабатываем результат и форматируем его в виде двумерного массива
    }
    else {
        $error = mysqli_error($link);                                        //получить текст последней ошибки 
        $layout_content = include_template('error.php', ['error' => $error]);
    }

    $layout_content = include_template('register-form.php', [
        'users' => $users,
        'title' => 'Дела в порядке'
    ]);     
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {   //проверяем суперглобальную переменную SERVER на то какой метод отправки был использован, в данном случае 'POST', согласно форме  
    $required = ['email', 'password', 'name']; //создаем массив с значениями соответствующими name полям формы, обязательным для заполнения
    $errors = [];                              //создаем пустой массив, в который будут записаны ошибки заполнения формы

    $rules = [                                 //создаем ассоциативный массив, в котором каждому ключу (соответствующему имени полей из формы) присваивается анонимная фунция, которая валидирует значение поля
        'email' => function($value) use ($users) {           
            return validate_Email($value, $users); 
        },
        'password' => function($value) {
            return validate_Pass($value);
        },
        'name' => function($value) use ($users) {
            return validate_Name($value, $users);
        } 
    ];
    
    $new_user = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT], add_empty: true); //передаем в переменную $new_user значения полей из формы

    foreach ($new_user as $key => $value) {      //обходим массив и проверяем поля на наличие правил $rules и валидируем введенное значение
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле " . $key . " должно быть заполнено";
        }
    }
    
    $errors = array_filter($errors);           //Обходит каждое значение массива array, передавая его в callback-функцию. Если callback-функция возвращает true, данное значение из array возвращается в результирующий array

    if (count($errors)) {                                            //проверяем массив с ошибками на наличие ошибок 
        $layout_content = include_template('register-form.php', [     //передаем в шаблон ошибки для отображения 
            'errors' => $errors,
            'title' => 'Дела в порядке'
        ]);             
    }
    else {

        $new_user['password'] = password_hash($new_user['password'], PASSWORD_DEFAULT);     //Для хранения пароля в базе предварительно хешируем его
        $result = addNewUser ($link, $new_user);      //Функция обработки запроса на добавление в таблицу users  нового пользователя 
        
        if ($result) {                                       //проверяем успешно ли выполнен запрос на внесение задачи в базу данных
            $user_id = mysqli_insert_id($link);              //определяем id нового пользователя  
            header(header: 'Location: index.php?id=');       //используется для отправки HTTP-заголовка
        }
    }
}
else {
    $layout_content = include_template('register-form.php', [ 
        'errors' => $errors,
        'title' => 'Дела в порядке'
    ]);   
}

$layout_content = include_template('register-form.php', [ 
    'errors' => $errors,   
    'title' => 'Дела в порядке'
]);

print($layout_content);