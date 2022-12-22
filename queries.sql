USE doingsdone;

/* Запрос на добавление в БД пользователей - таблица "users" */  
INSERT INTO users (email, name, password) VALUES 
    ("porter@gmail.com", "Nikita", "qwerty"),
    ("maria@yahoo.com", "Maria", "123456"),
    ("rogoza@mail.ru", "Dimon", "qwerty123456");

/* Запрс на добавление в БД существующего списка проектов - таблица "projects" */
INSERT INTO projects (name, user_id) VALUES 
    ("Входящие", 1),
    ("Учеба", 2),
    ("Работа", 1),
    ("Домашние дела", 3),
    ("Авто", 2);

/* Запрос на добавление в БД задач - таблица "tasks" */
INSERT INTO tasks (status, name, date_end, user_id, project_id) VALUES 
    (0, "Собеседование в IT компании", "2019-12-01", 1, 3),
    (0, "Выполнить тестовое задание", "2019-12-21", 2, 2),
    (1, "Сделать задание первого раздела", "2019-12-21", 2, 2),
    (0, "Всреча с другом", "2019-12-22", 1, 1),
    (0, "Купить корм для кота", "", 3, 4),
    (0, "Заказать пиццу", "", 2, 4),
    (0, "Проверить задание по работе с датами", "2022-01-11", 1, 2);

/* Запрос на получение списка из всех проектов для одного пользователя */
SELECT name, user_id FROM projects WHERE user_id=1;

/* Запрос на получение списка из всех задач для одного проекта */
SELECT name, project_id FROM tasks WHERE project_id=1;

/* Запрос на изменение - пометить задачу как выполненную */
UPDATE tasks SET status=1 WHERE name="Собседование в IT компании";

/* Запрос на изменение - обновить название задачи по её идентификатору */ 
UPDATE tasks SET name="Заменить карбюратор", project_id=5 WHERE id=6;

/* Запрос на добавление поля в таблицу - tasks */
ALTER TABLE tasks ADD COLUMN file_path VARCHAR(200);  

