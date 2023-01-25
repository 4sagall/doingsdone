<?php
/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/** 
 * Функция вычисляет количество задач в каждой категории проектов.
 * @param array $tasks - массив задач
 * @param  string $project - название проекта.
 * @return int
 */
function counter_tasks (array $tasks, $project): int
{
    $amount = 0;
    foreach ($tasks as $key => $value) {
        if ($value['project_id'] == $project) {
            $amount++;
        } 
        else continue; 
    }
    return $amount;
}

/** 
 * Функция рассчитывает сколько часов осталось до даты выполнения задачи.
 * @param date $date - функция получает один аргумент в виде даты в произвольном виде
 * полученная дата сравнивается с текущим временем. @return boolean - если до переданной даты осталось 24 часа и меньше, то возвращается true
 */
function time_left ($date) {
    date_default_timezone_set("Europe/Moscow");
    $cur_time = strtotime('now');
    $spec_time = strtotime($date);
    if(floor(($spec_time - $cur_time)/3600) <= 24) {
        return true;
    } else {
        return false;
    }
}

/** 
 * Функция сравнивает переданный в нее id с имеющимися в массиве $projects, т.е. функция проверякт наличие проекта с данным id
 * @param int $pid - фунция получает аргумент и @param array $projects - массив с имеющимися проектами, и сравнивает id проектов с переданным аргументом
 * @return boolean - возвращает булево значение, если совпадение найдено - 1, совпадений нет - 0.    
 */
function getProjectById($pid, $projects) {
    foreach ($projects as $key => $value) {
        if ($value['id'] == $pid) return $pid;           
    }
    return null;
}

/**
 * Функция енерирует капчу
 * @param string $text
 * @return string $result
 */
function generate_captcha($text, $width = 100, $height = 40, $noise_level = 250)
{
    $colors = [
        'bg' => [26, 100, 219],
        'text' => [255, 255, 255],
        'noise' => [227, 234, 242]
    ];

    $im = imagecreatetruecolor($width, $height);

    $bg_color = imagecolorallocate($im, ...$colors['bg']);
    $text_color = imagecolorallocate($im, ...$colors['text']);
    $noise_color = imagecolorallocate($im, ...$colors['noise']);

    imagefill($im, 0, 0, $bg_color);
    imagestring($im, 5, 10, 10, $text, $text_color);

    for ($i = 0; $i < $noise_level; $i++) {
        $x_pos = rand(1, $width);
        $y_pos = rand(1, $height);

        imagesetpixel($im, $x_pos, $y_pos, $noise_color);
    }

    imagepng($im);
}

function generate_random_string($length = 8){
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $chars_arr = str_split($chars);

    $rand_keys = array_rand($chars_arr, $length);
    $rand_chars = array_intersect_key($chars_arr, array_flip($rand_keys));

    $result = implode("", $rand_chars);

    return $result;
}