<?php
/** Запросы на главной странице сценарий index.php */
/**
 * Функция обработки запроса к базе на получение проектов с подсчетом задач в каждом проекте для пользователя с user_id
 * @param mysqli $link
 * @param int $user_id - идентификатор пользователя; @param object $link результат выполнения функции подключения к базе
 * @return bool|mysqli_result
 */
function getProjects_CountTasks(mysqli $link, int $user_id): mysqli_result|bool
{
    $sql = 'SELECT p.id, p.name, count(t.id) AS task_count, p.user_id 
    FROM projects p JOIN tasks t ON p.id = t.project_id 
    WHERE t.user_id = ' . $user_id . ' GROUP BY id';

    return mysqli_query($link, $sql);
}

/**
 * Функция обработки запроса к базе на получение всех записей таблицы tasks по проекту $project_id и для пользователя user_id
 * @param mysqli $link результат выполнения функции подключения к базе
 * @param $project_id
 * @param int $user_id - идентификатор пользователя; @param int $project_id - идентификатор проекта
 * @param string $sql_filter
 * @return array|string - mysqli_fetch_all — Выбирает все строки из результирующего набора и помещает их в ассоциативный массив, обычный массив или в оба,
 * если результат запроса отрицательный возвращается шаблон ошибки
 */
function getTasks_ProjectId_UserId(mysqli $link, $project_id, int $user_id, string $sql_filter): array|string
{
    $sql = 'SELECT * FROM tasks WHERE project_id = ' . $project_id . ' AND user_id = ' . $user_id . $sql_filter;

    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    } else {
        return mysqli_error($link);
    }
}

/**
 * Функция обработки запроса к базе на получение всех записей таблицы tasks для пользователя user_id
 * @param mysqli $link
 * @param int $user_id - идентификатор пользователя; @param object $link результат выполнения функции подключения к базе
 * @param string $sql_filter
 * @return array|string
 * если результат запроса отрицательный возвращается шаблон ошибки
 */
function getTasks_UserId(mysqli $link, int $user_id, string $sql_filter): array|string
{
    $sql = 'SELECT * FROM tasks WHERE user_id=' . $user_id . $sql_filter;

    $result = mysqli_query($link, $sql);                           //запрос на получение из БД данных из таблицы задач - tasks для user_id
    if ($result) {
        return mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
    } else {
        return mysqli_error($link);
    }
}

/**
 * Функция обработки запроса на осуществление полнотекстового поиска из таблицы tasks для $user_id
 * @param mysqli $link результат выполнения функции подключения к базе,
 * @param string $search строка из формы поиска
 * @param $user_id
 * @return false|mysqli_result - возвращает объект mysqli_result с буферизованным набором результатов (по умолчанию)
 */
function getSearchTasks(mysqli $link, string $search, $user_id): bool|mysqli_result
{
    $search = trim($search);
    $sql = 'SELECT * FROM tasks WHERE MATCH(name) AGAINST(?) AND tasks.user_id =' . $user_id;

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/** Запросы на странице добавления задачи сценарий add.php */
/**
 * Функция обработки запроса к базе на получение всех записей таблицы projects для $user_id
 * @param mysqli $link результат выполнения функции подключения к базе
 * @param int $user_id
 * @return bool|mysqli_result - возвращает объект mysqli_result с буферизованным набором результатов (по умолчанию)
 */
function getAllProjects(mysqli $link, int $user_id): mysqli_result|bool
{
    $sql = 'SELECT * FROM projects WHERE user_id=' . $user_id;
    return mysqli_query($link, $sql);
}

/**
 * Функция обработки запроса на добавление в таблицу tasks новой задачи
 * @param mysqli $link результат выполнения функции подключения к базе, @param array $tasks массив данных из формы
 * @param $task
 * @param $user_id
 * @return bool - возвращает объект mysqli_result с буферизованным набором результатов (по умолчанию)
 */
function addNewTask(mysqli $link, $task, $user_id): bool
{
    $sql = 'INSERT INTO tasks (name, project_id, date_end, file, file_path, user_id) VALUES (?,?,?,?,?,' . $user_id . ')';  //подготовленное выражение запроса на внесение в БД задачи
    $stmt = db_get_prepare_stmt($link, $sql, $task);                  //функция - создает подготовленное выражение на основе готового SQL запроса и переданных данных
    return mysqli_stmt_execute($stmt);                                //возвращает результат выполнения подготовленного утверждения
}

/** Запросы страницы добавления проекта сценарий add-project.php */
/**
* Функция обработки запроса на добавление в таблицу projects нового проекта
* @param mysqli $link результат выполнения функции подключения к базе, @param array $tasks массив данных из формы
* @param $project
* @param $user_id
* @return bool - возвращает объект mysqli_result с буферизованным набором результатов (по умолчанию)
 */
function addNewProject(mysqli $link, $project, $user_id): bool
{
    $sql = 'INSERT INTO projects (name, user_id) VALUES (?,' . $user_id . ')';      //подготовленное выражение запроса на внесение в БД задачи
    $stmt = db_get_prepare_stmt($link, $sql, $project);                                //функция - создает подготовленное выражение на основе готового SQL запроса и переданных данных
    return mysqli_stmt_execute($stmt);                                              //возвращает результат выполнения подготовленного утверждения
}

/**
 * Функция обработки запроса, который инвертирует статус задачи (выполнена → не выполнена, не выполнена → выполнена)
 * @param mysqli $link результат выполнения функции подключения к базе, @param array $tasks массив данных из формы
 * @param $task_id
 * @return bool|mysqli_result - возвращает объект mysqli_result с буферизованным набором результатов (по умолчанию)
 */
function sqlSwitchTaskStatus(mysqli $link, $task_id): bool|mysqli_result
{
    $sql1 = 'SELECT * FROM tasks WHERE id=' . $task_id;
    $result = mysqli_query($link, $sql1);
    if ($result) {
        $task = mysqli_fetch_all($result, mode: MYSQLI_ASSOC);
        $status = $task['0']['status'];
    } else {
        return mysqli_error($link);
    }

    $sql2 = 'UPDATE tasks SET status=' . ($status == 0? '1':'0') . ' WHERE id=' . $task_id;
    return mysqli_query($link, $sql2);
}


