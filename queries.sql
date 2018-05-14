USE doingsdone;

INSERT INTO users(name, email, password)
    VALUES
        ("Игнат", "ignat.v@gmail.com", "$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka"),
        ("Леночка", "kitty_93@li.ru", "$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa"),
        ("Руслан", "warrior07@mail.ru", "$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW");


INSERT INTO projects (title, users_id)
    VALUES
        ("Входящие", 1),
        ("Учеба", 1),
        ("Работа", 1),
        ("Домашние дела", 3),
        ("Авто", 2);


INSERT INTO tasks (title, deadline, projects_id, users_id)
    VALUES
        ("Собеседование в IT компании", STR_TO_DATE("2018-06-01", "%Y-%m-%d"), 4, 1),
        ("Выполнить тестовое задание", STR_TO_DATE("2018-05-25", "%Y-%m-%d"), 4, 1),
        ("Сделать задание первого раздела", STR_TO_DATE("2018-04-21", "%Y-%m-%d"), 3, 1),
        ("Встреча с другом", STR_TO_DATE("2018-04-22", "%Y-%m-%d"), 2, 2),
        ("Купить корм для кота", NULL , 5, 3),
        ("Заказать пиццу", NULL, 5, 3);


/*получить список из всех проектов для одного пользователя*/
SELECT * FROM projects
WHERE users_id = 1;

/*получить список из всех задач для одного проекта*/
SELECT * FROM tasks
WHERE projects_id = 2;

/*пометить задачу как выполненную*/
UPDATE tasks SET doneDate = NOW()
WHERE id = 5;

/*получить все задачи для завтрашнего дня*/
SELECT * FROM tasks
WHERE STR_TO_DATE(CURDATE() + INTERVAL 1 DAY, "%Y-%m-%d") = STR_TO_DATE(deadline, "%Y-%m-%d");

/*обновить название задачи по её идентификатору*/
UPDATE tasks SET title = "новое название"
WHERE id = 3;
