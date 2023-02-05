<?php
/**  Валидация формы из сценария add.php */
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 * @param string $date Дата в виде строки
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Функция для для получения значений из POST-запроса и сохранения введенных пользователем значений в полях формы
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * Функция для валидации введенной задачи на пустую строку и длину названия задачи
 * @param string $value - принимает строку и @param int $max - целое число, определяет введено ли название и сравнивает длину строки со значением
 * @param $max
 * @return string|null - возвращает строку
 */
function validateTaskName(string $value, $max): ?string
{
    if (!empty($value)) {
        $len = strlen($value);
        if ($len > $max) return "Имя задачи - не более " . $max . " символов";
    } else {
        return null;
    }
    return null;
}

/**
 * Функция для валидации введенного проекта, сравнивает переданный в нее id с имеющимися в массиве $projects, т.е. функция проверякт наличие проекта с данным id
 * @param $value - фунция получает аргумент и @param array $projects - массив с имеющимися проектами, и сравнивает id проектов с переданным аргументом
 * @return string|null;
 * В свою очередь функция array_key_exists — проверяет, присутствует ли в массиве указанный ключ или индекс
 */
function validateProjectId($value, $projects): ?string
{
    foreach ($projects as $project) {
        if ($value == $project['id']) {
            return null;
        }
    }
    return "Указан несуществующий проект";
}

/**
 * Функция для валидации даты. Эта дата должна быть больше или равна текущей и соответствовать формату.
 * Для валидации формата используется другая функция - is_date_valid()
 * @param string $value - принимает один аргумент в виде строки
 * @return string|null текст ошибки либо null, если ошибки нет
 */
function validateDate(string $value): ?string
{
    date_default_timezone_set("Europe/Moscow");
    $cur_time = strtotime('now');
    $spec_time = strtotime($value);
    if ($value) {
        if (!is_date_valid($value)) {
            return "Содержимое поля - дата завершения, должно быть датой в формате ГГГГ-ММ-ДД";
        } elseif ($cur_time > $spec_time && ($cur_time - $spec_time) < 2000) {
            return "Эта дата должна быть больше или равна текущей";
        }
    } else {
        return null;
    }
    return null;
}

/** Валидация формы из сценария add-project.php */
/**
 * Функция для валидации названия проекта
 * @param string $value - принимает строку
 * @param $max
 * @param array $projects_user
 * @return string|null - возвращает строку ошибки или null - если ошибки валидации нет
 */
function validateProject(string $value, $max, array $projects_user): ?string
{
    if (!empty($value)) {
        $len = strlen($value);
        if ($len > $max) {
            return "Название проекта - не более " . $max . " символов";
        } else {
            foreach ($projects_user as $project) {
                if (strnatcasecmp($value, $project['name']) == 0) {
                    return "Такой проект уже существует. Выберете другое название проекта";
                }
            }
        }
    }
    return null;
}